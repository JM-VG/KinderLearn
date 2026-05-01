@extends('layouts.app')
@section('title', 'Sign In')

@section('content')
<div class="min-h-screen flex">

    {{-- LEFT: gradient + welcome message --}}
    <div class="hidden md:flex flex-col justify-center items-center flex-1 text-white p-12"
         style="background: linear-gradient(135deg, #FF6B6B, #FF8E53, #FFD700);">
        <div class="mb-8 animate-bounce" style="font-size:5rem;"><i class="fa-solid fa-book-open"></i></div>
        <h1 class="font-fredoka text-5xl mb-4 text-center">Welcome Back!</h1>
        <p class="text-xl text-center opacity-90 mb-10 max-w-xs leading-relaxed">
            Your learning adventure continues! Sign in to keep earning badges.
        </p>
        <div class="grid grid-cols-2 gap-4 w-full max-w-xs">
            @php
                $subjects = [
                    ['fa-solid fa-font', 'Alphabet'],
                    ['fa-solid fa-hashtag', 'Numbers'],
                    ['fa-solid fa-palette', 'Colors'],
                    ['fa-solid fa-star', 'Shapes'],
                ];
            @endphp
            @foreach($subjects as [$fa, $label])
            <div class="bg-white bg-opacity-20 rounded-2xl p-4 text-center font-bold">
                <i class="{{ $fa }}"></i> {{ $label }}
            </div>
            @endforeach
        </div>
    </div>

    {{-- RIGHT: login form --}}
    <div class="flex-1 flex flex-col justify-center items-center p-8 bg-white">

        {{-- Mobile logo --}}
        <div class="md:hidden mb-8 text-center">
            <div class="mb-2" style="font-size:3.5rem; color:#FF6B6B;"><i class="fa-solid fa-book-open"></i></div>
            <div class="font-fredoka text-3xl"><span style="color:#1A212F">Kinder</span><span style="color:#F4654D">Learn</span></div>
        </div>

        <div class="w-full max-w-md">
            <h2 class="font-fredoka text-4xl text-gray-800 mb-2">Sign In</h2>
            <p class="text-gray-500 mb-8">Enter your email and password to continue.</p>

            @if($errors->any())
            <div class="bg-red-50 border-2 border-red-200 rounded-2xl p-4 mb-6">
                @foreach($errors->all() as $error)
                <p class="text-red-600 font-semibold flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation"></i> {{ $error }}
                </p>
                @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label class="block text-gray-700 font-bold text-lg mb-2">
                        <i class="fa-solid fa-envelope" style="color:#FF6B6B;"></i> Email Address
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           placeholder="your@email.com"
                           class="w-full px-5 py-4 text-lg rounded-2xl border-2 border-gray-200 focus:border-orange-400 focus:outline-none transition">
                </div>

                {{-- Password with show/hide --}}
                <div>
                    <label class="block text-gray-700 font-bold text-lg mb-2">
                        <i class="fa-solid fa-key" style="color:#FF6B6B;"></i> Password
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="login-password" required
                               placeholder="Enter your password"
                               class="w-full px-5 py-4 text-lg rounded-2xl border-2 border-gray-200 focus:border-orange-400 focus:outline-none transition pr-14">
                        <button type="button" onclick="togglePassword('login-password','eye-login')"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                            <i id="eye-login" class="ri-eye-off-line" style="font-size:1.25rem;"></i>
                        </button>
                    </div>
                </div>

                {{-- Remember me --}}
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="remember" id="remember"
                           class="w-5 h-5 rounded accent-orange-500">
                    <label for="remember" class="text-gray-600 font-semibold">Keep me signed in</label>
                </div>

                {{-- Submit button --}}
                <button type="submit"
                        class="btn-kid w-full text-white text-xl justify-center shadow-lg"
                        style="background: linear-gradient(135deg, #FF6B6B, #FF8E53);">
                    <i class="fa-solid fa-rocket"></i> Sign In
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-500 font-semibold">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-orange-500 font-bold hover:underline">
                        Create one free!
                    </a>
                </p>
            </div>

            <div class="mt-4 text-center">
                <a href="{{ route('home') }}" class="text-gray-400 hover:text-gray-600 font-semibold">
                    ← Back to Homepage
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId, iconId) {
    var input = document.getElementById(inputId);
    var icon  = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'ri-eye-line';
    } else {
        input.type = 'password';
        icon.className = 'ri-eye-off-line';
    }
}
</script>
@endsection
