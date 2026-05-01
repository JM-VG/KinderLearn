@extends('layouts.student')
@section('title', 'Settings')

@section('student-content')
<h1 class="font-fredoka text-4xl text-gray-800 mb-2">Settings</h1>
<p class="text-gray-400 mb-6">Personalize your KinderLearn experience.</p>

<div class="max-w-2xl space-y-5">

    {{-- Appearance --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm">
        <h2 class="font-fredoka text-xl text-gray-800 mb-4 flex items-center gap-2">
            <i class="ri-palette-line text-orange-400"></i> Appearance
        </h2>

        {{-- Theme --}}
        <div class="flex items-center justify-between py-3 border-b border-gray-100">
            <div>
                <p class="font-bold text-gray-700">Theme</p>
                <p class="text-xs text-gray-400">Light or dark mode</p>
            </div>
            <div class="flex gap-2">
                <button id="theme-light" onclick="setTheme('light')"
                        class="px-4 py-2 rounded-xl font-bold text-sm border-2 transition">
                    <i class="ri-sun-line"></i> Light
                </button>
                <button id="theme-dark" onclick="setTheme('dark')"
                        class="px-4 py-2 rounded-xl font-bold text-sm border-2 transition">
                    <i class="ri-moon-line"></i> Dark
                </button>
            </div>
        </div>

        {{-- Font size --}}
        <div class="flex items-center justify-between py-3 border-b border-gray-100">
            <div>
                <p class="font-bold text-gray-700">Font Size</p>
                <p class="text-xs text-gray-400">Make text bigger or smaller</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="changeFontSize(-1)"
                        class="w-9 h-9 rounded-full border-2 border-gray-200 font-bold text-gray-600 hover:bg-gray-50 transition text-lg flex items-center justify-center">
                    A<sup style="font-size:0.6em">-</sup>
                </button>
                <span id="font-size-label" class="font-bold text-gray-700 w-14 text-center text-sm">Medium</span>
                <button onclick="changeFontSize(1)"
                        class="w-9 h-9 rounded-full border-2 border-gray-200 font-bold text-gray-600 hover:bg-gray-50 transition text-lg flex items-center justify-center">
                    A<sup style="font-size:0.6em">+</sup>
                </button>
            </div>
        </div>

        {{-- High contrast --}}
        <div class="flex items-center justify-between py-3">
            <div>
                <p class="font-bold text-gray-700">High Contrast</p>
                <p class="text-xs text-gray-400">Stronger colors for easier reading</p>
            </div>
            <button id="contrast-toggle" onclick="toggleSetting('highContrast')"
                    class="w-12 h-6 rounded-full transition-colors relative"
                    style="background:#e5e7eb;">
                <span id="contrast-knob"
                      class="absolute top-0.5 left-0.5 w-5 h-5 rounded-full bg-white shadow transition-transform"></span>
            </button>
        </div>
    </div>

    {{-- Sound --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm">
        <h2 class="font-fredoka text-xl text-gray-800 mb-4 flex items-center gap-2">
            <i class="ri-volume-up-line text-orange-400"></i> Sound
        </h2>

        <div class="flex items-center justify-between py-3 border-b border-gray-100">
            <div>
                <p class="font-bold text-gray-700">Sound Effects</p>
                <p class="text-xs text-gray-400">Plays sounds during activities</p>
            </div>
            <button id="sfx-toggle" onclick="toggleSetting('sfx')"
                    class="w-12 h-6 rounded-full transition-colors relative"
                    style="background:#F4654D;">
                <span id="sfx-knob"
                      class="absolute top-0.5 left-0.5 w-5 h-5 rounded-full bg-white shadow transition-transform"
                      style="transform:translateX(1.5rem);"></span>
            </button>
        </div>

        <div class="flex items-center justify-between py-3 border-b border-gray-100">
            <div>
                <p class="font-bold text-gray-700">Text to Speech</p>
                <p class="text-xs text-gray-400">Reads questions aloud automatically</p>
            </div>
            <button id="tts-toggle" onclick="toggleSetting('tts')"
                    class="w-12 h-6 rounded-full transition-colors relative"
                    style="background:#F4654D;">
                <span id="tts-knob"
                      class="absolute top-0.5 left-0.5 w-5 h-5 rounded-full bg-white shadow transition-transform"
                      style="transform:translateX(1.5rem);"></span>
            </button>
        </div>

        {{-- Volume slider --}}
        <div class="py-3">
            <div class="flex items-center justify-between mb-2">
                <p class="font-bold text-gray-700">Volume</p>
                <span id="vol-label" class="text-sm font-bold text-gray-500">80%</span>
            </div>
            <input type="range" id="vol-slider" min="0" max="100" value="80"
                   oninput="setVolume(this.value)"
                   class="w-full accent-orange-400">
            <div class="flex justify-between text-xs text-gray-400 mt-1">
                <span>0%</span><span>50%</span><span>100%</span>
            </div>
            {{-- Live TTS preview --}}
            <button onclick="previewTTS()"
                    class="mt-3 px-4 py-2 rounded-xl border-2 border-orange-200 text-orange-500 font-bold text-xs hover:bg-orange-50 transition flex items-center gap-2">
                <i class="ri-volume-up-line"></i> Test Voice
            </button>
        </div>
    </div>

    {{-- Accessibility --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm">
        <h2 class="font-fredoka text-xl text-gray-800 mb-4 flex items-center gap-2">
            <i class="ri-eye-line text-orange-400"></i> Accessibility
        </h2>

        <div class="flex items-center justify-between py-3 border-b border-gray-100">
            <div>
                <p class="font-bold text-gray-700">Reduce Motion</p>
                <p class="text-xs text-gray-400">Turns off bouncing animations</p>
            </div>
            <button id="motion-toggle" onclick="toggleSetting('reduceMotion')"
                    class="w-12 h-6 rounded-full transition-colors relative"
                    style="background:#e5e7eb;">
                <span id="motion-knob"
                      class="absolute top-0.5 left-0.5 w-5 h-5 rounded-full bg-white shadow transition-transform"></span>
            </button>
        </div>

        <div class="flex items-center justify-between py-3 border-b border-gray-100">
            <div>
                <p class="font-bold text-gray-700">Large Buttons</p>
                <p class="text-xs text-gray-400">Bigger tap targets for easier use</p>
            </div>
            <button id="large-toggle" onclick="toggleSetting('largeButtons')"
                    class="w-12 h-6 rounded-full transition-colors relative"
                    style="background:#e5e7eb;">
                <span id="large-knob"
                      class="absolute top-0.5 left-0.5 w-5 h-5 rounded-full bg-white shadow transition-transform"></span>
            </button>
        </div>

        <div class="flex items-center justify-between py-3">
            <div>
                <p class="font-bold text-gray-700">Dyslexia-Friendly Font</p>
                <p class="text-xs text-gray-400">Uses OpenDyslexic font throughout</p>
            </div>
            <button id="dyslexia-toggle" onclick="toggleSetting('dyslexia')"
                    class="w-12 h-6 rounded-full transition-colors relative"
                    style="background:#e5e7eb;">
                <span id="dyslexia-knob"
                      class="absolute top-0.5 left-0.5 w-5 h-5 rounded-full bg-white shadow transition-transform"></span>
            </button>
        </div>
    </div>

    {{-- Reset --}}
    <button onclick="resetSettings()"
            class="w-full py-3 rounded-2xl border-2 border-gray-200 font-bold text-gray-400 hover:bg-gray-50 transition text-sm">
        <i class="ri-refresh-line"></i> Reset to Defaults
    </button>
</div>

<script>
/* ── helpers ── */
function getS(k, def) { try { return JSON.parse(localStorage.getItem('kl_' + k)); } catch(e) { return def; } }
function setS(k, v)   { localStorage.setItem('kl_' + k, JSON.stringify(v)); }

/* ── sync UI to current saved values on page load ── */
(function syncUI() {
    syncThemeButtons(getS('theme', 'light'));

    var fsIdx = Math.max(0, Math.min(3, parseInt(getS('fontSize', 1)) || 1));
    document.getElementById('font-size-label').textContent = ['Small','Medium','Large','X-Large'][fsIdx];

    applyToggle('sfx',          getS('sfx', true));
    applyToggle('tts',          getS('tts', true));
    applyToggle('reduceMotion', getS('reduceMotion', false));
    applyToggle('largeButtons', getS('largeButtons', false));
    applyToggle('dyslexia',     getS('dyslexia', false));
    applyToggle('highContrast', getS('highContrast', false));

    var vol = getS('volume', 80);
    document.getElementById('vol-slider').value = vol;
    document.getElementById('vol-label').textContent = vol + '%';
}());

/* ── theme ── */
function syncThemeButtons(t) {
    var dark  = (t === 'dark');
    var bDark  = document.getElementById('theme-dark');
    var bLight = document.getElementById('theme-light');
    bDark.style.cssText  = dark
        ? 'border-color:#F4654D;color:#fff;background:#F4654D;'
        : 'border-color:#e5e7eb;color:#6b7280;background:#fff;';
    bLight.style.cssText = dark
        ? 'border-color:#e5e7eb;color:#9ca3af;background:#fff;'
        : 'border-color:#F4654D;color:#F4654D;background:#fff9f7;';
}
window.setTheme = function(t) {
    setS('theme', t);
    if (t === 'dark') document.documentElement.classList.add('kl-dark');
    else               document.documentElement.classList.remove('kl-dark');
    syncThemeButtons(t);
};

/* ── font size ── */
var fontSizes  = ['Small','Medium','Large','X-Large'];
var fontScales = ['0.85rem','1rem','1.15rem','1.3rem'];
window.changeFontSize = function(dir) {
    var cur = Math.max(0, Math.min(3, (parseInt(getS('fontSize', 1)) || 1) + dir));
    setS('fontSize', cur);
    document.documentElement.style.fontSize = fontScales[cur];
    document.getElementById('font-size-label').textContent = fontSizes[cur];
};

/* ── toggles ── */
window.toggleSetting = function(key) {
    var def  = (key === 'sfx' || key === 'tts');
    var next = !getS(key, def);
    setS(key, next);
    applyToggle(key, next);

    if (key === 'highContrast') {
        if (next) document.documentElement.classList.add('kl-contrast');
        else       document.documentElement.classList.remove('kl-contrast');
    }
    if (key === 'dyslexia') {
        document.documentElement.style.fontFamily = next
            ? "'OpenDyslexic','Nunito',sans-serif"
            : "'Nunito',sans-serif";
    }
    if (key === 'reduceMotion' || key === 'largeButtons') rebuildDynamicStyle();
};

function applyToggle(key, on) {
    var map = { sfx:'sfx', tts:'tts', reduceMotion:'motion', largeButtons:'large', dyslexia:'dyslexia', highContrast:'contrast' };
    var id   = map[key];
    var btn  = document.getElementById(id + '-toggle');
    var knob = document.getElementById(id + '-knob');
    if (!btn) return;
    btn.style.background = on ? '#F4654D' : '#e5e7eb';
    knob.style.transform = on ? 'translateX(1.5rem)' : 'translateX(0)';
}

function rebuildDynamicStyle() {
    var el  = document.getElementById('kl-dynamic') || document.createElement('style');
    el.id   = 'kl-dynamic';
    var rm  = getS('reduceMotion', false);
    var lb  = getS('largeButtons', false);
    var css = '';
    if (rm) css += '*, *::before, *::after { animation: none !important; transition: none !important; }';
    if (lb) css += 'button, .btn-kid { min-height: 3rem !important; }';
    el.textContent = css;
    document.head.appendChild(el);
}

/* ── volume ── */
window.setVolume = function(v) {
    setS('volume', parseInt(v));
    document.getElementById('vol-label').textContent = v + '%';
};

/* ── TTS preview (Puter.js) ── */
var _previewAudio = null;
window.previewTTS = function() {
    if (_previewAudio) { _previewAudio.pause(); _previewAudio = null; }
    var vol = getS('volume', 80) / 100;
    if (typeof puter !== 'undefined' && puter.ai && puter.ai.txt2speech) {
        puter.ai.txt2speech('Hello! Text to speech is working great!').then(function(audio) {
            audio.volume = vol;
            _previewAudio = audio;
            audio.play();
        }).catch(function() {});
    }
};

/* ── reset ── */
window.resetSettings = function() {
    ['theme','fontSize','sfx','tts','reduceMotion','largeButtons','dyslexia','highContrast','volume']
        .forEach(function(k) { localStorage.removeItem('kl_' + k); });
    location.reload();
};
</script>
@endsection
