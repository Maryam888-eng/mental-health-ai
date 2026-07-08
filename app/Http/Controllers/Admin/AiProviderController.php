<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiProvider;
use Illuminate\Http\Request;
use App\Models\AiResponse;
use App\Models\Conversation;
use App\Models\DoctorReview;
use App\Models\User;

class AiProviderController extends Controller
{
    public function index()
    {
        return view('admin.ai-providers.index', [
                'usersCount' => User::where('role', 'user')->count(),
                'doctorsCount' => User::where('role', 'doctor')->count(),
                'adminsCount' => User::where('role', 'admin')->count(),
                'conversationsCount' => Conversation::count(),
                'aiResponsesCount' => AiResponse::count(),
                'doctorReviewsCount' => DoctorReview::count(),

                'providers' => AiProvider::query()
                    ->withCount([
                        'doctorReviews as reviews_count',
                        'aiResponses as responses_count',
                    ])
                    ->withAvg('doctorReviews as avg_accuracy', 'accuracy_score')
                    ->withAvg('doctorReviews as avg_empathy', 'empathy_score')
                    ->withAvg('doctorReviews as avg_safety', 'safety_score')
                    ->withAvg('doctorReviews as avg_usefulness', 'usefulness_score')
                    ->with([
                        'doctorReviews' => fn ($query) => $query->with('doctor')->latest()->limit(5),
                    ])
                    ->latest()
                    ->get(),
            ]);
    }

    public function create()
{
    return view('admin.ai-providers.create');
}

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => ['required', 'string'],
        'slug' => ['required', 'string', 'unique:ai_providers,slug'],
        'driver' => ['required', 'string'],
        'model' => ['required', 'string'],
        'api_url' => ['nullable', 'string'],
        'daily_limit' => ['required', 'integer'],
        'is_free' => ['nullable', 'boolean'],
    ]);

    AiProvider::create([
        'name' => $validated['name'],
        'slug' => $validated['slug'],
        'driver' => $validated['driver'],
        'model' => $validated['model'],
        'api_url' => $validated['api_url'] ?? null,
        'daily_limit' => $validated['daily_limit'],
        'is_free' => $request->boolean('is_free'),
        'is_active' => false,
    ]);

    return redirect()
        ->route('admin.ai-providers.index')
        ->with('success', 'Provider created successfully.');
}

public function destroy(AiProvider $provider)
{
    if ($provider->is_active) {
        return back()->with(
            'error',
            'Deactivate provider before deleting.'
        );
    }

    $provider->delete();

    return back()->with(
        'success',
        'Provider deleted successfully.'
    );
}

    public function activate(\App\Models\AiProvider $aiProvider)
    {
        \App\Models\AiProvider::query()->update(['is_active' => false]);

        $aiProvider->update(['is_active' => true]);

        return back()->with('success', "{$aiProvider->name} is now active.");
    }

    public function deactivate(\App\Models\AiProvider $aiProvider)
    {
        $aiProvider->update(['is_active' => false]);

        return back()->with('success', "{$aiProvider->name} is now inactive.");
    }

    public function update(Request $request, AiProvider $aiProvider)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'api_url' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
            'daily_limit' => ['nullable', 'integer', 'min:1'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $aiProvider->update($data);

        return back()->with('success', 'AI provider updated.');
    }
}