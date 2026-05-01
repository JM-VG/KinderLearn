@extends('layouts.student')
@section('title', 'My Progress')

@section('student-content')

<div class="mb-8">
    <h1 class="font-fredoka text-4xl text-gray-800">My Progress <i class="fa-solid fa-chart-column" style="color:#FF6B6B;"></i></h1>
    <p class="text-gray-500 text-lg mt-1">{{ $completedCount }} of {{ $modules->count() }} lessons completed</p>
</div>

{{-- Overall progress bar --}}
@php $overallPercent = $modules->count() > 0 ? round(($completedCount / $modules->count()) * 100) : 0; @endphp
<div class="bg-white rounded-3xl p-6 shadow-sm mb-8">
    <div class="flex items-center justify-between mb-3">
        <span class="font-bold text-gray-700 text-lg">Overall Progress</span>
        <span class="font-fredoka text-2xl" style="color: #FF6B6B">{{ $overallPercent }}%</span>
    </div>
    <div class="bg-gray-100 rounded-full h-6">
        <div class="h-6 rounded-full transition-all progress-bar-animated flex items-center justify-end pr-3"
             style="width: {{ $overallPercent }}%; background: linear-gradient(90deg, #FF6B6B, #FFD700);">
            <span class="text-white text-xs font-bold">{{ $overallPercent }}%</span>
        </div>
    </div>
    <div class="flex justify-between text-sm text-gray-400 mt-2">
        <span>0%</span>
        <span><i class="fa-solid fa-star" style="color:#FFD700;"></i> {{ $totalStars }} total stars</span>
        <span>100%</span>
    </div>
</div>

{{-- Per-module progress --}}
<div class="space-y-4">
    @foreach($modules as $module)
    @php
        $p = $progresses[$module->id] ?? null;
        $stars = $p ? $p->stars_earned : 0;
        $done  = $p && $p->completed;
    @endphp
    <div class="bg-white rounded-3xl p-5 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="text-4xl">{{ $module->icon }}</div>
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-2">
                    <span class="font-bold text-gray-800 text-lg">{{ $module->title }}</span>
                    @if($done)
                    <span class="bg-green-100 text-green-600 text-xs font-bold px-2 py-0.5 rounded-full">
                        <i class="fa-solid fa-circle-check"></i> Complete
                    </span>
                    @endif
                </div>
                <div class="flex items-center gap-1 mb-2">
                    @for($i = 1; $i <= 5; $i++)
                    <i class="{{ $i <= $stars ? 'fa-solid' : 'fa-regular' }} fa-star" style="font-size:1.1rem; color:#FFD700;"></i>
                    @endfor
                    <span class="ml-2 text-sm text-gray-500 font-bold">{{ $stars }} stars</span>
                </div>
                <div class="bg-gray-100 rounded-full h-3">
                    @php $pct = min(100, $stars * 20); @endphp
                    <div class="h-3 rounded-full" style="width: {{ $pct }}%; background: {{ $module->color }};"></div>
                </div>
            </div>
            <a href="{{ route('student.modules.show', $module->id) }}"
               class="px-4 py-2 rounded-full font-bold text-white text-sm"
               style="background: {{ $module->color }}">
                @if($done)
                    <i class="fa-solid fa-rotate"></i> Review
                @else
                    <i class="fa-solid fa-play"></i> Go
                @endif
            </a>
        </div>
    </div>
    @endforeach
</div>
@endsection
