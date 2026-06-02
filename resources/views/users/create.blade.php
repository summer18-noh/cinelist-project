@extends('layouts.app')

@section('title', 'Add User — CineList')
@section('page-title', 'ADD USER')

@section('content')

<div style="max-width:620px;">
    <div style="margin-bottom:24px;">
        <a href="{{ route('users.index') }}" style="color:rgba(255,255,255,0.35);font-size:13px;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
            <i class="bi bi-arrow-left"></i> Back to Users
        </a>
    </div>

    <div class="dark-card" style="padding:28px;">
        <div class="section-label">USER DETAILS</div>

        <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
            @csrf

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;">

                <div style="grid-column:1/-1;">
                    <label class="form-label-dark">FULL NAME <span style="color:#e05555;">*</span></label>
                    <input type="text" name="name" class="form-control-dark" value="{{ old('name') }}" placeholder="Juan Dela Cruz" required>
                    @error('name')<div style="color:#e05555;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <div style="grid-column:1/-1;">
                    <label class="form-label-dark">EMAIL ADDRESS <span style="color:#e05555;">*</span></label>
                    <input type="email" name="email" class="form-control-dark" value="{{ old('email') }}" placeholder="user@example.com" required>
                    @error('email')<div style="color:#e05555;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="form-label-dark">PASSWORD <span style="color:#e05555;">*</span></label>
                    <input type="password" name="password" class="form-control-dark" placeholder="Min. 6 characters" required>
                    @error('password')<div style="color:#e05555;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="form-label-dark">CONFIRM PASSWORD <span style="color:#e05555;">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control-dark" placeholder="Repeat password" required>
                </div>

                <div>
                    <label class="form-label-dark">ROLE <span style="color:#e05555;">*</span></label>
                    <select name="role" class="form-control-dark" required>
                        <option value="user"  {{ old('role') == 'user'  ? 'selected' : '' }} style="background:#0d0d14;">User</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }} style="background:#0d0d14;">Admin</option>
                    </select>
                    @error('role')<div style="color:#e05555;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="form-label-dark">PROFILE IMAGE</label>
                    <input type="file" name="profile_image" class="form-control-dark" accept="image/*" style="padding:8px 14px;">
                    <div style="font-size:10px;color:rgba(255,255,255,0.25);margin-top:4px;">JPG, PNG, WEBP — max 2MB</div>
                </div>

                <div style="grid-column:1/-1;">
                    <label class="form-label-dark">BIO</label>
                    <textarea name="bio" class="form-control-dark" rows="2" placeholder="Short bio...">{{ old('bio') }}</textarea>
                </div>
            </div>

            <div style="margin-top:28px;padding-top:20px;border-top:1px solid rgba(255,255,255,0.06);display:flex;gap:10px;">
                <button type="submit" class="btn-primary-custom"><i class="bi bi-person-plus"></i> Add User</button>
                <a href="{{ route('users.index') }}" style="padding:8px 18px;border-radius:7px;border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.45);font-size:13px;text-decoration:none;display:inline-flex;align-items:center;">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection