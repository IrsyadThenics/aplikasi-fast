<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FASTON360</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-blue: #0073cc;
            --primary-blue-hover: #005fa3;
            --card-bg: rgba(255, 255, 255, 0.18);
            --input-bg: #94a3b8;
            --input-placeholder: #f8fafc;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
            background: linear-gradient(135deg, #0d1b4b 0%, #0a3272 40%, #1565C0 100%);
        }

        /* ── BACKGROUND LOGO ── */
        .bg-logo {
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            z-index: 0;
            will-change: transform;
            transition: transform 0.12s ease-out;
        }

        .bg-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.33;
            mix-blend-mode: screen;
            animation: floatBg 9s ease-in-out infinite, glowPulse 4s ease-in-out infinite;
            filter: brightness(1.8) saturate(1.4);
            transform-origin: center center;
        }

        /* Shimmer sweep overlay on top of logo */
        .bg-logo::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(
                120deg,
                transparent 0%,
                transparent 30%,
                rgba(255, 255, 255, 0.06) 45%,
                rgba(100, 200, 255, 0.10) 50%,
                rgba(255, 255, 255, 0.06) 55%,
                transparent 70%,
                transparent 100%
            );
            background-size: 250% 100%;
            animation: shimmer 5s linear infinite;
            pointer-events: none;
        }

        /* Dynamic radial glow that pulses */
        .bg-glow {
            position: fixed;
            inset: 0;
            background: radial-gradient(ellipse 65% 55% at 50% 50%, rgba(0, 115, 204, 0.25) 0%, transparent 70%);
            z-index: 0;
            pointer-events: none;
            animation: glowMove 6s ease-in-out infinite;
        }

        /* ── LOGIN WRAPPER ── */
        .login-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            animation: fadeInScale 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: scale(0.96) translateY(16px);
        }

        /* ── TOP BRAND LABEL ── */
        .top-brand {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 0.85rem;
            letter-spacing: 2px;
            color: rgba(255, 255, 255, 0.55);
            text-transform: uppercase;
            margin-bottom: 1.5rem;
        }

        /* ── CARD ── */
        .login-card {
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.10);
            border: 1px solid rgba(255, 255, 255, 0.20);
            border-radius: 28px;
            padding: 3rem 2.75rem;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 32px 64px rgba(0, 0, 0, 0.35),
                        0 0 0 1px rgba(255,255,255,0.08) inset;
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275),
                        box-shadow 0.4s ease;
        }

        .login-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 48px 80px rgba(0, 0, 0, 0.45),
                        0 0 0 1px rgba(255,255,255,0.12) inset;
        }

        /* ── CARD HEADER ── */
        .login-header {
            text-align: center;
            margin-bottom: 2.25rem;
        }

        .login-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.4rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.60);
            font-weight: 400;
        }

        /* ── FORM ── */
        .form-group {
            margin-bottom: 1.25rem;
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.70);
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            transition: color 0.3s ease, transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: 2;
        }

        .form-group input {
            width: 100%;
            padding: 1rem 1.25rem 1rem 3.5rem;
            border: 1.5px solid rgba(255, 255, 255, 0.20);
            border-radius: 9999px;
            background-color: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            font-size: 1rem;
            font-family: inherit;
            font-weight: 500;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.50);
        }

        .form-group input:focus {
            background-color: rgba(255, 255, 255, 0.20);
            border-color: rgba(255, 255, 255, 0.45);
            box-shadow: 0 0 0 4px rgba(0, 115, 204, 0.30);
        }

        .form-group input:focus ~ .input-icon {
            color: #ffffff;
            transform: translateY(-50%) scale(1.1);
        }

        /* ── ERROR ── */
        .error-message {
            color: #fca5a5;
            font-size: 0.82rem;
            margin-top: 0.4rem;
            margin-left: 1.25rem;
            display: block;
            font-weight: 500;
            animation: fadeIn 0.3s ease-in-out;
        }

        /* ── SUBMIT BUTTON ── */
        .btn-submit {
            width: 100%;
            padding: 1.05rem;
            margin-top: 1rem;
            border: none;
            border-radius: 9999px;
            background: linear-gradient(135deg, var(--primary-blue), #0093e9);
            color: #fff;
            font-family: inherit;
            font-size: 1.05rem;
            font-weight: 600;
            cursor: pointer;
            outline: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(0, 115, 204, 0.40);
            position: relative;
            overflow: hidden;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 115, 204, 0.60);
            background: linear-gradient(135deg, var(--primary-blue-hover), #0073cc);
        }

        .btn-submit:active {
            transform: translateY(1px);
            box-shadow: 0 2px 10px rgba(0, 115, 204, 0.40);
        }

        .btn-submit::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255,255,255,0.12);
            transform: rotate(45deg) scale(0);
            transition: transform 0.6s ease;
        }

        .btn-submit:hover::after {
            transform: rotate(45deg) scale(1);
        }

        /* ── FOOTER TAGLINE ── */
        .card-footer {
            text-align: center;
            margin-top: 1.75rem;
        }

        .card-footer span {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.35);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 480px) {
            .login-card {
                padding: 2.25rem 1.75rem;
                border-radius: 22px;
            }

            .login-header h1 {
                font-size: 2rem;
            }
        }

        @media (max-height: 600px) {
            .login-card {
                padding: 1.75rem 2rem;
            }

            .login-header {
                margin-bottom: 1.25rem;
            }

            .form-group {
                margin-bottom: 0.85rem;
            }
        }

        /* ── ANIMATIONS ── */
        @keyframes fadeInScale {
            0%   { opacity: 0; transform: scale(0.95) translateY(20px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }

        @keyframes fadeIn {
            0%   { opacity: 0; }
            100% { opacity: 1; }
        }

        @keyframes floatBg {
            0%   { transform: translateY(0px) scale(1.0); }
            25%  { transform: translateY(-14px) scale(1.015); }
            50%  { transform: translateY(-22px) scale(1.025); }
            75%  { transform: translateY(-10px) scale(1.01); }
            100% { transform: translateY(0px) scale(1.0); }
        }

        /* Color-shifting glow: blue → cyan → white → blue */
        @keyframes glowPulse {
            0%   { filter: brightness(1.6) saturate(1.2) hue-rotate(0deg)   drop-shadow(0 0 30px rgba(0,115,204,0.5)); }
            25%  { filter: brightness(2.0) saturate(1.6) hue-rotate(20deg)  drop-shadow(0 0 60px rgba(0,200,255,0.7)); }
            50%  { filter: brightness(2.4) saturate(1.0) hue-rotate(0deg)   drop-shadow(0 0 80px rgba(200,240,255,0.6)); }
            75%  { filter: brightness(2.0) saturate(1.6) hue-rotate(-15deg) drop-shadow(0 0 60px rgba(0,100,220,0.7)); }
            100% { filter: brightness(1.6) saturate(1.2) hue-rotate(0deg)   drop-shadow(0 0 30px rgba(0,115,204,0.5)); }
        }

        /* Shimmer sweep across the logo */
        @keyframes shimmer {
            0%   { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Pulsing background glow size */
        @keyframes glowMove {
            0%   { opacity: 0.8; transform: scale(1.0); }
            50%  { opacity: 1.0; transform: scale(1.15); }
            100% { opacity: 0.8; transform: scale(1.0); }
        }
    </style>
</head>
<body>

    <!-- Background logo (transparent watermark) -->
    <div class="bg-logo">
        <img src="{{ asset('images/faston.png') }}" alt="">
    </div>
    <div class="bg-glow"></div>

    <!-- Centered login wrapper -->
    <div class="login-wrapper">

        <div class="top-brand">UP3 Bojonegoro &trade;</div>

        <div class="login-card">
            <div class="login-header">
                <h1>Login</h1>
                <p>Masukkan User ID dan Password Anda.</p>
            </div>
            
            <form action="{{ route('auth.authenticate') }}" method="post">
                @csrf
                
                <div class="form-group">
                    <input type="text" name="user_id" id="user_id" placeholder="User Id" required autocomplete="username">
                    <span class="input-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-3.75V8.25m-6 3.75h6m-6 0a3 3 0 116 0 3 3 0 01-6 0z" />
                        </svg>
                    </span>
                    @error('user_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <input type="password" name="password" id="password" placeholder="Password" required autocomplete="current-password">
                    <span class="input-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </span>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">Login</button>
                
                @if (session('error') || $errors->has('error'))
                    <div class="error-message" style="text-align: center; margin-top: 1rem; margin-left: 0;">
                        {{ session('error') ?? $errors->first('error') }}
                    </div>
                @endif
            </form>

            <div class="card-footer">
                <span>Full Acceleration &amp; Service Tracking ON 360&deg;</span>
            </div>
        </div>

    </div>

<script>
    // Mouse parallax – logo shifts slightly toward cursor
    const bgLogo = document.querySelector('.bg-logo');
    let targetX = 0, targetY = 0, currentX = 0, currentY = 0;
    const strength = 18;

    document.addEventListener('mousemove', (e) => {
        const cx = window.innerWidth / 2;
        const cy = window.innerHeight / 2;
        targetX = ((e.clientX - cx) / cx) * strength;
        targetY = ((e.clientY - cy) / cy) * strength;
    });

    (function loop() {
        currentX += (targetX - currentX) * 0.06;
        currentY += (targetY - currentY) * 0.06;
        bgLogo.style.transform = `translate(${currentX}px, ${currentY}px)`;
        requestAnimationFrame(loop);
    })();

    document.addEventListener('touchmove', (e) => {
        const t = e.touches[0];
        const cx = window.innerWidth / 2;
        const cy = window.innerHeight / 2;
        targetX = ((t.clientX - cx) / cx) * (strength * 0.5);
        targetY = ((t.clientY - cy) / cy) * (strength * 0.5);
    }, { passive: true });

    // Auto-refresh CSRF token setiap 15 menit agar form tidak expired
    // jika user membiarkan tab terbuka terlalu lama
    setInterval(async () => {
        try {
            const res = await fetch('/up', { method: 'GET', credentials: 'same-origin' });
            // Reload hanya token input di form
            const metaToken = document.querySelector('meta[name="csrf-token"]');
            if (metaToken) metaToken.setAttribute('content', '');
        } catch (e) {
            // Jika sudah tidak ada koneksi, reload halaman agar token fresh
            window.location.reload();
        }
    }, 15 * 60 * 1000); // setiap 15 menit
</script>
</body>
</html>
