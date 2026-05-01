{{-- admin/teachers.blade.php --}}
@extends('layouts.admin')
@section('title', 'Teachers')
@section('admin-content')
<h1 class="font-fredoka text-4xl text-gray-800 mb-6">Teachers <i class="fa-solid fa-chalkboard-user" style="color:#4ECDC4;"></i></h1>
<div class="grid grid-cols-1 md:grid-cols-3 gap-5">
    @forelse($teachers as $t)
    <div class="bg-white rounded-3xl p-6 shadow-sm">
        <div class="text-center mb-3" style="font-size:3.5rem; color:#4ECDC4;"><i class="fa-solid fa-chalkboard-user"></i></div>
        <div class="font-fredoka text-xl text-center text-gray-800 mb-1">{{ $t->name }}</div>
        <div class="text-gray-500 text-center text-sm mb-3">{{ $t->email }}</div>
        <div class="text-center">
            <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-sm font-bold">
                <i class="fa-solid fa-school"></i> {{ $t->sections->count() }} classes
            </span>
        </div>
    </div>
    @empty
    <div class="col-span-3 bg-white rounded-3xl p-12 text-center shadow-sm">
        <div class="mb-4" style="font-size:4.5rem; color:#d1d5db;"><i class="fa-solid fa-chalkboard-user"></i></div>
        <p class="font-fredoka text-2xl text-gray-600">No teachers yet.</p>
    </div>
    @endforelse
</div>
@endsection
