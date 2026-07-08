<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alert Details - Emergency Alert</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('admin.emergency-alerts') }}" class="btn btn-secondary mb-3">
                    <i class="fas fa-arrow-left"></i> Back to Alerts
                </a>

                <div class="card">
                    <div class="card-header {{ $alert->is_resolved ? 'bg-success' : 'bg-danger' }} text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3>{{ $alert->alert_icon }} Emergency Alert Details</h3>
                            <span class="badge bg-light text-dark">
                                {{ $alert->is_resolved ? '✅ Resolved' : '⚠️ Pending' }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-user"></i> User:</strong> {{ $alert->user->name ?? 'Unknown' }}</p>
                                <p><strong><i class="fas fa-envelope"></i> Email:</strong> {{ $alert->user->email ?? 'N/A' }}</p>
                                <p><strong><i class="fas fa-tag"></i> Alert Type:</strong> 
                                    <span class="badge bg-danger">{{ $alert->alert_type }}</span>
                                </p>
                                <p><strong><i class="fas fa-hashtag"></i> Conversation ID:</strong> #{{ $alert->conversation_id }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="far fa-clock"></i> Created:</strong> {{ $alert->created_at->format('Y-m-d H:i:s') }}</p>
                                @if($alert->resolved_at)
                                    <p><strong><i class="fas fa-check-circle text-success"></i> Resolved:</strong> {{ $alert->resolved_at->format('Y-m-d H:i:s') }}</p>
                                @endif
                                @if($alert->resolution_notes)
                                    <p><strong><i class="fas fa-sticky-note"></i> Resolution Notes:</strong> {{ $alert->resolution_notes }}</p>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <h5><i class="fas fa-comment"></i> Alert Message</h5>
                        <div class="alert alert-danger">
                            <pre class="mb-0" style="white-space: pre-wrap;">{{ $alert->message }}</pre>
                        </div>

                        @if($alert->message_id)
                            <div class="mt-3">
                                <h6><i class="fas fa-comment-dots"></i> Original Message</h6>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        {{ $alert->message->content ?? 'Message not found' }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="mt-4">
                            @if(!$alert->is_resolved)
                                <form action="{{ route('admin.emergency-alerts.resolve', $alert) }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-8">
                                            <input type="text" name="notes" class="form-control" 
                                                   placeholder="Enter resolution notes..." required>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-success w-100">
                                                <i class="fas fa-check"></i> Resolve Alert
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle"></i> This alert has been resolved.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>