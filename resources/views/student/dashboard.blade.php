@extends('layouts.student')

@section('title', 'My Dashboard')

@section('student-content')

{{-- Welcome header --}}
<div class="mb-8">
    <h1 class="font-fredoka text-4xl md:text-5xl text-gray-800">
        Hello, {{ $student->name }}! <i class="fa-solid fa-hand-wave" style="color:#FF6B6B;"></i>
    </h1>
    <p class="text-gray-500 text-lg mt-1">Ready to learn something new today?</p>
</div>

{{-- Stats row --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    @php
        $statCards = [
            ['fa' => 'fa-solid fa-star',      'value' => $totalStars,                    'label' => 'Total Stars', 'color' => '#FFD700', 'bg' => '#fffdf0'],
            ['fa' => 'fa-solid fa-book-open', 'value' => $completedCount,                'label' => 'Completed',   'color' => '#4ECDC4', 'bg' => '#f0fffe'],
            ['fa' => 'fa-solid fa-trophy',    'value' => $recentAchievements->count(),   'label' => 'Badges',      'color' => '#FF6B6B', 'bg' => '#fff0f0'],
            ['fa' => 'fa-solid fa-gamepad',   'value' => count($modules),                'label' => 'Lessons',     'color' => '#BB8FCE', 'bg' => '#f8f0ff'],
        ];
    @endphp

    @foreach($statCards as $s)
    <div class="rounded-3xl p-5 text-center shadow-sm card-hover"
         style="background: {{ $s['bg'] }}; border: 2px solid {{ $s['color'] }}30;">
        <div class="mb-2" style="font-size:2.2rem; color:{{ $s['color'] }};"><i class="{{ $s['fa'] }}"></i></div>
        <div class="font-fredoka text-3xl" style="color: {{ $s['color'] }}">{{ $s['value'] }}</div>
        <div class="text-gray-500 font-semibold text-sm">{{ $s['label'] }}</div>
    </div>
    @endforeach
</div>

{{-- My Learning Modules --}}
<div class="mb-10">
    <div class="flex items-center justify-between mb-5">
        <h2 class="font-fredoka text-3xl text-gray-800">My Lessons <i class="fa-solid fa-book-open" style="color:#FF6B6B;"></i></h2>
        <a href="{{ route('student.modules') }}" class="text-orange-500 font-bold hover:underline">See all →</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
        @foreach($modules->take(3) as $module)
        @php
            $progress = $progresses[$module->id] ?? null;
            $percent  = $progress && $progress->stars_earned > 0 ? min(100, ($progress->stars_earned / ($module->activities()->count() * 3)) * 100) : 0;
        @endphp
        <a href="{{ route('student.modules.show', $module->id) }}"
           class="block rounded-3xl p-6 shadow-md card-hover text-white relative overflow-hidden"
           style="background: linear-gradient(135deg, {{ $module->color }}, {{ $module->color }}cc);">

            @if($progress && $progress->completed)
            <div class="absolute top-3 right-3 bg-white bg-opacity-30 rounded-full px-3 py-1 text-sm font-bold">
                <i class="fa-solid fa-circle-check"></i> Done!
            </div>
            @endif

            <div class="text-5xl mb-3">{{ $module->icon }}</div>
            <div class="font-fredoka text-2xl mb-1">{{ $module->title }}</div>
            <div class="opacity-80 text-sm mb-4">{{ $module->description }}</div>

            <div class="bg-white bg-opacity-30 rounded-full h-3">
                <div class="bg-white rounded-full h-3 transition-all" style="width: {{ $percent }}%"></div>
            </div>
            <div class="text-right text-xs opacity-80 mt-1 font-bold">
                {{ $progress ? $progress->stars_earned : 0 }} <i class="fa-solid fa-star"></i>
            </div>
        </a>
        @endforeach
    </div>
</div>

{{-- Bottom row: recent badges + announcements --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Recent achievements --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm">
        <h3 class="font-fredoka text-2xl text-gray-800 mb-4"><i class="fa-solid fa-trophy" style="color:#FFD700;"></i> Recent Badges</h3>
        @if($recentAchievements->isEmpty())
        <div class="text-center py-8">
            <div class="mb-3" style="font-size:3.5rem; color:#86efac;"><i class="fa-solid fa-seedling"></i></div>
            <p class="text-gray-500 font-semibold">Complete a lesson to earn your first badge!</p>
        </div>
        @else
        <div class="space-y-3">
            @foreach($recentAchievements as $a)
            <div class="flex items-center gap-4 p-3 rounded-2xl bg-yellow-50">
                <div class="text-4xl">{{ $a->icon }}</div>
                <div>
                    <div class="font-bold text-gray-800">{{ $a->title }}</div>
                    <div class="text-sm text-gray-500">{{ $a->description }}</div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Announcements --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm">
        <h3 class="font-fredoka text-2xl text-gray-800 mb-4"><i class="fa-solid fa-bullhorn" style="color:#FF6B6B;"></i> From Your Teacher</h3>
        @if($announcements->isEmpty())
        <div class="text-center py-8">
            <div class="mb-3" style="font-size:3.5rem; color:#d1d5db;"><i class="fa-solid fa-envelope-open"></i></div>
            <p class="text-gray-500 font-semibold">No announcements yet.</p>
        </div>
        @else
        <div class="space-y-3">
            @foreach($announcements as $ann)
            <div class="p-4 rounded-2xl {{ $ann->pinned ? 'bg-orange-50 border-2 border-orange-200' : 'bg-gray-50' }}">
                @if($ann->pinned)
                <span class="text-xs bg-orange-500 text-white px-2 py-0.5 rounded-full font-bold mb-2 inline-block">
                    <i class="fa-solid fa-thumbtack"></i> Pinned
                </span>
                @endif
                <div class="font-bold text-gray-800">{{ $ann->title }}</div>
                <p class="text-sm text-gray-500 mt-1">{{ Str::limit($ann->body, 80) }}</p>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

<div class="mt-4 text-center">
    <button onclick="window.__klTourReset && window.__klTourReset()"
            class="text-xs text-gray-400 hover:text-orange-500 transition font-semibold">
        <i class="fa-solid fa-route"></i> Restart tour
    </button>
</div>
@endsection
