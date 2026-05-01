@extends('layouts.admin')
@section('title', 'Students')
@section('admin-content')
<h1 class="font-fredoka text-4xl text-gray-800 mb-6">Students <i class="fa-solid fa-child" style="color:#FF6B6B;"></i></h1>
<div class="bg-white rounded-3xl shadow-sm overflow-hidden">
    <table class="w-full">
        <thead style="background: linear-gradient(90deg, #1a1a2e, #16213e);">
            <tr class="text-white text-left">
                <th class="px-6 py-4 font-bold">Name</th>
                <th class="px-6 py-4 font-bold">Email</th>
                <th class="px-6 py-4 font-bold">Class</th>
                <th class="px-6 py-4 font-bold">Stars</th>
                <th class="px-6 py-4 font-bold">Joined</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($students as $s)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 font-bold text-gray-800">{{ $s->name }}</td>
                <td class="px-6 py-4 text-gray-500 text-sm">{{ $s->email }}</td>
                <td class="px-6 py-4 text-gray-600 font-semibold">{{ $s->section->name ?? '—' }}</td>
                <td class="px-6 py-4 font-bold text-yellow-600"><i class="fa-solid fa-star" style="color:#FFD700;"></i> {{ $s->getTotalStars() }}</td>
                <td class="px-6 py-4 text-sm text-gray-400">{{ $s->created_at->format('M d, Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400 font-semibold">No students yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $students->links() }}</div>
</div>
@endsection
