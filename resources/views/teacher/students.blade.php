@extends('layouts.teacher')
@section('title', 'Students')
@section('teacher-content')
<h1 class="font-fredoka text-4xl text-gray-800 mb-6">All Students <i class="fa-solid fa-child" style="color:#4ECDC4;"></i></h1>
<div class="bg-white rounded-3xl shadow-sm overflow-hidden">
    <table class="w-full">
        <thead style="background: linear-gradient(90deg, #2C3E7A, #4A90D9);">
            <tr class="text-white text-left">
                <th class="px-6 py-4 font-bold">Student</th>
                <th class="px-6 py-4 font-bold">Class</th>
                <th class="px-6 py-4 font-bold">Stars</th>
                <th class="px-6 py-4 font-bold">Completed</th>
                <th class="px-6 py-4 font-bold">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($students as $s)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4">
                    <div class="font-bold text-gray-800">{{ $s->name }}</div>
                    <div class="text-sm text-gray-400">{{ $s->email }}</div>
                </td>
                <td class="px-6 py-4 text-gray-600 font-semibold">{{ $s->section->name ?? 'No class' }}</td>
                <td class="px-6 py-4 font-bold text-yellow-600"><i class="fa-solid fa-star" style="color:#FFD700;"></i> {{ $s->getTotalStars() }}</td>
                <td class="px-6 py-4 font-bold text-green-600"><i class="fa-solid fa-circle-check"></i> {{ $s->getCompletedModules() }}</td>
                <td class="px-6 py-4">
                    <a href="{{ route('teacher.students.show', $s->id) }}"
                       class="text-blue-500 font-bold hover:underline text-sm">View →</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400 font-semibold">No students found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
