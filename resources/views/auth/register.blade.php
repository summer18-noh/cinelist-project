<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — CineList</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;900&family=Barlow:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family:'Barlow',sans-serif;
            background:#09090f;
            color:#fff;
            height:100vh;
        }

        /* CENTER EVERYTHING */
        .auth-container {
            width:100%;
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            gap:100px;
        }

        /* LEFT */
        .auth-left {
            width:400px;
            text-align:center;
        }

        .brand {
            font-family:'Barlow Condensed',sans-serif;
            font-weight:900;
            font-size:52px;
            line-height:1;
        }

        .brand span { color:#2979ff; }

        .brand-sub {
            font-size:12px;
            letter-spacing:4px;
            color:rgba(255,255,255,0.3);
            margin-top:8px;
        }

        .features {
            margin-top:40px;
            display:flex;
            flex-direction:column;
            gap:12px;
        }

        .feature-item {
            display:flex;
            align-items:center;
            gap:10px;
            font-size:13px;
            color:rgba(255,255,255,0.45);
            justify-content:center;
        }

        .feature-item i { color:#2979ff; }

        /* RIGHT */
        .auth-right {
            width:400px;
            display:flex;
            justify-content:center;
            align-items:center;
        }

        .auth-box {
            width:100%;
            max-width:320px;
        }

        /* FORM */
        .form-label {
            font-size:11px;
            letter-spacing:1px;
            color:rgba(255,255,255,0.4);
            margin-bottom:6px;
        }

        .form-control {
            background:rgba(255,255,255,0.04);
            border:1px solid rgba(255,255,255,0.1);
            border-radius:7px;
            color:#fff;
            padding:11px 14px;
            font-size:13px;
        }

        .form-control:focus {
            border-color:#2979ff;
            background:rgba(41,121,255,0.05);
            box-shadow:none;
            color:#fff;
        }

        .btn-register {
            width:100%;
            background:#2979ff;
            border:none;
            padding:12px;
            border-radius:7px;
            font-weight:600;
            margin-top:10px;
            transition:.2s;
        }

        .btn-register:hover {
            background:#1a5fd4;
        }

        .divider {
            border-color:rgba(255,255,255,0.06);
            margin:24px 0;
        }

        @media (max-width:768px) {
            .auth-container {
                flex-direction:column;
                gap:40px;
            }
        }
    </style>
</head>

<body>

<div class="auth-container">

    <!-- LEFT -->
    <div class="auth-left">
        <div class="brand">CINE<br><span>LIST</span></div>
        <div class="brand-sub">YOUR PERSONAL MOVIE LIST</div>

        <div class="features">
            <div class="feature-item"><i class="bi bi-film"></i> Track your movies</div>
            <div class="feature-item"><i class="bi bi-star"></i> Rate films</div>
            <div class="feature-item"><i class="bi bi-bar-chart"></i> View dashboard</div>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="auth-right">
        <div class="auth-box">

            <div style="font-family:'Barlow Condensed';font-size:28px;font-weight:700;">
                Create account
            </div>

            <div style="font-size:13px;color:rgba(255,255,255,0.35);margin-bottom:25px;">
                Start your movie list
            </div>

            <!-- FORM -->
            <form method="POST" action="{{ route('register.post') }}" class="needs-validation" novalidate>
                @csrf

                <!-- FULL NAME -->
                <div style="margin-bottom:15px;">
                    <label class="form-label">FULL NAME</label>
                    <input 
                        type="text"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        required
                        minlength="2"
                        value="{{ old('name') }}"
                    >
                    <div class="invalid-feedback">
                        Please enter your full name (at least 2 characters).
                    </div>
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- EMAIL -->
                <div style="margin-bottom:15px;">
                    <label class="form-label">EMAIL ADDRESS</label>
                    <input 
                        type="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        required
                        value="{{ old('email') }}"
                    >
                    <div class="invalid-feedback">
                        Please enter a valid email address.
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- PASSWORD -->
                <div style="margin-bottom:15px;">
                    <label class="form-label">PASSWORD</label>
                    <input 
                        type="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        required
                        minlength="6"
                    >
                    <div class="invalid-feedback">
                        Password must be at least 6 characters.
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- CONFIRM PASSWORD -->
                <div style="margin-bottom:15px;">
                    <label class="form-label">CONFIRM PASSWORD</label>
                    <input 
                        type="password"
                        name="password_confirmation"
                        class="form-control @error('password_confirmation') is-invalid @enderror"
                        required
                    >
                    <div class="invalid-feedback">
                        Please confirm your password.
                    </div>
                </div>

                <button class="btn-register">CREATE ACCOUNT</button>
            </form>

            <hr class="divider">

            <div style="text-align:center;font-size:13px;color:rgba(255,255,255,0.4);">
                Already have an account?
                <a href="{{ route('login') }}" style="color:#2979ff;">Sign in</a>
            </div>

        </div>
    </div>

</div>

<!-- BOOTSTRAP VALIDATION SCRIPT -->
<script>
(() => {
    'use strict';

    const forms = document.querySelectorAll('.needs-validation');

    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add('was-validated');
        }, false);
    });
})();
</script>

</body>
</html>