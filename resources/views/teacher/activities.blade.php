@extends('layouts.teacher')
@section('title', 'Activities')
@section('teacher-content')
<div class="flex items-center justify-between mb-6">
    <h1 class="font-fredoka text-4xl text-gray-800">Activities <i class="fa-solid fa-gamepad" style="color:#4A90D9;"></i></h1>
    <a href="{{ route('teacher.activities.create') }}"
       class="btn-kid text-white"
       style="background: linear-gradient(135deg, #FF6B6B, #FF8E53)">
        <i class="fa-solid fa-plus"></i> New Activity
    </a>
</div>

<div class="bg-white rounded-3xl shadow-sm overflow-hidden">
    <table class="w-full">
        <thead style="background: linear-gradient(90deg, #2C3E7A, #4A90D9);">
            <tr class="text-white text-left">
                <th class="px-6 py-4 font-bold">Activity</th>
                <th class="px-6 py-4 font-bold">Module</th>
                <th class="px-6 py-4 font-bold">Type</th>
                <th class="px-6 py-4 font-bold text-center">Stars</th>
                <th class="px-6 py-4 font-bold">Deadline</th>
                <th class="px-6 py-4 font-bold">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($activities as $act)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 font-bold text-gray-800">{{ $act->title }}</td>
                <td class="px-6 py-4 text-gray-500">{{ $act->module?->icon }} {{ $act->module?->title ?? '—' }}</td>
                <td class="px-6 py-4">
                    <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-xs font-bold capitalize">
                        {{ str_replace('_', ' ', $act->type) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-center text-yellow-500 font-bold">
                    {{ $act->stars_reward }} <i class="fa-solid fa-star" style="color:#FFD700;"></i>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">
                    {{ $act->deadline ? $act->deadline->format('M d, Y') : '—' }}
                </td>
                <td class="px-6 py-4 flex gap-2">
                    @if($act->type === 'tracing')
                    <a href="{{ route('teacher.activities.grade', $act->id) }}"
                       class="px-3 py-1 rounded-xl font-bold text-xs text-white hover:opacity-90"
                       style="background: linear-gradient(135deg, #8b5cf6, #ec4899)">
                        <i class="fa-solid fa-pen-nib"></i> Grade
                    </a>
                    @endif
                    <a href="{{ route('teacher.activities.edit', $act->id) }}"
                       class="px-3 py-1 rounded-xl bg-blue-100 text-blue-600 font-bold text-sm hover:bg-blue-200">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <form method="POST" action="{{ route('teacher.activities.destroy', $act->id) }}"
                          onsubmit="return confirm('Delete this activity?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-3 py-1 rounded-xl bg-red-100 text-red-500 font-bold text-sm hover:bg-red-200">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-400 font-semibold">
                    No activities yet. Click "New Activity" to create one!
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
