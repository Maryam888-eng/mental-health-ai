<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Conversation;

class DoctorDashboardController extends Controller
{
    public function index()
    {
        $conversations = Conversation::with(['user', 'latestMessage'])
            ->withCount(['messages', 'aiResponses', 'doctorReviews'])
            ->latest()
            ->paginate(15);

        return view('doctor.dashboard', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        $conversation->load([
            'user',
            'messages.aiResponses.aiProvider',
            'doctorReviews.doctor',
        ]);

        return view('doctor.conversation-show', compact('conversation'));
    }
}