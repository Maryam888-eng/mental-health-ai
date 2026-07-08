@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">✏️ Edit Diary Entry</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('diary.update', $diary) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="content" class="form-label">Your Thoughts</label>
                            <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" 
                                      rows="8" placeholder="Write your thoughts here..." required>{{ old('content', $diary->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="mood" class="form-label">Mood</label>
                            <select name="mood" id="mood" class="form-select @error('mood') is-invalid @enderror">
                                <option value="happy" {{ old('mood', $diary->mood) == 'happy' ? 'selected' : '' }}>😊 Happy</option>
                                <option value="sad" {{ old('mood', $diary->mood) == 'sad' ? 'selected' : '' }}>😔 Sad</option>
                                <option value="anxious" {{ old('mood', $diary->mood) == 'anxious' ? 'selected' : '' }}>😰 Anxious</option>
                                <option value="angry" {{ old('mood', $diary->mood) == 'angry' ? 'selected' : '' }}>😡 Angry</option>
                                <option value="neutral" {{ old('mood', $diary->mood) == 'neutral' || !$diary->mood ? 'selected' : '' }}>😐 Neutral</option>
                            </select>
                            @error('mood')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" name="share_with_doctor" id="share_with_doctor" 
                                   class="form-check-input" {{ old('share_with_doctor', $diary->is_shared_with_doctor) ? 'checked' : '' }}>
                            <label for="share_with_doctor" class="form-check-label">
                                <i class="fas fa-user-md text-primary"></i> Share with your doctor
                            </label>
                            <small class="d-block text-muted">Your doctor can view this entry if shared.</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Entry
                            </button>
                            <a href="{{ route('diary.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Option -->
            <div class="card mt-3 border-danger">
                <div class="card-body">
                    <h5 class="text-danger"><i class="fas fa-exclamation-triangle"></i> Danger Zone</h5>
                    <p class="text-muted small">Delete this diary entry permanently. This action cannot be undone.</p>
                    <form action="{{ route('diary.destroy', $diary) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this entry?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete Entry
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection