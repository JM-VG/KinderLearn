@extends('layouts.teacher')
@section('title', 'Attendance')
@section('teacher-content')
<script>
document.addEventListener('DOMContentLoaded', function(){ showComingSoon('Attendance'); });
</script>
<h1 class="font-fredoka text-4xl text-gray-800 mb-6">Attendance <i class="fa-solid fa-circle-check" style="color:#4ECDC4;"></i></h1>

<div class="bg-white rounded-3xl p-6 shadow-sm" data-tour="attendance-area">
    <form method="POST" action="{{ route('teacher.attendance.record') }}" class="space-y-5">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-gray-700 mb-2">Select Class</label>
                <select name="section_id" required class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none font-semibold">
                    @foreach($sections as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-bold text-gray-700 mb-2">Date</label>
                <input type="date" name="date" value="{{ $today }}" required class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none font-semibold">
            </div>
        </div>

        @foreach($sections as $section)
        <div class="border-2 border-gray-100 rounded-2xl p-4">
            <h3 class="font-fredoka text-xl text-gray-800 mb-4">{{ $section->name }}</h3>
            @forelse($section->students as $student)
            <div class="flex items-center gap-4 py-3 border-b border-gray-50 last:border-0">
                <div style="font-size:1.4rem; color:#4ECDC4;"><i class="fa-solid fa-child"></i></div>
                <span class="flex-1 font-bold text-gray-700">{{ $student->name }}</span>
                <div class="flex gap-3">
                    @php
                        $statusOpts = [
                            'present' => ['fa-circle-check', 'green', 'text-green-600'],
                            'late'    => ['fa-clock', 'yellow', 'text-yellow-600'],
                            'absent'  => ['fa-circle-xmark', 'red', 'text-red-500'],
                        ];
                    @endphp
                    @foreach($statusOpts as $status => [$icon, $col, $textClass])
                    <label class="cursor-pointer flex items-center gap-1">
                        <input type="radio" name="attendance[{{ $student->id }}]" value="{{ $status }}"
                               {{ isset($todayAttendance[$student->id]) && $todayAttendance[$student->id]->status === $status ? 'checked' : ($status === 'present' ? 'checked' : '') }}
                               class="accent-{{ $col }}-500">
                        <span class="text-sm font-bold {{ $textClass }}">
                            <i class="fa-solid {{ $icon }}"></i> {{ ucfirst($status) }}
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>
            @empty
            <p class="text-gray-400 font-semibold">No students in this class.</p>
            @endforelse
        </div>
        @endforeach

        <button type="submit" class="btn-kid text-white w-full justify-center" style="background: linear-gradient(135deg, #2C3E7A, #4A90D9)">
            <i class="fa-solid fa-floppy-disk"></i> Save Attendance
        </button>
    </form>
</div>
@endsection
