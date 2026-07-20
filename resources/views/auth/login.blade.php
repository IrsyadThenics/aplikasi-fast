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
            --bg-color: #f0f6fc;
            --primary-blue: #0073cc;
            --primary-blue-hover: #005fa3;
            --card-bg: #ffffff;
            --text-dark: #1f2937;
            --text-muted: #6b7280;
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
            background-color: var(--bg-color);
            background-image: radial-gradient(circle at 100% 100%, rgba(200, 220, 255, 0.4) 0%, transparent 50%),
                              radial-gradient(circle at 0% 0%, rgba(255, 255, 255, 0.8) 0%, transparent 50%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-dark);
            overflow: hidden;
        }

        .login-layout {
            display: flex;
            width: 100%;
            max-width: 1200px;
            min-height: 650px;
            padding: 2rem;
            animation: fadeInScale 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: scale(0.98);
        }

        /* LEFT SIDE - BRANDING */
        .branding-sec {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            position: relative;
            text-align: center;
        }

        .top-brand {
            position: absolute;
            top: 2rem;
            left: 2rem;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: 1px;
            color: #111827;
            opacity: 0.9;
        }

        .logo-container {
            margin-bottom: 2.5rem;
            animation: float 6s ease-in-out infinite;
        }

        .logo-container img {
            max-width: 450px;
            width: 100%;
            height: auto;
            filter: drop-shadow(0 15px 20px rgba(0, 0, 0, 0.05));
            transition: filter 0.3s ease;
            mix-blend-mode: multiply; /* Removes white JPEG background */
        }

        .logo-container img:hover {
            filter: drop-shadow(0 20px 25px rgba(0, 0, 0, 0.1));
        }

        .tagline-main {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 1.05rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 1.25rem;
            color: #111827;
            line-height: 1.4;
        }

        .tagline-sub {
            font-size: 0.75rem;
            font-weight: 600;
            color: #4b5563;
            letter-spacing: 0.75px;
            text-transform: uppercase;
        }

        /* RIGHT SIDE - LOGIN CARD */
        .login-sec {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }

        .login-card {
            background: var(--card-bg);
            width: 100%;
            max-width: 440px;
            border-radius: 24px;
            padding: 3.5rem 3rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08), 
                        0 0 0 1px rgba(255,255,255,0.8) inset;
            backdrop-filter: blur(10px);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s ease;
        }

        .login-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 35px 60px -15px rgba(0, 0, 0, 0.15), 
                        0 0 0 1px rgba(255,255,255,1) inset;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .login-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.75rem;
        }

        .login-header p {
            font-size: 0.95rem;
            color: #111827;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1.35rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.85); /* Slightly brighter icon */
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            transition: color 0.3s ease, transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: 2;
        }

        .form-group input {
            width: 100%;
            padding: 1.125rem 1.25rem 1.125rem 3.75rem;
            border: 2px solid transparent; /* Prepare for focus state border */
            border-radius: 9999px; /* Pill shape */
            background-color: var(--input-bg); /* Match the gray in the mockup */
            color: #ffffff;
            font-size: 1.05rem;
            font-family: inherit;
            font-weight: 500;
            outline: none;
            transition: all 0.3s ease;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
        }

        .form-group input::placeholder {
            color: var(--input-placeholder);
            opacity: 0.9;
        }

        .form-group input:focus {
            background-color: #64748b;
            border-color: rgba(255,255,255,0.4);
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.1), 0 0 0 4px rgba(0, 115, 204, 0.25);
        }

        .form-group input:focus + .input-icon,
        .form-group input:focus ~ .input-icon {
            color: #ffffff;
            transform: translateY(-50%) scale(1.1);
        }

        /* Error styling */
        .error-message {
            color: #ef4444;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            margin-left: 1.25rem;
            display: block;
            font-weight: 500;
            animation: fadeIn 0.3s ease-in-out;
        }

        .btn-submit {
            width: 100%;
            padding: 1.125rem;
            margin-top: 1.25rem;
            border: none;
            border-radius: 9999px; /* Pill shape */
            background: linear-gradient(135deg, var(--primary-blue), #0093e9);
            color: #fff;
            font-family: inherit;
            font-size: 1.15rem;
            font-weight: 600;
            cursor: pointer;
            outline: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 14px rgba(0, 115, 204, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 115, 204, 0.5);
            background: linear-gradient(135deg, var(--primary-blue-hover), #0073cc);
        }

        .btn-submit:active {
            transform: translateY(1px);
            box-shadow: 0 2px 10px rgba(0, 115, 204, 0.4);
        }

        .btn-submit::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255,255,255,0.15);
            transform: rotate(45deg) scale(0);
            transition: transform 0.6s ease;
        }

        .btn-submit:hover::after {
            transform: rotate(45deg) scale(1);
        }

        /* Media Queries */
        @media (max-width: 992px) {
            body {
                padding: 1rem;
            }
            .login-layout {
                flex-direction: column;
                padding: 1rem;
                min-height: auto;
            }
            .top-brand {
                position: relative;
                top: 0;
                left: 0;
                margin-bottom: 2rem;
            }
            .branding-sec {
                padding: 1rem 1rem 3rem 1rem;
            }
            .login-sec {
                padding: 1rem;
            }
            .login-card {
                padding: 2.5rem 2rem;
            }
        }
        
        /* Animations */
        @keyframes fadeInScale {
            0% { opacity: 0; transform: scale(0.95) translateY(20px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }
        
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body>

    <div class="login-layout">
        
        <div class="branding-sec">
            <div class="top-brand">UP3 BOJONEGORO&trade;</div>
            
            <div class="logo-container">
                <!-- Replace src below with the actual logo image -->
                <img src="{{ asset('images/faston.png') }}" alt="FASTON360 Logo">
            </div>

            <h2 class="tagline-main">Full Acceleration & Service Tracking ON 360&deg;</h2>
            <p class="tagline-sub">JER KARTA RAHARJA MAWA KARYA</p>
        </div>

        <div class="login-sec">
            <div class="login-card">
                <div class="login-header">
                    <h1>Login</h1>
                    <p>Log in using your registered User ID and Password.</p>
                </div>
                
                <form action="{{ route('auth.authenticate') }}" method="post">
                    @csrf
                    
                    <div class="form-group">
                        <input type="text" name="user_id" id="user_id" placeholder="User Id" required autocomplete="username">
                        <!-- ID Card Icon -->
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
                        <!-- Lock Icon -->
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
                    
                    @if (session('error'))
                        <div class="error-message" style="text-align: center; margin-top: 1rem; margin-left: 0;">
                            {{ session('error') }}
                        </div>
                    @endif
                </form>
            </div>
        </div>

    </div>

</body>
</html>
