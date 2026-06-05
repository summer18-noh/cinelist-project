@extends('layouts.app')

@section('title', 'Profile — CineList')
@section('page-title', 'MY PROFILE')

@push('styles')
<style>
    .avatar-wrap { position:relative; display:inline-block; }
    .avatar-large { width:90px; height:90px; border-radius:50%; background:#2979ff; display:flex; align-items:center; justify-content:center; font-size:30px; font-weight:600; border:3px solid #09090f; overflow:hidden; flex-shrink:0; }
    .avatar-large img { width:100%; height:100%; object-fit:cover; }
    .avatar-upload-btn { position:absolute; bottom:2px; right:2px; width:26px; height:26px; border-radius:50%; background:#2979ff; border:2px solid #09090f; display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:11px; color:#fff; }
    .tab-nav { display:flex; gap:0; border-bottom:1px solid rgba(255,255,255,0.06); margin-bottom:24px; }
    .tab-btn { padding:12px 0; margin-right:28px; font-size:12px; letter-spacing:1px; color:rgba(255,255,255,0.3); cursor:pointer; border:none; border-bottom:2px solid transparent; background:none; position:relative; top:1px; transition:color .2s; font-family:'Barlow',sans-serif; }
    .tab-btn.active { color:#fff; border-bottom-color:#2979ff; }
    .tab-content { display:none; }
    .tab-content.active { display:block; }
    .stat-mini { background:#0d0d14; border:1px solid rgba(255,255,255,0.06); border-radius:8px; padding:16px 20px; text-align:center; }
    .stat-mini-val { font-family:'Barlow Condensed',sans-serif; font-size:32px; font-weight:900; color:#fff; line-height:1; }
    .stat-mini-label { font-size:10px; letter-spacing:2px; color:rgba(255,255,255,0.25); margin-top:4px; }
    .movie-grid-card { background:#0d0d14; border:1px solid rgba(255,255,255,0.06); border-radius:8px; overflow:hidden; transition:border-color .2s; }
    .movie-grid-card:hover { border-color:rgba(41,121,255,0.3); }
    .movie-poster-wrap { height:120px; background:#131320; display:flex; align-items:center; justify-content:center; overflow:hidden; position:relative; }
    .movie-poster-wrap img { width:100%; height:100%; object-fit:cover; }
</style>
@endpush

@section('content')

{{-- PROFILE HEADER CARD --}}
<div class="dark-card" style="padding:24px;margin-bottom:20px;">
    <div style="display:flex;align-items:center;gap:20px;">

        {{-- AVATAR --}}
        <div style="position:relative;flex-shrink:0;">
            <div class="avatar-large" id="avatarPreview">
                @if($user->profile_image)
                    <img src="{{ asset('storage/'.$user->profile_image) }}" alt="{{ $user->name }}">
                @else
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                @endif
            </div>
            <label for="quickUpload" class="avatar-upload-btn" title="Change photo">
                <i class="bi bi-camera"></i>
            </label>
            <input type="file" id="quickUpload" accept="image/*" style="display:none;" onchange="previewAvatar(this)">
        </div>

        {{-- INFO --}}
        <div style="flex:1;">
            <div style="font-family:'Barlow Condensed',sans-serif;font-size:26px;font-weight:900;color:#fff;line-height:1;">
                {{ $user->name }}
            </div>
            <div style="font-size:12px;color:rgba(255,255,255,0.35);margin-top:3px;">
                {{ $user->email }}
            </div>
            @if($user->bio)
            <div style="font-size:12px;color:rgba(255,255,255,0.4);margin-top:6px;">
                {{ $user->bio }}
            </div>
            @endif
            <div style="display:flex;align-items:center;gap:8px;margin-top:10px;">
                <span style="font-size:10px;padding:2px 10px;border-radius:20px;letter-spacing:1px;
                    {{ $user->isAdmin()
                        ? 'background:rgba(41,121,255,0.12);border:1px solid rgba(41,121,255,0.25);color:#5c9fff;'
                        : 'background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.4);'
                    }}">
                    {{ strtoupper($user->role) }}
                </span>
                <span style="font-size:11px;color:rgba(255,255,255,0.2);">
                    Member since {{ $user->created_at->format('M Y') }}
                </span>
            </div>
        </div>
    </div>
</div>

{{-- MINI STATS --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:20px;">
    <div class="stat-mini">
        <div class="stat-mini-val">{{ $user->movies()->count() }}</div>
        <div class="stat-mini-label">TOTAL MOVIES</div>
    </div>
    <div class="stat-mini">
        <div class="stat-mini-val">
            {{ $user->movies()->avg('rating') ? number_format($user->movies()->avg('rating'),1) : '—' }}
        </div>
        <div class="stat-mini-label">AVG RATING</div>
    </div>
    <div class="stat-mini">
        @php
            $genreCount = $user->movies()->pluck('genre')
                ->flatMap(fn($g) => array_map('trim', explode(',', $g)))
                ->unique()->filter()->count();
        @endphp
        <div class="stat-mini-val">{{ $genreCount }}</div>
        <div class="stat-mini-label">GENRES</div>
    </div>
    <div class="stat-mini">
        <div class="stat-mini-val">
            {{ $user->movies()->whereMonth('created_at', now()->month)->count() }}
        </div>
        <div class="stat-mini-label">THIS MONTH</div>
    </div>
</div>

{{-- TABS --}}
<div class="tab-nav">
    <button type="button" class="tab-btn active" id="btn-movies"
        onclick="switchTab('movies')">MY MOVIES</button>
    <button type="button" class="tab-btn" id="btn-settings"
        onclick="switchTab('settings')">EDIT PROFILE</button>
    <button type="button" class="tab-btn" id="btn-password"
        onclick="switchTab('password')">CHANGE PASSWORD</button>
</div>

{{-- TAB: MY MOVIES --}}
<div class="tab-content active" id="tab-movies">
    @if($movies->count())
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;">
        @foreach($movies as $movie)
        <div class="movie-grid-card">
            <div class="movie-poster-wrap">
                @if($movie->poster)
                    <img src="{{ asset('storage/'.$movie->poster) }}" alt="{{ $movie->title }}">
                @else
                    <i class="bi bi-film" style="font-size:28px;color:rgba(255,255,255,0.07);"></i>
                @endif
                <div style="position:absolute;top:8px;right:8px;background:rgba(0,0,0,0.7);border:1px solid rgba(41,121,255,0.3);padding:2px 7px;border-radius:5px;font-size:11px;color:#2979ff;font-weight:600;">
                    <i class="bi bi-star-fill" style="font-size:9px;"></i> {{ number_format($movie->rating,1) }}
                </div>
            </div>
            <div style="padding:12px;">
                <div style="font-family:'Barlow Condensed',sans-serif;font-size:16px;font-weight:700;color:#fff;margin-bottom:2px;">
                    {{ $movie->title }}
                </div>
                <div style="font-size:11px;color:rgba(255,255,255,0.3);margin-bottom:8px;">
                    {{ $movie->release_year }} · {{ Str::limit($movie->director,20) }}
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <div style="display:flex;gap:4px;flex-wrap:wrap;">
                        @foreach(array_slice(explode(',', $movie->genre), 0, 2) as $g)
                            <span class="genre-pill" style="font-size:9px;">{{ trim($g) }}</span>
                        @endforeach
                    </div>
                    <div style="display:flex;gap:4px;">
                        <a href="{{ route('movies.show', $movie) }}" class="btn-icon" style="width:26px;height:26px;font-size:11px;"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('movies.edit', $movie) }}" class="btn-icon" style="width:26px;height:26px;font-size:11px;"><i class="bi bi-pencil"></i></a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @if($movies->hasPages())
        <div style="margin-top:16px;">{{ $movies->links() }}</div>
    @endif
    @else
    <div style="text-align:center;padding:60px;background:#0d0d14;border-radius:10px;border:1px solid rgba(255,255,255,0.05);">
        <i class="bi bi-film" style="font-size:40px;color:rgba(255,255,255,0.08);display:block;margin-bottom:14px;"></i>
        <div style="font-size:14px;color:rgba(255,255,255,0.3);margin-bottom:14px;">No movies added yet.</div>
        <a href="{{ route('movies.create') }}" class="btn-primary-custom"><i class="bi bi-plus-lg"></i> Add Movie</a>
    </div>
    @endif
</div>

{{-- TAB: EDIT PROFILE --}}
<div class="tab-content" id="tab-settings">
    <div class="dark-card" style="padding:28px;max-width:580px;">
        <div class="section-label">PERSONAL INFORMATION</div>
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="file" name="profile_image" id="profileImageInput" accept="image/*" style="display:none;">

            <div style="display:flex;flex-direction:column;gap:16px;">
                <div>
                    <label class="form-label-dark">FULL NAME <span style="color:#e05555;">*</span></label>
                    <input type="text" name="name" class="form-control-dark" value="{{ old('name', $user->name) }}" required>
                    @error('name')<div style="color:#e05555;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="form-label-dark">EMAIL ADDRESS <span style="color:#e05555;">*</span></label>
                    <input type="email" name="email" class="form-control-dark" value="{{ old('email', $user->email) }}" required>
                    @error('email')<div style="color:#e05555;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="form-label-dark">BIO</label>
                    <textarea name="bio" class="form-control-dark" rows="3" placeholder="Tell something about yourself...">{{ old('bio', $user->bio) }}</textarea>
                </div>
                <div>
                    <label class="form-label-dark">PROFILE PHOTO</label>
                    <label for="profileImageInput" class="btn-primary-custom" style="cursor:pointer;display:inline-flex;font-size:12px;padding:7px 14px;">
                        <i class="bi bi-upload"></i> Choose Photo
                    </label>
                    <span id="fileNameLabel" style="font-size:11px;color:rgba(255,255,255,0.3);margin-left:10px;">No file chosen</span>
                </div>
            </div>

            <div style="margin-top:24px;padding-top:18px;border-top:1px solid rgba(255,255,255,0.06);display:flex;gap:10px;">
                <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg"></i> Save Changes</button>
                <button type="button" onclick="switchTab('movies')"
                    style="padding:8px 18px;border-radius:7px;border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.45);font-size:13px;background:none;cursor:pointer;">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

{{-- TAB: CHANGE PASSWORD --}}
<div class="tab-content" id="tab-password">
    <div class="dark-card" style="padding:28px;max-width:500px;">
        <div class="section-label">CHANGE PASSWORD</div>
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="display:flex;flex-direction:column;gap:16px;">
                <div>
                    <label class="form-label-dark">CURRENT PASSWORD <span style="color:#e05555;">*</span></label>
                    <input type="password" name="current_password" class="form-control-dark {{ $errors->has('current_password') ? 'border-danger' : '' }}" placeholder="Your current password">
                    @error('current_password')
                        <div style="color:#e05555;font-size:11px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="form-label-dark">NEW PASSWORD <span style="color:#e05555;">*</span></label>
                    <input type="password" name="new_password" class="form-control-dark {{ $errors->has('new_password') ? 'border-danger' : '' }}" placeholder="Min. 6 characters">
                    @error('new_password')
                        <div style="color:#e05555;font-size:11px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="form-label-dark">CONFIRM NEW PASSWORD <span style="color:#e05555;">*</span></label>
                    <input type="password" name="new_password_confirmation" class="form-control-dark" placeholder="Repeat new password">
                </div>
                <div style="padding:12px 14px;background:rgba(41,121,255,0.05);border:1px solid rgba(41,121,255,0.12);border-radius:7px;font-size:12px;color:rgba(255,255,255,0.35);">
                    <i class="bi bi-shield-check" style="color:#2979ff;margin-right:6px;"></i>
                    Password must be at least 6 characters.
                </div>
            </div>

            <div style="margin-top:24px;padding-top:18px;border-top:1px solid rgba(255,255,255,0.06);">
                <button type="submit" class="btn-primary-custom">
                    <i class="bi bi-key"></i> Update Password
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function switchTab(name) {
    document.querySelectorAll('.tab-content').forEach(function(t) {
        t.classList.remove('active');
        t.style.display = 'none';
    });
    document.querySelectorAll('.tab-btn').forEach(function(b) {
        b.classList.remove('active');
    });
    document.getElementById('tab-' + name).classList.add('active');
    document.getElementById('tab-' + name).style.display = 'block';
    document.getElementById('btn-' + name).classList.add('active');
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('tab-movies').style.display = 'block';
    document.getElementById('tab-settings').style.display = 'none';
    document.getElementById('tab-password').style.display = 'none';

    @if($errors->has('current_password') || $errors->has('new_password'))
        switchTab('password');
    @elseif($errors->has('name') || $errors->has('email'))
        switchTab('settings');
    @endif
});

document.getElementById('quickUpload').addEventListener('change', function() {
    const formInput = document.getElementById('profileImageInput');
    if (formInput && this.files[0]) {
        const dt = new DataTransfer();
        dt.items.add(this.files[0]);
        formInput.files = dt.files;
    }
    const label = document.getElementById('fileNameLabel');
    if (label && this.files[0]) label.textContent = this.files[0].name;
    previewAvatar(this);
    switchTab('settings');
    if (typeof showToast === 'function') showToast('Photo selected! Click Save Changes.', 'info');
});

const profileImgInput = document.getElementById('profileImageInput');
if (profileImgInput) {
    profileImgInput.addEventListener('change', function() {
        if (this.files[0]) {
            const label = document.getElementById('fileNameLabel');
            if (label) label.textContent = this.files[0].name;
        }
    });
}

function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatarPreview').innerHTML =
                '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;">';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush