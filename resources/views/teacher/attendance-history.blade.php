@extends('layouts.teacher')
@section('title', 'Attendance History')
@section('teacher-content')
<div class="flex items-center justify-between mb-6">
    <h1 class="font-fredoka text-4xl text-gray-800">Attendance History <i class="fa-solid fa-calendar-check" style="color:#4ECDC4;"></i></h1>
    <a href="{{ route('teacher.attendance') }}" class="btn-kid text-white text-sm"
       style="background: linear-gradient(135deg, #2C3E7A, #4A90D9)">
        <i class="fa-solid fa-plus"></i> Record Today
    </a>
</div>

<div class="bg-white rounded-3xl shadow-sm overflow-hidden">
    <table class="w-full">
        <thead style="background: linear-gradient(90deg, #2C3E7A, #4A90D9);">
            <tr class="text-white text-left">
                <th class="px-6 py-4 font-bold">Student</th>
                <th class="px-6 py-4 font-bold">Class</th>
                <th class="px-6 py-4 font-bold">Date</th>
                <th class="px-6 py-4 font-bold">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($records as $r)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 font-bold text-gray-800">{{ $r->student->name }}</td>
                <td class="px-6 py-4 text-gray-500">{{ $r->section->name }}</td>
                <td class="px-6 py-4 text-gray-600">{{ $r->date->format('M d, Y') }}</td>
                <td class="px-6 py-4">
                    @php
                        $statusStyles = [
                            'present' => 'bg-green-100 text-green-600',
                            'absent'  => 'bg-red-100 text-red-600',
                            'late'    => 'bg-yellow-100 text-yellow-600',
                        ];
                        $statusIcons = [
                            'present' => '<i class="fa-solid fa-circle-check"></i>',
                            'absent'  => '<i class="fa-solid fa-circle-xmark"></i>',
                            'late'    => '<i class="fa-solid fa-clock"></i>',
                        ];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-bold {{ $statusStyles[$r->status] ?? '' }}">
                        {!! $statusIcons[$r->status] ?? '' !!} {{ ucfirst($r->status) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-12 text-center text-gray-400 font-semibold">
                    No attendance records yet.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $records->links() }}</div>
</div>
@endsection
