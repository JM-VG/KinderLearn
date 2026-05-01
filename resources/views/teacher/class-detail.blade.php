@extends('layouts.teacher')
@section('title', $class->name)

@section('teacher-content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('teacher.classes') }}" class="text-gray-400 hover:text-gray-600 font-bold">← Classes</a>
    <h1 class="font-fredoka text-4xl text-gray-800">{{ $class->name }}</h1>
</div>

<div class="bg-blue-50 rounded-3xl p-5 mb-8 flex items-center gap-4">
    <div style="font-size:2rem; color:#4A90D9;"><i class="fa-solid fa-key"></i></div>
    <div>
        <div class="text-sm text-blue-600 font-semibold">Join Code</div>
        <div class="font-fredoka text-3xl text-blue-800 tracking-widest">{{ $class->join_code }}</div>
    </div>
</div>

<div class="bg-white rounded-3xl p-6 shadow-sm">
    <h2 class="font-fredoka text-2xl text-gray-800 mb-5">
        <i class="fa-solid fa-child" style="color:#4ECDC4;"></i> Students ({{ $students->count() }})
    </h2>
    @if($students->isEmpty())
    <div class="text-center py-10">
        <div class="mb-3" style="font-size:3.5rem; color:#d1d5db;"><i class="fa-solid fa-hand-wave"></i></div>
        <p class="text-gray-500 font-semibold">No students yet. Share the join code!</p>
    </div>
    @else
    <div class="space-y-3">
        @foreach($students as $s)
        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl">
            <div style="font-size:2rem; color:#4ECDC4;"><i class="fa-solid fa-child"></i></div>
            <div class="flex-1">
                <div class="font-bold text-gray-800">{{ $s->name }}</div>
                <div class="text-sm text-gray-500">{{ $s->email }}</div>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-yellow-600">
                    <i class="fa-solid fa-star" style="color:#FFD700;"></i> {{ $s->getTotalStars() }}
                </span>
                <a href="{{ route('teacher.students.show', $s->id) }}"
                   class="text-sm font-bold text-blue-500 hover:underline">View</a>
                <form method="POST" action="{{ route('teacher.classes.remove-student', $class->id) }}"
                      onsubmit="return confirm('Remove this student from the class?')">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $s->id }}">
                    <button type="submit" class="text-sm font-bold text-red-400 hover:text-red-600">Remove</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
