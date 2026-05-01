@extends('layouts.teacher')
@section('title', 'Analytics')

@section('teacher-content')
<h1 class="font-fredoka text-4xl text-gray-800 mb-6">Learning Analytics <i class="fa-solid fa-chart-line" style="color:#4A90D9;"></i></h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

    {{-- Completion rate cards --}}
    @foreach($modules as $module)
    <div class="bg-white rounded-3xl p-6 shadow-sm">
        <div class="flex items-center gap-4 mb-4">
            <span class="text-4xl">{{ $module->icon }}</span>
            <div>
                <div class="font-fredoka text-xl text-gray-800">{{ $module->title }}</div>
                <div class="text-sm text-gray-500">{{ $module->activities_count }} activities</div>
            </div>
            <div class="ml-auto text-right">
                <div class="font-fredoka text-3xl" style="color: {{ $module->color }}">
                    {{ $module->completion_rate }}%
                </div>
                <div class="text-xs text-gray-400">completion rate</div>
            </div>
        </div>

        {{-- Progress bar --}}
        <div class="bg-gray-100 rounded-full h-4">
            <div class="h-4 rounded-full transition-all"
                 style="width: {{ $module->completion_rate }}%; background: {{ $module->color }};"></div>
        </div>
    </div>
    @endforeach
</div>

{{-- Bar chart using HTML/CSS (no library needed) --}}
<div class="bg-white rounded-3xl p-6 shadow-sm">
    <h2 class="font-fredoka text-2xl text-gray-800 mb-6">Completion Rate by Subject</h2>
    <div class="flex items-end justify-around gap-4 h-48">
        @foreach($modules as $module)
        <div class="flex flex-col items-center gap-2 flex-1">
            <div class="font-bold text-sm" style="color: {{ $module->color }}">
                {{ $module->completion_rate }}%
            </div>
            <div class="w-full rounded-t-2xl transition-all"
                 style="height: {{ max(8, $module->completion_rate * 1.6) }}px; background: {{ $module->color }}; min-height: 8px;">
            </div>
            <div class="text-2xl">{{ $module->icon }}</div>
            <div class="text-xs text-gray-500 text-center font-semibold">{{ Str::limit($module->title, 8) }}</div>
        </div>
        @endforeach
    </div>
</div>
@endsection
