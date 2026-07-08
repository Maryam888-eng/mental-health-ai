<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <div class="bg-light min-vh-100 py-4">
        <div class="container-fluid px-4">

            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h2 class="fw-bold mb-1">{{ $conversation->title }}</h2>
                    <p class="text-muted mb-0">
                        User: {{ $conversation->user?->name }} — {{ $conversation->user?->email }}
                    </p>
                </div>

                <a href="{{ route('doctor.dashboard') }}" class="btn btn-outline-secondary">
                    ← Back
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row g-4">
                <div class="col-12 col-lg-8">
                    <div class="d-flex flex-column gap-4">

                        @foreach($conversation->messages as $message)
                            @if($message->role === 'user')
                                <div class="d-flex justify-content-end">
                                    <div style="max-width:75%;">
                                        <div class="small text-muted text-end mb-1">User</div>
                                        <div class="bg-primary text-white rounded-4 px-3 py-2" style="white-space:pre-wrap;">
                                            {{ $message->content }}
                                        </div>
                                    </div>
                                </div>

                                @foreach($message->aiResponses as $response)
                                    <div class="card shadow-sm">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h5 class="fw-bold mb-0">
                                                    {{ $response->aiProvider?->name ?? 'AI Provider' }}
                                                </h5>

                                                @if($response->is_successful)
                                                    <span class="badge bg-success">Success</span>
                                                @else
                                                    <span class="badge bg-danger">Failed</span>
                                                @endif
                                            </div>

                                            <div class="mb-3" style="white-space:pre-wrap;">
                                                {{ $response->response }}
                                            </div>

                                            @if($response->error_message)
                                                <div class="alert alert-danger small" style="white-space:pre-wrap;">
                                                    {{ $response->error_message }}
                                                </div>
                                            @endif

                                            <div class="small text-muted mb-3">
                                                Response time: {{ $response->response_time_ms ?? '-' }} ms
                                            </div>

                                            <button class="btn btn-outline-primary btn-sm toggleReview">
                                                Add / Hide Review
                                            </button>

                                            <form method="POST"
                                                  action="{{ route('doctor.reviews.store') }}"
                                                  class="reviewForm mt-3 border-top pt-3 d-none">
                                                @csrf

                                                <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                                                <input type="hidden" name="ai_response_id" value="{{ $response->id }}">
                                                <input type="hidden" name="ai_provider_id" value="{{ $response->ai_provider_id }}">

                                                <div class="row g-3">
                                                    @foreach([
                                                        'accuracy_score' => 'Accuracy',
                                                        'empathy_score' => 'Empathy',
                                                        'safety_score' => 'Safety',
                                                        'usefulness_score' => 'Usefulness',
                                                    ] as $field => $label)
                                                        <div class="col-6 col-md-3">
                                                            <label class="form-label small fw-semibold">{{ $label }}</label>
                                                            <select name="{{ $field }}" class="form-select">
                                                                @for($i = 1; $i <= 5; $i++)
                                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                    @endforeach

                                                    <div class="col-12">
                                                        <label class="form-label small fw-semibold">Risk Level</label>
                                                        <select name="risk_level" class="form-select">
                                                            <option value="low">Low</option>
                                                            <option value="medium">Medium</option>
                                                            <option value="high">High</option>
                                                            <option value="crisis">Crisis</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-check">
                                                            <input class="form-check-input"
                                                                   type="checkbox"
                                                                   name="needs_follow_up"
                                                                   value="1"
                                                                   id="followUp{{ $response->id }}">
                                                            <label class="form-check-label" for="followUp{{ $response->id }}">
                                                                Needs follow-up
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <label class="form-label small fw-semibold">Doctor Notes</label>
                                                        <textarea name="notes" class="form-control" rows="3"></textarea>
                                                    </div>

                                                    <div class="col-12">
                                                        <button class="btn btn-primary">
                                                            Save Review
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        @endforeach

                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="card shadow-sm sticky-top" style="top: 20px;">
                        <div class="card-header bg-white">
                            <strong>Existing Reviews</strong>
                        </div>

                        <div class="card-body">
                            @forelse($conversation->doctorReviews as $review)
                                <div class="border-bottom pb-3 mb-3">
                                    <div class="fw-semibold">
                                        {{ $review->doctor?->name ?? 'Doctor' }}
                                    </div>

                                    <div class="mt-2">
                                        <span class="badge bg-primary">
                                            Provider: {{ $review->aiProvider?->name ?? 'Unknown Provider' }}
                                        </span>
                                    </div>

                                    <div class="small text-muted mt-2">
                                        Model:
                                        <code>{{ $review->aiProvider?->model ?? '-' }}</code>
                                    </div>

                                    <div class="small text-muted">
                                        Driver: {{ $review->aiProvider?->driver ?? '-' }}
                                    </div>

                                    <div class="small text-muted mt-2">
                                        Risk: {{ ucfirst($review->risk_level) }}
                                    </div>

                                    <div class="small">
                                        Avg Score: {{ $review->averageScore() ?? '-' }}
                                    </div>

                                    @if($review->needs_follow_up)
                                        <span class="badge bg-warning text-dark mt-2">
                                            Needs follow-up
                                        </span>
                                    @endif

                                    @if($review->notes)
                                        <div class="small mt-2" style="white-space:pre-wrap;">
                                            {{ $review->notes }}
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-muted mb-0">No reviews yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        $(document).on('click', '.toggleReview', function () {
            $(this).closest('.card-body').find('.reviewForm').toggleClass('d-none');
        });
    </script>
</x-app-layout>