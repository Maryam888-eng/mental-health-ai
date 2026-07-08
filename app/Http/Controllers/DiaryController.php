<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiaryController extends Controller
{
    /**
     * Display a listing of diary entries.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'doctor') {
            $diaries = Diary::with('user')->latest()->get();
        } else {
            $diaries = Diary::where('user_id', Auth::id())->latest()->get();
        }

        return view('diary.index', compact('diaries'));
    }

    /**
     * Store a newly created diary entry.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:5000',
            'mood' => 'nullable|string|in:happy,sad,anxious,angry,neutral'
        ]);

        // 🔴 FIX: Prevent duplicate entry for today
        $existing = Diary::where('user_id', Auth::id())
            ->whereDate('created_at', today())
            ->first();

        if ($existing) {
            return redirect()->route('diary.index')
                ->with('error', 'You already wrote a diary entry today! You can edit it instead.');
        }

        Diary::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'mood' => $request->mood ?? 'neutral',
            'is_shared_with_doctor' => $request->has('share_with_doctor'),
        ]);

        return redirect()->route('diary.index')->with('success', 'Diary entry saved!');
    }

    /**
     * Show the form for editing a diary entry.
     */
    public function edit(Diary $diary)
    {
        // 🔴 FIX: Check if user owns this diary or is doctor
        if (Auth::user()->role !== 'doctor' && Auth::id() !== $diary->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('diary.edit', compact('diary'));
    }

    /**
     * Update the specified diary entry.
     */
    public function update(Request $request, Diary $diary)
    {
        // 🔴 FIX: Check if user owns this diary or is doctor
        if (Auth::user()->role !== 'doctor' && Auth::id() !== $diary->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'content' => 'required|string|max:5000',
            'mood' => 'nullable|string|in:happy,sad,anxious,angry,neutral'
        ]);

        $diary->update([
            'content' => $request->content,
            'mood' => $request->mood ?? 'neutral',
            'is_shared_with_doctor' => $request->has('share_with_doctor'),
        ]);

        return redirect()->route('diary.index')
            ->with('success', 'Diary entry updated successfully!');
    }

    /**
     * Remove the specified diary entry.
     */
    public function destroy(Diary $diary)
    {
        // 🔴 FIX: Check if user owns this diary or is doctor
        if (Auth::user()->role !== 'doctor' && Auth::id() !== $diary->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $diary->delete();

        return redirect()->route('diary.index')
            ->with('success', 'Diary entry deleted successfully!');
    }

    /**
     * Toggle share with doctor status.
     */
    public function toggleShare(Diary $diary)
    {
        if (Auth::id() !== $diary->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $diary->update([
            'is_shared_with_doctor' => !$diary->is_shared_with_doctor,
        ]);

        return redirect()->route('diary.index')
            ->with('success', 'Share status updated successfully!');
    }

    /**
     * Display the specified diary entry (for doctor view).
     */
    public function show(Diary $diary)
    {
        // Doctor can view any diary, user can only view their own
        if (Auth::user()->role !== 'doctor' && Auth::id() !== $diary->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('diary.show', compact('diary'));
    }
}