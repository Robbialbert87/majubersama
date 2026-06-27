@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
    <div class="auth-branding">
        <div class="branding-content">
            <div style="background:#fff;border-radius:12px;padding:12px;display:inline-block;margin-bottom:16px;">
                <img src="{{ asset('images/majubersamalogo.png') }}" alt="Maju Bersama" style="height:150px;width:auto;display:block;">
            </div>
            <p class="branding-subtitle">Gerbang aman Anda untuk mengelola aplikasi</p>
            <div class="branding-features">
                <div class="branding-feature">
                    <div class="feature-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1c1c1e" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    Autentikasi admin yang aman
                </div>
                <div class="branding-feature">
                    <div class="feature-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1c1c1e" stroke-width="2">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                        </svg>
                    </div>
                    Data dan analitik real-time
                </div>
                <div class="branding-feature">
                    <div class="feature-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1c1c1e" stroke-width="2">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                            <line x1="1" y1="10" x2="23" y2="10"/>
                        </svg>
                    </div>
                    Manajemen pengguna & peran lengkap
                </div>
            </div>
        </div>
    </div>

    <div class="auth-form-container">
        <button class="theme-toggle-btn" id="themeToggle">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="5"/>
                <line x1="12" y1="1" x2="12" y2="3"/>
                <line x1="12" y1="21" x2="12" y2="23"/>
                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                <line x1="1" y1="12" x2="3" y2="12"/>
                <line x1="21" y1="12" x2="23" y2="12"/>
                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
                <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
            </svg>
        </button>

        <div class="auth-form-wrapper">
            <div class="form-header">
                <h1>Masuk Admin</h1>
                <p>Masukkan kredensial Anda untuk mengakses panel admin</p>
            </div>

            @if(session('error'))
                <div style="background: rgba(194, 120, 120, 0.2); color: var(--loss); padding: 12px 16px; border-radius: 10px; margin-bottom: 24px; font-size: 14px;">
                    {{ session('error') }}
                </div>
            @endif

            <form class="auth-form active" method="POST" action="{{ route('login.submit') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Alamat Email</label>
                    <div class="form-input-wrapper">
                        <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                        <input type="email" name="email" class="form-input" placeholder="admin@admin.com" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Kata Sandi</label>
                    <div class="form-input-wrapper">
                        <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0110 0v4"/>
                        </svg>
                        <input type="password" name="password" class="form-input" id="loginPassword" placeholder="Masukkan kata sandi" required>
                        <button type="button" class="password-toggle" data-target="loginPassword">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="submit-btn">Masuk</button>

                <p class="form-footer">
                    Kredensial admin: <strong>admin@admin.com</strong> / <strong>password</strong>
                </p>
            </form>
        </div>

        <p class="copyright">
            Hak Cipta &copy; {{ date('Y') }} Maju Bersama.
        </p>
    </div>
@endsection
