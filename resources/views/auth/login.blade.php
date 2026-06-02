<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — CineList</title>

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

        /* MAIN CONTAINER (CENTER EVERYTHING) */
        .auth-container {
            width:100%;
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            gap:100px;
        }

        /* LEFT SIDE */
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

        .quote {
            font-size:15px;
            color:rgba(255,255,255,0.5);
            line-height:1.8;
            margin-top:32px;
        }

        /* RIGHT SIDE */
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
            display:block;
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

        .btn-login {
            width:100%;
            background:#2979ff;
            border:none;
            padding:12px;
            border-radius:7px;
            font-weight:600;
            margin-top:10px;
            transition:.2s;
        }

        .btn-login:hover {
            background:#1a5fd4;
        }

        .divider {
            border-color:rgba(255,255,255,0.06);
            margin:24px 0;
        }

        /* MOBILE */
        @media (max-width: 768px) {
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

        <div class="quote">
            "Cinema is a matter of what's in the frame and what's out."<br>
            <small style="font-size:12px;letter-spacing:2px;color:rgba(255,255,255,0.25);">
                — MARTIN SCORSESE
            </small>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="auth-right">
        <div class="auth-box">

            <div style="font-family:'Barlow Condensed';font-size:28px;font-weight:700;">
                Welcome back
            </div>

            <div style="font-size:13px;color:rgba(255,255,255,0.35);margin-bottom:30px;">
                Sign in to your account
            </div>

            @if($errors->any())
                <div style="background:rgba(220,60,60,0.08);padding:10px;border-radius:6px;margin-bottom:15px;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <div style="margin-bottom:15px;">
                    <label class="form-label">EMAIL ADDRESS</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div style="margin-bottom:15px;">
                    <label class="form-label">PASSWORD</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button class="btn-login">SIGN IN</button>
            </form>

            <hr class="divider">

            <div style="text-align:center;font-size:13px;color:rgba(255,255,255,0.4);">
                No account yet?
                <a href="{{ route('register') }}" style="color:#2979ff;">Create one</a>
            </div>

        </div>
    </div>

</div>

</body>
</html>