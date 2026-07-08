<!DOCTYPE html>
<html>
<head>
    <title>Emergency Alerts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .alert-card { border-left: 5px solid #dc3545; margin-bottom: 15px; }
        .alert-card.resolved { border-left-color: #28a745; opacity: 0.7; }
        .badge-crisis { background: #dc3545; }
        .badge-high { background: #fd7e14; }
        .badge-medium { background: #ffc107; color: #000; }
        .badge-low { background: #28a745; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-exclamation-triangle text-danger"></i> Emergency Alerts</h1>
            <span class="badge bg-danger fs-5 p-2">
                {{ $alerts->where('is_resolved', false)->count() }} Pending
            </span>
        </div>

        @if($alerts->isEmpty())
            <div class="alert alert-success text-center p-5">
                <i class="fas fa-check-circle fa-3x mb-3 d-block"></i>
                <h3>No alerts found</h3>
                <p class="text-muted">All clear! No emergency alerts to show.</p>
            </div>
        @else
            @foreach($alerts as $alert)
                <div class="card alert-card {{ $alert->is_resolved ? 'resolved' : '' }}">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-1 text-center">
                                <span style="font-size: 2rem;">{{ $alert->alert_icon }}</span>
                            </div>
                            <div class="col-md-7">
                                <div class="d-flex gap-2 flex-wrap">
                                    <span class="badge 
                                        @if($alert->alert_type == 'crisis') badge-crisis
                                        @elseif($alert->alert_type == 'high_risk') badge-high
                                        @elseif($alert->alert_type == 'medium_risk') badge-medium
                                        @else badge-low @endif
                                        p-2">
                                        {{ $alert->alert_type }}
                                    </span>
                                    <span class="badge bg-secondary">{{ $alert->created_at->diffForHumans() }}</span>
                                    <span class="badge {{ $alert->is_resolved ? 'bg-success' : 'bg-danger' }}">
                                        {{ $alert->is_resolved ? '✅ Resolved' : '⚠️ Pending' }}
                                    </span>
                                </div>
                                <h5 class="mt-2">{{ $alert->user->name ?? 'Unknown User' }}</h5>
                                <p class="text-muted mb-1">{{ Str::limit($alert->message, 150) }}</p>
                                <small class="text-muted">Conversation #{{ $alert->conversation_id }}</small>
                            </div>
                            <div class="col-md-4 text-end">
                                @if(!$alert->is_resolved)
                                    <form action="{{ route('doctor.emergency-alerts.resolve', $alert) }}" method="POST" class="d-inline">
                                        @csrf
                                        <div class="d-flex gap-2">
                                            <input type="text" name="notes" class="form-control form-control-sm" placeholder="Notes..." style="min-width:150px;">
                                            <button type="submit" class="btn btn-success btn-sm">Resolve</button>
                                        </div>
                                    </form>
                                @else
                                    <span class="text-success"><i class="fas fa-check-circle"></i> Resolved</span>
                                    @if($alert->resolution_notes)
                                        <p class="text-muted small mt-1">{{ $alert->resolution_notes }}</p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</body>
</html>