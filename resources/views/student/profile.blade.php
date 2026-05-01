@extends('layouts.student')
@section('title', 'My Profile')

@section('student-content')
<h1 class="font-fredoka text-4xl text-gray-800 mb-2">My Profile</h1>
<p class="text-gray-400 mb-6">Your info, stats, and achievements.</p>

@if(session('success'))
<div class="mb-5 px-5 py-3 bg-green-50 border border-green-200 text-green-700 rounded-2xl font-semibold flex items-center gap-2">
    <i class="ri-checkbox-circle-line text-lg"></i> {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 items-start">

    {{-- ══════════════════════ LEFT COLUMN ══════════════════════ --}}
    <div class="space-y-5">

        {{-- Avatar + name card --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm flex items-center gap-5">
            @php
                $isPhoto = str_starts_with($student->avatar ?? '', 'avatars/');
                $initial = strtoupper(substr($student->name, 0, 1));
            @endphp
            <div class="flex-shrink-0 relative group" id="avatar-trigger" style="cursor:pointer;"
                 onclick="document.getElementById('photo-upload-section').scrollIntoView({behavior:'smooth',block:'center'})">
                @if($isPhoto)
                    <img src="{{ asset('storage/' . $student->avatar) }}" alt="Profile photo"
                         class="w-20 h-20 rounded-2xl object-cover">
                @else
                    <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-3xl font-fredoka text-white font-bold flex-shrink-0"
                         style="background: linear-gradient(135deg, #F4654D, #ff8c42);">
                        {{ $initial }}
                    </div>
                @endif
                <div class="absolute inset-0 rounded-2xl bg-black bg-opacity-30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                    <i class="ri-camera-line text-white text-xl"></i>
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-fredoka text-2xl text-gray-800 truncate">{{ $student->name }}</div>
                <div class="text-xs text-gray-400 mt-0.5 truncate">{{ $student->email }}</div>
                <div class="flex flex-wrap items-center gap-2 mt-2">
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold"
                          style="background:rgba(244,101,77,0.1);color:#F4654D;">Student</span>
                    @if($student->section)
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-sky-100 text-sky-600">
                        {{ $student->section->name }}
                    </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Photo upload card --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm" id="photo-upload-section">
            <h2 class="font-fredoka text-xl text-gray-800 mb-3 flex items-center gap-2">
                <i class="ri-camera-line text-orange-400"></i> Profile Photo
            </h2>
            <form method="POST" action="{{ route('student.avatar.upload') }}" enctype="multipart/form-data" class="flex items-center gap-4">
                @csrf
                <label class="flex-1 flex items-center gap-3 px-4 py-3 rounded-2xl border-2 border-dashed border-gray-200 cursor-pointer hover:border-orange-400 transition group">
                    <i class="ri-image-add-line text-2xl text-gray-300 group-hover:text-orange-400 transition"></i>
                    <span class="text-sm text-gray-400 group-hover:text-orange-400 transition font-semibold" id="photo-label">Choose a photo…</span>
                    <input type="file" name="photo" accept="image/*" class="hidden" onchange="document.getElementById('photo-label').textContent = this.files[0]?.name ?? 'Choose a photo…'">
                </label>
                <button type="submit"
                        class="flex-shrink-0 px-5 py-3 rounded-2xl font-bold text-white text-sm transition"
                        style="background: linear-gradient(135deg, #F4654D, #ff8c42);">
                    Upload
                </button>
            </form>
            <p class="text-xs text-gray-400 mt-2">JPG, PNG, GIF or WebP · max 3 MB</p>
        </div>

        {{-- Stats row --}}
        <div class="grid grid-cols-3 gap-3">
            @php
                $stars     = $student->getTotalStars();
                $completed = $student->getCompletedModules();
                $badges    = $achievements->count();
            @endphp
            <div class="bg-white rounded-3xl p-4 text-center shadow-sm">
                <div class="font-fredoka text-2xl text-yellow-500">{{ $stars }}</div>
                <div class="text-xs font-bold text-gray-400 mt-0.5">Stars</div>
            </div>
            <div class="bg-white rounded-3xl p-4 text-center shadow-sm">
                <div class="font-fredoka text-2xl text-green-500">{{ $completed }}</div>
                <div class="text-xs font-bold text-gray-400 mt-0.5">Completed</div>
            </div>
            <div class="bg-white rounded-3xl p-4 text-center shadow-sm">
                <div class="font-fredoka text-2xl text-purple-500">{{ $badges }}</div>
                <div class="text-xs font-bold text-gray-400 mt-0.5">Badges</div>
            </div>
        </div>

        {{-- Edit form --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <h2 class="font-fredoka text-xl text-gray-800 mb-4">Edit Profile</h2>
            <form method="POST" action="{{ route('student.profile.update') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block font-bold text-gray-700 mb-1 text-sm">Display Name</label>
                    <input type="text" name="name" value="{{ old('name', $student->name) }}" required
                           class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-orange-400 focus:outline-none font-semibold text-sm"
                           placeholder="Your name">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1 text-sm">
                        About Me <span class="font-normal text-gray-400">(optional, max 300 chars)</span>
                    </label>
                    <textarea name="bio" rows="3" maxlength="300"
                              class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-orange-400 focus:outline-none resize-none text-sm"
                              placeholder="Tell us something about yourself...">{{ old('bio', $student->bio) }}</textarea>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1 text-sm">
                        Change PIN <span class="font-normal text-gray-400">(4 digits, optional)</span>
                    </label>
                    <input type="password" name="pin" maxlength="4" inputmode="numeric" pattern="\d{4}"
                           class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-orange-400 focus:outline-none font-semibold tracking-widest text-sm"
                           placeholder="Leave blank to keep current">
                    @error('pin')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <button type="submit"
                        class="w-full py-3 rounded-2xl font-bold text-white transition text-sm"
                        style="background: linear-gradient(135deg, #F4654D, #ff8c42);">
                    <i class="ri-save-line"></i> Save Changes
                </button>
            </form>
        </div>

        {{-- Enrolled class --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <h2 class="font-fredoka text-xl text-gray-800 mb-4 flex items-center gap-2">
                <i class="ri-group-line text-sky-400"></i> My Class
            </h2>
            @if($student->section)
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0"
                     style="background:rgba(14,165,233,0.1); font-size:1.5rem; color:#0ea5e9;">
                    <i class="fa-solid fa-school"></i>
                </div>
                <div>
                    <div class="font-bold text-gray-800">{{ $student->section->name }}</div>
                    @if($student->section->teacher)
                    <div class="text-xs text-gray-500">Teacher: {{ $student->section->teacher->name }}</div>
                    @endif
                </div>
            </div>
            @else
            <div class="text-center py-4">
                <div class="mb-2" style="font-size:2rem; color:#d1d5db;"><i class="fa-solid fa-school"></i></div>
                <p class="text-gray-400 font-semibold text-sm mb-3">No class yet.</p>
                <a href="{{ route('student.classes') }}"
                   class="inline-block px-5 py-2 rounded-2xl font-bold text-white text-sm"
                   style="background: linear-gradient(135deg, #0ea5e9, #6366f1);">
                    Join a Class
                </a>
            </div>
            @endif
        </div>

    </div>

    {{-- ══════════════════════ RIGHT COLUMN ══════════════════════ --}}
    <div class="space-y-5">

        {{-- Certificates of completion --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <h2 class="font-fredoka text-xl text-gray-800 mb-1 flex items-center gap-2">
                <i class="ri-award-fill text-yellow-400"></i> Subject Certificates
            </h2>
            <p class="text-xs text-gray-400 mb-4">Awarded for completing a full subject module.</p>

            @if($completedProgress->isEmpty())
            <div class="text-center py-10">
                <div class="mb-3" style="font-size:3rem; color:#d1d5db;"><i class="fa-solid fa-scroll"></i></div>
                <p class="font-fredoka text-lg text-gray-500">No certificates yet.</p>
                <p class="text-xs text-gray-400 mt-1">Complete a module to earn your first!</p>
                <a href="{{ route('student.modules') }}"
                   class="inline-block mt-4 px-5 py-2 rounded-2xl font-bold text-white text-sm"
                   style="background: linear-gradient(135deg, #F4654D, #ff8c42);">
                    Browse Lessons →
                </a>
            </div>
            @else
            <div class="space-y-3">
                @foreach($completedProgress as $prog)
                @if($prog->module)
                @php
                    $subjectColors = [
                        'Alphabet' => ['#FF6B6B','#FF8E53','fa-font'],
                        'Numbers'  => ['#4ECDC4','#45B7D1','fa-hashtag'],
                        'Colors'   => ['#BB8FCE','#9B59B6','fa-palette'],
                        'Shapes'   => ['#F7DC6F','#F0B429','fa-shapes'],
                        'Words'    => ['#52BE80','#27AE60','fa-book'],
                    ];
                    $name   = $prog->module->title ?? $prog->module->name ?? 'Module';
                    $match  = null;
                    foreach ($subjectColors as $subj => $cfg) {
                        if (stripos($name, $subj) !== false) { $match = $cfg; break; }
                    }
                    $c1     = $match[0] ?? '#F4654D';
                    $c2     = $match[1] ?? '#ff8c42';
                    $icon   = $match[2] ?? 'fa-book-open';
                    $stars  = $prog->stars_earned ?? 0;
                @endphp
                <div class="relative rounded-2xl p-4 overflow-hidden"
                     style="background: linear-gradient(135deg, {{ $c1 }}18, {{ $c2 }}0d); border: 1.5px solid {{ $c1 }}40;">
                    {{-- Decorative corner ribbon --}}
                    <div class="absolute top-0 right-0 w-16 h-16 overflow-hidden">
                        <div class="absolute top-2 right-[-20px] w-20 text-center text-white text-xs font-bold py-1 rotate-45"
                             style="background:{{ $c1 }};">Done</div>
                    </div>
                    <div class="flex items-center gap-3 pr-10">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background:{{ $c1 }}20; font-size:1.2rem; color:{{ $c1 }};">
                            <i class="fa-solid {{ $icon }}"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="font-bold text-gray-800 text-sm truncate">{{ $name }}</div>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs font-bold" style="color:{{ $c1 }};">
                                    ★ {{ $stars }} stars
                                </span>
                                <span class="text-xs text-gray-400">
                                    · {{ $prog->updated_at->format('M d, Y') }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <a href="{{ route('student.certificate', $prog->module_id) }}"
                               class="flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-bold transition"
                               style="background:{{ $c1 }}15; color:{{ $c1 }};"
                               title="View Certificate">
                                <i class="ri-award-line"></i> Cert
                            </a>
                            <i class="ri-checkbox-circle-fill text-lg" style="color:{{ $c1 }};"></i>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
            @endif
        </div>

        {{-- Recent badges --}}
        @if($achievements->count())
        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <h2 class="font-fredoka text-xl text-gray-800 mb-4 flex items-center gap-2">
                <i class="ri-medal-line text-purple-400"></i> Recent Badges
            </h2>
            <div class="flex flex-wrap gap-2">
                @foreach($achievements->take(8) as $ach)
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-2xl bg-purple-50">
                    <span class="text-lg">{{ $ach->icon ?? '🏅' }}</span>
                    <span class="font-bold text-purple-700 text-xs">{{ $ach->title }}</span>
                </div>
                @endforeach
            </div>
            @if($achievements->count() > 8)
            <a href="{{ route('student.achievements') }}"
               class="inline-block mt-3 text-xs font-bold text-purple-500 hover:text-purple-700 transition">
                View all {{ $achievements->count() }} badges →
            </a>
            @endif
        </div>
        @endif

        {{-- Bio card (read-only display) --}}
        @if($student->bio)
        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <h2 class="font-fredoka text-xl text-gray-800 mb-3 flex items-center gap-2">
                <i class="ri-user-heart-line text-orange-400"></i> About Me
            </h2>
            <p class="text-gray-600 text-sm leading-relaxed">{{ $student->bio }}</p>
        </div>
        @endif

    </div>
</div>
@endsection
