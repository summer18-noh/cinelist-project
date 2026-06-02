<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CineList')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;900&family=Barlow:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Barlow', sans-serif;
            background: #09090f;
            color: #fff;
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: 220px;
            min-height: 100vh;
            background: #0c0c14;
            border-right: 1px solid rgba(255,255,255,0.06);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
        }

        .sidebar-logo {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 900;
            font-size: 22px;
            letter-spacing: 2px;
            color: #fff;
            text-decoration: none;
            display: block;
        }

        .sidebar-logo span {
            display: block;
            font-size: 10px;
            font-weight: 400;
            letter-spacing: 4px;
            color: #2979ff;
            margin-top: 2px;
        }

        .sidebar-nav {
            padding: 16px 0;
            flex: 1;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 20px;
            color: rgba(255,255,255,0.4);
            font-size: 13px;
            text-decoration: none;
            transition: all .2s;
            letter-spacing: .3px;
        }

        .nav-link:hover {
            color: rgba(255,255,255,0.75);
            background: rgba(255,255,255,0.03);
        }

        .nav-link.active {
            background: #2979ff;
            color: #fff;
            border-radius: 0 20px 20px 0;
            width: calc(100% - 12px);
        }

        .nav-link i {
            font-size: 17px;
        }

        .sidebar-user {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #2979ff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
            flex-shrink: 0;
            overflow: hidden;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* MAIN */
        .main-content {
            margin-left: 220px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            height: 56px;
            background: rgba(10,10,15,0.98);
            border-bottom: 1px solid rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-title {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 13px;
            letter-spacing: 3px;
            color: rgba(255,255,255,0.35);
        }

        .page-body {
            padding: 32px;
            flex: 1;
        }

        /* SECTION LABEL */
        .section-label {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 11px;
            letter-spacing: 3px;
            color: rgba(255,255,255,0.3);
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .section-label::before {
            content: '';
            width: 3px;
            height: 13px;
            background: #2979ff;
            border-radius: 2px;
        }

        /* CARDS */
        .dark-card {
            background: #0d0d14;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 10px;
        }

        /* TABLES */
        .dark-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .dark-table th {
            padding: 12px 16px;
            text-align: left;
            font-size: 10px;
            letter-spacing: 2px;
            color: rgba(255,255,255,0.25);
            font-weight: 500;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .dark-table td {
            padding: 13px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            color: rgba(255,255,255,0.72);
            vertical-align: middle;
        }

        .dark-table tr:last-child td {
            border-bottom: none;
        }

        .dark-table tbody tr:hover td {
            background: rgba(255,255,255,0.02);
        }

        /* GENRE PILLS */
        .genre-pill {
            font-size: 10px;
            padding: 2px 10px;
            border-radius: 20px;
            background: rgba(41,121,255,0.1);
            border: 1px solid rgba(41,121,255,0.2);
            color: #5c9fff;
            display: inline-block;
        }

        /* RATING */
        .rating-val {
            color: #2979ff;
            font-weight: 600;
        }

        /* BUTTONS */
        .btn-primary-custom {
            background: #2979ff;
            border: none;
            color: #fff;
            padding: 8px 18px;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: background .2s;
        }

        .btn-primary-custom:hover {
            background: #1a5fd4;
            color: #fff;
        }

        .btn-icon {
            width: 30px;
            height: 30px;
            border-radius: 6px;
            border: 1px solid rgba(255,255,255,0.08);
            background: transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: rgba(255,255,255,0.35);
            font-size: 13px;
            transition: all .15s;
            text-decoration: none;
        }

        .btn-icon:hover {
            background: rgba(255,255,255,0.06);
            color: #fff;
        }

        .btn-icon.danger:hover {
            background: rgba(220,50,50,0.12);
            color: #e05555;
            border-color: rgba(220,50,50,0.2);
        }

        /* LOGOUT TEXT BUTTON */
        .logout-btn {
            background: none;
            border: none;
            color: rgba(255,255,255,0.5);
            font-size: 12px;
            font-family: 'Barlow', sans-serif;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 6px;
            transition: all .2s ease;
        }

        .logout-btn:hover {
            color: #e05555;
            background: rgba(220,50,50,0.08);
        }

        /* FORM CONTROLS */
        .form-control-dark {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 7px;
            color: #fff;
            padding: 10px 14px;
            font-size: 13px;
            width: 100%;
            transition: border .2s;
        }

        .form-control-dark:focus {
            outline: none;
            border-color: #2979ff;
            background: rgba(41,121,255,0.05);
            color: #fff;
        }

        .form-control-dark::placeholder {
            color: rgba(255,255,255,0.25);
        }

        .form-label-dark {
            font-size: 11px;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.4);
            margin-bottom: 6px;
            display: block;
        }

        /* TOAST */
        .toast-container-custom {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .toast-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            min-width: 260px;
            animation: slideIn .3s ease;
        }

        .toast-success {
            background: #0f2010;
            border: 1px solid rgba(40,200,80,0.3);
            color: rgba(255,255,255,0.85);
        }

        .toast-error {
            background: #200f0f;
            border: 1px solid rgba(220,60,60,0.3);
            color: rgba(255,255,255,0.85);
        }

        .toast-info {
            background: #0f1525;
            border: 1px solid rgba(41,121,255,0.3);
            color: rgba(255,255,255,0.85);
        }

        .toast-icon {
            font-size: 16px;
            flex-shrink: 0;
        }

        .toast-success .toast-icon { color: #3ecf6a; }
        .toast-error   .toast-icon { color: #e05555; }
        .toast-info    .toast-icon { color: #2979ff; }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(20px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        /* SCROLLBAR */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #09090f; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 3px; }
    </style>

    @stack('styles')
</head>

<body>

{{-- SIDEBAR --}}
<div class="sidebar">
    <a href="{{ route('movies.index') }}" class="sidebar-logo">
        CINE<br>LIST <span>PERSONAL</span>
    </a>

    <nav class="sidebar-nav">
        <a href="{{ route('movies.index') }}"
           class="nav-link {{ request()->routeIs('movies*') ? 'active' : '' }}">
            <i class="bi bi-film"></i> Movies
        </a>

        <a href="{{ route('dashboard') }}"
           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>

        @if(auth()->user()->isAdmin())
            <a href="{{ route('users.index') }}"
               class="nav-link {{ request()->routeIs('users*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Users
            </a>
        @endif

        <a href="{{ route('profile.index') }}"
           class="nav-link {{ request()->routeIs('profile*') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i> Profile
        </a>
    </nav>

    {{-- SIDEBAR FOOTER --}}
    <div class="sidebar-user">
        <a href="{{ route('profile.index') }}"
           style="display:flex;align-items:center;gap:10px;flex:1;min-width:0;text-decoration:none;">

            <div class="user-avatar">
                @if(auth()->user()->profile_image)
                    <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="avatar">
                @else
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                @endif
            </div>

            <div style="flex:1;min-width:0;">
                <div style="font-size:12px;color:rgba(255,255,255,0.8);font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    {{ auth()->user()->name }}
                </div>
                <div style="font-size:10px;color:rgba(255,255,255,0.3);">
                    {{ ucfirst(auth()->user()->role) }}
                </div>
            </div>
        </a>

        <button type="button"
                class="logout-btn"
                data-bs-toggle="modal"
                data-bs-target="#logoutModal">
            Logout
        </button>
    </div>
</div>

{{-- MAIN --}}
<div class="main-content">
    <div class="topbar">
        <span class="topbar-title">@yield('page-title', 'CINELIST')</span>

        <a href="{{ route('profile.index') }}" style="text-decoration:none;">
            <div class="user-avatar" style="width:32px;height:32px;font-size:11px;cursor:pointer;">
                @if(auth()->user()->profile_image)
                    <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="avatar">
                @else
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                @endif
            </div>
        </a>
    </div>

    <div class="page-body">
        @yield('content')
    </div>
</div>

{{-- TOAST --}}
<div class="toast-container-custom" id="toastContainer"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function showToast(message, type = 'success') {
        const icons = {
            success: 'bi-check-circle-fill',
            error:   'bi-x-circle-fill',
            info:    'bi-info-circle-fill'
        };

        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');

        toast.className = `toast-item toast-${type}`;
        toast.innerHTML = `<i class="bi ${icons[type]} toast-icon"></i><span>${message}</span>`;

        container.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(20px)';
            toast.style.transition = 'all .3s';

            setTimeout(() => toast.remove(), 300);
        }, 3500);
    }

    @if(session('toast_success'))
        showToast("{{ session('toast_success') }}", 'success');
    @endif

    @if(session('toast_error'))
        showToast("{{ session('toast_error') }}", 'error');
    @endif

    @if(session('toast_info'))
        showToast("{{ session('toast_info') }}", 'info');
    @endif
</script>

@stack('scripts')

{{-- LOGOUT MODAL --}}
<form id="logoutForm" method="POST" action="{{ route('logout') }}" style="display:none;">
    @csrf
</form>

<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background:#0d0d14;border:1px solid rgba(255,255,255,0.08);border-radius:12px;">

            <div class="modal-header" style="border-bottom:1px solid rgba(255,255,255,0.06);padding:18px 22px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:36px;height:36px;border-radius:8px;background:rgba(220,50,50,0.12);border:1px solid rgba(220,50,50,0.2);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-box-arrow-right" style="color:#e05555;font-size:15px;"></i>
                    </div>

                    <h5 class="modal-title" style="font-family:'Barlow Condensed',sans-serif;font-size:18px;font-weight:700;color:#fff;margin:0;letter-spacing:.5px;">
                        Sign Out
                    </h5>
                </div>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        style="opacity:.4;">
                </button>
            </div>

            <div class="modal-body" style="padding:22px;">
                <p style="font-size:13px;color:rgba(255,255,255,0.5);margin:0;line-height:1.7;">
                    Are you sure you want to sign out of
                    <strong style="color:#fff;">CineList</strong>?
                </p>
            </div>

            <div class="modal-footer" style="border-top:1px solid rgba(255,255,255,0.06);padding:16px 22px;gap:8px;justify-content:flex-end;">
                <button type="button"
                        data-bs-dismiss="modal"
                        style="padding:8px 18px;border-radius:7px;border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.5);font-size:13px;background:none;cursor:pointer;font-family:'Barlow',sans-serif;">
                    Cancel
                </button>

                <button type="button"
                        onclick="document.getElementById('logoutForm').submit();"
                        style="padding:8px 18px;border-radius:7px;border:none;background:#e05555;color:#fff;font-size:13px;font-weight:600;cursor:pointer;font-family:'Barlow',sans-serif;display:flex;align-items:center;gap:6px;">
                    <i class="bi bi-box-arrow-right"></i>
                    Yes, Sign Out
                </button>
            </div>

        </div>
    </div>
</div>

</body>
</html>