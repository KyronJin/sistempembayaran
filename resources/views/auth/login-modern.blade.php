<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Sistem POS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-left {
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
            padding: 60px 40px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-right {
            padding: 60px 40px;
        }

        .form-input {
            width: 100%;
            padding: 16px;
            border: 2px solid #E5E7EB;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #4F46E5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .btn-primary {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
        }

        .feature-item {
            display: flex;
            align-items: start;
            gap: 16px;
            margin-bottom: 24px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            backdrop-filter: blur(10px);
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
            }
            .login-left {
                padding: 40px 30px;
            }
            .login-right {
                padding: 40px 30px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Side - Branding -->
        <div class="login-left">
            <div style="margin-bottom: 40px;">
                <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.2); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin-bottom: 24px;">
                    <i class="fas fa-store" style="font-size: 40px;"></i>
                </div>
                <h1 style="font-size: 36px; font-weight: 800; margin-bottom: 12px;">Sistem POS</h1>
                <p style="font-size: 16px; opacity: 0.9;">Platform manajemen penjualan modern untuk bisnis Anda</p>
            </div>

            <div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-bolt" style="font-size: 24px;"></i>
                    </div>
                    <div>
                        <div style="font-weight: 700; font-size: 16px; margin-bottom: 4px;">Transaksi Cepat</div>
                        <div style="opacity: 0.9; font-size: 14px;">Proses penjualan dengan QR scanner dan sistem yang responsif</div>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line" style="font-size: 24px;"></i>
                    </div>
                    <div>
                        <div style="font-weight: 700; font-size: 16px; margin-bottom: 4px;">Laporan Real-time</div>
                        <div style="opacity: 0.9; font-size: 14px;">Pantau performa penjualan dengan analitik mendalam</div>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt" style="font-size: 24px;"></i>
                    </div>
                    <div>
                        <div style="font-weight: 700; font-size: 16px; margin-bottom: 4px;">Keamanan Terjamin</div>
                        <div style="opacity: 0.9; font-size: 14px;">Data Anda aman dengan enkripsi tingkat enterprise</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="login-right">
            <a href="{{ route('home') }}" style="display: inline-flex; align-items: center; gap: 8px; color: #6B7280; text-decoration: none; margin-bottom: 32px; font-weight: 600; transition: color 0.2s;" onmouseover="this.style.color='#4F46E5'" onmouseout="this.style.color='#6B7280'">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>

            <div style="margin-bottom: 40px;">
                <h2 style="font-size: 32px; font-weight: 800; color: #111827; margin-bottom: 8px;">
                    Selamat Datang! 👋
                </h2>
                <p style="color: #6B7280; font-size: 15px;">Masuk untuk mengakses dashboard Anda</p>
            </div>

            @if ($errors->any())
                <div style="background: #FEE2E2; border-left: 4px solid #EF4444; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
                    <div style="display: flex; align-items: center; gap: 12px; color: #991B1B;">
                        <i class="fas fa-exclamation-circle" style="font-size: 20px;"></i>
                        <div>
                            <div style="font-weight: 700; margin-bottom: 4px;">Terjadi Kesalahan</div>
                            <ul style="list-style: none; padding: 0; margin: 0;">
                                @foreach ($errors->all() as $error)
                                    <li style="font-size: 14px;">• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div style="background: #D1FAE5; border-left: 4px solid #10B981; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
                    <div style="display: flex; align-items: center; gap: 12px; color: #065F46;">
                        <i class="fas fa-check-circle" style="font-size: 20px;"></i>
                        <div style="font-weight: 600; font-size: 14px;">{{ session('success') }}</div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}">
                @csrf
                
                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-weight: 700; color: #374151; margin-bottom: 8px; font-size: 14px;">
                        <i class="fas fa-envelope" style="color: #4F46E5; margin-right: 6px;"></i>
                        Email Address
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email" 
                           class="form-input" placeholder="your@email.com" />
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-weight: 700; color: #374151; margin-bottom: 8px; font-size: 14px;">
                        <i class="fas fa-lock" style="color: #4F46E5; margin-right: 6px;"></i>
                        Password
                    </label>
                    <input type="password" name="password" required autocomplete="current-password" 
                           class="form-input" placeholder="••••••••" />
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="remember" style="width: 18px; height: 18px; cursor: pointer;" />
                        <span style="color: #6B7280; font-size: 14px;">Ingat saya</span>
                    </label>
                    <a href="#" style="color: #4F46E5; font-size: 14px; font-weight: 600; text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                        Lupa password?
                    </a>
                </div>

                <button type="submit" class="btn-primary">
                    <i class="fas fa-sign-in-alt" style="margin-right: 8px;"></i>
                    Masuk Sekarang
                </button>
            </form>

            <div style="margin-top: 32px; text-align: center; padding-top: 32px; border-top: 2px solid #F3F4F6;">
                <span style="color: #6B7280; font-size: 14px;">Belum punya akun? </span>
                <a href="{{ route('register') }}" style="color: #4F46E5; font-weight: 700; text-decoration: none; font-size: 14px;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                    Daftar Member
                    <i class="fas fa-arrow-right" style="margin-left: 4px;"></i>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
