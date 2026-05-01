@extends('layouts.teacher')
@section('title', 'Modules')
@section('teacher-content')
<div class="flex items-center justify-between mb-6">
    <h1 class="font-fredoka text-4xl text-gray-800">Modules <i class="fa-solid fa-book-open" style="color:#4A90D9;"></i></h1>
    <a href="{{ route('teacher.modules.create') }}"
       class="btn-kid text-white"
       style="background: linear-gradient(135deg, #4ECDC4, #45B7D1)">
        <i class="fa-solid fa-plus"></i> New Module
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-5" data-tour="modules-grid">
    @forelse($modules as $module)
    <div class="bg-white rounded-3xl overflow-hidden shadow-sm card-hover">
        <div class="p-6 text-white text-center"
             style="background: linear-gradient(135deg, {{ $module->color }}, {{ $module->color }}cc);">
            <div class="text-5xl mb-2">{{ $module->icon }}</div>
            <div class="font-fredoka text-2xl">{{ $module->title }}</div>
            <div class="text-sm opacity-80 mt-1 capitalize">{{ $module->subject }}</div>
        </div>
        <div class="p-4">
            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                <span><i class="fa-solid fa-gamepad" style="color:#4ECDC4;"></i> {{ $module->activities_count }} activities</span>
                <span class="{{ $module->is_active ? 'text-green-500' : 'text-red-400' }} font-bold">
                    @if($module->is_active)
                        <i class="fa-solid fa-circle-check"></i> Active
                    @else
                        <i class="fa-solid fa-ban"></i> Hidden
                    @endif
                </span>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('teacher.modules.edit', $module->id) }}"
                   class="flex-1 text-center px-3 py-2 rounded-xl font-bold text-sm text-white"
                   style="background: #4A90D9">
                    <i class="fa-solid fa-pen"></i> Edit
                </a>
                <form method="POST" action="{{ route('teacher.modules.destroy', $module->id) }}"
                      onsubmit="return confirm('Delete this module?')" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full px-3 py-2 rounded-xl font-bold text-sm bg-red-100 text-red-500 hover:bg-red-200">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-3 bg-white rounded-3xl p-12 text-center shadow-sm">
        <div class="mb-4" style="font-size:4.5rem; color:#d1d5db;"><i class="fa-solid fa-book-open"></i></div>
        <p class="font-fredoka text-2xl text-gray-600">No modules yet. Create your first one!</p>
    </div>
    @endforelse
</div>
@endsection
