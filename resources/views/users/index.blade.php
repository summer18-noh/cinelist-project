@extends('layouts.app')

@section('title', 'Users — CineList')
@section('page-title', 'USERS MANAGEMENT')

@push('styles')
<style>
    .role-badge { font-size:10px; padding:2px 10px; border-radius:20px; letter-spacing:1px; font-weight:500; }
    .role-admin { background:rgba(41,121,255,0.12); border:1px solid rgba(41,121,255,0.25); color:#5c9fff; }
    .role-user  { background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); color:rgba(255,255,255,0.4); }
    .u-avatar { width:36px; height:36px; border-radius:50%; background:#2979ff; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:600; flex-shrink:0; overflow:hidden; }
    .u-avatar img { width:100%; height:100%; object-fit:cover; }
</style>
@endpush

@section('content')

<div class="dark-card">
    <div style="padding:16px 20px;border-bottom:1px solid rgba(255,255,255,0.06);display:flex;align-items:center;justify-content:space-between;">
        <div class="section-label" style="margin-bottom:0;">ALL USERS</div>
        <a href="{{ route('users.create') }}" class="btn-primary-custom">
            <i class="bi bi-person-plus"></i> Add User
        </a>
    </div>

    @if($users->count())
    <table class="dark-table">
        <thead>
            <tr>
                <th>#</th>
                <th>USER</th>
                <th>EMAIL</th>
                <th>ROLE</th>
                <th>MOVIES</th>
                <th>JOINED</th>
                <th>ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $i => $user)
            <tr>
                <td style="color:rgba(255,255,255,0.2);font-size:12px;">{{ $users->firstItem() + $i }}</td>
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div class="u-avatar">
                            @if($user->profile_image)
                                <img src="{{ asset('storage/'.$user->profile_image) }}" alt="{{ $user->name }}">
                            @else
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            @endif
                        </div>
                        <div>
                            <div style="color:#fff;font-weight:500;font-size:13px;">
                                {{ $user->name }}
                                @if($user->id === auth()->id())
                                    <span style="font-size:9px;color:#2979ff;letter-spacing:1px;"> YOU</span>
                                @endif
                            </div>
                            @if($user->bio)
                            <div style="font-size:11px;color:rgba(255,255,255,0.25);">{{ Str::limit($user->bio, 30) }}</div>
                            @endif
                        </div>
                    </div>
                </td>
                <td style="font-size:12px;color:rgba(255,255,255,0.45);">{{ $user->email }}</td>
                <td>
                    <span class="role-badge {{ $user->role === 'admin' ? 'role-admin' : 'role-user' }}">
                        {{ strtoupper($user->role) }}
                    </span>
                </td>
                <td style="font-size:13px;color:rgba(255,255,255,0.5);">{{ $user->movies_count }}</td>
                <td style="font-size:12px;color:rgba(255,255,255,0.3);">{{ $user->created_at->format('M d, Y') }}</td>
                <td>
                    <div style="display:flex;gap:5px;">
                        <a href="{{ route('users.show', $user) }}" class="btn-icon" title="View">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('users.edit', $user) }}" class="btn-icon" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        @if($user->id !== auth()->id())
                        <button class="btn-icon danger" title="Delete"
                            onclick="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}')">
                            <i class="bi bi-trash"></i>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- PAGINATION --}}
    @if($users->hasPages())
    <div style="padding:12px 20px;border-top:1px solid rgba(255,255,255,0.05);display:flex;align-items:center;justify-content:space-between;">
        <span style="font-size:11px;color:rgba(255,255,255,0.25);">
            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
        </span>
        <div style="display:flex;gap:4px;">
            @if($users->onFirstPage())
                <span class="btn-icon" style="opacity:.3;cursor:default;"><i class="bi bi-chevron-left"></i></span>
            @else
                <a href="{{ $users->previousPageUrl() }}" class="btn-icon"><i class="bi bi-chevron-left"></i></a>
            @endif
            @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="btn-icon"
                    style="{{ $users->currentPage() == $page ? 'background:#2979ff;color:#fff;border-color:#2979ff;' : '' }}">
                    {{ $page }}
                </a>
            @endforeach
            @if($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" class="btn-icon"><i class="bi bi-chevron-right"></i></a>
            @else
                <span class="btn-icon" style="opacity:.3;cursor:default;"><i class="bi bi-chevron-right"></i></span>
            @endif
        </div>
    </div>
    @endif

    @else
    <div style="padding:60px;text-align:center;">
        <i class="bi bi-people" style="font-size:40px;color:rgba(255,255,255,0.08);display:block;margin-bottom:16px;"></i>
        <div style="font-size:14px;color:rgba(255,255,255,0.3);">No users found.</div>
    </div>
    @endif
</div>

{{-- DELETE CONFIRMATION MODAL --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background:#0d0d14;border:1px solid rgba(255,255,255,0.08);border-radius:12px;">

            <div class="modal-header" style="border-bottom:1px solid rgba(255,255,255,0.06);padding:18px 22px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:36px;height:36px;border-radius:8px;background:rgba(220,50,50,0.12);border:1px solid rgba(220,50,50,0.2);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-trash" style="color:#e05555;font-size:15px;"></i>
                    </div>
                    <h5 class="modal-title" style="font-family:'Barlow Condensed',sans-serif;font-size:18px;font-weight:700;color:#fff;margin:0;letter-spacing:.5px;">
                        Delete User
                    </h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="opacity:.4;"></button>
            </div>

            <div class="modal-body" style="padding:22px;">
                <p style="font-size:13px;color:rgba(255,255,255,0.5);margin:0;line-height:1.7;">
                    Are you sure you want to delete
                    <strong style="color:#fff;" id="modalUserName"></strong>?
                    <br>
                    <span style="font-size:12px;color:rgba(220,80,80,0.7);margin-top:6px;display:block;">
                        <i class="bi bi-exclamation-triangle" style="margin-right:4px;"></i>
                        This action cannot be undone.
                    </span>
                </p>
            </div>

            <div class="modal-footer" style="border-top:1px solid rgba(255,255,255,0.06);padding:16px 22px;gap:8px;justify-content:flex-end;">
                <button type="button" data-bs-dismiss="modal"
                    style="padding:8px 18px;border-radius:7px;border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.5);font-size:13px;background:none;cursor:pointer;font-family:'Barlow',sans-serif;">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" style="margin:0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        style="padding:8px 18px;border-radius:7px;border:none;background:#e05555;color:#fff;font-size:13px;font-weight:600;cursor:pointer;font-family:'Barlow',sans-serif;display:flex;align-items:center;gap:6px;">
                        <i class="bi bi-trash"></i> Yes, Delete
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function confirmDelete(id, name) {
    document.getElementById('modalUserName').textContent = '"' + name + '"';
    document.getElementById('deleteForm').action = '/users/' + id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush