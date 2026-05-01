@extends('layouts.teacher')
@section('title', 'Teacher Dashboard')

@section('teacher-content')

<div class="mb-8">
    <h1 class="font-fredoka text-4xl text-gray-800">Welcome, {{ $teacher->name }}! <i class="fa-solid fa-chalkboard-user" style="color:#4ECDC4;"></i></h1>
    <p class="text-gray-500 text-lg mt-1">Here's what's happening in your classes today.</p>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    @php
        $stats = [
            ['fa' => 'fa-solid fa-child',           'value' => $totalStudents,             'label' => 'My Students',        'color' => '#FF6B6B', 'bg' => '#fff0f0'],
            ['fa' => 'fa-solid fa-school',           'value' => $sections->count(),         'label' => 'My Classes',         'color' => '#4ECDC4', 'bg' => '#f0fffe'],
            ['fa' => 'fa-solid fa-book-open',        'value' => $modules->count(),          'label' => 'Modules',            'color' => '#45B7D1', 'bg' => '#f0f8ff'],
            ['fa' => 'fa-solid fa-pen-to-square',    'value' => $recentSubmissions->count(),'label' => 'Recent Submissions', 'color' => '#BB8FCE', 'bg' => '#f8f0ff'],
        ];
    @endphp

    @foreach($stats as $s)
    <div class="rounded-3xl p-5 text-center shadow-sm" style="background: {{ $s['bg'] }}; border: 2px solid {{ $s['color'] }}30;">
        <div class="mb-2" style="font-size:2.2rem; color:{{ $s['color'] }};"><i class="{{ $s['fa'] }}"></i></div>
        <div class="font-fredoka text-3xl" style="color: {{ $s['color'] }}">{{ $s['value'] }}</div>
        <div class="text-gray-500 font-semibold text-sm">{{ $s['label'] }}</div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- My Classes --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-fredoka text-2xl text-gray-800"><i class="fa-solid fa-school" style="color:#4ECDC4;"></i> My Classes</h2>
            <a href="{{ route('teacher.classes') }}" class="text-blue-500 font-bold text-sm hover:underline">Manage →</a>
        </div>
        @if($sections->isEmpty())
        <div class="text-center py-8">
            <div class="mb-3" style="font-size:3rem; color:#d1d5db;"><i class="fa-solid fa-school"></i></div>
            <p class="text-gray-500 font-semibold">No classes yet.</p>
            <a href="{{ route('teacher.classes') }}" class="text-blue-500 font-bold hover:underline">Create your first class →</a>
        </div>
        @else
        <div class="space-y-3">
            @foreach($sections as $section)
            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl">
                <div style="font-size:1.8rem; color:#4ECDC4;"><i class="fa-solid fa-school"></i></div>
                <div class="flex-1">
                    <div class="font-bold text-gray-800">{{ $section->name }}</div>
                    <div class="text-sm text-gray-500">{{ $section->students_count }} students &bull; Code: <span class="font-bold text-blue-500">{{ $section->join_code }}</span></div>
                </div>
                <a href="{{ route('teacher.classes.show', $section->id) }}" class="text-sm font-bold text-blue-500 hover:underline">View</a>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Recent Submissions --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm">
        <h2 class="font-fredoka text-2xl text-gray-800 mb-5"><i class="fa-solid fa-pen-to-square" style="color:#BB8FCE;"></i> Recent Submissions</h2>
        @if($recentSubmissions->isEmpty())
        <div class="text-center py-8">
            <div class="mb-3" style="font-size:3rem; color:#d1d5db;"><i class="fa-solid fa-envelope-open"></i></div>
            <p class="text-gray-500 font-semibold">No submissions yet.</p>
        </div>
        @else
        <div class="space-y-3 max-h-80 overflow-y-auto">
            @foreach($recentSubmissions as $sub)
            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-2xl">
                <div style="font-size:1.6rem; color:#BB8FCE;"><i class="fa-solid fa-pen-to-square"></i></div>
                <div class="flex-1 min-w-0">
                    <div class="font-bold text-gray-800 truncate">{{ $sub->user->name }}</div>
                    <div class="text-sm text-gray-500 truncate">{{ $sub->activity->title }}</div>
                </div>
                <div class="text-right">
                    <div class="font-bold text-sm" style="color: {{ $sub->score >= 80 ? '#27ae60' : ($sub->score >= 60 ? '#f39c12' : '#e74c3c') }}">
                        {{ $sub->score }}%
                    </div>
                    <div class="text-xs text-gray-400">{{ $sub->created_at->diffForHumans() }}</div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

<div class="mt-4 text-center">
    <button onclick="window.__klTeacherTourReset && window.__klTeacherTourReset()"
            class="text-xs text-gray-400 hover:text-sky-500 transition font-semibold">
        <i class="fa-solid fa-route"></i> Restart tour
    </button>
</div>
@endsection
