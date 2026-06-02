@extends('layouts.app')

@section('title', 'Edit Movie — CineList')
@section('page-title', 'EDIT MOVIE')

@section('content')

<div style="max-width:720px;">
    <div style="margin-bottom:24px;">
        <a href="{{ route('movies.index') }}" style="color:rgba(255,255,255,0.35);font-size:13px;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
            <i class="bi bi-arrow-left"></i> Back to Movies
        </a>
    </div>

    <div class="dark-card" style="padding:28px;">
        <div class="section-label">EDIT DETAILS</div>

        <form method="POST" action="{{ route('movies.update', $movie) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;">
                <div style="grid-column:1/-1;">
                    <label class="form-label-dark">TITLE <span style="color:#e05555;">*</span></label>
                    <input type="text" name="title" class="form-control-dark" value="{{ old('title', $movie->title) }}" required>
                    @error('title')<div style="color:#e05555;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="form-label-dark">DIRECTOR <span style="color:#e05555;">*</span></label>
                    <input type="text" name="director" class="form-control-dark" value="{{ old('director', $movie->director) }}" required>
                </div>

                <div>
                    <label class="form-label-dark">GENRE <span style="color:#e05555;">*</span></label>
                    <input type="text" name="genre" class="form-control-dark" value="{{ old('genre', $movie->genre) }}" required>
                </div>

                <div>
                    <label class="form-label-dark">CAST</label>
                    <input type="text" name="cast" class="form-control-dark" value="{{ old('cast', $movie->cast) }}">
                </div>

                <div>
                    <label class="form-label-dark">RELEASE YEAR</label>
                    <input type="number" name="release_year" class="form-control-dark" value="{{ old('release_year', $movie->release_year) }}" min="1888" max="{{ date('Y') + 2 }}">
                </div>

                <div>
                    <label class="form-label-dark">DURATION</label>
                    <input type="text" name="duration" class="form-control-dark" value="{{ old('duration', $movie->duration) }}" placeholder="e.g. 2h 12m">
                </div>

                <div>
                    <label class="form-label-dark">RATING (0–10)</label>
                    <input type="number" name="rating" class="form-control-dark" value="{{ old('rating', $movie->rating) }}" min="0" max="10" step="0.1">
                </div>

                <div>
                    <label class="form-label-dark">LANGUAGE</label>
                    <input type="text" name="language" class="form-control-dark" value="{{ old('language', $movie->language) }}">
                </div>

                <div>
                    <label class="form-label-dark">POSTER IMAGE</label>
                    @if($movie->poster)
                    <div style="margin-bottom:8px;display:flex;align-items:center;gap:10px;">
                        <img src="{{ asset('storage/'.$movie->poster) }}" style="width:40px;height:55px;object-fit:cover;border-radius:4px;border:1px solid rgba(255,255,255,0.1);">
                        <span style="font-size:11px;color:rgba(255,255,255,0.3);">Current poster</span>
                    </div>
                    @endif
                    <input type="file" name="poster" class="form-control-dark" accept="image/*" style="padding:8px 14px;">
                    <div style="font-size:10px;color:rgba(255,255,255,0.25);margin-top:4px;">Leave blank to keep current poster</div>
                </div>

                <div style="grid-column:1/-1;">
                    <label class="form-label-dark">DESCRIPTION / REVIEW</label>
                    <textarea name="description" class="form-control-dark" rows="3">{{ old('description', $movie->description) }}</textarea>
                </div>

                <div style="grid-column:1/-1;display:flex;align-items:center;gap:10px;">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $movie->is_featured) ? 'checked' : '' }} style="accent-color:#2979ff;width:15px;height:15px;">
                    <label for="is_featured" style="font-size:13px;color:rgba(255,255,255,0.55);cursor:pointer;">Mark as Featured Movie</label>
                </div>
            </div>

            <div style="margin-top:28px;padding-top:20px;border-top:1px solid rgba(255,255,255,0.06);display:flex;gap:10px;">
                <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg"></i> Save Changes</button>
                <a href="{{ route('movies.index') }}" style="padding:8px 18px;border-radius:7px;border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.45);font-size:13px;text-decoration:none;display:inline-flex;align-items:center;">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection