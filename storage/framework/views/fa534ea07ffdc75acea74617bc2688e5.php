<?php $__env->startPush('styles'); ?>
<style>
/* ============================================================
   VARIABLES
   ============================================================ */
:root {
    --sb-w:      220px;
    --sb-mini:    68px;
    --hdr-h:      56px;
    --ease-sb: cubic-bezier(0.4, 0, 0.2, 1);
}

/* ============================================================
   SIDEBAR
   ============================================================ */
#main-sidebar {
    padding-top: var(--hdr-h);
    background: #ffffff;
    border-right: 1px solid #ebebeb;
    overflow-x: hidden;
    overflow-y: auto;
}

/* Desktop: icon-only by default, expands on hover */
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

/* Mobile: hidden off-screen, slides in when .sb-open added */
@media (max-width: 767px) {
    #main-sidebar {
        width: var(--sb-w);
        transform: translateX(-100%);
        transition: transform 0.28s var(--ease-sb);
    }
    #main-sidebar.sb-open { transform: translateX(0); }
}

/* ============================================================
   CONTENT WRAPPER
   ============================================================ */
#content-wrapper { padding-top: var(--hdr-h); }

@media (min-width: 768px) {
    #content-wrapper { padding-left: var(--sb-mini); }
}

/* ============================================================
   NAV LINKS
   ============================================================ */
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
.sb-link:hover     { background: rgba(244,101,77,0.08); color: #e84a2d; }
.sb-link.sb-active { background: rgba(244,101,77,0.12); color: #e84a2d; }

/* Icon */
.sb-icon {
    font-size: 1.15rem;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2rem;
    height: 2rem;
    color: #F4654D;
}

/* Label — fades in as sidebar expands */
.sb-label {
    overflow: hidden;
    opacity: 0;
    max-width: 0;
    transition: opacity 0.15s ease, max-width 0.22s var(--ease-sb);
}

/* Desktop: show label + left-align on hover */
@media (min-width: 768px) {
    #main-sidebar:hover .sb-label { opacity: 1; max-width: 160px; }
    #main-sidebar:hover .sb-link  { justify-content: flex-start; }
}

/* Mobile: labels always visible, links always left-aligned */
@media (max-width: 767px) {
    .sb-label { opacity: 1; max-width: 160px; }
    .sb-link  { justify-content: flex-start; }
}

/* ============================================================
   PROFILE DROPDOWN
   ============================================================ */
#profile-dropdown {
    min-width: 180px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
}
.dd-item {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.6rem 1rem;
    font-size: 0.85rem;
    font-weight: 600;
    color: #374151;
    text-decoration: none;
    border-radius: 0.5rem;
    transition: background 0.12s ease, color 0.12s ease;
    cursor: pointer;
    width: 100%;
    text-align: left;
    background: none;
    border: none;
}
.dd-item:hover { background: #f5f7fb; color: #111; }
.dd-item.dd-danger:hover { background: #fff1f0; color: #e53e3e; }
.dd-divider {
    border: none;
    border-top: 1px solid #f0f0f0;
    margin: 0.35rem 0;
}

/* ============================================================
   DARK MODE  (.kl-dark on <html>) — soft, easy on the eyes
   ============================================================ */
.kl-dark body,
.kl-dark #content-wrapper { background-color: #1a1e2e !important; color: #e2e8f2 !important; }
.kl-dark header            { background-color: #232b3e !important; border-color: #2e3a52 !important; box-shadow: 0 1px 4px rgba(0,0,0,0.25) !important; }
.kl-dark #main-sidebar     { background-color: #232b3e !important; border-color: #2e3a52 !important; }
.kl-dark .kl-logo-kinder   { color: #ffffff !important; }
.kl-dark .bg-white         { background-color: #232b3e !important; }
.kl-dark .bg-gray-50       { background-color: #1a1e2e !important; }
.kl-dark .bg-orange-50     { background-color: #2c1f18 !important; }
.kl-dark .bg-yellow-50     { background-color: #2a2315 !important; }
.kl-dark .bg-sky-50        { background-color: #172030 !important; }
.kl-dark .bg-purple-50     { background-color: #1e1730 !important; }
.kl-dark .bg-green-50      { background-color: #172218 !important; }
.kl-dark .bg-blue-50       { background-color: #161e30 !important; }
.kl-dark .bg-teal-50       { background-color: #162020 !important; }
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
.kl-dark .sb-link.sb-active { background: rgba(244,101,77,0.13) !important; color: #fb7a5a !important; }
.kl-dark .dd-item           { color: #b0bdd4 !important; }
.kl-dark .dd-item:hover     { background: #2e3a52 !important; color: #e2e8f2 !important; }
.kl-dark .dd-divider        { border-color: #2e3a52 !important; }
.kl-dark #sidebar-overlay   { background: rgba(0,0,0,0.6) !important; }
.kl-dark #logout-modal .bg-white,
.kl-dark .modal-panel       { background-color: #232b3e !important; }

/* ============================================================
   HIGH CONTRAST  (.kl-contrast on <html>)
   ============================================================ */
.kl-contrast .text-gray-400,
.kl-contrast .text-gray-500  { color: #1f2937 !important; font-weight: 700 !important; }
.kl-contrast .bg-white        { outline: 2px solid #374151 !important; }
.kl-contrast a                { text-decoration: underline !important; }
.kl-contrast button           { outline: 2px solid currentColor !important; }
</style>


<script>
(function () {
    function getS(k, d) { try { return JSON.parse(localStorage.getItem('kl_' + k)); } catch(e) { return d; } }
    var theme   = getS('theme', 'light');
    var fsIdx   = Math.max(0, Math.min(3, parseInt(getS('fontSize', 1)) || 1));
    var scales  = ['0.85rem','1rem','1.15rem','1.3rem'];
    var dys     = getS('dyslexia', false);
    var rm      = getS('reduceMotion', false);
    var lb      = getS('largeButtons', false);
    var hc      = getS('highContrast', false);

    if (theme === 'dark')  document.documentElement.classList.add('kl-dark');
    if (hc)                document.documentElement.classList.add('kl-contrast');
    document.documentElement.style.fontSize   = scales[fsIdx];
    if (dys) document.documentElement.style.fontFamily = "'OpenDyslexic','Nunito',sans-serif";

    var css = '';
    if (rm) css += '*, *::before, *::after { animation: none !important; transition: none !important; }';
    if (lb) css += 'button, .btn-kid, a.btn-kid { min-height: 3rem !important; }';
    if (css) { var s = document.createElement('style'); s.id = 'kl-dynamic'; s.textContent = css; document.head.appendChild(s); }
}());
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div class="flex h-screen overflow-hidden" style="background-color: #f5f7fb;">

    
    <div id="sidebar-overlay"
         class="fixed inset-0 z-20 hidden"
         style="background: rgba(0,0,0,0.45);"
         onclick="mobileSidebarClose()">
    </div>

    
    <aside id="main-sidebar"
           class="fixed inset-y-0 left-0 z-30 flex flex-col">

        
        <nav class="flex flex-col gap-0.5 flex-1 px-2 overflow-y-auto pb-4 pt-4">

            <a href="<?php echo e(route('student.dashboard')); ?>"
               class="sb-link <?php echo e(request()->routeIs('student.dashboard') ? 'sb-active' : ''); ?>">
                <span class="sb-icon"><i class="ri-home-5-line"></i></span>
                <span class="sb-label">Home</span>
            </a>

            <a href="<?php echo e(route('student.modules')); ?>"
               data-tour="link-modules"
               class="sb-link <?php echo e(request()->routeIs('student.modules*') ? 'sb-active' : ''); ?>">
                <span class="sb-icon"><i class="ri-book-2-line"></i></span>
                <span class="sb-label">My Lessons</span>
            </a>

            <a href="<?php echo e(route('student.activities')); ?>"
               data-tour="link-activities"
               class="sb-link <?php echo e(request()->routeIs('student.activities*') ? 'sb-active' : ''); ?>">
                <span class="sb-icon"><i class="ri-gamepad-line"></i></span>
                <span class="sb-label">Activities</span>
            </a>

            <a href="<?php echo e(route('student.achievements')); ?>"
               data-tour="link-badges"
               class="sb-link <?php echo e(request()->routeIs('student.achievements') ? 'sb-active' : ''); ?>">
                <span class="sb-icon"><i class="ri-award-line"></i></span>
                <span class="sb-label">My Badges</span>
            </a>

            <a href="<?php echo e(route('student.progress')); ?>"
               class="sb-link <?php echo e(request()->routeIs('student.progress') ? 'sb-active' : ''); ?>">
                <span class="sb-icon"><i class="ri-bar-chart-2-line"></i></span>
                <span class="sb-label">My Progress</span>
            </a>

            <a href="<?php echo e(route('student.classes')); ?>"
               class="sb-link <?php echo e(request()->routeIs('student.classes*') ? 'sb-active' : ''); ?>">
                <span class="sb-icon"><i class="ri-school-line"></i></span>
                <span class="sb-label">My Classes</span>
            </a>

        </nav>
    </aside>

    
    <div id="content-wrapper" class="flex flex-col w-full overflow-hidden">

        
        <header class="fixed top-0 left-0 right-0 z-40 bg-white flex items-center justify-between px-5 py-3"
                style="border-bottom: 1px solid #ebebeb; box-shadow: 0 1px 4px rgba(0,0,0,0.04);">

            
            <div class="flex items-center gap-3">

                
                <button onclick="sidebarToggle()"
                        class="md:hidden w-8 h-8 flex items-center justify-center rounded-lg text-gray-500
                               hover:bg-gray-100 hover:text-gray-800 transition"
                        title="Toggle sidebar">
                    <i class="ri-menu-line" style="font-size: 1.2rem;"></i>
                </button>

                
                <a href="<?php echo e(route('student.dashboard')); ?>"
                   class="font-fredoka text-xl leading-none select-none"
                   style="text-decoration: none;">
                    <span class="kl-logo-kinder" style="color: #1A212F;">Kinder</span><span style="color: #F4654D;">Learn</span>
                </a>
                <span class="hidden sm:inline-block text-xs font-bold px-2 py-0.5 rounded-full"
                      style="background: rgba(244,101,77,0.1); color: #F4654D;">Student</span>
            </div>

            
            <div class="flex items-center gap-1">

                
                <div class="hidden sm:flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-bold text-gray-400" id="ping-wrap">
                    <i class="ri-wifi-line" style="font-size:0.95rem;"></i>
                    <span id="ping-ms">--</span>
                </div>

                
                <button onclick="document.getElementById('support-modal').classList.remove('hidden')"
                        class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-500
                               hover:bg-orange-50 hover:text-orange-500 transition"
                        title="Customer Support">
                    <i class="ri-customer-service-2-line" style="font-size:1.15rem;"></i>
                </button>

                
                <div class="relative" id="notif-wrap">
                    <button onclick="toggleNotifDropdown()"
                            class="relative w-9 h-9 flex items-center justify-center rounded-lg text-gray-500
                                   hover:bg-gray-100 hover:text-gray-800 transition"
                            title="Notifications">
                        <i class="ri-notification-3-line" style="font-size: 1.15rem;"></i>
                        <span id="notif-badge" class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full hidden"
                              style="background:#F4654D;"></span>
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
                            <a href="<?php echo e(route('student.notifications')); ?>"
                               class="block text-center text-xs font-bold text-orange-500 hover:text-orange-600 transition">
                                See all notifications →
                            </a>
                        </div>
                    </div>
                </div>

                
                <div class="w-px h-5 bg-gray-200 mx-1 hidden sm:block"></div>

                
                <div class="relative" id="profile-menu-wrap">
                    <button onclick="toggleProfileDropdown()"
                            class="flex items-center gap-2 pl-1 pr-2 py-1 rounded-xl hover:bg-gray-100 transition">
                        <?php if(str_starts_with(auth()->user()->avatar ?? '', 'avatars/')): ?>
                        <img src="<?php echo e(asset('storage/' . auth()->user()->avatar)); ?>"
                             class="w-8 h-8 rounded-full object-cover flex-shrink-0 ring-2 ring-orange-200">
                        <?php else: ?>
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-white text-sm flex-shrink-0"
                             style="background: linear-gradient(135deg, #FF6B6B, #FF8E53);">
                            <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                        </div>
                        <?php endif; ?>
                        <span class="hidden md:block text-sm font-semibold text-gray-700 max-w-[90px] truncate">
                            <?php echo e(auth()->user()->name); ?>

                        </span>
                        <i class="ri-arrow-down-s-line text-gray-400 text-base hidden md:block"></i>
                    </button>

                    <div id="profile-dropdown"
                         class="hidden absolute right-0 mt-2 bg-white border border-gray-100 rounded-xl p-1.5"
                         style="top: 100%; min-width:180px; box-shadow:0 8px 24px rgba(0,0,0,0.12);">
                        <div class="px-3 py-2 mb-1">
                            <p class="text-xs font-bold text-gray-800 truncate"><?php echo e(auth()->user()->name); ?></p>
                            <p class="text-xs text-gray-400 mt-0.5">Student</p>
                        </div>
                        <hr class="dd-divider">
                        <a href="<?php echo e(route('student.profile')); ?>" class="dd-item">
                            <i class="ri-user-3-line text-gray-400"></i> My Profile
                        </a>
                        <a href="<?php echo e(route('student.settings')); ?>" class="dd-item">
                            <i class="ri-settings-3-line text-gray-400"></i> Settings
                        </a>
                        <hr class="dd-divider">
                        <form id="logout-form" method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="button" onclick="confirmLogout()" class="dd-item dd-danger w-full">
                                <i class="ri-logout-box-r-line"></i> Log Out
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 md:p-8">
            <?php echo $__env->yieldContent('student-content'); ?>
        </main>
    </div>
</div>


<div id="support-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl w-full mx-4 p-7" style="max-width:440px;">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-white text-lg"
                 style="background:linear-gradient(135deg,#FF6B6B,#FF8E53);">
                <i class="ri-customer-service-2-line"></i>
            </div>
            <div>
                <h3 class="font-fredoka text-xl text-gray-800 leading-tight">Need Help?</h3>
                <p class="text-xs text-gray-400 font-semibold">Send a message to our support team</p>
            </div>
            <button onclick="document.getElementById('support-modal').classList.add('hidden')"
                    class="ml-auto w-8 h-8 rounded-xl flex items-center justify-center text-gray-400
                           hover:bg-gray-100 hover:text-gray-700 transition">
                <i class="ri-close-line text-lg"></i>
            </button>
        </div>

        <?php if(session('support_success')): ?>
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-2xl text-sm font-semibold flex items-center gap-2">
            <i class="ri-checkbox-circle-line"></i> <?php echo e(session('support_success')); ?>

        </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('student.support.send')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Subject</label>
                <input type="text" name="subject" required maxlength="150"
                       class="w-full px-4 py-2.5 rounded-2xl border-2 border-gray-200 focus:border-orange-400 focus:outline-none text-sm"
                       placeholder="e.g., I can't access my activity">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Message</label>
                <textarea name="body" required maxlength="2000" rows="4"
                          class="w-full px-4 py-2.5 rounded-2xl border-2 border-gray-200 focus:border-orange-400 focus:outline-none text-sm resize-none"
                          placeholder="Tell us what's going on..."></textarea>
            </div>
            <div class="flex gap-3 pt-1">
                <button type="button"
                        onclick="document.getElementById('support-modal').classList.add('hidden')"
                        class="flex-1 py-2.5 rounded-2xl font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 transition text-sm">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 py-2.5 rounded-2xl font-bold text-white transition hover:opacity-90 text-sm"
                        style="background:linear-gradient(135deg,#FF6B6B,#FF8E53);">
                    <i class="ri-send-plane-line mr-1"></i> Send Message
                </button>
            </div>
        </form>
    </div>
</div>


<div id="logout-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center"
     style="background:rgba(0,0,0,0.45);">
    <div class="bg-white rounded-3xl p-8 shadow-2xl max-w-sm w-full mx-4 text-center">
        <div class="mb-4" style="font-size:3.5rem; color:#FF6B6B;"><i class="fa-solid fa-hand-wave"></i></div>
        <h3 class="font-fredoka text-2xl text-gray-800 mb-2">Leaving so soon?</h3>
        <p class="text-gray-500 text-sm mb-6">Are you sure you want to log out?</p>
        <div class="flex gap-3 justify-center">
            <button onclick="document.getElementById('logout-modal').classList.add('hidden')"
                    class="px-6 py-2.5 rounded-xl border-2 border-gray-200 font-bold text-gray-600 hover:bg-gray-50 transition">
                Stay
            </button>
            <button onclick="document.getElementById('logout-form').submit()"
                    class="px-6 py-2.5 rounded-xl font-bold text-white transition"
                    style="background:linear-gradient(135deg,#FF6B6B,#FF8E53);">
                Yes, Log Out
            </button>
        </div>
    </div>
</div>


<div id="kl-chat-root" class="fixed bottom-6 right-6 z-[200] flex flex-col items-end gap-3">

    <div id="kl-chat-panel" class="hidden flex-col bg-white rounded-3xl shadow-2xl overflow-hidden"
         style="width:320px; height:480px; display:none;">

        <div class="flex items-center justify-between px-4 py-3 flex-shrink-0"
             style="background:linear-gradient(135deg,#F4654D,#ff8c42);">
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

        <div id="kl-chat-list" class="flex flex-col flex-1 overflow-hidden">
            <div class="p-3 border-b border-gray-100 flex-shrink-0">
                <input type="text" id="kl-chat-search" placeholder="Search conversations…"
                       oninput="chatFilterConvos(this.value)"
                       class="w-full px-3 py-2 rounded-xl border-2 border-gray-200 text-sm focus:outline-none focus:border-orange-300">
            </div>
            <div id="kl-chat-convos" class="flex-1 overflow-y-auto divide-y divide-gray-50">
                <p class="text-xs text-gray-400 text-center py-8">Loading…</p>
            </div>
        </div>

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
                          class="w-full px-3 py-2 rounded-xl border-2 border-gray-200 text-sm focus:outline-none focus:border-orange-300 resize-none mb-2"
                          onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();chatSend();}"></textarea>
                <button onclick="chatSend()"
                        class="w-full py-2 rounded-xl font-bold text-white text-sm transition hover:opacity-90"
                        style="background:linear-gradient(135deg,#F4654D,#ff8c42);">
                    <i class="fa-solid fa-paper-plane mr-1"></i> Send
                </button>
            </div>
        </div>

        <div id="kl-chat-new" class="hidden flex-col flex-1 overflow-hidden">
            <div class="flex items-center gap-3 px-4 py-2.5 border-b border-gray-100 flex-shrink-0">
                <button onclick="chatShowList()" class="text-gray-400 hover:text-gray-700 transition">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <span class="font-bold text-gray-800 text-sm">New Message</span>
            </div>
            <div class="p-3 border-b border-gray-100 flex-shrink-0">
                <input type="text" id="kl-new-search" placeholder="Search for a teacher…"
                       oninput="chatSearchUsers(this.value)"
                       class="w-full px-3 py-2 rounded-xl border-2 border-gray-200 text-sm focus:outline-none focus:border-orange-300">
            </div>
            <div id="kl-new-results" class="flex-1 overflow-y-auto divide-y divide-gray-50">
                <p class="text-xs text-gray-400 text-center py-6">Start typing to search…</p>
            </div>
        </div>
    </div>

    <button id="kl-chat-btn" onclick="chatToggle()"
            class="w-14 h-14 rounded-full shadow-xl flex items-center justify-center text-white relative transition-transform hover:scale-110 active:scale-95"
            style="background:linear-gradient(135deg,#F4654D,#ff8c42);" title="Messages">
        <i id="kl-chat-ico" class="ri-message-3-line text-xl"></i>
        <span id="kl-chat-badge" class="hidden absolute -top-1 -right-1 min-w-[20px] h-5 rounded-full bg-blue-500 text-white text-xs font-bold flex items-center justify-center px-1"></span>
    </button>
</div>

<style>
#kl-chat-panel { display:none; }
#kl-chat-panel.chat-open { display:flex !important; }
.chat-bubble-me   { align-self:flex-end;  background:linear-gradient(135deg,#F4654D,#ff8c42); color:#fff; border-radius:18px 18px 4px 18px; }
.chat-bubble-them { align-self:flex-start; background:#f3f4f6; color:#1f2937; border-radius:18px 18px 18px 4px; }
.chat-bubble      { max-width:80%; padding:8px 12px; font-size:0.82rem; line-height:1.45; word-break:break-word; }
.chat-time        { font-size:0.65rem; opacity:.55; margin-top:2px; }
.convo-row        { display:flex; align-items:center; gap:10px; padding:10px 14px; cursor:pointer; transition:background .12s; }
.convo-row:hover  { background:#f9fafb; }
.convo-avatar     { width:36px; height:36px; border-radius:50%; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:.85rem; color:#fff; }
</style>

<script>
(function () {
    var CONVOS_URL  = '<?php echo e(route("student.messages.conversations")); ?>';
    var THREAD_BASE = '<?php echo e(url("/student/messages/thread")); ?>/';
    var SEARCH_URL  = '<?php echo e(route("student.messages.search")); ?>';
    var SEND_URL    = '<?php echo e(route("student.messages.send")); ?>';
    var CSRF        = '<?php echo e(csrf_token()); ?>';

    var panelOpen = false, currentPid = null, allConvos = [], convosLoaded = false;

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

    function showView(v) {
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

    function avatarColor(name) {
        var cols = ['#F4654D','#6366f1','#10b981','#f59e0b','#3b82f6','#8b5cf6'];
        return cols[(name||'').charCodeAt(0) % cols.length];
    }
    function escHtml(s) {
        return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function loadConvos() {
        fetch(CONVOS_URL, { headers:{'X-Requested-With':'XMLHttpRequest'} })
            .then(function(r){return r.json();})
            .then(function(data){
                allConvos = data; convosLoaded = true; renderConvos(data);
                var u = data.reduce(function(s,c){return s+(c.unread||0);},0);
                if (u > 0) { var b=document.getElementById('kl-chat-badge'); b.textContent=u>9?'9+':u; b.classList.remove('hidden'); }
            }).catch(function(){});
    }

    function renderConvos(list) {
        var el = document.getElementById('kl-chat-convos');
        if (!list.length) { el.innerHTML='<p class="text-xs text-gray-400 text-center py-8">No conversations yet.</p>'; return; }
        el.innerHTML = list.map(function(c){
            var ini=(c.name||'?').split(' ').map(function(w){return w[0];}).join('').substring(0,2).toUpperCase();
            return '<div class="convo-row" onclick="chatOpenThread('+c.user_id+',\''+escHtml(c.name)+'\',\''+c.role+'\')">' +
                '<div class="convo-avatar" style="background:'+avatarColor(c.name)+'">'+ini+'</div>' +
                '<div class="flex-1 min-w-0"><div class="flex items-center justify-between">' +
                '<span class="font-bold text-gray-800 text-sm truncate">'+escHtml(c.name)+'</span>' +
                '<span class="text-xs text-gray-400 ml-2 flex-shrink-0">'+( c.time||'')+'</span></div>' +
                '<p class="text-xs text-gray-400 truncate mt-0.5">'+(c.from_me?'You: ':'')+escHtml(c.latest||'')+'</p></div>' +
                (c.unread?'<span class="w-5 h-5 rounded-full bg-orange-500 text-white text-xs font-bold flex items-center justify-center">'+c.unread+'</span>':'') +
            '</div>';
        }).join('');
    }

    window.chatFilterConvos = function(q){
        renderConvos(allConvos.filter(function(c){return (c.name||'').toLowerCase().includes(q.toLowerCase());}));
    };

    window.chatOpenThread = function(uid, name, role) {
        currentPid = uid;
        document.getElementById('kl-thread-name').textContent = name;
        document.getElementById('kl-thread-role').textContent = role;
        document.getElementById('kl-chat-messages').innerHTML = '<p class="text-xs text-gray-400 text-center py-6">Loading…</p>';
        showView('thread');
        fetch(THREAD_BASE + uid, { headers:{'X-Requested-With':'XMLHttpRequest'} })
            .then(function(r){return r.json();})
            .then(function(d){ renderMessages(d.messages); }).catch(function(){});
    };

    function renderMessages(msgs) {
        var el = document.getElementById('kl-chat-messages');
        if (!msgs.length) { el.innerHTML='<p class="text-xs text-gray-400 text-center py-6">Say hi!</p>'; return; }
        el.innerHTML = msgs.map(function(m){
            return '<div class="flex flex-col '+(m.from_me?'items-end':'items-start')+'">' +
                '<div class="chat-bubble '+(m.from_me?'chat-bubble-me':'chat-bubble-them')+'">'+escHtml(m.body)+'</div>' +
                '<span class="chat-time '+(m.from_me?'text-right':'')+' ">'+m.time+'</span></div>';
        }).join('');
        el.scrollTop = el.scrollHeight;
    }

    window.chatSend = function() {
        var body = (document.getElementById('kl-chat-reply').value||'').trim();
        if (!body || !currentPid) return;
        var fd = new FormData();
        fd.append('_token', CSRF); fd.append('receiver_id', currentPid);
        fd.append('body', body); fd.append('subject', 'Chat');
        document.getElementById('kl-chat-reply').value = '';
        fetch(SEND_URL, { method:'POST', body:fd, headers:{'X-Requested-With':'XMLHttpRequest'} })
            .then(function(r){return r.json();})
            .then(function(msg){
                var el = document.getElementById('kl-chat-messages');
                el.innerHTML += '<div class="flex flex-col items-end"><div class="chat-bubble chat-bubble-me">'+escHtml(msg.body)+'</div><span class="chat-time text-right">'+msg.time+'</span></div>';
                el.scrollTop = el.scrollHeight;
                convosLoaded = false;
            }).catch(function(){});
    };

    var searchTimer;
    window.chatSearchUsers = function(q) {
        clearTimeout(searchTimer);
        if (!q.trim()) { document.getElementById('kl-new-results').innerHTML='<p class="text-xs text-gray-400 text-center py-6">Start typing to search…</p>'; return; }
        searchTimer = setTimeout(function(){
            fetch(SEARCH_URL+'?q='+encodeURIComponent(q), { headers:{'X-Requested-With':'XMLHttpRequest'} })
                .then(function(r){return r.json();})
                .then(function(users){
                    var el = document.getElementById('kl-new-results');
                    if (!users.length) { el.innerHTML='<p class="text-xs text-gray-400 text-center py-6">No teachers found.</p>'; return; }
                    el.innerHTML = users.map(function(u){
                        var ini=(u.name||'?').split(' ').map(function(w){return w[0];}).join('').substring(0,2).toUpperCase();
                        return '<div class="convo-row" onclick="chatOpenThread('+u.id+',\''+escHtml(u.name)+'\',\''+u.role+'\')">' +
                            '<div class="convo-avatar" style="background:'+avatarColor(u.name)+'">'+ini+'</div>' +
                            '<div><p class="font-bold text-gray-800 text-sm">'+escHtml(u.name)+'</p>' +
                            '<p class="text-xs text-gray-400 capitalize">'+u.role+'</p></div></div>';
                    }).join('');
                }).catch(function(){});
        }, 300);
    };

    setTimeout(function(){
        fetch(CONVOS_URL, { headers:{'X-Requested-With':'XMLHttpRequest'} })
            .then(function(r){return r.json();})
            .then(function(data){
                allConvos = data;
                var u = data.reduce(function(s,c){return s+(c.unread||0);},0);
                if (u > 0) { var b=document.getElementById('kl-chat-badge'); b.textContent=u>9?'9+':u; b.classList.remove('hidden'); }
            }).catch(function(){});
    }, 1500);
}());
</script>

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

    /* Profile dropdown */
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

    /* Logout confirmation */
    window.confirmLogout = function () {
        document.getElementById('profile-dropdown').classList.add('hidden');
        document.getElementById('logout-modal').classList.remove('hidden');
    };

    /* Notification dropdown */
    var notifLoaded = false;
    window.toggleNotifDropdown = function () {
        var dd = document.getElementById('notif-dropdown');
        var hidden = dd.classList.toggle('hidden');
        document.getElementById('profile-dropdown').classList.add('hidden');
        if (!hidden && !notifLoaded) { loadNotifications(); notifLoaded = true; }
    };
    function loadNotifications() {
        fetch('/student/notifications/preview', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
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
                    var inner = '<span class="mt-0.5 text-base flex-shrink-0">' + (n.icon || '🔔') + '</span>' +
                        '<div class="min-w-0"><p class="text-xs font-bold text-gray-800 truncate">' + n.title + '</p>' +
                        '<p class="text-xs text-gray-400 mt-0.5 truncate">' + n.body + '</p></div>';
                    if (n.link) {
                        return '<li><a href="' + n.link + '" class="flex items-start gap-3 px-4 py-3 hover:bg-orange-50 transition">' + inner + '</a></li>';
                    }
                    return '<li class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition">' + inner + '</li>';
                }).join('');
            })
            .catch(function(){ /* ignore fetch errors silently */ });
    }

    /* WiFi ping */
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


<style>
@keyframes klModalPop {
    0%   { transform: scale(0.65) translateY(12px); opacity: 0; }
    100% { transform: scale(1)    translateY(0);    opacity: 1; }
}
@keyframes klRingPulse {
    0%, 100% { box-shadow: 0 0 0 4px rgba(244,101,77,0.9), 0 0 0 8px rgba(244,101,77,0.3),  0 0 0 9999px rgba(10,15,40,0.80); }
    50%       { box-shadow: 0 0 0 4px rgba(244,101,77,1),   0 0 0 14px rgba(244,101,77,0.15), 0 0 0 9999px rgba(10,15,40,0.80); }
}
#kl-tour-ring {
    position: fixed;
    border-radius: 14px;
    pointer-events: none;
    z-index: 9001;
    transition: left 0.3s ease, top 0.3s ease, width 0.3s ease, height 0.3s ease;
    animation: klRingPulse 1.8s ease-in-out infinite;
}
#kl-tour-tip {
    position: fixed;
    z-index: 9010;
    width: 270px;
    max-width: calc(100vw - 24px);
    background: #fff;
    border-radius: 22px;
    box-shadow: 0 16px 48px rgba(0,0,0,0.26);
    padding: 22px 20px 18px;
    pointer-events: all;
    transition: top 0.3s ease, left 0.3s ease;
}
#kl-tour-tip::before {
    content: '';
    position: absolute;
    width: 12px; height: 12px;
    background: #fff;
    transform: rotate(45deg);
}
#kl-tour-tip.tip-below::before { top: -6px; left: 22px; }
#kl-tour-tip.tip-above::before { bottom: -6px; left: 22px; }
#kl-tour-tip.tip-right::before { left: -6px; top: 22px; }
#kl-tour-skip {
    position: fixed;
    top: 68px;
    right: 16px;
    z-index: 9012;
    padding: 7px 16px;
    border-radius: 999px;
    border: 1.5px solid rgba(255,255,255,0.3);
    background: rgba(15,20,50,0.6);
    color: #fff;
    font-size: 0.78rem;
    font-weight: 700;
    cursor: pointer;
    backdrop-filter: blur(6px);
    letter-spacing: 0.04em;
    pointer-events: all;
    transition: background 0.15s;
}
#kl-tour-skip:hover { background: rgba(15,20,50,0.85); }
#kl-tour-modal-wrap {
    position: fixed;
    inset: 0;
    z-index: 9010;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(10,15,40,0.84);
    backdrop-filter: blur(4px);
    pointer-events: all;
}
#kl-tour-modal-card {
    background: #fff;
    border-radius: 28px;
    box-shadow: 0 24px 64px rgba(0,0,0,0.32);
    padding: 36px 30px 28px;
    max-width: 320px;
    width: calc(100vw - 48px);
    text-align: center;
    animation: klModalPop 0.45s cubic-bezier(0.34,1.56,0.64,1) both;
    pointer-events: all;
}
</style>
<script>
(function () {
    var STEP_KEY = 'kl_tour_step';
    var DONE_KEY = 'kl_tour_done';
    function lsGet(k)    { return localStorage.getItem(k); }
    function lsSet(k, v) { localStorage.setItem(k, v); }
    function lsDel(k)    { localStorage.removeItem(k); }

    window.__klTourReset = function(){ lsDel(DONE_KEY); lsDel(STEP_KEY); location.href = '/student/dashboard'; };

    if (lsGet(DONE_KEY)) return;

    /* ── Step catalogue ─────────────────────────────────────── */
    var STEPS = [
        { id:1, page:'dashboard',    type:'modal',
          icon:'<i class="fa-solid fa-hand-wave" style="color:#FF6B6B;"></i>', title:'Welcome to KinderLearn!',
          body:"Let's take a quick tour so you know where everything is. It'll only take a minute!",
          btn:"Let's Go! →" },

        { id:2, page:'dashboard',    type:'spotlight', target:'[data-tour="link-modules"]',
          icon:'<i class="fa-solid fa-book-open" style="color:#FF8E53;"></i>', title:'My Lessons',
          body:'All your learning modules live here. <strong>Tap it to explore!</strong>',
          action:'click', next:3 },

        { id:3, page:'modules',      type:'spotlight', target:'[data-tour="modules-grid"]',
          icon:'<i class="fa-solid fa-star" style="color:#FFD700;"></i>', title:'Your Subjects',
          body:'Each card is a subject — Alphabet, Numbers, Colors and more. Tap any card to dive in!',
          action:'next', next:4 },

        { id:4, page:'modules',      type:'spotlight', target:'[data-tour="link-activities"]',
          icon:'<i class="fa-solid fa-gamepad" style="color:#4ECDC4;"></i>', title:'Activities',
          body:'Fun challenges that earn you <i class="fa-solid fa-star" style="color:#FFD700;"></i> stars. <strong>Tap here to see them!</strong>',
          action:'click', next:5 },

        { id:5, page:'activities',   type:'spotlight', target:'[data-tour="activities-list"]',
          icon:'<i class="fa-solid fa-bullseye" style="color:#FF6B6B;"></i>', title:'Your Activities',
          body:'Complete these to earn stars and unlock badges. The more you do, the better you get!',
          action:'next', next:6 },

        { id:6, page:'activities',   type:'spotlight', target:'[data-tour="link-badges"]',
          icon:'<i class="fa-solid fa-medal" style="color:#FFD700;"></i>', title:'My Badges',
          body:'Here you can see all the badges you can collect. <strong>Tap here!</strong>',
          action:'click', next:7 },

        { id:7, page:'achievements', type:'spotlight', target:'[data-tour="badges-area"]',
          icon:'<i class="fa-solid fa-trophy" style="color:#FFD700;"></i>', title:'Earn Badges!',
          body:'Complete modules with full stars to unlock these. How many can YOU collect?',
          action:'next', next:8 },

        { id:8, page:'achievements', type:'modal',
          icon:'<i class="fa-solid fa-rocket" style="color:#FF6B6B;"></i>', title:"You're all set!",
          body:"Now you know your way around. Jump into a lesson and start having fun!",
          btn:'Start Learning!', done:true },
    ];

    var PAGE_URLS = {
        dashboard:    '/student/dashboard',
        modules:      '/student/modules',
        activities:   '/student/activities',
        achievements: '/student/achievements',
    };

    /* Detect which page we're on */
    var p = window.location.pathname;
    var pageKey = 'dashboard';
    if      (/\/student\/modules$/.test(p))      pageKey = 'modules';
    else if (/\/student\/activities$/.test(p))   pageKey = 'activities';
    else if (/\/student\/achievements$/.test(p)) pageKey = 'achievements';
    else if (/\/student\/dashboard/.test(p))     pageKey = 'dashboard';

    var currentId = parseInt(lsGet(STEP_KEY) || '1', 10);
    var step = STEPS.find(function(s){ return s.id === currentId && s.page === pageKey; });
    if (!step) return;

    /* ── Elements ───────────────────────────────────────────── */
    var elRing, elTip, elSkip, elModal, elSidebarExpanded;

    function removeAll() {
        [elRing, elTip, elSkip, elModal].forEach(function(el){ if (el) el.remove(); });
        elRing = elTip = elSkip = elModal = null;
        collapseSidebar();
        window.removeEventListener('resize', reposition);
        window.removeEventListener('scroll', reposition, true);
    }

    /* ── Sidebar helpers for desktop expand ─────────────────── */
    function expandSidebar() {
        var sb = document.getElementById('main-sidebar');
        if (!sb) return;
        if (window.innerWidth >= 768) {
            // Force expand on desktop by overriding the hover-only width
            sb.style.width = '220px';
            elSidebarExpanded = true;
        } else {
            // Mobile: slide open
            sb.classList.add('sb-open');
            var ov = document.getElementById('sidebar-overlay');
            if (ov) ov.classList.remove('hidden');
        }
    }
    function collapseSidebar() {
        if (!elSidebarExpanded) return;
        var sb = document.getElementById('main-sidebar');
        if (sb) sb.style.width = '';
        elSidebarExpanded = false;
    }

    /* ── Tour flow ──────────────────────────────────────────── */
    function tourSkip() {
        lsDel(STEP_KEY); lsSet(DONE_KEY, '1');
        removeAll();
    }
    function tourDone() {
        lsDel(STEP_KEY); lsSet(DONE_KEY, '1');
        if (elModal) {
            elModal.style.transition = 'opacity 0.35s';
            elModal.style.opacity = '0';
            setTimeout(removeAll, 350);
        } else { removeAll(); }
    }
    function advanceTo(nextId) {
        lsSet(STEP_KEY, nextId);
        var ns = STEPS.find(function(s){ return s.id === nextId; });
        if (ns && ns.page !== pageKey) {
            window.location.href = PAGE_URLS[ns.page];
        } else {
            removeAll();
            showStep(nextId);
        }
    }

    /* ── Dots ───────────────────────────────────────────────── */
    function dots(activeId) {
        return '<div style="display:flex;gap:5px;justify-content:center;margin-top:14px;">' +
            STEPS.map(function(s){
                return '<span style="width:7px;height:7px;border-radius:50%;display:inline-block;background:' +
                    (s.id === activeId ? '#F4654D' : '#d1d5db') + ';"></span>';
            }).join('') + '</div>';
    }

    /* ── Skip button ────────────────────────────────────────── */
    function makeSkip() {
        elSkip = document.createElement('button');
        elSkip.id = 'kl-tour-skip';
        elSkip.innerHTML = '✕&nbsp; Skip Tour';
        elSkip.addEventListener('click', tourSkip);
        document.body.appendChild(elSkip);
    }

    /* ── Modal steps (welcome / done) ───────────────────────── */
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
            '<button id="kl-modal-btn" style="width:100%;padding:14px 0;border-radius:16px;background:linear-gradient(135deg,#F4654D,#ff8c42);color:#fff;font-weight:800;font-size:1rem;border:none;cursor:pointer;">' + s.btn + '</button>' +
            (s.done ? '' : dots(s.id));
        elModal.appendChild(card);
        document.body.appendChild(elModal);
        makeSkip();
        card.querySelector('#kl-modal-btn').addEventListener('click', function(){
            if (s.done) { tourDone(); } else { advanceTo(2); }
        });
    }

    /* ── Spotlight steps ────────────────────────────────────── */
    var _target; // keep ref to remove pulse class on cleanup

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
            top  = r.bottom + pad + gap;
            left = Math.max(12, Math.min(winW - tipW - 12, r.left));
            elTip.classList.add('tip-below');
        } else if (r.top - pad - gap - tipH > 0) {
            top  = r.top - pad - gap - tipH;
            left = Math.max(12, Math.min(winW - tipW - 12, r.left));
            elTip.classList.add('tip-above');
        } else if (r.right + pad + gap + tipW < winW) {
            top  = Math.max(12, r.top - 10);
            left = r.right + pad + gap;
            elTip.classList.add('tip-right');
        } else {
            top  = Math.max(12, r.top - 10);
            left = Math.max(12, r.left - tipW - gap);
        }
        elTip.style.top  = top  + 'px';
        elTip.style.left = left + 'px';
    }

    function showSpotlight(s) {
        removeAll();

        var isSidebarLink = s.target && s.target.indexOf('link-') !== -1;
        if (isSidebarLink) expandSidebar();

        // Small delay so sidebar expansion is rendered before we measure
        setTimeout(function(){
            var el = document.querySelector(s.target);
            if (!el) { advanceTo(s.next); return; }
            _target = el;

            /* Ring */
            elRing = document.createElement('div');
            elRing.id = 'kl-tour-ring';
            document.body.appendChild(elRing);

            /* Tooltip */
            elTip = document.createElement('div');
            elTip.id = 'kl-tour-tip';
            var isClick = s.action === 'click';
            var btnHtml = isClick
                ? '<div style="margin-top:14px;padding:9px 14px;background:rgba(244,101,77,0.1);border-radius:12px;text-align:center;font-size:0.8rem;font-weight:700;color:#F4654D;"><i class=\"fa-solid fa-hand-pointer\"></i> Tap the highlighted area</div>'
                : '<button id="kl-next-btn" style="margin-top:14px;width:100%;padding:10px 0;border-radius:12px;background:linear-gradient(135deg,#F4654D,#ff8c42);color:#fff;font-weight:700;font-size:0.85rem;border:none;cursor:pointer;">Next →</button>';
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
                document.getElementById('kl-next-btn').addEventListener('click', function(){
                    advanceTo(s.next);
                });
            } else {
                /* "click-action" step: listen for click on target to advance */
                function onTargetClick() {
                    el.removeEventListener('click', onTargetClick);
                    lsSet(STEP_KEY, s.next);
                    /* let the link navigate naturally */
                }
                el.addEventListener('click', onTargetClick);
            }

            makeSkip();
        }, isSidebarLink ? 160 : 0);
    }

    /* ── Entry point ────────────────────────────────────────── */
    function showStep(id) {
        var s = STEPS.find(function(s){ return s.id === id; });
        if (!s) { tourSkip(); return; }
        if (s.type === 'modal') showModal(s); else showSpotlight(s);
    }

    window.addEventListener('load', function(){ showStep(currentId); });
}());
</script>


<?php if(session('new_badges')): ?>
<style>
@keyframes bdgSlideIn {
    from { transform: translateX(120%); opacity: 0; }
    to   { transform: translateX(0);    opacity: 1; }
}
@keyframes bdgSlideOut {
    from { transform: translateX(0);    opacity: 1; }
    to   { transform: translateX(120%); opacity: 0; }
}
@keyframes bdgIconPop {
    0%   { transform: scale(0.5); }
    70%  { transform: scale(1.2); }
    100% { transform: scale(1); }
}
@keyframes bdgSparkle {
    0%   { transform: translate(0,0) scale(0); opacity:1; }
    100% { transform: translate(var(--tx), var(--ty)) scale(1); opacity:0; }
}
#badge-toast {
    position: fixed;
    bottom: 90px;
    right: 20px;
    z-index: 9100;
    width: 300px;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.18);
    overflow: hidden;
    animation: bdgSlideIn 0.4s cubic-bezier(0.34,1.56,0.64,1) both;
}
#badge-toast.hiding { animation: bdgSlideOut 0.3s ease-in forwards; }
.bdg-sparkle {
    position: fixed;
    width: 8px; height: 8px;
    border-radius: 50%;
    pointer-events: none;
    z-index: 9099;
    animation: bdgSparkle 0.7s ease-out forwards;
}
</style>

<div id="badge-toast">
    <div id="bdg-header" style="background:linear-gradient(135deg,#1a1e2e,#2e3a52); padding:10px 14px; display:flex; align-items:center; gap:10px;">
        <div id="bdg-icon" style="font-size:2rem; line-height:1; animation:bdgIconPop 0.5s ease both 0.1s;"></div>
        <div style="flex:1; min-width:0;">
            <div style="font-size:0.62rem; font-weight:800; color:#ffd700; letter-spacing:.15em;">BADGE UNLOCKED!</div>
            <div id="bdg-title" style="font-size:0.95rem; font-weight:800; color:#fff; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"></div>
        </div>
        <button onclick="badgeDismiss()" style="color:rgba(255,255,255,0.5); font-size:1.1rem; background:none; border:none; cursor:pointer; line-height:1; padding:2px;">✕</button>
    </div>
    <div style="padding:10px 14px 12px; display:flex; align-items:center; justify-content:space-between; gap:8px;">
        <div id="bdg-desc" style="font-size:0.78rem; color:#6b7280; flex:1; line-height:1.4;"></div>
        <div id="bdg-more" style="font-size:0.7rem; font-weight:700; color:#F4654D; white-space:nowrap; display:none;"></div>
    </div>
    <div id="bdg-bar" style="height:3px; background:linear-gradient(90deg,#F4654D,#ff8c42); width:100%; transform-origin:left; animation:none;"></div>
</div>

<script>
(function () {
    var badges  = <?php echo json_encode(session('new_badges'), 15, 512) ?>;
    var current = 0;
    var toast   = document.getElementById('badge-toast');
    var timer;

    function playBadgeSound() {
        var AudioCtx = window.AudioContext || window.webkitAudioContext;
        if (!AudioCtx) return;
        try {
            var ctx = new AudioCtx();
            var notes = [523.25, 659.25, 783.99, 1046.50];
            var t = ctx.currentTime;
            notes.forEach(function (freq, i) {
                var osc = ctx.createOscillator();
                var gain = ctx.createGain();
                osc.connect(gain); gain.connect(ctx.destination);
                osc.type = 'triangle'; osc.frequency.value = freq;
                var s = t + i * 0.13, d = i === notes.length - 1 ? 0.5 : 0.1;
                gain.gain.setValueAtTime(0, s);
                gain.gain.linearRampToValueAtTime(0.2, s + 0.02);
                gain.gain.exponentialRampToValueAtTime(0.001, s + d + 0.12);
                osc.start(s); osc.stop(s + d + 0.15);
            });
        } catch(e) {}
    }

    function spawnSparkles() {
        var r = toast.getBoundingClientRect();
        var cx = r.left + 36, cy = r.top + 28;
        var colors = ['#ffd700','#ff8c42','#F4654D','#a78bfa','#38bdf8'];
        for (var i = 0; i < 12; i++) {
            var el = document.createElement('div');
            el.className = 'bdg-sparkle';
            var angle = Math.random() * 2 * Math.PI;
            var dist  = 30 + Math.random() * 60;
            el.style.cssText = 'left:' + cx + 'px;top:' + cy + 'px;background:' +
                colors[i % colors.length] + ';' +
                '--tx:' + (Math.cos(angle)*dist) + 'px;' +
                '--ty:' + (Math.sin(angle)*dist) + 'px;' +
                'animation-delay:' + (Math.random()*0.2) + 's;';
            document.body.appendChild(el);
            setTimeout(function(e){ e.remove(); }, 900, el);
        }
    }

    function showBadge(idx) {
        var b = badges[idx];
        document.getElementById('bdg-icon').textContent  = b.icon;
        document.getElementById('bdg-title').textContent = b.title;
        document.getElementById('bdg-desc').textContent  = b.desc;

        var moreEl = document.getElementById('bdg-more');
        var remaining = badges.length - idx - 1;
        if (remaining > 0) {
            moreEl.textContent = '+' + remaining + ' more';
            moreEl.style.display = '';
        } else {
            moreEl.style.display = 'none';
        }

        // Reset slide animation
        toast.classList.remove('hiding');
        toast.style.animation = 'none';
        toast.offsetHeight;
        toast.style.animation = '';

        spawnSparkles();
        playBadgeSound();

        // Auto-advance after 4s
        clearTimeout(timer);
        timer = setTimeout(badgeNext, 4000);
    }

    window.badgeNext = function () {
        current++;
        if (current >= badges.length) { badgeDismiss(); return; }
        showBadge(current);
    };

    window.badgeDismiss = function () {
        clearTimeout(timer);
        toast.classList.add('hiding');
        setTimeout(function(){ toast.remove(); }, 300);
    };

    showBadge(0);
}());
</script>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\kinderlearn\resources\views/layouts/student.blade.php ENDPATH**/ ?>