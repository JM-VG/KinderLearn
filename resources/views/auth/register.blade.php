@extends('layouts.app')
@section('title', 'Create Account')

@section('content')
<div class="min-h-screen flex">

    {{-- LEFT: decorative strip (hidden on short/mobile screens) --}}
    <div class="hidden lg:flex flex-col justify-center items-center w-80 flex-shrink-0 text-white px-8 py-6"
         style="background: linear-gradient(160deg, #4ECDC4, #45B7D1, #2C3E7A);">
        <div class="mb-4" style="font-size:4rem;"><i class="fa-solid fa-graduation-cap"></i></div>
        <h1 class="font-fredoka text-3xl mb-3 text-center">Join KinderLearn!</h1>
        <p class="text-sm text-center opacity-90 mb-6 leading-relaxed">
            Start your learning adventure today — completely free!
        </p>
        <div class="space-y-2 w-full">
            @php $bullets = [['fa-circle-check','Free forever'],['fa-gamepad','Fun activities'],['fa-trophy','Earn badges'],['fa-chart-column','Track progress']]; @endphp
            @foreach($bullets as [$fa, $text])
            <div class="bg-white bg-opacity-20 rounded-xl px-4 py-2 font-bold text-sm">
                <i class="fa-solid {{ $fa }}"></i> {{ $text }}
            </div>
            @endforeach
        </div>
    </div>

    {{-- RIGHT: form (scrolls independently if needed) --}}
    <div class="flex-1 flex flex-col justify-center items-center px-6 py-6 bg-white overflow-y-auto">

        {{-- Mobile brand --}}
        <div class="lg:hidden mb-4 text-center">
            <div class="mb-1" style="font-size:2.5rem; color:#4ECDC4;"><i class="fa-solid fa-graduation-cap"></i></div>
            <div class="font-fredoka text-2xl text-teal-600">KinderLearn</div>
        </div>

        <div class="w-full max-w-sm">

            {{-- Step progress --}}
            <div class="flex items-center gap-2 mb-5">
                <div id="dot1" class="w-7 h-7 rounded-full flex items-center justify-center font-bold text-xs text-white flex-shrink-0"
                     style="background:#4ECDC4;">1</div>
                <div class="flex-1 h-1 rounded-full bg-gray-100 overflow-hidden">
                    <div id="bar1" class="h-full rounded-full transition-all duration-500 w-0" style="background:#4ECDC4;"></div>
                </div>
                <div id="dot2" class="w-7 h-7 rounded-full flex items-center justify-center font-bold text-xs text-gray-400 bg-gray-200 flex-shrink-0">2</div>
                <div class="flex-1 h-1 rounded-full bg-gray-100"></div>
                <div id="dot3" class="w-7 h-7 rounded-full flex items-center justify-center font-bold text-xs text-gray-400 bg-gray-200 flex-shrink-0"><i class="fa-solid fa-check text-xs"></i></div>
            </div>

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-2xl p-3 mb-4 text-sm text-red-600 font-semibold space-y-1">
                @foreach($errors->all() as $e)<p><i class="fa-solid fa-triangle-exclamation"></i> {{ $e }}</p>@endforeach
            </div>
            @endif

            <form id="reg-form" method="POST" action="{{ route('register.post') }}">
                @csrf

                {{-- ── Phase 1 ── --}}
                <div id="phase1">
                    <h2 class="font-fredoka text-2xl text-gray-800 mb-0.5">Tell us about you</h2>
                    <p class="text-gray-400 text-xs mb-4">Step 1 of 2 — basic info</p>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-gray-700 font-bold text-xs mb-1">Full Name</label>
                            <input type="text" id="p1-name" name="name" value="{{ old('name') }}"
                                   autocomplete="name"
                                   placeholder="e.g., Maria Clara"
                                   class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-teal-400 focus:outline-none font-semibold text-sm transition"
                                   oninput="clearFieldErr('err-name',this)">
                            <p id="err-name" class="hidden text-red-500 text-xs mt-1 font-semibold flex items-center gap-1">
                                <i class="ri-error-warning-line"></i> <span></span>
                            </p>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-bold text-xs mb-1">Email Address</label>
                            <input type="email" id="p1-email" name="email" value="{{ old('email') }}"
                                   autocomplete="email"
                                   placeholder="your@email.com"
                                   class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-teal-400 focus:outline-none font-semibold text-sm transition"
                                   oninput="clearFieldErr('err-email',this)">
                            <p id="err-email" class="hidden text-red-500 text-xs mt-1 font-semibold flex items-center gap-1">
                                <i class="ri-error-warning-line"></i> <span></span>
                            </p>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-bold text-xs mb-2">I am a...</label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="cursor-pointer">
                                    <input type="radio" name="role" value="student" class="sr-only peer" {{ old('role','student')==='student'?'checked':'' }}>
                                    <div class="peer-checked:border-teal-500 peer-checked:bg-teal-50 border-2 border-gray-200 rounded-2xl py-3 text-center font-bold text-gray-600 text-sm hover:border-teal-300 transition">
                                        <i class="fa-solid fa-child" style="font-size:1.5rem;"></i><br><span class="text-xs">Student</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="role" value="teacher" class="sr-only peer" {{ old('role')==='teacher'?'checked':'' }}>
                                    <div class="peer-checked:border-teal-500 peer-checked:bg-teal-50 border-2 border-gray-200 rounded-2xl py-3 text-center font-bold text-gray-600 text-sm hover:border-teal-300 transition">
                                        <i class="fa-solid fa-chalkboard-user" style="font-size:1.5rem;"></i><br><span class="text-xs">Teacher</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <button type="button" onclick="goPhase2()"
                                class="w-full py-3 rounded-2xl font-bold text-white text-sm transition"
                                style="background: linear-gradient(135deg, #4ECDC4, #45B7D1);">
                            Next → Set Password
                        </button>
                    </div>
                </div>

                {{-- ── Phase 2 ── --}}
                <div id="phase2" class="hidden">
                    <h2 class="font-fredoka text-2xl text-gray-800 mb-0.5">Secure your account</h2>
                    <p class="text-gray-400 text-xs mb-4">Step 2 of 2 — choose a password</p>

                    <div class="space-y-3">
                        <div id="join-code-wrap">
                            <label class="block text-gray-700 font-bold text-xs mb-1">
                                Class Join Code <span class="text-gray-400 font-normal">(optional)</span>
                            </label>
                            <input type="text" name="join_code" value="{{ old('join_code') }}"
                                   autocomplete="off"
                                   placeholder="Ask your teacher for the code"
                                   class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-teal-400 focus:outline-none font-semibold text-sm uppercase transition">
                        </div>

                        <div>
                            <label class="block text-gray-700 font-bold text-xs mb-1">Password</label>
                            <div class="relative">
                                <input type="password" name="password" id="reg-pw"
                                       autocomplete="new-password"
                                       placeholder="At least 6 characters"
                                       class="w-full px-4 py-3 pr-12 rounded-2xl border-2 border-gray-200 focus:border-teal-400 focus:outline-none font-semibold text-sm transition"
                                       oninput="clearFieldErr('err-pw',this)">
                                <button type="button" onclick="togglePw('reg-pw','eye1')"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i id="eye1" class="ri-eye-off-line"></i>
                                </button>
                            </div>
                            <p id="err-pw" class="hidden text-red-500 text-xs mt-1 font-semibold flex items-center gap-1">
                                <i class="ri-error-warning-line"></i> <span></span>
                            </p>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-bold text-xs mb-1">Confirm Password</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="reg-pw2"
                                       autocomplete="new-password"
                                       placeholder="Type it again"
                                       class="w-full px-4 py-3 pr-12 rounded-2xl border-2 border-gray-200 focus:border-teal-400 focus:outline-none font-semibold text-sm transition"
                                       oninput="clearFieldErr('err-pw2',this)">
                                <button type="button" onclick="togglePw('reg-pw2','eye2')"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i id="eye2" class="ri-eye-off-line"></i>
                                </button>
                            </div>
                            <p id="err-pw2" class="hidden text-red-500 text-xs mt-1 font-semibold flex items-center gap-1">
                                <i class="ri-error-warning-line"></i> <span></span>
                            </p>
                        </div>

                        <div class="flex gap-2 pt-1">
                            <button type="button" onclick="goPhase1()"
                                    class="flex-1 py-3 rounded-2xl border-2 border-gray-200 font-bold text-gray-600 text-sm hover:bg-gray-50 transition">
                                ← Back
                            </button>
                            <button type="submit" id="submit-btn"
                                    class="flex-1 py-3 rounded-2xl font-bold text-white text-sm transition"
                                    style="background: linear-gradient(135deg, #4ECDC4, #45B7D1);">
                                <i class="fa-solid fa-graduation-cap"></i> Create Account
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="mt-4 text-center space-y-2">
                <p class="text-gray-500 text-xs font-semibold">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-teal-500 font-bold hover:underline">Sign in</a>
                </p>
                <a href="{{ route('home') }}" class="block text-gray-400 hover:text-gray-600 text-xs font-semibold">
                    ← Back to Homepage
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function togglePw(id, iconId) {
    var el = document.getElementById(id);
    var ic = document.getElementById(iconId);
    el.type = el.type === 'password' ? 'text' : 'password';
    ic.className = el.type === 'password' ? 'ri-eye-off-line' : 'ri-eye-line';
}

function showFieldErr(errId, inputEl, msg) {
    var p = document.getElementById(errId);
    p.querySelector('span').textContent = msg;
    p.classList.remove('hidden');
    if (inputEl) {
        inputEl.classList.remove('border-gray-200');
        inputEl.classList.add('border-red-400');
    }
}
function clearFieldErr(errId, inputEl) {
    var p = document.getElementById(errId);
    if (p) p.classList.add('hidden');
    if (inputEl) {
        inputEl.classList.remove('border-red-400');
        inputEl.classList.add('border-gray-200');
    }
}

function goPhase2() {
    var nameEl  = document.getElementById('p1-name');
    var emailEl = document.getElementById('p1-email');
    clearFieldErr('err-name',  nameEl);
    clearFieldErr('err-email', emailEl);

    var name  = nameEl.value.trim();
    var email = emailEl.value.trim();
    var ok    = true;

    if (!name)  { showFieldErr('err-name',  nameEl,  'Please enter your full name.');       ok = false; }
    if (!email || !/\S+@\S+\.\S+/.test(email)) { showFieldErr('err-email', emailEl, 'Please enter a valid email address.'); ok = false; }
    if (!ok) return;

    document.getElementById('phase1').classList.add('hidden');
    document.getElementById('phase2').classList.remove('hidden');
    document.getElementById('dot2').style.cssText = 'background:#4ECDC4;color:#fff;';
    document.getElementById('bar1').style.width = '100%';

    var role = (document.querySelector('input[name="role"]:checked') || {}).value;
    document.getElementById('join-code-wrap').style.display = role === 'student' ? '' : 'none';
}

function goPhase1() {
    document.getElementById('phase2').classList.add('hidden');
    document.getElementById('phase1').classList.remove('hidden');
    document.getElementById('dot2').style.cssText = 'background:#e5e7eb;color:#6b7280;';
    document.getElementById('bar1').style.width = '0%';
}

// Refresh CSRF token from meta tag right before submit to avoid 419
document.getElementById('reg-form').addEventListener('submit', function(e) {
    var pwEl  = document.getElementById('reg-pw');
    var pw2El = document.getElementById('reg-pw2');
    clearFieldErr('err-pw',  pwEl);
    clearFieldErr('err-pw2', pw2El);
    var pw  = pwEl.value;
    var pw2 = pw2El.value;
    if (pw.length < 6) { e.preventDefault(); showFieldErr('err-pw',  pwEl,  'Password must be at least 6 characters.'); return; }
    if (pw !== pw2)    { e.preventDefault(); showFieldErr('err-pw2', pw2El, 'Passwords do not match.'); return; }

    // Update CSRF token from meta tag (guards against cached stale tokens)
    var meta = document.querySelector('meta[name="csrf-token"]');
    if (meta) { document.querySelector('input[name="_token"]').value = meta.content; }
});

// If server returned errors on phase-2 fields, jump straight to phase 2
@if($errors->has('password') || $errors->has('password_confirmation') || $errors->has('join_code') || $errors->has('email'))
document.addEventListener('DOMContentLoaded', function() {
    @if(!$errors->has('name') && old('name'))
    goPhase2();
    @endif
});
@endif
</script>
@endsection
