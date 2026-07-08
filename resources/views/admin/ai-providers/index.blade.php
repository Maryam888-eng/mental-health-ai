<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <div class="bg-light min-vh-100 py-4">
        <div class="container-fluid px-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">AI Providers</h2>
                    <p class="text-muted mb-0">Activate providers based on doctor review scores.</p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.ai-providers.create') }}"
                    class="btn btn-primary">
                        + Add Provider
                    </a>

                    <a href="{{ route('admin.dashboard') }}"
                    class="btn btn-outline-secondary">
                        ← Back
                    </a>
                </div>
            </div>

            <div id="alertBox" class="alert d-none"></div>

            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <strong>Provider Management</strong>
                    <span class="badge bg-primary">{{ $providers->count() }} Providers</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Provider</th>
                                <th>Driver</th>
                                <th>Model</th>
                                <th>Status</th>
                                <th>Reviews</th>
                                <th>Avg Score</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($providers as $provider)
                                @php
                                    $accuracy = $provider->avg_accuracy ?? 0;
                                    $empathy = $provider->avg_empathy ?? 0;
                                    $safety = $provider->avg_safety ?? 0;
                                    $usefulness = $provider->avg_usefulness ?? 0;

                                    $avgScore = collect([$accuracy, $empathy, $safety, $usefulness])
                                        ->filter(fn ($score) => $score > 0)
                                        ->avg();

                                    $avgScore = $avgScore ? number_format($avgScore, 1) : '0.0';
                                @endphp

                                <tr class="provider-row"
                                    data-id="{{ $provider->id }}"
                                    data-activate-url="{{ route('admin.ai-providers.activate', $provider) }}"
                                    data-deactivate-url="{{ route('admin.ai-providers.deactivate', $provider) }}">

                                    <td>
                                        <strong>{{ $provider->name }}</strong>
                                        <div class="small text-muted">{{ $provider->slug }}</div>
                                    </td>

                                    <td>{{ $provider->driver }}</td>

                                    <td>
                                        <code>{{ $provider->model }}</code>
                                    </td>

                                    <td class="status-cell">
                                        @if((bool) $provider->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="badge bg-dark">
                                            {{ $provider->reviews_count ?? 0 }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge {{ $avgScore >= 4 ? 'bg-success' : ($avgScore >= 3 ? 'bg-warning text-dark' : 'bg-danger') }}">
                                            {{ $avgScore }}/5
                                        </span>
                                    </td>

                                    <td class="action-cell">
    <div class="d-flex gap-2">

        @if((bool) $provider->is_active)
            <button type="button"
                    class="btn btn-danger btn-sm deactivateBtn">
                Deactivate
            </button>
        @else
            <button type="button"
                    class="btn btn-success btn-sm activateBtn">
                Activate
            </button>
        @endif

        <form method="POST"
              action="{{ route('admin.ai-providers.destroy', $provider) }}"
              onsubmit="return confirm('Delete this provider?')">
            @csrf
            @method('DELETE')

            <button class="btn btn-outline-danger btn-sm">
                Delete
            </button>
        </form>

    </div>
</td>
                                </tr>

                                <tr class="table-light">
                                    <td colspan="7">
                                        <form class="providerSettingsForm row g-2"
                                              data-url="{{ route('admin.ai-providers.update', $provider) }}">
                                            @csrf

                                            <div class="col-md-3">
                                                <label class="form-label small">Name</label>
                                                <input name="name" value="{{ $provider->name }}" class="form-control">
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label small">Model</label>
                                                <input name="model" value="{{ $provider->model }}" class="form-control">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label small">API URL</label>
                                                <input name="api_url" value="{{ $provider->api_url }}" class="form-control">
                                            </div>

                                            <div class="col-md-1">
                                                <label class="form-label small">Limit</label>
                                                <input type="number" name="daily_limit" value="{{ $provider->daily_limit }}" class="form-control">
                                            </div>

                                            <div class="col-md-1 d-flex align-items-end">
                                                <button type="submit" class="btn btn-primary w-100">
                                                    Save
                                                </button>
                                            </div>
                                        </form>

                                        <div class="row g-3 mt-3">
                                            <div class="col-md-2">
                                                <div class="border rounded p-3 bg-white text-center">
                                                    <div class="small text-muted">Responses</div>
                                                    <div class="fw-bold fs-5">{{ $provider->responses_count ?? 0 }}</div>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="border rounded p-3 bg-white text-center">
                                                    <div class="small text-muted">Accuracy</div>
                                                    <div class="fw-bold fs-5">{{ number_format($provider->avg_accuracy ?? 0, 1) }}/5</div>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="border rounded p-3 bg-white text-center">
                                                    <div class="small text-muted">Empathy</div>
                                                    <div class="fw-bold fs-5">{{ number_format($provider->avg_empathy ?? 0, 1) }}/5</div>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="border rounded p-3 bg-white text-center">
                                                    <div class="small text-muted">Safety</div>
                                                    <div class="fw-bold fs-5">{{ number_format($provider->avg_safety ?? 0, 1) }}/5</div>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="border rounded p-3 bg-white text-center">
                                                    <div class="small text-muted">Usefulness</div>
                                                    <div class="fw-bold fs-5">{{ number_format($provider->avg_usefulness ?? 0, 1) }}/5</div>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="border rounded p-3 bg-white text-center">
                                                    <div class="small text-muted">Reviews</div>
                                                    <div class="fw-bold fs-5">{{ $provider->reviews_count ?? 0 }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <button class="btn btn-outline-secondary btn-sm toggleReviews" type="button">
                                                Show / Hide Doctor Notes
                                            </button>

                                            <div class="providerReviews mt-3 d-none">
                                                @forelse($provider->doctorReviews as $review)
                                                    <div class="border rounded p-3 bg-white mb-2">
                                                        <div class="d-flex justify-content-between">
                                                            <strong>{{ $review->doctor?->name ?? 'Doctor' }}</strong>
                                                            <span class="badge bg-secondary">
                                                                {{ ucfirst($review->risk_level) }}
                                                            </span>
                                                        </div>

                                                        <div class="small text-muted">
                                                            Average: {{ $review->averageScore() ?? '-' }}/5
                                                        </div>

                                                        @if($review->needs_follow_up)
                                                            <span class="badge bg-warning text-dark mt-2">
                                                                Needs Follow-up
                                                            </span>
                                                        @endif

                                                        @if($review->notes)
                                                            <div class="mt-2 small">
                                                                {{ $review->notes }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                @empty
                                                    <div class="text-muted small">
                                                        No doctor reviews yet for this provider.
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        function showAlert(message, type = 'success') {
            $('#alertBox')
                .removeClass('d-none alert-success alert-danger')
                .addClass(type === 'success' ? 'alert-success' : 'alert-danger')
                .text(message);
        }

        function makeRowActive(row) {
            $('.provider-row').each(function () {
                const currentRow = $(this);

                currentRow.find('.status-cell').html('<span class="badge bg-secondary">Inactive</span>');
                currentRow.find('.action-cell').html(`
                    <button type="button" class="btn btn-success btn-sm activateBtn">
                        Activate
                    </button>
                `);
            });

            row.find('.status-cell').html('<span class="badge bg-success">Active</span>');
            row.find('.action-cell').html(`
                <button type="button" class="btn btn-danger btn-sm deactivateBtn">
                    Deactivate
                </button>
            `);
        }

        function makeRowInactive(row) {
            row.find('.status-cell').html('<span class="badge bg-secondary">Inactive</span>');
            row.find('.action-cell').html(`
                <button type="button" class="btn btn-success btn-sm activateBtn">
                    Activate
                </button>
            `);
        }

        $(document).on('click', '.activateBtn', function () {
            const button = $(this);
            const row = button.closest('.provider-row');

            button.prop('disabled', true).text('Activating...');

            $.ajax({
                url: row.data('activate-url'),
                method: 'POST',
                data: {_method: 'PATCH'},
                success: function () {
                    makeRowActive(row);
                    showAlert('Provider activated successfully.');
                },
                error: function (xhr) {
                    button.prop('disabled', false).text('Activate');
                    showAlert(xhr.responseJSON?.message || 'Could not activate provider.', 'error');
                }
            });
        });

        $(document).on('click', '.deactivateBtn', function () {
            const button = $(this);
            const row = button.closest('.provider-row');

            button.prop('disabled', true).text('Deactivating...');

            $.ajax({
                url: row.data('deactivate-url'),
                method: 'POST',
                data: {_method: 'PATCH'},
                success: function () {
                    makeRowInactive(row);
                    showAlert('Provider deactivated successfully.');
                },
                error: function (xhr) {
                    button.prop('disabled', false).text('Deactivate');
                    showAlert(xhr.responseJSON?.message || 'Could not deactivate provider.', 'error');
                }
            });
        });

        $(document).on('submit', '.providerSettingsForm', function (e) {
            e.preventDefault();

            const form = $(this);
            const button = form.find('button[type="submit"]');

            button.prop('disabled', true).text('Saving...');

            $.ajax({
                url: form.data('url'),
                method: 'POST',
                data: form.serialize() + '&_method=PATCH',
                success: function () {
                    button.prop('disabled', false).text('Save');
                    showAlert('Provider settings saved.');
                },
                error: function (xhr) {
                    button.prop('disabled', false).text('Save');
                    showAlert(xhr.responseJSON?.message || 'Could not save settings.', 'error');
                }
            });
        });

        $(document).on('click', '.toggleReviews', function () {
            $(this).closest('td').find('.providerReviews').toggleClass('d-none');
        });
    </script>
</x-app-layout>