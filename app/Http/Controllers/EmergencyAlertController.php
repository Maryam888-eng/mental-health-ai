<?php

namespace App\Http\Controllers;

use App\Models\EmergencyAlert;
use Illuminate\Http\Request;

class EmergencyAlertController extends Controller
{
    /**
     * Display a listing of emergency alerts.
     */
    public function index()
    {
        $alerts = EmergencyAlert::with(['user', 'conversation'])
            ->orderByRaw("FIELD(is_resolved, 0, 1)")
            ->orderBy('created_at', 'desc')
            ->get();

      return view('emergency-alerts', compact('alerts'));
    }

    /**
     * Show a specific alert details.
     */
    public function show(EmergencyAlert $alert)
    {
        $alert->load(['user', 'conversation', 'message']);
        return view('admin.emergency-alerts-show', compact('alert'));
    }

    /**
     * Resolve an alert.
     */
    public function resolve(Request $request, EmergencyAlert $alert)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $alert->resolve($request->notes);

        return back()->with('success', '✅ Emergency alert resolved successfully!');
    }

    /**
     * Bulk resolve all alerts.
     */
    public function resolveAll(Request $request)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        EmergencyAlert::unresolved()->each(function ($alert) use ($request) {
            $alert->resolve($request->notes);
        });

        return back()->with('success', '✅ All alerts resolved successfully!');
    }

    /**
     * Get alert statistics (for dashboard).
     */
    public function stats()
    {
        $stats = EmergencyAlert::getStats();
        $byPriority = EmergencyAlert::getByPriority();

        return response()->json([
            'stats' => $stats,
            'by_priority' => $byPriority,
        ]);
    }
}