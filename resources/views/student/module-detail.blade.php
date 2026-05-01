@extends('layouts.student')
@section('title', $module->title)

@section('student-content')

{{-- Module header --}}
<div class="rounded-3xl text-white p-6 mb-6 flex items-center gap-5"
     style="background: linear-gradient(135deg, {{ $module->color }}, {{ $module->color }}bb);">
    <div class="text-6xl flex-shrink-0">{{ $module->icon }}</div>
    <div class="flex-1 min-w-0">
        <div class="font-fredoka text-3xl leading-tight">{{ $module->title }}</div>
        <div class="opacity-80 text-sm mt-1">{{ $module->description }}</div>
        <div class="flex items-center gap-1 mt-2">
            @for($i = 1; $i <= 5; $i++)
                <i class="{{ $i <= $progress->stars_earned ? 'fa-solid' : 'fa-regular' }} fa-star" style="font-size:1rem; color:#FFD700;"></i>
            @endfor
        </div>
        @if(isset($progressPct) && $progressPct > 0)
        <div class="mt-2 flex items-center gap-2">
            <div class="flex-1 bg-white bg-opacity-30 rounded-full h-2">
                <div class="h-2 rounded-full bg-white bg-opacity-90 transition-all"
                     style="width: {{ $progressPct }}%"></div>
            </div>
            <span class="text-xs font-bold opacity-80">{{ $progressPct }}% done</span>
        </div>
        @endif
    </div>
    <a href="{{ route('student.modules') }}"
       class="flex-shrink-0 flex items-center gap-1 bg-white bg-opacity-20 hover:bg-opacity-30
              transition rounded-xl px-4 py-2 text-sm font-bold">
        <i class="ri-arrow-left-line"></i> Back
    </a>
</div>

{{-- Flash messages --}}
@if(session('success'))
<div class="mb-5 px-5 py-3 bg-green-50 border border-green-200 text-green-700 rounded-2xl font-semibold flex items-center gap-2">
    <i class="ri-checkbox-circle-line text-lg"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-5 px-5 py-3 bg-red-50 border border-red-200 text-red-600 rounded-2xl font-semibold flex items-center gap-2">
    <i class="ri-error-warning-line text-lg"></i> {{ session('error') }}
</div>
@endif

{{-- Levels --}}
@if($levelNumbers->isEmpty())
<div class="bg-white rounded-3xl p-12 text-center shadow-sm">
    <div class="mb-3" style="font-size:4rem; color:#d1d5db;"><i class="fa-solid fa-triangle-exclamation"></i></div>
    <p class="font-fredoka text-2xl text-gray-500">No activities yet — check back soon!</p>
</div>
@else

@php
    $levelFaIcons = ['fa-seedling','fa-rocket','fa-trophy','fa-star','fa-gem','fa-fire','fa-rainbow','fa-paw'];
    $levelColors  = ['#52BE80','#FF6B6B','#FFD700','#FF8E53','#BB8FCE','#FF4500','#45B7D1','#4ECDC4'];
@endphp

<div class="flex flex-col gap-5">
@foreach($levelNumbers as $levelNum)
@php
    $levelActivities = $activitiesByLevel[$levelNum];
    $isUnlocked      = $unlockedLevels[$levelNum] ?? false;
    $lvlProgress     = $levelProgresses->get($levelNum);
    $doneCount       = $levelActivities->filter(fn($a) => isset($submissions[$a->id]))->count();
    $totalCount      = $levelActivities->count();
    $allDone         = $doneCount >= $totalCount && $totalCount > 0;
    // Use actual submission count for completion badge — LevelProgress can be stale if activities were added later
    $isComplete      = $allDone;
    $lvlIdx          = ($levelNum - 1) % count($levelFaIcons);
    $lvlIcon         = $levelFaIcons[$lvlIdx];
    $lvlIconColor    = $levelColors[$lvlIdx];
@endphp

<div class="rounded-3xl overflow-hidden shadow-sm border
            {{ $isUnlocked ? 'bg-white border-gray-100' : 'bg-gray-50 border-gray-200' }}">

    {{-- Level header --}}
    <div class="flex items-center gap-4 px-5 py-4"
         style="background: {{ $isUnlocked ? ($module->color . '14') : '#f3f4f6' }};
                border-bottom: 1px solid {{ $isUnlocked ? ($module->color . '28') : '#e5e7eb' }};">

        <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 text-xl text-white shadow-sm"
             style="background: {{ $isUnlocked ? $module->color : '#9ca3af' }};">
            @if($isUnlocked)
                <i class="fa-solid {{ $lvlIcon }}"></i>
            @else
                <i class="fa-solid fa-lock"></i>
            @endif
        </div>

        <div class="flex-1">
            <div class="font-fredoka text-xl text-gray-800">
                Level {{ $levelNum }}
                @if($isComplete)
                    <span class="ml-2 text-sm font-bold text-green-600">— Complete!</span>
                @endif
            </div>
            <div class="text-xs text-gray-400 mt-0.5">
                @if($isUnlocked)
                    {{ $doneCount }} of {{ $totalCount }} activities done
                @else
                    Complete Level {{ $levelNum - 1 }} to unlock
                @endif
            </div>
        </div>

        @if($isComplete)
            <div class="flex gap-0.5 flex-shrink-0">
                @for($s = 1; $s <= 3; $s++)
                    <i class="{{ $s <= ($lvlProgress->stars_earned ?? 0) ? 'fa-solid' : 'fa-regular' }} fa-star" style="font-size:1.1rem; color:#FFD700;"></i>
                @endfor
            </div>
        @elseif(!$isUnlocked)
            <i class="ri-lock-line text-gray-300 text-2xl flex-shrink-0"></i>
        @else
            <div class="hidden sm:flex flex-col items-end gap-1 flex-shrink-0">
                <div class="w-24 h-2 rounded-full bg-gray-200 overflow-hidden">
                    <div class="h-full rounded-full"
                         style="width: {{ $totalCount > 0 ? round($doneCount / $totalCount * 100) : 0 }}%;
                                background: {{ $module->color }};"></div>
                </div>
                <span class="text-xs text-gray-400">{{ $totalCount > 0 ? round($doneCount / $totalCount * 100) : 0 }}%</span>
            </div>
        @endif
    </div>

    {{-- Activities — only shown when level is unlocked --}}
    @if($isUnlocked)
    <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-3">
        @foreach($levelActivities as $activity)
        @php $sub = $submissions[$activity->id] ?? null; $done = $sub !== null; @endphp

        <div class="flex items-center gap-4 p-4 rounded-2xl border transition-all
                    {{ $done ? 'bg-green-50 border-green-100' : 'bg-gray-50 border-gray-100 hover:bg-orange-50 hover:border-orange-200' }}">

            @php
                $actTypeIcons = ['video'=>['fa-film','#FF6B6B'],'quiz'=>['fa-brain','#BB8FCE'],'matching'=>['fa-shuffle','#4ECDC4'],'drag_drop'=>['fa-hand','#45B7D1'],'coloring'=>['fa-palette','#FF8E53'],'audio'=>['fa-volume-high','#52BE80'],'tracing'=>['fa-pen-nib','#8b5cf6']];
                [$actIcon, $actColor] = $actTypeIcons[$activity->type] ?? ['fa-file-pen','#9ca3af'];
            @endphp
            <div class="flex-shrink-0 w-10 text-center" style="font-size:1.8rem; color:{{ $actColor }};">
                <i class="fa-solid {{ $actIcon }}"></i>
            </div>

            <div class="flex-1 min-w-0">
                <div class="font-bold text-gray-800 text-sm truncate">{{ $activity->title }}</div>
                @if($done)
                    <div class="flex items-center gap-0.5 mt-1">
                        @for($s = 1; $s <= 3; $s++)
                            <i class="{{ $s <= $sub->stars_earned ? 'fa-solid' : 'fa-regular' }} fa-star" style="font-size:0.85rem; color:#FFD700;"></i>
                        @endfor
                        <span class="text-xs text-gray-400 ml-1">{{ $sub->score }}%</span>
                    </div>
                @else
                    <div class="text-xs text-gray-400 mt-0.5 capitalize">
                        {{ str_replace('_', ' ', $activity->type) }} &bull; {{ $activity->stars_reward }} <i class="fa-solid fa-star" style="color:#FFD700;font-size:0.75rem;"></i> possible
                    </div>
                @endif
            </div>

            @if($done && $activity->type === 'quiz')
            <a href="{{ route('student.activities.review', $activity->id) }}"
               class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center
                      text-white transition hover:scale-110 active:scale-95 shadow-sm"
               style="background:#6366f1;" title="Review answers">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
            </a>
            @else
            <a href="{{ route('student.activities.show', $activity->id) }}"
               class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center
                      text-white transition hover:scale-110 active:scale-95 shadow-sm"
               style="background: {{ $done ? '#10b981' : $module->color }};">
                <i class="{{ $done ? 'ri-refresh-line' : 'ri-play-fill' }}"></i>
            </a>
            @endif
        </div>
        @endforeach
    </div>

    @endif

</div>
@endforeach
</div>
@endif

@endsection
