@extends('layouts.app')

@section('title', 'Edit User — CineList')
@section('page-title', 'EDIT USER')

@section('content')

<div style="max-width:620px;">
    <div style="margin-bottom:24px;">
        <a href="{{ route('users.index') }}" style="color:rgba(255,255,255,0.35);font-size:13px;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
            <i class="bi bi-arrow-left"></i> Back to Users
        </a>
    </div>

    <div class="dark-card" style="padding:28px;">
        <div style="display:flex;align-items:center;gap:14px;margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid rgba(255,255,255,0.05);">
            <div style="width:52px;height:52px;border-radius:50%;background:#2979ff;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:600;overflow:hidden;flex-shrink:0;">
                @if($user->profile_image)
                    <img src="{{ asset('storage/'.$user->profile_image) }}" style="width:100%;height:100%;object-fit:cover;">
                @else
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                @endif
            </div>
            <div>
                <div style="font-family:'Barlow Condensed',sans-serif;font-size:20px;font-weight:700;color:#fff;">{{ $user->name }}</div>
                <div style="font-size:12px;color:rgba(255,255,255,0.3);">{{ $user->email }}</div>
            </div>
        </div>

        <div class="section-label">EDIT DETAILS</div>

        <form method="POST" action="{{ route('users.update', $user) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;">

                <div style="grid-column:1/-1;">
                    <label class="form-label-dark">FULL NAME <span style="color:#e05555;">*</span></label>
                    <input type="text" name="name" class="form-control-dark" value="{{ old('name', $user->name) }}" required>
                    @error('name')<div style="color:#e05555;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <div style="grid-column:1/-1;">
                    <label class="form-label-dark">EMAIL ADDRESS <span style="color:#e05555;">*</span></label>
                    <input type="email" name="email" class="form-control-dark" value="{{ old('email', $user->email) }}" required>
                    @error('email')<div style="color:#e05555;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="form-label-dark">NEW PASSWORD</label>
                    <input type="password" name="password" class="form-control-dark" placeholder="Leave blank to keep current">
                    @error('password')<div style="color:#e05555;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="form-label-dark">CONFIRM NEW PASSWORD</label>
                    <input type="password" name="password_confirmation" class="form-control-dark" placeholder="Repeat new password">
                </div>

                <div>
                    <label class="form-label-dark">ROLE <span style="color:#e05555;">*</span></label>
                    <select name="role" class="form-control-dark" required>
                        <option value="user"  {{ old('role', $user->role) == 'user'  ? 'selected' : '' }} style="background:#0d0d14;">User</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }} style="background:#0d0d14;">Admin</option>
                    </select>
                </div>

                <div>
                    <label class="form-label-dark">PROFILE IMAGE</label>
                    @if($user->profile_image)
                    <div style="margin-bottom:8px;display:flex;align-items:center;gap:10px;">
                        <img src="{{ asset('storage/'.$user->profile_image) }}" style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:1px solid rgba(255,255,255,0.1);">
                        <span style="font-size:11px;color:rgba(255,255,255,0.3);">Current photo</span>
                    </div>
                    @endif
                    <input type="file" name="profile_image" class="form-control-dark" accept="image/*" style="padding:8px 14px;">
                    <div style="font-size:10px;color:rgba(255,255,255,0.25);margin-top:4px;">Leave blank to keep current</div>
                </div>

                <div style="grid-column:1/-1;">
                    <label class="form-label-dark">BIO</label>
                    <textarea name="bio" class="form-control-dark" rows="2" placeholder="Short bio...">{{ old('bio', $user->bio) }}</textarea>
                </div>
            </div>

            <div style="margin-top:28px;padding-top:20px;border-top:1px solid rgba(255,255,255,0.06);display:flex;gap:10px;">
                <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg"></i> Save Changes</button>
                <a href="{{ route('users.index') }}" style="padding:8px 18px;border-radius:7px;border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.45);font-size:13px;text-decoration:none;display:inline-flex;align-items:center;">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection