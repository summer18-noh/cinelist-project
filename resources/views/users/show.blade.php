@extends('layouts.app')

@section('title', $user->name . ' — CineList')
@section('page-title', 'USER DETAIL')

@section('content')

<div style="margin-bottom:20px;">
    <a href="{{ route('users.index') }}" style="color:rgba(255,255,255,0.35);font-size:13px;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
        <i class="bi bi-arrow-left"></i> Back to Users
    </a>
</div>

<div style="display:grid;grid-template-columns:240px 1fr;gap:20px;">

    {{-- USER CARD --}}
    <div>
        <div class="dark-card" style="padding:24px;text-align:center;">
            <div style="width:80px;height:80px;border-radius:50%;background:#2979ff;display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:600;margin:0 auto 16px;overflow:hidden;">
                @if($user->profile_image)
                    <img src="{{ asset('storage/'.$user->profile_image) }}" style="width:100%;height:100%;object-fit:cover;">
                @else
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                @endif
            </div>
            <div style="font-family:'Barlow Condensed',sans-serif;font-size:22px;font-weight:700;color:#fff;margin-bottom:4px;">{{ $user->name }}</div>
            <span class="role-badge {{ $user->role === 'admin' ? 'role-admin' : 'role-user' }}" style="font-size:10px;padding:2px 10px;border-radius:20px;letter-spacing:1px;">
                {{ strtoupper($user->role) }}
            </span>
            @if($user->bio)
            <p style="font-size:12px;color:rgba(255,255,255,0.35);margin-top:12px;line-height:1.6;">{{ $user->bio }}</p>
            @endif

            <div style="margin-top:18px;padding-top:16px;border-top:1px solid rgba(255,255,255,0.05);">
                <div style="font-size:11px;color:rgba(255,255,255,0.25);margin-bottom:3px;">EMAIL</div>
                <div style="font-size:12px;color:rgba(255,255,255,0.6);word-break:break-all;">{{ $user->email }}</div>
            </div>

            <div style="margin-top:14px;">
                <div style="font-size:11px;color:rgba(255,255,255,0.25);margin-bottom:3px;">JOINED</div>
                <div style="font-size:12px;color:rgba(255,255,255,0.6);">{{ $user->created_at->format('F d, Y') }}</div>
            </div>

            <div style="margin-top:14px;">
                <div style="font-size:11px;color:rgba(255,255,255,0.25);margin-bottom:3px;">TOTAL MOVIES</div>
                <div style="font-family:'Barlow Condensed',sans-serif;font-size:28px;font-weight:900;color:#2979ff;">{{ $movies->total() }}</div>
            </div>

            <div style="margin-top:18px;display:flex;gap:6px;">
                <a href="{{ route('users.edit', $user) }}" class="btn-primary-custom" style="flex:1;justify-content:center;font-size:12px;padding:7px;"><i class="bi bi-pencil"></i> Edit</a>
                @if($user->id !== auth()->id())
                <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Delete {{ addslashes($user->name) }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-icon danger" style="width:34px;height:34px;"><i class="bi bi-trash"></i></button>
                </form>
                @endif
            </div>
        </div>
    </div>

    {{-- USER MOVIES --}}
    <div class="dark-card">
        <div style="padding:18px 20px;border-bottom:1px solid rgba(255,255,255,0.06);">
            <div class="section-label" style="margin-bottom:0;">MOVIES BY {{ strtoupper($user->name) }}</div>
        </div>

        @if($movies->count())
        <table class="dark-table">
            <thead>
                <tr>
                    <th>TITLE</th>
                    <th>GENRE</th>
                    <th>YEAR</th>
                    <th>RATING</th>
                </tr>
            </thead>
            <tbody>
                @foreach($movies as $movie)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:32px;height:42px;border-radius:4px;background:#131320;display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0;">
                                @if($movie->poster)
                                    <img src="{{ asset('storage/'.$movie->poster) }}" style="width:100%;height:100%;object-fit:cover;">
                                @else
                                    <i class="bi bi-film" style="font-size:12px;color:rgba(255,255,255,0.1);"></i>
                                @endif
                            </div>
                            <span style="font-size:13px;color:rgba(255,255,255,0.8);">{{ $movie->title }}</span>
                        </div>
                    </td>
                    <td>
                        @foreach(array_slice(explode(',', $movie->genre), 0, 2) as $g)
                            <span class="genre-pill">{{ trim($g) }}</span>
                        @endforeach
                    </td>
                    <td style="font-size:12px;color:rgba(255,255,255,0.35);">{{ $movie->release_year ?? '—' }}</td>
                    <td><span class="rating-val"><i class="bi bi-star-fill" style="font-size:11px;"></i> {{ number_format($movie->rating,1) }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($movies->hasPages())
        <div style="padding:12px 20px;border-top:1px solid rgba(255,255,255,0.05);">
            {{ $movies->links() }}
        </div>
        @endif
        @else
        <div style="padding:48px;text-align:center;">
            <i class="bi bi-film" style="font-size:32px;color:rgba(255,255,255,0.08);display:block;margin-bottom:12px;"></i>
            <div style="font-size:13px;color:rgba(255,255,255,0.25);">This user has no movies yet.</div>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .role-badge { font-size:10px; padding:2px 10px; border-radius:20px; letter-spacing:1px; font-weight:500; }
    .role-admin { background:rgba(41,121,255,0.12); border:1px solid rgba(41,121,255,0.25); color:#5c9fff; }
    .role-user  { background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); color:rgba(255,255,255,0.4); }
</style>
@endpush

@endsection