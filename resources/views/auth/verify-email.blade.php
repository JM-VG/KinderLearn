@extends('layouts.app')
@section('title', 'Verify Your Email')

@section('content')
<div class="min-h-screen flex">

    {{-- LEFT: branded panel --}}
    <div class="hidden md:flex flex-col justify-center items-center flex-1 text-white p-12"
         style="background: linear-gradient(135deg, #F4654D, #ff8c42, #FFD700);">
        <div class="mb-8" style="font-size:5rem;"><i class="fa-solid fa-envelope"></i></div>
        <h1 class="font-fredoka text-5xl mb-4 text-center">Check Your Email!</h1>
        <p class="text-xl text-center opacity-90 mb-8 max-w-xs leading-relaxed">
            We sent a 6-character code to your email address. Enter it to activate your account.
        </p>
        <div class="bg-white bg-opacity-20 rounded-3xl p-6 max-w-xs w-full text-center">
            <div class="mb-2" style="font-size:2.5rem;"><i class="fa-solid fa-lock"></i></div>
            <p class="font-bold text-lg">Code expires in 15 minutes</p>
            <p class="text-sm opacity-80 mt-1">Check your spam folder if you don't see it</p>
        </div>
    </div>

    {{-- RIGHT: verify form --}}
    <div class="flex-1 flex items-center justify-center p-8 bg-white">
        <div class="w-full max-w-sm">

            {{-- Logo --}}
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="font-fredoka text-4xl" style="text-decoration:none;">
                    <span style="color:#1A212F;">Kinder</span><span style="color:#F4654D;">Learn</span>
                </a>
                <p class="text-gray-400 text-sm mt-1">Email Verification</p>
            </div>

            {{-- Email hint --}}
            @if(session('verification_email'))
            <div class="mb-5 text-center">
                <p class="text-gray-500 text-sm">Code sent to</p>
                <p class="font-bold text-gray-800 text-sm mt-0.5">{{ session('verification_email') }}</p>
            </div>
            @endif

            {{-- Success message --}}
            @if(session('success'))
            <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-2xl text-sm font-semibold flex items-center gap-2">
                <i class="ri-checkbox-circle-line"></i> {{ session('success') }}
            </div>
            @endif

            {{-- Error messages --}}
            @if(session('error'))
            <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-600 rounded-2xl text-sm font-semibold flex items-center gap-2">
                <i class="ri-error-warning-line"></i> {{ session('error') }}
            </div>
            @endif

            @if($errors->any())
            <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-600 rounded-2xl text-sm font-semibold flex items-center gap-2">
                <i class="ri-error-warning-line"></i> {{ $errors->first() }}
            </div>
            @endif

            {{-- Code form --}}
            <form method="POST" action="{{ route('auth.verify') }}" id="verify-form">
                @csrf

                <p class="text-center text-gray-700 font-bold mb-4">Enter your 6-character code</p>

                {{-- 6 individual boxes --}}
                <div class="flex justify-center gap-2 mb-6" id="code-boxes">
                    @for($i = 0; $i < 6; $i++)
                    <input type="text"
                           id="box{{ $i }}"
                           maxlength="1"
                           class="w-12 h-14 text-center text-2xl font-black border-2 border-gray-200 rounded-xl focus:border-orange-400 focus:outline-none uppercase transition"
                           style="font-family:'Courier New',monospace;"
                           autocomplete="off"
                           inputmode="text">
                    @endfor
                </div>

                {{-- Hidden combined input --}}
                <input type="hidden" name="code" id="code-hidden">

                <button type="submit" id="verify-btn"
                        class="w-full py-3 rounded-2xl font-bold text-white text-sm transition opacity-50 cursor-not-allowed"
                        style="background: linear-gradient(135deg, #F4654D, #ff8c42);"
                        disabled>
                    <i class="ri-shield-check-line"></i> Verify Email
                </button>
            </form>

            {{-- Resend --}}
            <div class="mt-5 text-center">
                <p class="text-gray-400 text-xs mb-2">Didn't receive the code?</p>
                <form method="POST" action="{{ route('auth.verify.resend') }}">
                    @csrf
                    <button type="submit"
                            class="text-sm font-bold text-orange-500 hover:text-orange-700 transition underline-offset-2 hover:underline">
                        <i class="ri-mail-send-line"></i> Resend Verification Email
                    </button>
                </form>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('register') }}" class="text-xs text-gray-400 hover:text-gray-600 transition">
                    ← Back to Register
                </a>
            </div>

        </div>
    </div>
</div>

<script>
(function () {
    var boxes = Array.from(document.querySelectorAll('#code-boxes input'));
    var hidden = document.getElementById('code-hidden');
    var btn    = document.getElementById('verify-btn');
    var form   = document.getElementById('verify-form');

    function updateState() {
        var val = boxes.map(function(b) { return b.value.toUpperCase(); }).join('');
        hidden.value = val;
        var full = val.length === 6;
        btn.disabled = !full;
        btn.style.opacity  = full ? '1' : '0.5';
        btn.style.cursor   = full ? 'pointer' : 'not-allowed';
    }

    boxes.forEach(function(box, i) {
        box.addEventListener('input', function(e) {
            var v = e.target.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
            e.target.value = v.slice(-1);
            updateState();
            if (v && i < 5) boxes[i + 1].focus();
        });

        box.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !box.value && i > 0) {
                boxes[i - 1].value = '';
                boxes[i - 1].focus();
                updateState();
            }
        });

        box.addEventListener('paste', function(e) {
            e.preventDefault();
            var pasted = (e.clipboardData || window.clipboardData).getData('text')
                            .replace(/[^a-zA-Z0-9]/g, '').toUpperCase().slice(0, 6);
            pasted.split('').forEach(function(ch, j) {
                if (boxes[i + j]) boxes[i + j].value = ch;
            });
            var next = Math.min(i + pasted.length, 5);
            boxes[next].focus();
            updateState();
        });
    });

    form.addEventListener('submit', function() {
        updateState();
    });

    boxes[0].focus();
}());
</script>
@endsection
