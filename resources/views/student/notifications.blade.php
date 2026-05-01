@extends('layouts.student')
@section('title', 'Notifications')

@section('student-content')
<div class="mb-8">
    <h1 class="font-fredoka text-4xl text-gray-800">Notifications <i class="fa-solid fa-bell" style="color:#FF6B6B;"></i></h1>
</div>

<div class="max-w-2xl mx-auto">
@if($notifications->isEmpty())
<div class="bg-white rounded-3xl p-12 text-center shadow-sm">
    <div class="mb-4" style="font-size:4.5rem; color:#d1d5db;"><i class="fa-solid fa-bell-slash"></i></div>
    <p class="font-fredoka text-2xl text-gray-600">No notifications yet!</p>
</div>
@else
<div class="space-y-3">
    @foreach($notifications as $n)
    <div class="bg-white rounded-2xl p-5 shadow-sm flex items-start gap-4">
        <div style="font-size:1.8rem;">
            @switch($n->type)
                @case('achievement') <i class="fa-solid fa-trophy" style="color:#FFD700;"></i> @break
                @case('message') <i class="fa-solid fa-envelope" style="color:#4ECDC4;"></i> @break
                @case('announcement') <i class="fa-solid fa-bullhorn" style="color:#FF6B6B;"></i> @break
                @default <i class="fa-solid fa-bell" style="color:#BB8FCE;"></i>
            @endswitch
        </div>
        <div class="flex-1">
            <div class="font-bold text-gray-800">{{ $n->title }}</div>
            <p class="text-gray-500 text-sm mt-1">{{ $n->message }}</p>
            <div class="text-xs text-gray-400 mt-2">{{ $n->created_at->diffForHumans() }}</div>
        </div>
    </div>
    @endforeach
</div>
@endif
</div>
@endsection
