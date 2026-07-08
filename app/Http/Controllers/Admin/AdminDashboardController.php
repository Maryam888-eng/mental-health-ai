<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiProvider;
use App\Models\AiResponse;
use App\Models\Conversation;
use App\Models\DoctorReview;
use App\Models\User;

class AdminDashboardController extends Controller
{

    public function AIProvider()
        {
            return view('admin.dashboard', [
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


    public function index()
    {
        return view('admin.dashboard', [
            'doctors' => User::where('role', 'doctor')->latest()->get(),
            'patients' => User::where('role', 'user')->latest()->get(),
        ]);
    }
    
    public function activateProvider(AiProvider $provider)
    {
        AiProvider::query()->update([
            'is_active' => false,
        ]);

        $provider->update([
            'is_active' => true,
        ]);

        return redirect()
            ->back()
            ->with('success', "{$provider->name} is now active.");
    }
}