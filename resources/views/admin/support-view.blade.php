@extends('layouts.admin')
@section('title', 'Support Message')
@section('admin-content')

<div class="mb-6">
    <a href="{{ route('admin.support') }}" class="text-gray-400 font-bold hover:underline text-sm">
        <i class="ri-arrow-left-line"></i> Back to Inbox
    </a>
</div>

<div class="max-w-2xl mx-auto">

    {{-- Message card --}}
    <div class="bg-white rounded-3xl shadow-sm p-7 mb-5">

        <div class="flex items-start gap-4 mb-6">
            <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center font-bold text-white text-lg"
                 style="background: linear-gradient(135deg, {{ $message->sender->role === 'teacher' ? '#0ea5e9,#6366f1' : '#FF6B6B,#FF8E53' }});">
                {{ strtoupper(substr($message->sender->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="font-bold text-gray-800">{{ $message->sender->name }}</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold capitalize"
                          style="background: {{ $message->sender->role === 'teacher' ? '#e0f2fe' : '#fff7ed' }};
                                 color: {{ $message->sender->role === 'teacher' ? '#0369a1' : '#c2410c' }};">
                        {{ $message->sender->role }}
                    </span>
                    <span class="text-xs text-gray-400">{{ $message->sender->email }}</span>
                </div>
                <p class="text-xs text-gray-400 mt-0.5">{{ $message->created_at->format('M d, Y \a\t g:i A') }}</p>
            </div>
            <form method="POST" action="{{ route('admin.support.delete', $message->id) }}"
                  onsubmit="return confirm('Delete this message?')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="px-3 py-1.5 rounded-xl text-xs font-bold text-red-500 border border-red-200 hover:bg-red-50 transition">
                    <i class="ri-delete-bin-line mr-1"></i> Delete
                </button>
            </form>
        </div>

        <div class="border-t border-gray-100 pt-5">
            <h2 class="font-fredoka text-2xl text-gray-800 mb-3">{{ $message->subject }}</h2>
            <p class="text-gray-600 text-sm leading-relaxed whitespace-pre-wrap">{{ $message->body }}</p>
        </div>
    </div>

    {{-- Reply form --}}
    <div class="bg-white rounded-3xl shadow-sm p-7">
        <h3 class="font-fredoka text-xl text-gray-800 mb-4">
            <i class="ri-reply-line" style="color:#6366f1;"></i> Reply to {{ $message->sender->name }}
        </h3>

        @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-2xl text-sm font-semibold flex items-center gap-2">
            <i class="ri-checkbox-circle-line"></i> {{ session('success') }}
        </div>
        @endif

        <form method="POST" action="{{ route('admin.support.reply', $message->id) }}">
            @csrf
            <textarea name="body" required maxlength="2000" rows="5"
                      class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-indigo-400 focus:outline-none text-sm resize-none mb-4"
                      placeholder="Write your reply...">{{ old('body') }}</textarea>
            @error('body')
            <p class="text-red-500 text-xs mb-3 font-semibold">{{ $message }}</p>
            @enderror
            <button type="submit"
                    class="px-6 py-2.5 rounded-2xl font-bold text-white transition hover:opacity-90"
                    style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                <i class="ri-send-plane-line mr-1"></i> Send Reply
            </button>
        </form>
    </div>

</div>

@endsection
