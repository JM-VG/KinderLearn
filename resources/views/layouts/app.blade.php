<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'KinderLearn') | KinderLearn</title>
    <link rel="icon" type="image/png" href="/images/shortend-logo.png">
    <style>html { scroll-behavior: smooth; }</style>

    {{-- Google Fonts: Nunito (very readable for kids) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Fredoka+One&display=swap" rel="stylesheet">

    {{-- Compiled CSS (includes Tailwind) --}}
    @vite(['resources/css/app.css'])

    {{-- Puter.js — free AI APIs including TTS --}}
    <script src="https://js.puter.com/v2/"></script>

    {{-- Font Awesome for icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Remix Icons (free CDN) - clean, modern icon set --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css">

    <style>
        /* Use Nunito as the default font - very clean and readable */
        body { font-family: 'Nunito', sans-serif; }

        /* Fredoka One for big headings - playful and child-friendly */
        .font-fredoka { font-family: 'Fredoka One', cursive; }

        /* Soft gradient background for the whole app */
        .bg-gradient-main {
            background: linear-gradient(135deg, #fff9f0 0%, #f0f8ff 50%, #fff0f8 100%);
        }

        /* Star animation for achievements */
        @keyframes star-pop {
            0%   { transform: scale(0) rotate(-20deg); opacity: 0; }
            60%  { transform: scale(1.2) rotate(10deg); opacity: 1; }
            100% { transform: scale(1) rotate(0deg); opacity: 1; }
        }
        .star-pop { animation: star-pop 0.5s ease forwards; }

        /* Bounce animation for buttons */
        @keyframes bounce-soft {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-4px); }
        }
        .btn-bounce:hover { animation: bounce-soft 0.4s ease; }

        /* Progress bar animation */
        @keyframes fill-bar {
            from { width: 0%; }
        }
        .progress-bar-animated { animation: fill-bar 1s ease forwards; }

        /* Card hover lift effect */
        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.12);
        }

        /* Big touch-friendly buttons for kids */
        .btn-kid {
            padding: 14px 28px;
            font-size: 1.1rem;
            font-weight: 700;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }
        .btn-kid:active { transform: scale(0.96); }

        /* Sidebar link active state */
        .nav-active {
            background: rgba(255,255,255,0.3);
            border-radius: 12px;
        }

        /* Floating notification badge */
        .notif-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #FF4757;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gradient-main min-h-screen">

{{-- ===================================================
     FLASH MESSAGES (success, error, info)
     These show up at the top of every page automatically.
     =================================================== --}}
@if(session('success'))
<div id="flash-msg" class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-4 rounded-2xl shadow-xl flex items-center gap-3 text-lg font-bold">
    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
@endif

@if(session('error'))
<div id="flash-msg" class="fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-4 rounded-2xl shadow-xl flex items-center gap-3 text-lg font-bold">
    <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
</div>
@endif

@if(session('info'))
<div id="flash-msg" class="fixed top-4 right-4 z-50 bg-blue-500 text-white px-6 py-4 rounded-2xl shadow-xl flex items-center gap-3 text-lg font-bold">
    <span>ℹ️</span> {{ session('info') }}
</div>
@endif

{{-- ===================================================
     MAIN CONTENT AREA
     Each page fills in this section using @yield('content')
     =================================================== --}}
@yield('content')

{{-- Auto-hide flash messages after 3 seconds --}}
<script>
    const flash = document.getElementById('flash-msg');
    if (flash) {
        setTimeout(() => {
            flash.style.transition = 'opacity 0.5s';
            flash.style.opacity = '0';
            setTimeout(() => flash.remove(), 500);
        }, 3000);
    }
</script>

@stack('scripts')
</body>
</html>
