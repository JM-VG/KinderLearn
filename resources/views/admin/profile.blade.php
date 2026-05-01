@extends('layouts.admin')
@section('title', 'My Profile')

@section('admin-content')
<h1 class="font-fredoka text-4xl text-gray-800 mb-2">My Profile</h1>
<p class="text-gray-400 mb-6">Manage your admin account.</p>

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
            @if(str_starts_with($admin->avatar ?? '', 'avatars/'))
            <img src="{{ asset('storage/' . $admin->avatar) }}"
                 class="w-16 h-16 rounded-full object-cover ring-4 ring-indigo-200 flex-shrink-0">
            @else
            <div class="w-16 h-16 rounded-full flex items-center justify-center font-bold text-white text-2xl flex-shrink-0"
                 style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                {{ strtoupper(substr($admin->name, 0, 1)) }}
            </div>
            @endif
            <div class="flex-1 min-w-0">
                <div class="font-fredoka text-2xl text-gray-800 truncate">{{ $admin->name }}</div>
                <div class="text-xs text-gray-400 mt-0.5 truncate">{{ $admin->email }}</div>
                <div class="mt-2">
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold"
                          style="background:rgba(99,102,241,0.1);color:#6366f1;">Administrator</span>
                </div>
            </div>
        </div>

        {{-- System stats --}}
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-white rounded-3xl p-4 text-center shadow-sm">
                <div class="font-fredoka text-2xl text-indigo-500">{{ $stats['total_students'] }}</div>
                <div class="text-xs font-bold text-gray-400 mt-0.5">Students</div>
            </div>
            <div class="bg-white rounded-3xl p-4 text-center shadow-sm">
                <div class="font-fredoka text-2xl text-sky-500">{{ $stats['total_teachers'] }}</div>
                <div class="text-xs font-bold text-gray-400 mt-0.5">Teachers</div>
            </div>
            <div class="bg-white rounded-3xl p-4 text-center shadow-sm">
                <div class="font-fredoka text-2xl text-green-500">{{ $stats['total_classes'] }}</div>
                <div class="text-xs font-bold text-gray-400 mt-0.5">Classes</div>
            </div>
            <div class="bg-white rounded-3xl p-4 text-center shadow-sm">
                <div class="font-fredoka text-2xl text-purple-500">{{ $stats['total_modules'] }}</div>
                <div class="text-xs font-bold text-gray-400 mt-0.5">Modules</div>
            </div>
        </div>

        {{-- Edit form --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <h2 class="font-fredoka text-xl text-gray-800 mb-4">Edit Profile</h2>
            <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block font-bold text-gray-700 mb-1 text-sm">Display Name</label>
                    <input type="text" name="name" value="{{ old('name', $admin->name) }}" required
                           class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-indigo-400 focus:outline-none font-semibold text-sm"
                           placeholder="Your name">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1 text-sm">
                        About <span class="font-normal text-gray-400">(optional, max 300 chars)</span>
                    </label>
                    <textarea name="bio" rows="3" maxlength="300"
                              class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-indigo-400 focus:outline-none resize-none text-sm"
                              placeholder="Something about you...">{{ old('bio', $admin->bio) }}</textarea>
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <p class="font-bold text-gray-700 mb-3 text-sm">Change Password <span class="font-normal text-gray-400">(leave blank to keep current)</span></p>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Current Password</label>
                            <input type="password" name="current_password"
                                   class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-indigo-400 focus:outline-none text-sm"
                                   placeholder="Enter current password">
                            @error('current_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">New Password</label>
                            <input type="password" name="new_password"
                                   class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-indigo-400 focus:outline-none text-sm"
                                   placeholder="At least 6 characters">
                            @error('new_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation"
                                   class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-indigo-400 focus:outline-none text-sm"
                                   placeholder="Repeat new password">
                        </div>
                    </div>
                </div>

                <button type="submit"
                        class="w-full py-3 rounded-2xl font-bold text-white transition text-sm"
                        style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                    <i class="ri-save-line"></i> Save Changes
                </button>
            </form>
        </div>
    </div>

    {{-- RIGHT COLUMN --}}
    <div class="space-y-5">

        {{-- Bio display --}}
        @if($admin->bio)
        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <h2 class="font-fredoka text-xl text-gray-800 mb-3 flex items-center gap-2">
                <i class="ri-user-heart-line text-indigo-400"></i> About Me
            </h2>
            <p class="text-gray-600 text-sm leading-relaxed">{{ $admin->bio }}</p>
        </div>
        @endif

        {{-- Quick links --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <h2 class="font-fredoka text-xl text-gray-800 mb-4 flex items-center gap-2">
                <i class="ri-link text-indigo-400"></i> Quick Links
            </h2>
            <div class="space-y-2">
                <a href="{{ route('admin.users') }}"
                   class="flex items-center gap-3 p-3 rounded-2xl hover:bg-indigo-50 transition">
                    <i class="ri-group-line text-indigo-400 text-lg"></i>
                    <span class="font-semibold text-gray-700 text-sm">Manage Users</span>
                    <i class="ri-arrow-right-s-line text-gray-400 ml-auto"></i>
                </a>
                <a href="{{ route('admin.teachers') }}"
                   class="flex items-center gap-3 p-3 rounded-2xl hover:bg-indigo-50 transition">
                    <i class="ri-user-star-line text-indigo-400 text-lg"></i>
                    <span class="font-semibold text-gray-700 text-sm">Manage Teachers</span>
                    <i class="ri-arrow-right-s-line text-gray-400 ml-auto"></i>
                </a>
                <a href="{{ route('admin.reports') }}"
                   class="flex items-center gap-3 p-3 rounded-2xl hover:bg-indigo-50 transition">
                    <i class="ri-file-chart-line text-indigo-400 text-lg"></i>
                    <span class="font-semibold text-gray-700 text-sm">Reports</span>
                    <i class="ri-arrow-right-s-line text-gray-400 ml-auto"></i>
                </a>
                <a href="{{ route('admin.settings') }}"
                   class="flex items-center gap-3 p-3 rounded-2xl hover:bg-indigo-50 transition">
                    <i class="ri-settings-3-line text-indigo-400 text-lg"></i>
                    <span class="font-semibold text-gray-700 text-sm">System Settings</span>
                    <i class="ri-arrow-right-s-line text-gray-400 ml-auto"></i>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
