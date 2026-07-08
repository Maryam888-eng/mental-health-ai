<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <div class="bg-light min-vh-100 py-4">
        <div class="container-fluid px-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">👨‍⚕️ Doctor Dashboard</h2>
                    <p class="text-muted mb-0">Review user conversations and evaluate AI responses.</p>
                </div>

                <input id="searchBox" class="form-control w-auto" placeholder="Search conversations...">
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- ===== DIARY STATS SECTION (NEW) ===== -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 bg-gradient-to-r from-blue-50 to-blue-100">
                        <div class="card-body">
                            <div class="text-muted small">📖 Total Diaries</div>
                            <div class="fs-3 fw-bold text-primary">{{ \App\Models\Diary::count() }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 bg-gradient-to-r from-green-50 to-green-100">
                        <div class="card-body">
                            <div class="text-muted small">📝 Today's Entries</div>
                            <div class="fs-3 fw-bold text-success">{{ \App\Models\Diary::whereDate('created_at', today())->count() }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 bg-gradient-to-r from-purple-50 to-purple-100">
                        <div class="card-body">
                            <div class="text-muted small">👤 Patients with Diaries</div>
                            <div class="fs-3 fw-bold text-purple-600">{{ \App\Models\Diary::distinct('user_id')->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== RECENT DIARY ENTRIES (NEW) ===== -->
            @php
                $recentDiaries = \App\Models\Diary::with('user')->latest()->limit(5)->get();
            @endphp

            @if($recentDiaries->count() > 0)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <strong>📖 Recent Patient Diaries</strong>
                    <a href="{{ route('diary.index') }}" class="btn btn-outline-primary btn-sm">
                        View All →
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Mood</th>
                                    <th>Entry</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentDiaries as $diary)
                                <tr>
                                    <td>
                                        <strong>{{ $diary->user->display_name ?? $diary->user->name }}</strong>
                                    </td>
                                    <td>
                                        @if($diary->mood == 'happy') 😊 Happy
                                        @elseif($diary->mood == 'sad') 😢 Sad
                                        @elseif($diary->mood == 'anxious') 😰 Anxious
                                        @elseif($diary->mood == 'angry') 😡 Angry
                                        @else 😐 Neutral @endif
                                    </td>
                                    <td>{{ \Illuminate\Support\Str::limit($diary->content, 60) }}</td>
                                    <td>{{ $diary->created_at->diffForHumans() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- ===== CONVERSATIONS TABLE ===== -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <strong>💬 Conversations</strong>
                    <span class="badge bg-primary">{{ $conversations->total() }} Total</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>User</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Messages</th>
                                <th>AI Replies</th>
                                <th>Reviews</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody id="conversationTable">
                            @forelse($conversations as $conversation)
                                <tr class="conversation-row">
                                    <td>
                                        <strong>{{ $conversation->user?->name ?? 'Unknown' }}</strong>
                                        <div class="small text-muted">
                                            {{ $conversation->user?->email }}
                                        </div>
                                    </td>

                                    <td>{{ $conversation->title }}</td>

                                    <td>
                                        @if($conversation->status === 'flagged')
                                            <span class="badge bg-danger">Flagged</span>
                                        @elseif($conversation->status === 'under_review')
                                            <span class="badge bg-warning text-dark">Under Review</span>
                                        @else
                                            <span class="badge bg-success">
                                                {{ ucfirst(str_replace('_', ' ', $conversation->status)) }}
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $conversation->messages_count }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge bg-info text-dark">
                                            {{ $conversation->ai_responses_count }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge bg-dark">
                                            {{ $conversation->doctor_reviews_count }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $conversation->created_at->format('d M Y H:i') }}
                                    </td>

                                    <td>
                                        <a href="{{ route('doctor.conversations.show', $conversation) }}"
                                           class="btn btn-primary btn-sm">
                                            Review
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">
                                        No conversations found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $conversations->links() }}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

    <script>
        $('#searchBox').on('keyup', function() {
            const value = $(this).val().toLowerCase();

            $('#conversationTable tr.conversation-row').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    </script>
</x-app-layout>