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
.sb-link:hover     { background: rgba(99,102,241,0.08); color: #6366f1; }
.sb-link.sb-active { background: rgba(99,102,241,0.13); color: #6366f1; }

.sb-icon {
    font-size: 1.15rem;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2rem;
    height: 2rem;
    color: #6366f1;
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
.kl-dark .bg-purple-50     { background-color: #1e1730 !important; }
.kl-dark .bg-indigo-50     { background-color: #1a1e30 !important; }
.kl-dark .bg-sky-50        { background-color: #172030 !important; }
.kl-dark .bg-green-50      { background-color: #172218 !important; }
.kl-dark .bg-orange-50     { background-color: #2c1f18 !important; }
.kl-dark .bg-yellow-50     { background-color: #2a2315 !important; }
.kl-dark .bg-blue-50       { background-color: #161e30 !important; }
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
.kl-dark .sb-link.sb-active { background: rgba(99,102,241,0.13) !important; color: #818cf8 !important; }
.kl-dark .dd-item           { color: #b0bdd4 !important; }
.kl-dark .dd-item:hover     { background: #2e3a52 !important; color: #e2e8f2 !important; }
.kl-dark .dd-divider        { border-color: #2e3a52 !important; }
.kl-dark #sidebar-overlay   { background: rgba(0,0,0,0.6) !important; }
.kl-dark #logout-modal .bg-white { background-color: #232b3e !important; }

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

    <aside id="main-sidebar" class="fixed inset-y-0 left-0 z-30 flex flex-col">
        <nav class="flex flex-col gap-0.5 flex-1 px-2 overflow-y-auto pb-4 pt-4">

            <a href="{{ route('admin.dashboard') }}"
               class="sb-link {{ request()->routeIs('admin.dashboard') ? 'sb-active' : '' }}">
                <span class="sb-icon"><i class="ri-dashboard-line"></i></span>
                <span class="sb-label">Dashboard</span>
            </a>

            <a href="{{ route('admin.users') }}"
               class="sb-link {{ request()->routeIs('admin.users*') ? 'sb-active' : '' }}">
                <span class="sb-icon"><i class="ri-group-line"></i></span>
                <span class="sb-label">All Users</span>
            </a>

            <a href="{{ route('admin.teachers') }}"
               class="sb-link {{ request()->routeIs('admin.teachers*') ? 'sb-active' : '' }}">
                <span class="sb-icon"><i class="ri-user-star-line"></i></span>
                <span class="sb-label">Teachers</span>
            </a>

            <a href="{{ route('admin.students') }}"
               class="sb-link {{ request()->routeIs('admin.students*') ? 'sb-active' : '' }}">
                <span class="sb-icon"><i class="ri-user-heart-line"></i></span>
                <span class="sb-label">Students</span>
            </a>

            <a href="{{ route('admin.reports') }}"
               class="sb-link {{ request()->routeIs('admin.reports*') ? 'sb-active' : '' }}">
                <span class="sb-icon"><i class="ri-file-chart-line"></i></span>
                <span class="sb-label">Reports</span>
            </a>

            <a href="{{ route('admin.settings') }}"
               class="sb-link {{ request()->routeIs('admin.settings*') ? 'sb-active' : '' }}">
                <span class="sb-icon"><i class="ri-settings-3-line"></i></span>
                <span class="sb-label">Settings</span>
            </a>

            @php $supportUnread = \App\Models\Message::where('receiver_id', auth()->id())->whereNull('read_at')->count(); @endphp
            <a href="{{ route('admin.support') }}"
               class="sb-link {{ request()->routeIs('admin.support*') ? 'sb-active' : '' }}">
                <span class="sb-icon relative">
                    <i class="ri-customer-service-2-line"></i>
                    @if($supportUnread > 0)
                    <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full text-white flex items-center justify-center font-bold"
                          style="font-size:0.55rem; background:#ef4444;">{{ $supportUnread > 9 ? '9+' : $supportUnread }}</span>
                    @endif
                </span>
                <span class="sb-label flex items-center gap-2">
                    Support
                    @if($supportUnread > 0)
                    <span class="px-1.5 py-0.5 rounded-full text-white font-bold"
                          style="font-size:0.6rem; background:#ef4444;">{{ $supportUnread }}</span>
                    @endif
                </span>
            </a>

        </nav>
    </aside>

    <div id="content-wrapper" class="flex flex-col w-full overflow-hidden">

        <header class="fixed top-0 left-0 right-0 z-40 bg-white flex items-center justify-between px-5 py-3"
                style="border-bottom: 1px solid #ebebeb; box-shadow: 0 1px 4px rgba(0,0,0,0.04);">

            <div class="flex items-center gap-3">
                <button onclick="sidebarToggle()"
                        class="md:hidden w-8 h-8 flex items-center justify-center rounded-lg
                               text-gray-500 hover:bg-gray-100 transition">
                    <i class="ri-menu-line" style="font-size: 1.2rem;"></i>
                </button>

                <a href="{{ route('admin.dashboard') }}"
                   class="font-fredoka text-xl leading-none select-none"
                   style="text-decoration: none;">
                    <span class="kl-logo-kinder" style="color: #1A212F;">Kinder</span><span style="color: #F4654D;">Learn</span>
                </a>

                <span class="hidden sm:inline-block text-xs font-bold px-2 py-0.5 rounded-full"
                      style="background: rgba(99,102,241,0.1); color: #6366f1;">Admin</span>
            </div>

            <div class="flex items-center gap-1">

                {{-- WiFi ping --}}
                <div class="hidden sm:flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-bold text-gray-400">
                    <i class="ri-wifi-line" style="font-size:0.95rem;"></i>
                    <span id="ping-ms">--</span>
                </div>

                {{-- Notification bell --}}
                <div class="relative" id="notif-wrap">
                    <button onclick="toggleNotifDropdown()"
                            class="relative w-9 h-9 flex items-center justify-center rounded-lg text-gray-500
                                   hover:bg-gray-100 hover:text-gray-800 transition"
                            title="Notifications">
                        <i class="ri-notification-3-line" style="font-size: 1.15rem;"></i>
                        <span id="notif-badge" class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full hidden"
                              style="background:#6366f1;"></span>
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
                            <span class="block text-center text-xs text-gray-400">Admin notifications</span>
                        </div>
                    </div>
                </div>

                <div class="w-px h-5 bg-gray-200 mx-1 hidden sm:block"></div>

                <div class="relative" id="profile-menu-wrap">
                    <button onclick="toggleProfileDropdown()"
                            class="flex items-center gap-2 pl-1 pr-2 py-1 rounded-xl hover:bg-gray-100 transition">
                        @if(str_starts_with(auth()->user()->avatar ?? '', 'avatars/'))
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                             class="w-8 h-8 rounded-full object-cover ring-2 ring-indigo-200">
                        @else
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-white text-sm"
                             style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
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
                            <p class="text-xs text-gray-400 mt-0.5">Administrator</p>
                        </div>
                        <a href="{{ route('admin.profile') }}" class="dd-item">
                            <i class="ri-user-line text-gray-400"></i> My Profile
                        </a>
                        <a href="{{ route('admin.my-settings') }}" class="dd-item">
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
            @yield('admin-content')
        </main>
    </div>
</div>

{{-- Logout confirmation modal --}}
<div id="logout-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center"
     style="background:rgba(0,0,0,0.45);">
    <div class="bg-white rounded-3xl p-8 shadow-2xl max-w-sm w-full mx-4 text-center">
        <div class="mb-4" style="font-size:3.5rem; color:#BB8FCE;"><i class="fa-solid fa-hand-wave"></i></div>
        <h3 class="font-fredoka text-2xl text-gray-800 mb-2">Leaving so soon?</h3>
        <p class="text-gray-500 text-sm mb-6">Are you sure you want to log out?</p>
        <div class="flex gap-3 justify-center">
            <button onclick="document.getElementById('logout-modal').classList.add('hidden')"
                    class="px-6 py-2.5 rounded-xl border-2 border-gray-200 font-bold text-gray-600 hover:bg-gray-50 transition">
                Stay
            </button>
            <button onclick="document.getElementById('logout-form').submit()"
                    class="px-6 py-2.5 rounded-xl font-bold text-white transition"
                    style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
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
        fetch('/admin/notifications/preview', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r){ return r.json(); })
            .then(function(data) {
                var list = document.getElementById('notif-list');
                if (!data.length) {
                    list.innerHTML = '<li class="px-4 py-4 text-xs text-gray-400 text-center">No new notifications</li>';
                    return;
                }
                document.getElementById('notif-count-label').textContent = data.length + ' new';
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

@endsection
