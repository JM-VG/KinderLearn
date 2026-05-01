@extends('layouts.teacher')
@section('title', $student->name)
@section('teacher-content')
<div class="mb-6"><a href="{{ route('teacher.students') }}" class="text-gray-400 font-bold hover:underline">← Students</a></div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-3xl p-6 shadow-sm text-center">
        <div class="mb-3" style="font-size:4.5rem; color:#4ECDC4;"><i class="fa-solid fa-child"></i></div>
        <div class="font-fredoka text-2xl text-gray-800">{{ $student->name }}</div>
        <div class="text-gray-500">{{ $student->section->name ?? 'No class' }}</div>
        <div class="mt-3 font-bold text-yellow-600 text-xl">
            <i class="fa-solid fa-star" style="color:#FFD700;"></i> {{ $student->getTotalStars() }} Stars
        </div>
    </div>

    <div class="md:col-span-2 grid grid-cols-2 gap-4">
        @foreach($modules as $module)
        @php $p = $progresses[$module->id] ?? null; @endphp
        <div class="bg-white rounded-2xl p-4 shadow-sm flex items-center gap-3">
            <span class="text-3xl">{{ $module->icon }}</span>
            <div>
                <div class="font-bold text-gray-800 text-sm">{{ $module->title }}</div>
                <div class="flex gap-0.5">
                    @for($i=1;$i<=3;$i++)
                    <i class="{{ $i<=($p?$p->stars_earned:0) ? 'fa-solid' : 'fa-regular' }} fa-star" style="font-size:0.9rem; color:#FFD700;"></i>
                    @endfor
                </div>
                @if($p && $p->completed)
                <span class="text-xs bg-green-100 text-green-600 font-bold px-2 py-0.5 rounded-full">Done</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>

<div class="bg-white rounded-3xl p-6 shadow-sm">
    <h2 class="font-fredoka text-2xl text-gray-800 mb-5"><i class="fa-solid fa-file-pen" style="color:#BB8FCE;"></i> Recent Submissions</h2>
    <div class="space-y-3">
        @forelse($submissions as $sub)
        <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-2xl">
            <div class="flex-1">
                <div class="font-bold text-gray-800">{{ $sub->activity?->title ?? '—' }}</div>
                <div class="text-sm text-gray-500">{{ $sub->activity?->module?->title ?? '—' }}</div>
            </div>
            <div class="font-fredoka text-2xl {{ $sub->score>=80?'text-green-600':($sub->score>=60?'text-yellow-500':'text-red-500') }}">
                {{ $sub->score }}%
            </div>
            <div class="text-yellow-500 font-bold">
                {{ $sub->stars_earned }} <i class="fa-solid fa-star" style="color:#FFD700;"></i>
            </div>
        </div>
        @empty
        <p class="text-gray-400 font-semibold text-center py-6">No submissions yet.</p>
        @endforelse
    </div>
</div>
@endsection
