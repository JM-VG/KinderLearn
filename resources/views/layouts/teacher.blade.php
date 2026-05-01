@extends('layouts.app')

@push('styles')
<style>
:root {
    --sb-w:    220px;
    --sb-mini:  68px;
    --hdr-h:    56px;
    --ease-sb: cubic-bezier(0.4, 0, 0.2, 1);
}

#main-sidebar {
    padding-top: var(--hdr-h);
    background: #ffffff;
    border-right: 1px solid #ebebeb;
    overflow-x: hidden;
    overflow-y: auto;
}

@media (min-width: 768px) {
    #main-sidebar {
        width: var(--sb-mini);
        transform: translateX(0) !important;
        transition: width 0.22s var(--ease-sb), box-shadow 0.22s ease;
    }
    #main-sidebar:hover {
        width: var(--sb-w);
        box-shadow: 4px 0 20px rgba(0,0,0,0.06);
    }
    #sidebar-overlay { display: none !important; }
}

@media (max-width: 767px) {
    #main-sidebar {
        width: var(--sb-w);
        transform: translateX(-100%);
        transition: transform 0.28s var(--ease-sb);
    }
    #main-sidebar.sb-open { transform: translateX(0); }
}

#content-wrapper { padding-top: var(--hdr-h); }
@media (min-width: 768px) {
    #content-wrapper { padding-left: var(--sb-mini); }
}

.sb-link {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.875rem;
    padding: 0.5rem 0.875rem;
    border-radius: 0.75rem;
    font-weight: 600;
    font-size: 0.875rem;
    color: #4b5563;
    text-decoration: none;
    white-space: nowrap;
    transition: background 0.15s ease, color 0.15s ease;
}
.sb-link:hover     { background: rgba(14,165,233,0.08); color: #0284c7; }
.sb-link.sb-active { background: rgba(14,165,233,0.13); color: #0284c7; }

.sb-icon {
    font-size: 1.15rem;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2rem;
    height: 2rem;
    color: #0ea5e9;
}

.sb-label {
    overflow: hidden;
    opacity: 0;
    max-width: 0;
    transition: opacity 0.15s ease, max-width 0.22s var(--ease-sb);
}
@media (min-width: 768px) {
    #main-sidebar:hover .sb-label { opacity: 1; max-width: 160px; }
    #main-sidebar:hover .sb-link  { justify-content: flex-start; }
}
@media (max-width: 767px) {
    .sb-label { opacity: 1; max-width: 160px; }
    .sb-link  { justify-content: flex-start; }
}

#profile-dropdown { min-width: 180px; box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
.dd-item {
    display: flex; align-items: center; gap: 0.6rem;
    padding: 0.6rem 1rem; font-size: 0.85rem; font-weight: 600;
    color: #374151; text-decoration: none; border-radius: 0.5rem;
    transition: background 0.12s ease, color 0.12s ease;
    cursor: pointer; width: 100%; text-align: left; background: none; border: none;
}
.dd-item:hover { background: #f5f7fb; color: #111; }
.dd-item.dd-danger:hover { background: #fff1f0; color: #e53e3e; }
.dd-divider { border: none; border-top: 1px solid #f0f0f0; margin: 0.35rem 0; }

/* ── Dark Mode (soft, easy on the eyes) ── */
.kl-dark body,
.kl-dark #content-wrapper { background-color: #1a1e2e !important; color: #e2e8f2 !important; }
.kl-dark header            { background-color: #232b3e !important; border-color: #2e3a52 !important; box-shadow: 0 1px 4px rgba(0,0,0,0.25) !important; }
.kl-dark #main-sidebar     { background-color: #232b3e !important; border-color: #2e3a52 !important; }
.kl-dark .kl-logo-kinder   { color: #ffffff !important; }
.kl-dark .bg-white         { background-color: #232b3e !important; }
.kl-dark .bg-gray-50       { background-color: #1a1e2e !important; }
.kl-dark .bg-sky-50        { background-color: #172030 !important; }
.kl-dark .bg-blue-50       { background-color: #161e30 !important; }
.kl-dark .bg-teal-50       { background-color: #162020 !important; }
.kl-dark .bg-orange-50     { background-color: #2c1f18 !important; }
.kl-dark .bg-yellow-50     { background-color: #2a2315 !important; }
.kl-dark .bg-green-50      { background-color: #172218 !important; }
.kl-dark .bg-purple-50     { background-color: #1e1730 !important; }
.kl-dark .text-gray-800    { color: #e2e8f2 !important; }
.kl-dark .text-gray-700    { color: #d0d9e8 !important; }
.kl-dark .text-gray-600    { color: #b0bdd4 !important; }
.kl-dark .text-gray-500    { color: #8494ae !important; }
.kl-dark .text-gray-400    { color: #566278 !important; }
.kl-dark .border-gray-200  { border-color: #2e3a52 !important; }
.kl-dark .border-gray-100  { border-color: #232b3e !important; }
.kl-dark .divide-y > * + * { border-color: #2e3a52 !important; }
.kl-dark input, .kl-dark textarea, .kl-dark select {
    background-color: #2a3347 !important;
    border-color: #3a4a62 !important;
    color: #e2e8f2 !important;
}
.kl-dark .hover\:bg-gray-50:hover  { background-color: #232b3e !important; }
.kl-dark .hover\:bg-gray-100:hover { background-color: #2a3347 !important; }
.kl-dark #profile-dropdown { background-color: #232b3e !important; border-color: #2e3a52 !important; }
.kl-dark #notif-dropdown   { background-color: #232b3e !important; border-color: #2e3a52 !important; }
.kl-dark .sb-link           { color: #b0bdd4 !important; }
.kl-dark .sb-link:hover,
.kl-dark .sb-link.sb-active { background: rgba(14,165,233,0.13) !important; color: #38bdf8 !important; }
.kl-dark .dd-item           { color: #b0bdd4 !important; }
.kl-dark .dd-item:hover     { background: #2e3a52 !important; color: #e2e8f2 !important; }
.kl-dark .dd-divider        { border-color: #2e3a52 !important; }
.kl-dark #sidebar-overlay   { background: rgba(0,0,0,0.6) !important; }
.kl-dark #logout-modal .bg-white,
.kl-dark #delete-class-modal .bg-white { background-color: #232b3e !important; }

/* ── High Contrast ── */
.kl-contrast .text-gray-400,
.kl-contrast .text-gray-500 { color: #1f2937 !important; font-weight: 700 !important; }
.kl-contrast .bg-white       { outline: 2px solid #374151 !important; }
.kl-contrast a                { text-decoration: underline !important; }
.kl-contrast button           { outline: 2px solid currentColor !important; }
</style>

{{-- Settings init: runs in <head> to avoid FOUC --}}
<script>
(function () {
    function getS(k, d) { try { return JSON.parse(localStorage.getItem('kl_' + k)); } catch(e) { return d; } }
    var theme = getS('theme', 'light');
    var fsIdx = Math.max(0, Math.min(3, parseInt(getS('fontSize', 1)) || 1));
    var scales = ['0.85rem','1rem','1.15rem','1.3rem'];
    if (theme === 'dark') document.documentElement.classList.add('kl-dark');
    if (getS('highContrast', false)) document.documentElement.classList.add('kl-contrast');
    document.documentElement.style.fontSize = scales[fsIdx];
    if (getS('dyslexia', false)) document.documentElement.style.fontFamily = "'OpenDyslexic','Nunito',sans-serif";
    var css = '';
    if (getS('reduceMotion', false)) css += '*, *::before, *::after { animation: none !important; transition: none !important; }';
    if (getS('largeButtons', false)) css += 'button, .btn-kid, a.btn-kid { min-height: 3rem !important; }';
    if (css) { var s = document.createElement('style'); s.id = 'kl-dynamic'; s.textContent = css; document.head.appendChild(s); }
}());
</script>
@endpush

@section('content')
<div class="flex h-screen overflow-hidden" style="background-color: #f5f7fb;">

    <div id="sidebar-overlay" class="fixed inset-0 z-20 hidden"
         style="background: rgba(0,0,0,0.45);" onclick="mobileSidebarClose()"></div>

    {{-- Sidebar — Messages removed (top nav), Analytics merged into Progress, Activities inside Modules, Students inside Classes --}}
    <aside id="main-sidebar" class="fixed inset-y-0 left-0 z-30 flex flex-col">
        <nav class="flex flex-col gap-0.5 flex-1 px-2 overflow-y-auto pb-4 pt-4">

            <a href="{{ route('teacher.dashboard') }}"
               class="sb-link {{ request()->routeIs('teacher.dashboard') ? 'sb-active' : '' }}">
                <span class="sb-icon"><i class="ri-home-5-line"></i></span>
                <span class="sb-label">Dashboard</span>
            </a>

            <a href="{{ route('teacher.classes') }}"
               data-tour="link-classes"
               class="sb-link {{ request()->routeIs('teacher.classes*') || request()->routeIs('teacher.students*') ? 'sb-active' : '' }}">
                <span class="sb-icon"><i class="ri-school-line"></i></span>
                <span class="sb-label">My Classes</span>
            </a>

            <a href="{{ route('teacher.modules') }}"
               data-tour="link-modules"
               class="sb-link {{ request()->routeIs('teacher.modules*') ? 'sb-active' : '' }}">
                <span class="sb-icon"><i class="ri-book-2-line"></i></span>
                <span class="sb-label">Modules</span>
            </a>

            <a href="{{ route('teacher.activities') }}"
               class="sb-link {{ request()->routeIs('teacher.activities*') ? 'sb-active' : '' }}">
                <span class="sb-icon"><i class="ri-gamepad-line"></i></span>
                <span class="sb-label">Activities</span>
            </a>

            <button type="button" onclick="showComingSoon('Attendance')"
               class="sb-link w-full text-left opacity-70">
                <span class="sb-icon"><i class="ri-calendar-check-line"></i></span>
                <span class="sb-label">Attendance</span>
            </button>

            <button type="button" onclick="showComingSoon('Online Class')"
               class="sb-link w-full text-left opacity-70">
                <span class="sb-icon"><i class="ri-video-chat-line"></i></span>
                <span class="sb-label">Online Class</span>
            </button>

            <a href="{{ route('teacher.progress') }}"
               class="sb-link {{ request()->routeIs('teacher.progress*') || request()->routeIs('teacher.analytics*') ? 'sb-active' : '' }}">
                <span class="sb-icon"><i class="ri-bar-chart-2-line"></i></span>
                <span class="sb-label">Progress</span>
            </a>

            <a href="{{ route('teacher.reports') }}"
               class="sb-link {{ request()->routeIs('teacher.reports*') ? 'sb-active' : '' }}">
                <span class="sb-icon"><i class="ri-file-chart-line"></i></span>
                <span class="sb-label">Reports</span>
            </a>

        </nav>
    </aside>

    {{-- Content wrapper --}}
    <div id="content-wrapper" class="flex flex-col w-full overflow-hidden">

        <header class="fixed top-0 left-0 right-0 z-40 bg-white flex items-center justify-between px-5 py-3"
                style="border-bottom: 1px solid #ebebeb; box-shadow: 0 1px 4px rgba(0,0,0,0.04);">

            <div class="flex items-center gap-3">
                <button onclick="sidebarToggle()"
                        class="md:hidden w-8 h-8 flex items-center justify-center rounded-lg
                               text-gray-500 hover:bg-gray-100 transition">
                    <i class="ri-menu-line" style="font-size: 1.2rem;"></i>
                </button>

                <a href="{{ route('teacher.dashboard') }}"
                   class="font-fredoka text-xl leading-none select-none"
                   style="text-decoration: none;">
                    <span class="kl-logo-kinder" style="color: #1A212F;">Kinder</span><span style="color: #F4654D;">Learn</span>
                </a>

                <span class="hidden sm:inline-block text-xs font-bold px-2 py-0.5 rounded-full"
                      style="background: rgba(14,165,233,0.1); color: #0ea5e9;">Teacher</span>
            </div>

            <div class="flex items-center gap-1">

                {{-- WiFi ping --}}
                <div class="hidden sm:flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-bold text-gray-400">
                    <i class="ri-wifi-line" style="font-size:0.95rem;"></i>
                    <span id="ping-ms">--</span>
                </div>

                {{-- Announcements in nav bar --}}
                <a href="{{ route('teacher.announcements') }}"
                   class="relative w-9 h-9 flex items-center justify-center rounded-lg text-gray-500
                          hover:bg-gray-100 hover:text-gray-800 transition {{ request()->routeIs('teacher.announcements*') ? 'bg-sky-50 text-sky-600' : '' }}"
                   title="Announcements">
                    <i class="ri-megaphone-line" style="font-size: 1.15rem;"></i>
                </a>

                {{-- Notification bell with dropdown --}}
                <div class="relative" id="notif-wrap">
                    <button onclick="toggleNotifDropdown()"
                            class="relative w-9 h-9 flex items-center justify-center rounded-lg text-gray-500
                                   hover:bg-gray-100 hover:text-gray-800 transition"
                            title="Notifications">
                        <i class="ri-notification-3-line" style="font-size: 1.15rem;"></i>
                        <span id="notif-badge" class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full hidden"
                              style="background:#0ea5e9;"></span>
                    </button>
                    <div id="notif-dropdown"
                         class="hidden absolute right-0 mt-2 bg-white border border-gray-100 rounded-2xl shadow-xl"
                         style="width:300px; top:100%; z-index:50;">
                        <div class="flex items-center justify-between px-4 pt-3 pb-2 border-b border-gray-100">
                            <span class="font-bold text-sm text-gray-800">Notifications</span>
                            <span id="notif-count-label" class="text-xs text-gray-400"></span>
                        </div>
                        <ul id="notif-list" class="max-h-64 overflow-y-auto divide-y divide-gray-50">
                            <li class="px-4 py-3 text-xs text-gray-400 text-center">Loading…</li>
                        </ul>
                        <div class="px-4 py-2.5 border-t border-gray-100">
                            <a href="{{ route('teacher.announcements') }}"
                               class="block text-center text-xs font-bold hover:opacity-80 transition"
                               style="color:#0ea5e9;">See all notifications →</a>
                        </div>
                    </div>
                </div>

                <div class="w-px h-5 bg-gray-200 mx-1 hidden sm:block"></div>

                <div class="relative" id="profile-menu-wrap">
                    <button onclick="toggleProfileDropdown()"
                            class="flex items-center gap-2 pl-1 pr-2 py-1 rounded-xl hover:bg-gray-100 transition">
                        @if(str_starts_with(auth()->user()->avatar ?? '', 'avatars/'))
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                             class="w-8 h-8 rounded-full object-cover ring-2 ring-sky-200">
                        @else
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-white text-sm"
                             style="background: linear-gradient(135deg, #0ea5e9, #6366f1);">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        @endif
                        <span class="hidden md:block text-sm font-semibold text-gray-700 max-w-[90px] truncate">
                            {{ auth()->user()->name }}
                        </span>
                        <i class="ri-arrow-down-s-line text-gray-400 text-base hidden md:block"></i>
                    </button>

                    <div id="profile-dropdown"
                         class="hidden absolute right-0 mt-2 bg-white border border-gray-100 rounded-xl p-1.5"
                         style="top: 100%;">
                        <div class="px-3 py-2 mb-1">
                            <p class="text-xs font-bold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">Teacher</p>
                        </div>
                        <a href="{{ route('teacher.profile') }}" class="dd-item">
                            <i class="ri-user-line text-gray-400"></i> My Profile
                        </a>
                        <a href="{{ route('teacher.settings') }}" class="dd-item">
                            <i class="ri-settings-3-line text-gray-400"></i> Settings
                        </a>
                        <hr class="dd-divider">
                        <form id="logout-form" method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="button" onclick="confirmLogout()" class="dd-item dd-danger w-full">
                                <i class="ri-logout-box-r-line"></i> Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 md:p-8">
            @yield('teacher-content')
        </main>
    </div>
</div>

{{-- ── Floating Chat Widget ─────────────────────────────────────── --}}
<div id="kl-chat-root" class="fixed bottom-6 right-6 z-[200] flex flex-col items-end gap-3">

    {{-- Panel --}}
    <div id="kl-chat-panel" class="hidden flex-col bg-white rounded-3xl shadow-2xl overflow-hidden"
         style="width:340px; height:500px; display:none;">

        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3 flex-shrink-0"
             style="background:linear-gradient(135deg,#0ea5e9,#6366f1);">
            <span class="font-fredoka text-white text-lg leading-none">Messages</span>
            <div class="flex items-center gap-3">
                <button onclick="chatShowNew()" title="New message"
                        class="text-white/80 hover:text-white transition text-base">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>
                <button onclick="chatToggle()" class="text-white/80 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>

        {{-- LIST VIEW --}}
        <div id="kl-chat-list" class="flex flex-col flex-1 overflow-hidden">
            <div class="p-3 border-b border-gray-100 flex-shrink-0">
                <input type="text" id="kl-chat-search" placeholder="Search conversations…"
                       oninput="chatFilterConvos(this.value)"
                       class="w-full px-3 py-2 rounded-xl border-2 border-gray-200 text-sm focus:outline-none focus:border-blue-300">
            </div>
            <div id="kl-chat-convos" class="flex-1 overflow-y-auto divide-y divide-gray-50">
                <p class="text-xs text-gray-400 text-center py-8">Loading…</p>
            </div>
        </div>

        {{-- THREAD VIEW --}}
        <div id="kl-chat-thread" class="hidden flex-col flex-1 overflow-hidden">
            <div class="flex items-center gap-3 px-4 py-2.5 border-b border-gray-100 flex-shrink-0">
                <button onclick="chatShowList()" class="text-gray-400 hover:text-gray-700 transition">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <div>
                    <p id="kl-thread-name" class="font-bold text-gray-800 text-sm leading-tight"></p>
                    <p id="kl-thread-role" class="text-xs text-gray-400 capitalize"></p>
                </div>
            </div>
            <div id="kl-chat-messages" class="flex-1 overflow-y-auto p-3 flex flex-col gap-2"></div>
            <div class="p-3 border-t border-gray-100 flex-shrink-0">
                <textarea id="kl-chat-reply" rows="2" placeholder="Type a message…"
                          class="w-full px-3 py-2 rounded-xl border-2 border-gray-200 text-sm focus:outline-none focus:border-blue-300 resize-none mb-2"
                          onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();chatSend();}"></textarea>
                <button onclick="chatSend()"
                        class="w-full py-2 rounded-xl font-bold text-white text-sm transition hover:opacity-90"
                        style="background:linear-gradient(135deg,#0ea5e9,#6366f1);">
                    <i class="fa-solid fa-paper-plane mr-1"></i> Send
                </button>
            </div>
        </div>

        {{-- NEW MESSAGE VIEW --}}
        <div id="kl-chat-new" class="hidden flex-col flex-1 overflow-hidden">
            <div class="flex items-center gap-3 px-4 py-2.5 border-b border-gray-100 flex-shrink-0">
                <button onclick="chatShowList()" class="text-gray-400 hover:text-gray-700 transition">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <span class="font-bold text-gray-800 text-sm">New Message</span>
            </div>
            <div class="p-3 border-b border-gray-100 flex-shrink-0">
                <input type="text" id="kl-new-search" placeholder="Search for a student…"
                       oninput="chatSearchUsers(this.value)"
                       class="w-full px-3 py-2 rounded-xl border-2 border-gray-200 text-sm focus:outline-none focus:border-blue-300">
            </div>
            <div id="kl-new-results" class="flex-1 overflow-y-auto divide-y divide-gray-50">
                <p class="text-xs text-gray-400 text-center py-6">Start typing to search…</p>
            </div>
        </div>
    </div>

    {{-- Bubble button --}}
    <button id="kl-chat-btn" onclick="chatToggle()"
            class="w-14 h-14 rounded-full shadow-xl flex items-center justify-center text-white relative transition-transform hover:scale-110 active:scale-95"
            style="background:linear-gradient(135deg,#0ea5e9,#6366f1);" title="Messages">
        <i id="kl-chat-ico" class="ri-message-3-line text-xl"></i>
        <span id="kl-chat-badge" class="hidden absolute -top-1 -right-1 min-w-[20px] h-5 rounded-full bg-red-500 text-white text-xs font-bold flex items-center justify-center px-1"></span>
    </button>
</div>

<style>
#kl-chat-panel { display:none; }
#kl-chat-panel.chat-open { display:flex !important; }
.chat-bubble-me   { align-self:flex-end;  background:linear-gradient(135deg,#0ea5e9,#6366f1); color:#fff; border-radius:18px 18px 4px 18px; }
.chat-bubble-them { align-self:flex-start; background:#f3f4f6; color:#1f2937; border-radius:18px 18px 18px 4px; }
.chat-bubble      { max-width:80%; padding:8px 12px; font-size:0.82rem; line-height:1.45; word-break:break-word; }
.chat-time        { font-size:0.65rem; opacity:.55; margin-top:2px; }
.convo-row        { display:flex; align-items:center; gap:10px; padding:10px 14px; cursor:pointer; transition:background .12s; }
.convo-row:hover  { background:#f9fafb; }
.convo-avatar     { width:36px; height:36px; border-radius:50%; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:.85rem; color:#fff; }
</style>

<script>
(function () {
    var CONVOS_URL  = '{{ route("teacher.messages.conversations") }}';
    var THREAD_BASE = '{{ url("/teacher/messages/thread") }}/';
    var SEARCH_URL  = '{{ route("teacher.messages.search") }}';
    var SEND_URL    = '{{ route("teacher.messages.send") }}';
    var CSRF        = '{{ csrf_token() }}';

    var panelOpen   = false;
    var currentView = 'list';   // 'list' | 'thread' | 'new'
    var currentPid  = null;     // partner user id
    var currentPname= '';
    var allConvos   = [];
    var convosLoaded= false;

    // ── Open / close ─────────────────────────────────────────────
    window.chatToggle = function () {
        panelOpen = !panelOpen;
        var panel = document.getElementById('kl-chat-panel');
        var ico   = document.getElementById('kl-chat-ico');
        if (panelOpen) {
            panel.classList.add('chat-open');
            ico.className = 'fa-solid fa-xmark text-xl';
            if (!convosLoaded) loadConvos();
        } else {
            panel.classList.remove('chat-open');
            ico.className = 'ri-message-3-line text-xl';
        }
    };

    // ── Views ─────────────────────────────────────────────────────
    function showView(v) {
        currentView = v;
        document.getElementById('kl-chat-list').classList.toggle('hidden', v !== 'list');
        document.getElementById('kl-chat-thread').classList.toggle('hidden', v !== 'thread');
        document.getElementById('kl-chat-new').classList.toggle('hidden', v !== 'new');
    }

    window.chatShowList = function () { showView('list'); };
    window.chatShowNew  = function () {
        document.getElementById('kl-new-search').value = '';
        document.getElementById('kl-new-results').innerHTML =
            '<p class="text-xs text-gray-400 text-center py-6">Start typing to search…</p>';
        showView('new');
    };

    // ── Load conversations ────────────────────────────────────────
    function loadConvos() {
        fetch(CONVOS_URL, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                allConvos   = data;
                convosLoaded = true;
                renderConvos(data);
                var unread = data.reduce(function (s, c) { return s + (c.unread || 0); }, 0);
                if (unread > 0) {
                    var b = document.getElementById('kl-chat-badge');
                    b.textContent = unread > 9 ? '9+' : unread;
                    b.classList.remove('hidden');
                }
            }).catch(function () {});
    }

    function avatarColor(name) {
        var colors = ['#0ea5e9','#6366f1','#ec4899','#10b981','#f59e0b','#ef4444','#8b5cf6'];
        var i = (name || '').charCodeAt(0) % colors.length;
        return colors[i];
    }

    function renderConvos(list) {
        var el = document.getElementById('kl-chat-convos');
        if (!list.length) {
            el.innerHTML = '<p class="text-xs text-gray-400 text-center py-8">No conversations yet.<br>Tap <i class="fa-solid fa-pen-to-square"></i> to start one.</p>';
            return;
        }
        el.innerHTML = list.map(function (c) {
            var initials = (c.name || '?').split(' ').map(function(w){return w[0];}).join('').substring(0,2).toUpperCase();
            var col = avatarColor(c.name);
            return '<div class="convo-row" onclick="chatOpenThread(' + c.user_id + ',\'' + escHtml(c.name) + '\',\'' + c.role + '\')">' +
                '<div class="convo-avatar" style="background:' + col + ';">' + initials + '</div>' +
                '<div class="flex-1 min-w-0">' +
                    '<div class="flex items-center justify-between">' +
                        '<span class="font-bold text-gray-800 text-sm truncate">' + escHtml(c.name) + '</span>' +
                        '<span class="text-xs text-gray-400 flex-shrink-0 ml-2">' + (c.time||'') + '</span>' +
                    '</div>' +
                    '<p class="text-xs text-gray-400 truncate mt-0.5">' + (c.from_me ? 'You: ' : '') + escHtml(c.latest||'') + '</p>' +
                '</div>' +
                (c.unread ? '<span class="w-5 h-5 rounded-full bg-blue-500 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">' + c.unread + '</span>' : '') +
            '</div>';
        }).join('');
    }

    window.chatFilterConvos = function (q) {
        var filtered = allConvos.filter(function (c) {
            return (c.name||'').toLowerCase().includes(q.toLowerCase());
        });
        renderConvos(filtered);
    };

    // ── Open a thread ─────────────────────────────────────────────
    window.chatOpenThread = function (uid, name, role) {
        currentPid   = uid;
        currentPname = name;
        document.getElementById('kl-thread-name').textContent = name;
        document.getElementById('kl-thread-role').textContent = role;
        document.getElementById('kl-chat-messages').innerHTML =
            '<p class="text-xs text-gray-400 text-center py-6">Loading…</p>';
        showView('thread');

        fetch(THREAD_BASE + uid, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.json(); })
            .then(function (data) { renderMessages(data.messages); })
            .catch(function () {});
    };

    function renderMessages(msgs) {
        var el = document.getElementById('kl-chat-messages');
        if (!msgs.length) {
            el.innerHTML = '<p class="text-xs text-gray-400 text-center py-6">No messages yet. Say hi!</p>';
            return;
        }
        el.innerHTML = msgs.map(function (m) {
            return '<div class="flex flex-col ' + (m.from_me ? 'items-end' : 'items-start') + '">' +
                '<div class="chat-bubble ' + (m.from_me ? 'chat-bubble-me' : 'chat-bubble-them') + '">' +
                    escHtml(m.body) +
                '</div>' +
                '<span class="chat-time ' + (m.from_me ? 'text-right' : '') + '">' + m.time + '</span>' +
            '</div>';
        }).join('');
        el.scrollTop = el.scrollHeight;
    }

    // ── Send message ──────────────────────────────────────────────
    window.chatSend = function () {
        var body = (document.getElementById('kl-chat-reply').value || '').trim();
        if (!body || !currentPid) return;

        var formData = new FormData();
        formData.append('_token', CSRF);
        formData.append('receiver_id', currentPid);
        formData.append('body', body);
        formData.append('subject', 'Chat');

        document.getElementById('kl-chat-reply').value = '';

        fetch(SEND_URL, { method:'POST', body:formData, headers:{'X-Requested-With':'XMLHttpRequest'} })
            .then(function (r) { return r.json(); })
            .then(function (msg) {
                var el = document.getElementById('kl-chat-messages');
                el.innerHTML += '<div class="flex flex-col items-end">' +
                    '<div class="chat-bubble chat-bubble-me">' + escHtml(msg.body) + '</div>' +
                    '<span class="chat-time text-right">' + msg.time + '</span>' +
                '</div>';
                el.scrollTop = el.scrollHeight;
                convosLoaded = false; // refresh convos next time
            }).catch(function () {});
    };

    // ── Search users for new convo ────────────────────────────────
    var searchTimer;
    window.chatSearchUsers = function (q) {
        clearTimeout(searchTimer);
        if (!q.trim()) {
            document.getElementById('kl-new-results').innerHTML =
                '<p class="text-xs text-gray-400 text-center py-6">Start typing to search…</p>';
            return;
        }
        searchTimer = setTimeout(function () {
            fetch(SEARCH_URL + '?q=' + encodeURIComponent(q), { headers:{'X-Requested-With':'XMLHttpRequest'} })
                .then(function (r) { return r.json(); })
                .then(function (users) {
                    var el = document.getElementById('kl-new-results');
                    if (!users.length) {
                        el.innerHTML = '<p class="text-xs text-gray-400 text-center py-6">No students found.</p>';
                        return;
                    }
                    el.innerHTML = users.map(function (u) {
                        var initials = (u.name||'?').split(' ').map(function(w){return w[0];}).join('').substring(0,2).toUpperCase();
                        var col = avatarColor(u.name);
                        return '<div class="convo-row" onclick="chatOpenThread(' + u.id + ',\'' + escHtml(u.name) + '\',\'' + u.role + '\')">' +
                            '<div class="convo-avatar" style="background:' + col + ';">' + initials + '</div>' +
                            '<div><p class="font-bold text-gray-800 text-sm">' + escHtml(u.name) + '</p>' +
                            '<p class="text-xs text-gray-400 capitalize">' + u.role + '</p></div>' +
                        '</div>';
                    }).join('');
                }).catch(function () {});
        }, 300);
    };

    function escHtml(s) {
        return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // Pre-load unread count on page load
    setTimeout(function () {
        fetch(CONVOS_URL, { headers:{'X-Requested-With':'XMLHttpRequest'} })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                allConvos = data;
                var unread = data.reduce(function (s, c) { return s + (c.unread||0); }, 0);
                if (unread > 0) {
                    var b = document.getElementById('kl-chat-badge');
                    b.textContent = unread > 9 ? '9+' : unread;
                    b.classList.remove('hidden');
                }
            }).catch(function () {});
    }, 1500);
}());
</script>

{{-- Logout confirmation modal --}}
<div id="logout-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center"
     style="background:rgba(0,0,0,0.45);">
    <div class="bg-white rounded-3xl p-8 shadow-2xl max-w-sm w-full mx-4 text-center">
        <div class="mb-4" style="font-size:3.5rem; color:#0ea5e9;"><i class="fa-solid fa-hand-wave"></i></div>
        <h3 class="font-fredoka text-2xl text-gray-800 mb-2">Leaving so soon?</h3>
        <p class="text-gray-500 text-sm mb-6">Are you sure you want to log out?</p>
        <div class="flex gap-3 justify-center">
            <button onclick="document.getElementById('logout-modal').classList.add('hidden')"
                    class="px-6 py-2.5 rounded-xl border-2 border-gray-200 font-bold text-gray-600 hover:bg-gray-50 transition">
                Stay
            </button>
            <button onclick="document.getElementById('logout-form').submit()"
                    class="px-6 py-2.5 rounded-xl font-bold text-white transition"
                    style="background:linear-gradient(135deg,#0ea5e9,#6366f1);">
                Yes, Log Out
            </button>
        </div>
    </div>
</div>

<script>
(function () {
    var sidebar = document.getElementById('main-sidebar');
    var overlay = document.getElementById('sidebar-overlay');
    function isDesktop() { return window.innerWidth >= 768; }

    window.sidebarToggle = function () {
        if (!isDesktop()) {
            var open = sidebar.classList.toggle('sb-open');
            overlay.classList.toggle('hidden', !open);
        }
    };
    window.mobileSidebarClose = function () {
        sidebar.classList.remove('sb-open');
        overlay.classList.add('hidden');
    };
    window.addEventListener('resize', function () {
        if (isDesktop()) { sidebar.classList.remove('sb-open'); overlay.classList.add('hidden'); }
    });

    window.toggleProfileDropdown = function () {
        document.getElementById('profile-dropdown').classList.toggle('hidden');
        document.getElementById('notif-dropdown').classList.add('hidden');
    };
    document.addEventListener('click', function (e) {
        if (!document.getElementById('profile-menu-wrap').contains(e.target))
            document.getElementById('profile-dropdown').classList.add('hidden');
        if (!document.getElementById('notif-wrap').contains(e.target))
            document.getElementById('notif-dropdown').classList.add('hidden');
    });

    window.confirmLogout = function () {
        document.getElementById('profile-dropdown').classList.add('hidden');
        document.getElementById('logout-modal').classList.remove('hidden');
    };

    var notifLoaded = false;
    window.toggleNotifDropdown = function () {
        var dd = document.getElementById('notif-dropdown');
        var hidden = dd.classList.toggle('hidden');
        document.getElementById('profile-dropdown').classList.add('hidden');
        if (!hidden && !notifLoaded) { loadNotifications(); notifLoaded = true; }
    };
    function loadNotifications() {
        fetch('/teacher/notifications/preview', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r){ return r.json(); })
            .then(function(data) {
                var list = document.getElementById('notif-list');
                var label = document.getElementById('notif-count-label');
                if (!data.length) {
                    list.innerHTML = '<li class="px-4 py-4 text-xs text-gray-400 text-center">No new notifications</li>';
                    return;
                }
                label.textContent = data.length + ' new';
                document.getElementById('notif-badge').classList.remove('hidden');
                list.innerHTML = data.map(function(n) {
                    return '<li class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition">' +
                        '<span class="mt-0.5 text-base flex-shrink-0">' + (n.icon || '<i class="fa-solid fa-bell"></i>') + '</span>' +
                        '<div><p class="text-xs font-bold text-gray-800">' + n.title + '</p>' +
                        '<p class="text-xs text-gray-400 mt-0.5">' + n.body + '</p></div></li>';
                }).join('');
            })
            .catch(function(){});
    }

    function pingServer() {
        var t = Date.now();
        fetch('/ping?_=' + t, { cache:'no-store' })
            .then(function(){ document.getElementById('ping-ms').textContent = (Date.now()-t) + 'ms'; })
            .catch(function(){ document.getElementById('ping-ms').textContent = '--'; });
    }
    pingServer();
    setInterval(pingServer, 10000);
}());
</script>

{{-- ══════════════════════════════════════════════════
     COMING SOON MODAL
     ══════════════════════════════════════════════════ --}}
<div id="coming-soon-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center"
     style="background:rgba(10,15,40,0.80);backdrop-filter:blur(4px);">
    <div style="background:#fff;border-radius:28px;padding:40px 32px 32px;max-width:340px;width:calc(100vw - 48px);text-align:center;box-shadow:0 24px 64px rgba(0,0,0,0.3);animation:klModalPop 0.4s cubic-bezier(0.34,1.56,0.64,1) both;">
        <div style="font-size:3.5rem;margin-bottom:12px;color:#FF8E53;"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <div id="coming-soon-title" style="font-family:Fredoka One,Fredoka,cursive;font-size:1.7rem;color:#1a1e2e;margin-bottom:8px;">Coming Soon</div>
        <p style="color:#6b7280;font-size:0.88rem;line-height:1.65;margin-bottom:24px;">
            This feature is not yet available and is reserved for a future update. Stay tuned!
        </p>
        <button onclick="hideComingSoon()"
                style="width:100%;padding:13px 0;border-radius:16px;background:linear-gradient(135deg,#0ea5e9,#6366f1);color:#fff;font-weight:800;font-size:0.95rem;border:none;cursor:pointer;">
            Got it!
        </button>
    </div>
</div>
<script>
function showComingSoon(feature) {
    var el = document.getElementById('coming-soon-modal');
    var title = document.getElementById('coming-soon-title');
    if (title && feature) title.textContent = (feature || '') + ' — Coming Soon';
    el.classList.remove('hidden');
    el.style.display = 'flex';
}
function hideComingSoon() {
    var el = document.getElementById('coming-soon-modal');
    el.classList.add('hidden');
    el.style.display = '';
}
document.getElementById('coming-soon-modal').addEventListener('click', function(e){
    if (e.target === this) hideComingSoon();
});
</script>

{{-- ══════════════════════════════════════════════════
     TEACHER WALKTHROUGH TOUR
     ══════════════════════════════════════════════════ --}}
<style>
@keyframes klModalPop {
    0%   { transform: scale(0.65) translateY(12px); opacity: 0; }
    100% { transform: scale(1)    translateY(0);    opacity: 1; }
}
@keyframes klRingPulse {
    0%, 100% { box-shadow: 0 0 0 4px rgba(14,165,233,0.9),  0 0 0 8px rgba(14,165,233,0.3),  0 0 0 9999px rgba(10,15,40,0.80); }
    50%       { box-shadow: 0 0 0 4px rgba(14,165,233,1),    0 0 0 14px rgba(14,165,233,0.15), 0 0 0 9999px rgba(10,15,40,0.80); }
}
#kl-tour-ring {
    position: fixed; border-radius: 14px; pointer-events: none; z-index: 9001;
    transition: left 0.3s ease, top 0.3s ease, width 0.3s ease, height 0.3s ease;
    animation: klRingPulse 1.8s ease-in-out infinite;
}
#kl-tour-tip {
    position: fixed; z-index: 9010; width: 270px; max-width: calc(100vw - 24px);
    background: #fff; border-radius: 22px; box-shadow: 0 16px 48px rgba(0,0,0,0.26);
    padding: 22px 20px 18px; pointer-events: all;
    transition: top 0.3s ease, left 0.3s ease;
}
#kl-tour-tip::before {
    content: ''; position: absolute; width: 12px; height: 12px;
    background: #fff; transform: rotate(45deg);
}
#kl-tour-tip.tip-below::before { top: -6px; left: 22px; }
#kl-tour-tip.tip-above::before { bottom: -6px; left: 22px; }
#kl-tour-tip.tip-right::before { left: -6px; top: 22px; }
#kl-tour-skip {
    position: fixed; top: 68px; right: 16px; z-index: 9012;
    padding: 7px 16px; border-radius: 999px;
    border: 1.5px solid rgba(255,255,255,0.3);
    background: rgba(15,20,50,0.6); color: #fff;
    font-size: 0.78rem; font-weight: 700; cursor: pointer;
    backdrop-filter: blur(6px); letter-spacing: 0.04em;
    pointer-events: all; transition: background 0.15s;
}
#kl-tour-skip:hover { background: rgba(15,20,50,0.85); }
#kl-tour-modal-wrap {
    position: fixed; inset: 0; z-index: 9010;
    display: flex; align-items: center; justify-content: center;
    background: rgba(10,15,40,0.84); backdrop-filter: blur(4px); pointer-events: all;
}
#kl-tour-modal-card {
    background: #fff; border-radius: 28px;
    box-shadow: 0 24px 64px rgba(0,0,0,0.32);
    padding: 36px 30px 28px; max-width: 320px;
    width: calc(100vw - 48px); text-align: center;
    animation: klModalPop 0.45s cubic-bezier(0.34,1.56,0.64,1) both; pointer-events: all;
}
</style>
<script>
(function () {
    var STEP_KEY = 'kl_teacher_tour_step';
    var DONE_KEY = 'kl_teacher_tour_done';
    function lsGet(k)    { return localStorage.getItem(k); }
    function lsSet(k, v) { localStorage.setItem(k, v); }
    function lsDel(k)    { localStorage.removeItem(k); }

    window.__klTeacherTourReset = function(){ lsDel(DONE_KEY); lsDel(STEP_KEY); location.href = '/teacher/dashboard'; };

    if (lsGet(DONE_KEY)) return;

    var STEPS = [
        { id:1, page:'dashboard',  type:'modal',
          icon:'<i class="fa-solid fa-chalkboard-user" style="color:#0ea5e9;"></i>', title:'Welcome, Teacher!',
          body:"Let's take a quick tour of your tools. It'll only take a minute!",
          btn:"Let's Go! →" },

        { id:2, page:'dashboard',  type:'spotlight', target:'[data-tour="link-classes"]',
          icon:'<i class="fa-solid fa-school" style="color:#4ECDC4;"></i>', title:'My Classes',
          body:'Create classes and share join codes with your students. <strong>Tap to explore!</strong>',
          action:'click', next:3 },

        { id:3, page:'classes',    type:'spotlight', target:'[data-tour="classes-area"]',
          icon:'<i class="fa-solid fa-child" style="color:#FF6B6B;"></i>', title:'Your Classes',
          body:'Manage your classes here — view students, share join codes, and more.',
          action:'next', next:4 },

        { id:4, page:'classes',    type:'spotlight', target:'[data-tour="link-modules"]',
          icon:'<i class="fa-solid fa-book-open" style="color:#45B7D1;"></i>', title:'Modules',
          body:'Create and manage learning modules for your students. <strong>Tap here!</strong>',
          action:'click', next:5 },

        { id:5, page:'modules',    type:'spotlight', target:'[data-tour="modules-grid"]',
          icon:'<i class="fa-solid fa-star" style="color:#FFD700;"></i>', title:'Learning Modules',
          body:'Each card is a subject. Add activities, set levels, and track progress.',
          action:'next', next:6 },

        { id:6, page:'modules',    type:'modal',
          icon:'<i class="fa-solid fa-rocket" style="color:#0ea5e9;"></i>', title:"You're all set!",
          body:"You know your way around the teacher panel. Go manage your class!",
          btn:'Get Started!', done:true },
    ];

    var PAGE_URLS = {
        dashboard: '/teacher/dashboard',
        classes:   '/teacher/classes',
        modules:   '/teacher/modules',
    };

    var p = window.location.pathname;
    var pageKey = 'dashboard';
    if      (/\/teacher\/classes/.test(p))   pageKey = 'classes';
    else if (/\/teacher\/modules/.test(p))   pageKey = 'modules';
    else if (/\/teacher\/dashboard/.test(p)) pageKey = 'dashboard';

    var currentId = parseInt(lsGet(STEP_KEY) || '1', 10);
    var step = STEPS.find(function(s){ return s.id === currentId && s.page === pageKey; });
    if (!step) return;

    var elRing, elTip, elSkip, elModal, elSidebarExpanded;

    function removeAll() {
        [elRing, elTip, elSkip, elModal].forEach(function(el){ if (el) el.remove(); });
        elRing = elTip = elSkip = elModal = null;
        collapseSidebar();
        window.removeEventListener('resize', reposition);
        window.removeEventListener('scroll', reposition, true);
    }

    function expandSidebar() {
        var sb = document.getElementById('main-sidebar');
        if (!sb) return;
        if (window.innerWidth >= 768) { sb.style.width = '220px'; elSidebarExpanded = true; }
        else { sb.classList.add('sb-open'); var ov = document.getElementById('sidebar-overlay'); if (ov) ov.classList.remove('hidden'); }
    }
    function collapseSidebar() {
        if (!elSidebarExpanded) return;
        var sb = document.getElementById('main-sidebar');
        if (sb) sb.style.width = '';
        elSidebarExpanded = false;
    }

    function tourSkip() { lsDel(STEP_KEY); lsSet(DONE_KEY, '1'); removeAll(); }
    function tourDone() {
        lsDel(STEP_KEY); lsSet(DONE_KEY, '1');
        if (elModal) { elModal.style.transition = 'opacity 0.35s'; elModal.style.opacity = '0'; setTimeout(removeAll, 350); }
        else { removeAll(); }
    }
    function advanceTo(nextId) {
        lsSet(STEP_KEY, nextId);
        var ns = STEPS.find(function(s){ return s.id === nextId; });
        if (ns && ns.page !== pageKey) { window.location.href = PAGE_URLS[ns.page]; }
        else { removeAll(); showStep(nextId); }
    }

    function dots(activeId) {
        return '<div style="display:flex;gap:5px;justify-content:center;margin-top:14px;">' +
            STEPS.map(function(s){
                return '<span style="width:7px;height:7px;border-radius:50%;display:inline-block;background:' +
                    (s.id === activeId ? '#0ea5e9' : '#d1d5db') + ';"></span>';
            }).join('') + '</div>';
    }

    function makeSkip() {
        elSkip = document.createElement('button');
        elSkip.id = 'kl-tour-skip';
        elSkip.innerHTML = '✕&nbsp; Skip Tour';
        elSkip.addEventListener('click', tourSkip);
        document.body.appendChild(elSkip);
    }

    function showModal(s) {
        removeAll();
        elModal = document.createElement('div');
        elModal.id = 'kl-tour-modal-wrap';
        var card = document.createElement('div');
        card.id = 'kl-tour-modal-card';
        card.innerHTML =
            '<div style="font-size:4rem;margin-bottom:14px;">' + s.icon + '</div>' +
            '<div style="font-family:Fredoka One,Fredoka,cursive;font-size:1.65rem;color:#1a1e2e;margin-bottom:10px;">' + s.title + '</div>' +
            '<div style="color:#6b7280;font-size:0.88rem;line-height:1.65;margin-bottom:22px;">' + s.body + '</div>' +
            '<button id="kl-modal-btn" style="width:100%;padding:14px 0;border-radius:16px;background:linear-gradient(135deg,#0ea5e9,#6366f1);color:#fff;font-weight:800;font-size:1rem;border:none;cursor:pointer;">' + s.btn + '</button>' +
            (s.done ? '' : dots(s.id));
        elModal.appendChild(card);
        document.body.appendChild(elModal);
        makeSkip();
        card.querySelector('#kl-modal-btn').addEventListener('click', function(){
            if (s.done) { tourDone(); } else { advanceTo(2); }
        });
    }

    var _target;
    function reposition() {
        if (!_target || !elRing || !elTip) return;
        var r = _target.getBoundingClientRect();
        var pad = 10;
        elRing.style.left   = (r.left   - pad) + 'px';
        elRing.style.top    = (r.top    - pad) + 'px';
        elRing.style.width  = (r.width  + pad*2) + 'px';
        elRing.style.height = (r.height + pad*2) + 'px';
        positionTip(r, pad);
    }
    function positionTip(r, pad) {
        var tipW = 270, tipH = elTip.offsetHeight || 200;
        var winW = window.innerWidth, winH = window.innerHeight, gap = 14;
        elTip.className = '';
        var top, left;
        if (r.bottom + pad + gap + tipH < winH) {
            top = r.bottom + pad + gap; left = Math.max(12, Math.min(winW - tipW - 12, r.left)); elTip.classList.add('tip-below');
        } else if (r.top - pad - gap - tipH > 0) {
            top = r.top - pad - gap - tipH; left = Math.max(12, Math.min(winW - tipW - 12, r.left)); elTip.classList.add('tip-above');
        } else if (r.right + pad + gap + tipW < winW) {
            top = Math.max(12, r.top - 10); left = r.right + pad + gap; elTip.classList.add('tip-right');
        } else {
            top = Math.max(12, r.top - 10); left = Math.max(12, r.left - tipW - gap);
        }
        elTip.style.top = top + 'px'; elTip.style.left = left + 'px';
    }

    function showSpotlight(s) {
        removeAll();
        var isSidebarLink = s.target && s.target.indexOf('link-') !== -1;
        if (isSidebarLink) expandSidebar();
        setTimeout(function(){
            var el = document.querySelector(s.target);
            if (!el) { advanceTo(s.next); return; }
            _target = el;
            elRing = document.createElement('div'); elRing.id = 'kl-tour-ring'; document.body.appendChild(elRing);
            elTip = document.createElement('div'); elTip.id = 'kl-tour-tip';
            var isClick = s.action === 'click';
            var btnHtml = isClick
                ? '<div style="margin-top:14px;padding:9px 14px;background:rgba(14,165,233,0.1);border-radius:12px;text-align:center;font-size:0.8rem;font-weight:700;color:#0ea5e9;"><i class=\"fa-solid fa-hand-pointer\"></i> Tap the highlighted area</div>'
                : '<button id="kl-next-btn" style="margin-top:14px;width:100%;padding:10px 0;border-radius:12px;background:linear-gradient(135deg,#0ea5e9,#6366f1);color:#fff;font-weight:700;font-size:0.85rem;border:none;cursor:pointer;">Next →</button>';
            elTip.innerHTML =
                '<div style="font-size:1.9rem;margin-bottom:6px;">' + s.icon + '</div>' +
                '<div style="font-weight:800;color:#1a1e2e;font-size:0.98rem;margin-bottom:5px;">' + s.title + '</div>' +
                '<div style="color:#6b7280;font-size:0.82rem;line-height:1.5;">' + s.body + '</div>' +
                btnHtml + dots(s.id);
            document.body.appendChild(elTip);
            reposition();
            window.addEventListener('resize', reposition);
            window.addEventListener('scroll', reposition, true);
            if (!isClick) {
                document.getElementById('kl-next-btn').addEventListener('click', function(){ advanceTo(s.next); });
            } else {
                function onTargetClick() { el.removeEventListener('click', onTargetClick); lsSet(STEP_KEY, s.next); }
                el.addEventListener('click', onTargetClick);
            }
            makeSkip();
        }, isSidebarLink ? 160 : 0);
    }

    function showStep(id) {
        var s = STEPS.find(function(s){ return s.id === id; });
        if (!s) { tourSkip(); return; }
        if (s.type === 'modal') showModal(s); else showSpotlight(s);
    }

    window.addEventListener('load', function(){ showStep(currentId); });
}());
</script>

@endsection
