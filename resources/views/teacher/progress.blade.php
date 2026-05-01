@extends('layouts.teacher')
@section('title', 'Student Progress')
@section('teacher-content')
<h1 class="font-fredoka text-4xl text-gray-800 mb-6">Student Progress <i class="fa-solid fa-chart-column" style="color:#4A90D9;"></i></h1>

<div class="bg-white rounded-3xl shadow-sm overflow-x-auto">
    <table class="w-full min-w-[700px]">
        <thead style="background: linear-gradient(90deg, #2C3E7A, #4A90D9);">
            <tr class="text-white text-left">
                <th class="px-6 py-4 font-bold">Student</th>
                @foreach($modules as $m)
                <th class="px-4 py-4 font-bold text-center text-sm">{{ $m->icon }} {{ Str::limit($m->title, 10) }}</th>
                @endforeach
                <th class="px-6 py-4 font-bold text-center"><i class="fa-solid fa-star" style="color:#FFD700;"></i> Total</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($students as $s)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4">
                    <div class="font-bold text-gray-800">{{ $s->name }}</div>
                    <div class="text-xs text-gray-400">{{ $s->section->name ?? '' }}</div>
                </td>
                @foreach($modules as $m)
                @php $p = $progresses[$s->id][$m->id] ?? null; @endphp
                <td class="px-4 py-4 text-center">
                    @if($p && $p->completed)
                        <i class="fa-solid fa-circle-check text-green-500 font-bold"></i>
                    @elseif($p && $p->stars_earned > 0)
                        <span class="text-yellow-500 font-bold"><i class="fa-solid fa-hourglass-half"></i> {{ $p->stars_earned }} <i class="fa-solid fa-star" style="color:#FFD700;"></i></span>
                    @else
                        <span class="text-gray-300">—</span>
                    @endif
                </td>
                @endforeach
                <td class="px-6 py-4 text-center font-fredoka text-xl text-yellow-600">
                    {{ $s->getTotalStars() }}
                </td>
            </tr>
            @empty
            <tr><td colspan="{{ $modules->count() + 2 }}" class="px-6 py-12 text-center text-gray-400 font-semibold">No students found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>


@endsection
