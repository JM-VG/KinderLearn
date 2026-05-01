@extends('layouts.student')
@section('title', 'My Lessons')

@section('student-content')

<div class="mb-8">
    <h1 class="font-fredoka text-4xl text-gray-800">My Lessons <i class="fa-solid fa-book-open" style="color:#FF6B6B;"></i></h1>
    <p class="text-gray-500 text-lg mt-1">Pick a subject and start learning!</p>
</div>

<div data-tour="modules-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    @foreach($modules as $module)
    @php
        $progress = $progresses[$module->id] ?? null;
        $stars    = $progress ? $progress->stars_earned : 0;
        $ap       = $activityProgress[$module->id] ?? ['total' => 0, 'done' => 0, 'pct' => 0];
        // Consider truly done only when all current activities (including newly added) are submitted
        $done     = $ap['total'] > 0 && $ap['done'] >= $ap['total'];
    @endphp
    <a href="{{ route('student.modules.show', $module->id) }}"
       class="block rounded-3xl shadow-md card-hover overflow-hidden">

        <div class="p-8 text-white text-center"
             style="background: linear-gradient(135deg, {{ $module->color }}, {{ $module->color }}cc);">
            <div class="text-6xl mb-3">{{ $module->icon }}</div>
            <div class="font-fredoka text-2xl">{{ $module->title }}</div>
            @if($done)
            <div class="mt-2 bg-white bg-opacity-30 rounded-full px-4 py-1 inline-block text-sm font-bold">
                <i class="fa-solid fa-circle-check"></i> Completed!
            </div>
            @endif
        </div>

        <div class="bg-white p-5">
            <p class="text-gray-500 text-sm mb-4">{{ $module->description }}</p>

            {{-- Activity progress bar --}}
            @if($ap['total'] > 0)
            <div class="mb-3">
                <div class="flex justify-between text-xs font-bold text-gray-500 mb-1">
                    <span>Progress</span>
                    <span>{{ $ap['done'] }}/{{ $ap['total'] }} activities</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5">
                    <div class="h-2.5 rounded-full transition-all"
                         style="width: {{ $ap['pct'] }}%; background: {{ $module->color }}"></div>
                </div>
            </div>
            @endif

            <div class="flex items-center gap-1 mb-3">
                @for($i = 1; $i <= 5; $i++)
                <i class="{{ $i <= $stars ? 'fa-solid' : 'fa-regular' }} fa-star" style="font-size:1.3rem; color:#FFD700;"></i>
                @endfor
                <span class="ml-2 text-gray-500 font-bold text-sm">{{ $stars }} stars</span>
            </div>

            <div class="text-center">
                <span class="inline-block px-6 py-3 rounded-full font-bold text-white text-sm"
                      style="background: {{ $module->color }}">
                    @if($done)
                        <i class="fa-solid fa-rotate"></i> Review
                    @else
                        <i class="fa-solid fa-play"></i> Start Learning
                    @endif
                </span>
            </div>
        </div>
    </a>
    @endforeach
</div>
@endsection
