<div class="card shadow-sm">
    <div class="card-header bg-white">
        <strong>{{ $type }}</strong>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th style="width: 180px;">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <strong>{{ $user->name }}</strong>
                        </td>

                        <td>{{ $user->email }}</td>

                        <td>
                            @if($user->role === 'doctor')
                                <span class="badge bg-primary">Doctor</span>
                            @else
                                <span class="badge bg-info text-dark">Patient</span>
                            @endif
                        </td>

                        <td>
                            {{ $user->created_at?->format('d M Y') }}
                        </td>

                        <td>
                            <button type="button"
                                    class="btn btn-warning btn-sm editUserBtn"
                                    data-id="{{ $user->id }}"
                                    data-name="{{ $user->name }}"
                                    data-email="{{ $user->email }}"
                                    data-role="{{ $user->role }}">
                                Edit
                            </button>

                            <button type="button"
                                    class="btn btn-danger btn-sm deleteUserBtn"
                                    data-id="{{ $user->id }}">
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            No {{ strtolower($type) }} found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>