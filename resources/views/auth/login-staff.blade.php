<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #FFFFFF;
            --secondary: #1F2937;
            --accent: #FF6F00;
            --accent-hover: #F57C00;
            --light-bg: #F9FAFB;
            --border: #E5E7EB;
            --text-primary: #1F2937;
            --text-secondary: #6B7280;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            background: linear-gradient(135deg, #F9FAFB 0%, #FFFFFF 100%);
        }

        .login-container {
            display: flex;
            width: 100%;
            min-height: 100vh;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .login-left {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            background: var(--light-bg);
        }

        .login-left-content {
            max-width: 500px;
            color: var(--text-primary);
            animation: slideInLeft 0.8s ease-out;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .login-left h1 {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .login-left .subtitle {
            font-size: 1.125rem;
            opacity: 0.8;
            line-height: 1.8;
            margin-bottom: 2rem;
            color: var(--text-secondary);
        }

        .features {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            margin-top: 3rem;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.25rem;
            background: white;
            border-left: 3px solid var(--accent);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            animation: slideInLeft 0.8s ease-out backwards;
        }

        .feature-item:nth-child(1) { animation-delay: 0.2s; }
        .feature-item:nth-child(2) { animation-delay: 0.4s; }
        .feature-item:nth-child(3) { animation-delay: 0.6s; }

        .feature-item:hover {
            box-shadow: 0 8px 24px rgba(255, 111, 0, 0.2);
            transform: translateX(10px);
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            background: var(--accent);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .feature-item:hover .feature-icon {
            transform: rotate(360deg) scale(1.1);
        }

        .login-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--secondary) 0%, #111827 100%);
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }

        .login-right::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 111, 0, 0.1) 0%, transparent 70%);
            animation: pulse 15s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1) rotate(0deg); }
            50% { transform: scale(1.1) rotate(5deg); }
        }

        .login-box {
            width: 100%;
            max-width: 450px;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
            animation: slideInRight 0.8s ease-out;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo i {
            font-size: 3rem;
            color: var(--accent);
            margin-bottom: 1rem;
            animation: bounce 2s ease-in-out infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .login-box h2 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .subtitle-text {
            color: var(--text-secondary);
            margin-bottom: 2rem;
            font-size: 0.9375rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            animation: fadeInUp 0.6s ease-out backwards;
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.9375rem;
        }

        .form-input {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid var(--border);
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--light-bg);
            color: var(--text-primary);
        }

        .form-input::placeholder {
            color: var(--text-secondary);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--accent);
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 111, 0, 0.1);
        }

        .btn-login {
            width: 100%;
            padding: 1.125rem;
            background: var(--accent);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.0625rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 1px;
            animation: fadeInUp 0.6s ease-out 0.3s backwards;
        }

        .btn-login:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 111, 0, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .divider {
            text-align: center;
            margin: 2rem 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: rgba(221, 208, 200, 0.2);
        }

        .divider span {
            background: #323232;
            padding: 0 1rem;
            position: relative;
            color: rgba(221, 208, 200, 0.7);
            font-size: 0.875rem;
        }

        .member-login-link {
            text-align: center;
            padding: 1rem;
            background: rgba(221, 208, 200, 0.1);
            border-radius: 12px;
            border: 1px solid rgba(221, 208, 200, 0.2);
        }

        .member-login-link a {
            color: #DDD0C8;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .member-login-link a:hover {
            color: #C8BAB0;
        }

        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            border-left: 4px solid;
        }

        .alert-danger {
            background: rgba(204, 51, 51, 0.2);
            color: #FCC;
            border-left-color: #C33;
        }

        .alert-success {
            background: rgba(51, 102, 51, 0.2);
            color: #CFC;
            border-left-color: #363;
        }

        .role-selector {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .role-option {
            padding: 1rem;
            border: 2px solid rgba(221, 208, 200, 0.3);
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: rgba(221, 208, 200, 0.05);
        }

        .role-option:hover {
            border-color: #DDD0C8;
            background: rgba(221, 208, 200, 0.1);
        }

        .role-option.active {
            border-color: #DDD0C8;
            background: rgba(221, 208, 200, 0.15);
        }

        .role-option i {
            font-size: 2rem;
            color: #DDD0C8;
            margin-bottom: 0.5rem;
        }

        .role-option .role-name {
            color: #DDD0C8;
            font-weight: 600;
            font-size: 0.9375rem;
        }

        @media (max-width: 968px) {
            .login-container {
                flex-direction: column-reverse;
            }
            
            .login-left {
                min-height: 40vh;
            }
            
            .login-left h1 {
                font-size: 2rem;
            }

            .features {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Side - Branding -->
        <div class="login-left">
            <div class="login-left-content">
                <h1>Staff Portal</h1>
                <p class="subtitle">
                    Portal khusus untuk Admin dan Kasir. 
                    Kelola transaksi, produk, member, dan operasional toko 
                    dengan sistem yang efisien dan aman.
                </p>

                <div class="features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <strong style="display: block; margin-bottom: 0.25rem;">Akses Aman</strong>
                            <span style="opacity: 0.7; font-size: 0.9375rem;">Sistem keamanan berlapis</span>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-dashboard"></i>
                        </div>
                        <div>
                            <strong style="display: block; margin-bottom: 0.25rem;">Dashboard Lengkap</strong>
                            <span style="opacity: 0.7; font-size: 0.9375rem;">Monitor semua aktivitas real-time</span>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <div>
                            <strong style="display: block; margin-bottom: 0.25rem;">Manajemen Mudah</strong>
                            <span style="opacity: 0.7; font-size: 0.9375rem;">Interface user-friendly</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="login-right">
            <div class="login-box">
                <div class="logo">
                    <i class="fas fa-user-shield"></i>
                    <h2>Staff Login</h2>
                    <p class="subtitle-text">Admin & Kasir Portal</p>
                </div>

                @if($errors->any())
                <div class="alert alert-danger">
                    <strong><i class="fas fa-exclamation-circle"></i> Error!</strong>
                    <ul style="margin: 0.5rem 0 0 1.5rem; padding: 0;">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
                @endif

                <form action="{{ route('staff.login.post') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus placeholder="staff@example.com">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-input" required placeholder="••••••••">
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i> LOGIN
                    </button>
                </form>

                <div class="divider">
                    <span>Atau</span>
                </div>

                <div class="member-login-link">
                    <i class="fas fa-user"></i>
                    Login sebagai <a href="{{ route('member.login') }}">Member</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
