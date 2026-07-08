<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <div class="container py-4">
        <div class="card shadow">
            <div class="card-header">
                <h4 class="mb-0">Add AI Provider</h4>
            </div>

            <div class="card-body">
                <form method="POST"
                      action="{{ route('admin.ai-providers.store') }}">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input name="name"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Slug</label>
                            <input name="slug"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Driver</label>
                            <input name="driver"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Model</label>
                            <input name="model"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">API URL</label>
                            <input name="api_url"
                                   class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Daily Limit</label>
                            <input type="number"
                                   name="daily_limit"
                                   value="100"
                                   class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Free Provider</label>

                            <div class="form-check mt-2">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="is_free"
                                       value="1"
                                       checked>

                                <label class="form-check-label">
                                    Is Free
                                </label>
                            </div>
                        </div>

                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button class="btn btn-primary">
                            Create Provider
                        </button>

                        <a href="{{ route('admin.ai-providers.index') }}"
                           class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout> 