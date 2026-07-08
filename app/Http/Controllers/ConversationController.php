<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    public function index()
    {
        return Conversation::where(
            'user_id',
            Auth::id()
        )
        ->latest()
        ->get();
    }

    public function store(Request $request)
    {
        $conversation = Conversation::create([
            'user_id' => Auth::id(),
            'title' => $request->title ?: 'New Conversation',
        ]);

        return response()->json($conversation);
    }

    public function show(Conversation $conversation)
    {
        abort_unless(
            $conversation->user_id === Auth::id(),
            403
        );

        return $conversation->load([
            'messages',
            'aiResponses.aiProvider'
        ]);
    }

    public function destroy(Conversation $conversation)
    {
        abort_unless(
            $conversation->user_id === Auth::id(),
            403
        );

        $conversation->delete();

        return response()->json([
            'success' => true
        ]);
    }
}