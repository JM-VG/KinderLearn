@extends('layouts.teacher')
@section('title', 'My Classes')

@section('teacher-content')
<div class="mb-8 flex items-center justify-between" data-tour="classes-area">
    <div>
        <h1 class="font-fredoka text-4xl text-gray-800">My Classes <i class="fa-solid fa-school" style="color:#4ECDC4;"></i></h1>
        <p class="text-gray-500 mt-1">Create classes and share the join code with students.</p>
    </div>
</div>

@if(session('success'))
<div class="mb-5 px-5 py-3 bg-green-50 border border-green-200 text-green-700 rounded-2xl font-semibold flex items-center gap-2">
    <i class="ri-checkbox-circle-line text-lg"></i> {{ session('success') }}
</div>
@endif

{{-- Create class form --}}
<div class="bg-white rounded-3xl p-6 shadow-sm mb-8">
    <h2 class="font-fredoka text-2xl text-gray-800 mb-5">
        <i class="fa-solid fa-plus" style="color:#4ECDC4;"></i> Create New Class
    </h2>
    <form method="POST" action="{{ route('teacher.classes.create') }}" class="flex flex-col md:flex-row gap-4">
        @csrf
        <input type="text" name="name" placeholder="Class name (e.g., Sunflower Class)" required
               class="flex-1 px-5 py-3 rounded-2xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none font-semibold">
        <input type="text" name="description" placeholder="Description (optional)"
               class="flex-1 px-5 py-3 rounded-2xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none">
        <button type="submit"
                class="px-8 py-3 rounded-2xl font-bold text-white transition"
                style="background: linear-gradient(135deg, #4ECDC4, #45B7D1)">
            <i class="fa-solid fa-plus"></i> Create
        </button>
    </form>
</div>

@if($sections->isEmpty())
<div class="bg-white rounded-3xl p-12 text-center shadow-sm">
    <div class="mb-4" style="font-size:4.5rem; color:#d1d5db;"><i class="fa-solid fa-school"></i></div>
    <p class="font-fredoka text-2xl text-gray-600">No classes yet. Create your first class above!</p>
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @foreach($sections as $section)
    <div class="bg-white rounded-3xl p-6 shadow-sm">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="font-fredoka text-2xl text-gray-800">{{ $section->name }}</h3>
                <p class="text-gray-500 text-sm mt-1">{{ $section->description }}</p>
            </div>
            <div style="font-size:2.5rem; color:#4ECDC4;"><i class="fa-solid fa-school"></i></div>
        </div>

        {{-- Join code highlight --}}
        <div class="bg-blue-50 rounded-2xl p-4 mb-4 text-center">
            <div class="text-sm text-blue-600 font-semibold mb-1">Student Join Code:</div>
            <div class="font-fredoka text-4xl text-blue-700 tracking-widest">{{ $section->join_code }}</div>
            <div class="text-xs text-blue-400 mt-1">Share this with students to join your class</div>
        </div>

        <div class="flex items-center justify-between">
            <span class="text-gray-500 font-semibold">
                <i class="fa-solid fa-child" style="color:#FF6B6B;"></i> {{ $section->students->count() }} students
            </span>
            <div class="flex items-center gap-2">
                <a href="{{ route('teacher.classes.show', $section->id) }}"
                   class="px-5 py-2 rounded-full font-bold text-white text-sm"
                   style="background: linear-gradient(135deg, #2C3E7A, #4A90D9)">
                    View Class →
                </a>
                <form id="del-form-{{ $section->id }}" method="POST" action="{{ route('teacher.classes.destroy', $section->id) }}">
                    @csrf @method('DELETE')
                    <button type="button"
                            onclick="confirmDeleteClass('{{ $section->id }}', '{{ addslashes($section->name) }}')"
                            class="px-3 py-2 rounded-full font-bold text-red-400 border-2 border-red-100 hover:bg-red-50 text-sm transition">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
{{-- Delete Class Confirmation Modal --}}
<div id="delete-class-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center"
     style="background:rgba(0,0,0,0.45);">
    <div class="bg-white rounded-3xl p-8 shadow-2xl max-w-sm w-full mx-4 text-center">
        <div class="mb-4" style="font-size:3.5rem; color:#ef4444;"><i class="fa-solid fa-trash"></i></div>
        <h3 class="font-fredoka text-2xl text-gray-800 mb-2">Delete this class?</h3>
        <p class="text-gray-500 text-sm mb-1" id="del-class-name"></p>
        <p class="text-xs text-gray-400 mb-6">All students will be unenrolled. This cannot be undone.</p>
        <div class="flex gap-3 justify-center">
            <button onclick="document.getElementById('delete-class-modal').classList.add('hidden')"
                    class="px-6 py-2.5 rounded-xl border-2 border-gray-200 font-bold text-gray-600 hover:bg-gray-50 transition">
                Cancel
            </button>
            <button id="del-confirm-btn"
                    class="px-6 py-2.5 rounded-xl font-bold text-white transition"
                    style="background:linear-gradient(135deg,#ef4444,#dc2626);">
                Yes, Delete
            </button>
        </div>
    </div>
</div>

<script>
window.confirmDeleteClass = function(id, name) {
    document.getElementById('del-class-name').textContent = '"' + name + '"';
    document.getElementById('del-confirm-btn').onclick = function() {
        document.getElementById('del-form-' + id).submit();
    };
    document.getElementById('delete-class-modal').classList.remove('hidden');
};
</script>
@endsection
