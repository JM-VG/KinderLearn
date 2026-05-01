@extends('layouts.teacher')
@section('title', 'My Profile')

@section('teacher-content')
<h1 class="font-fredoka text-4xl text-gray-800 mb-2">My Profile</h1>
<p class="text-gray-400 mb-6">Manage your info and view your class stats.</p>

@if(session('success'))
<div class="mb-5 px-5 py-3 bg-green-50 border border-green-200 text-green-700 rounded-2xl font-semibold flex items-center gap-2">
    <i class="ri-checkbox-circle-line text-lg"></i> {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 items-start">

    {{-- LEFT COLUMN --}}
    <div class="space-y-5">

        {{-- Avatar + name card --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm flex items-center gap-5">
            @if(str_starts_with($teacher->avatar ?? '', 'avatars/'))
            <img src="{{ asset('storage/' . $teacher->avatar) }}"
                 class="w-16 h-16 rounded-full object-cover ring-4 ring-sky-200 flex-shrink-0">
            @else
            <div class="w-16 h-16 rounded-full flex items-center justify-center font-bold text-white text-2xl flex-shrink-0"
                 style="background: linear-gradient(135deg, #0ea5e9, #6366f1);">
                {{ strtoupper(substr($teacher->name, 0, 1)) }}
            </div>
            @endif
            <div class="flex-1 min-w-0">
                <div class="font-fredoka text-2xl text-gray-800 truncate">{{ $teacher->name }}</div>
                <div class="text-xs text-gray-400 mt-0.5 truncate">{{ $teacher->email }}</div>
                <div class="mt-2">
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-sky-100 text-sky-600">Teacher</span>
                </div>
            </div>
            <a href="{{ route('teacher.avatar') }}"
               class="flex-shrink-0 px-3 py-2 rounded-2xl border-2 border-gray-200 font-bold text-xs text-gray-500 hover:bg-gray-50 transition">
                <i class="ri-image-edit-line"></i> Photo
            </a>
        </div>

        {{-- Stats row --}}
        <div class="grid grid-cols-3 gap-3">
            <div class="bg-white rounded-3xl p-4 text-center shadow-sm">
                <div class="font-fredoka text-2xl text-sky-500">{{ $sections->count() }}</div>
                <div class="text-xs font-bold text-gray-400 mt-0.5">Classes</div>
            </div>
            <div class="bg-white rounded-3xl p-4 text-center shadow-sm">
                <div class="font-fredoka text-2xl text-green-500">{{ $totalStudents }}</div>
                <div class="text-xs font-bold text-gray-400 mt-0.5">Students</div>
            </div>
            <div class="bg-white rounded-3xl p-4 text-center shadow-sm">
                <div class="font-fredoka text-2xl text-purple-500">{{ $totalModules }}</div>
                <div class="text-xs font-bold text-gray-400 mt-0.5">Modules</div>
            </div>
        </div>

        {{-- Edit form --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <h2 class="font-fredoka text-xl text-gray-800 mb-4">Edit Profile</h2>
            <form method="POST" action="{{ route('teacher.profile.update') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block font-bold text-gray-700 mb-1 text-sm">Display Name</label>
                    <input type="text" name="name" value="{{ old('name', $teacher->name) }}" required
                           class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-sky-400 focus:outline-none font-semibold text-sm"
                           placeholder="Your name">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1 text-sm">
                        About Me <span class="font-normal text-gray-400">(optional, max 300 chars)</span>
                    </label>
                    <textarea name="bio" rows="3" maxlength="300"
                              class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-sky-400 focus:outline-none resize-none text-sm"
                              placeholder="Tell students something about yourself...">{{ old('bio', $teacher->bio) }}</textarea>
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <p class="font-bold text-gray-700 mb-3 text-sm">Change Password <span class="font-normal text-gray-400">(leave blank to keep current)</span></p>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Current Password</label>
                            <input type="password" name="current_password"
                                   class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-sky-400 focus:outline-none text-sm"
                                   placeholder="Enter current password">
                            @error('current_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">New Password</label>
                            <input type="password" name="new_password"
                                   class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-sky-400 focus:outline-none text-sm"
                                   placeholder="At least 6 characters">
                            @error('new_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation"
                                   class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-sky-400 focus:outline-none text-sm"
                                   placeholder="Repeat new password">
                        </div>
                    </div>
                </div>

                <button type="submit"
                        class="w-full py-3 rounded-2xl font-bold text-white transition text-sm"
                        style="background: linear-gradient(135deg, #0ea5e9, #6366f1);">
                    <i class="ri-save-line"></i> Save Changes
                </button>
            </form>
        </div>
    </div>

    {{-- RIGHT COLUMN --}}
    <div class="space-y-5">

        {{-- Classes taught --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <h2 class="font-fredoka text-xl text-gray-800 mb-4 flex items-center gap-2">
                <i class="ri-school-line text-sky-400"></i> My Classes
            </h2>
            @if($sections->isEmpty())
            <div class="text-center py-6">
                <div class="mb-2" style="font-size:2.5rem; color:#d1d5db;"><i class="fa-solid fa-school"></i></div>
                <p class="text-gray-400 text-sm font-semibold">No classes yet.</p>
                <a href="{{ route('teacher.classes') }}"
                   class="inline-block mt-3 px-5 py-2 rounded-2xl font-bold text-white text-sm"
                   style="background: linear-gradient(135deg, #0ea5e9, #6366f1);">
                    Create a Class
                </a>
            </div>
            @else
            <div class="space-y-3">
                @foreach($sections as $section)
                <div class="flex items-center justify-between p-3 rounded-2xl bg-sky-50">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(14,165,233,0.1); font-size:1.1rem; color:#0ea5e9;"><i class="fa-solid fa-school"></i></div>
                        <div>
                            <div class="font-bold text-gray-800 text-sm">{{ $section->name }}</div>
                            <div class="text-xs text-gray-400">{{ $section->students_count }} student{{ $section->students_count != 1 ? 's' : '' }} · Code: <span class="font-bold text-sky-600">{{ $section->join_code }}</span></div>
                        </div>
                    </div>
                    <a href="{{ route('teacher.classes.show', $section->id) }}"
                       class="text-xs font-bold text-sky-500 hover:text-sky-700 transition">View →</a>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Bio display --}}
        @if($teacher->bio)
        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <h2 class="font-fredoka text-xl text-gray-800 mb-3 flex items-center gap-2">
                <i class="ri-user-heart-line text-sky-400"></i> About Me
            </h2>
            <p class="text-gray-600 text-sm leading-relaxed">{{ $teacher->bio }}</p>
        </div>
        @endif

    </div>
</div>
@endsection
