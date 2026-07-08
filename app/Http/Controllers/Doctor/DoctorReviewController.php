<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\DoctorReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorReviewController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'conversation_id' => ['required', 'exists:conversations,id'],
            'ai_response_id' => ['nullable', 'exists:ai_responses,id'],
            'ai_provider_id' => ['nullable', 'exists:ai_providers,id'],
            'accuracy_score' => ['nullable', 'integer', 'min:1', 'max:5'],
            'empathy_score' => ['nullable', 'integer', 'min:1', 'max:5'],
            'safety_score' => ['nullable', 'integer', 'min:1', 'max:5'],
            'usefulness_score' => ['nullable', 'integer', 'min:1', 'max:5'],
            'risk_level' => ['required', 'in:low,medium,high,crisis'],
            'needs_follow_up' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $data['doctor_id'] = Auth::id();
        $data['needs_follow_up'] = $request->boolean('needs_follow_up');

        DoctorReview::create($data);

        return back()->with('success', 'Doctor review saved.');
    }
}