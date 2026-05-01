@extends('layouts.student')
@section('title', 'Messages')

@section('student-content')

<div class="mb-8">
    <h1 class="font-fredoka text-4xl text-gray-800">Messages <i class="fa-solid fa-envelope" style="color:#FF6B6B;"></i></h1>
    <p class="text-gray-500 text-lg mt-1">Talk to your teacher here</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Send a message --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm">
        <h2 class="font-fredoka text-2xl text-gray-800 mb-5"><i class="fa-solid fa-paper-plane" style="color:#FF6B6B;"></i> Send a Message</h2>
        <form method="POST" action="{{ route('student.messages.send') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block font-bold text-gray-700 mb-2">Send to:</label>
                <select name="receiver_id" required
                        class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-orange-400 focus:outline-none font-semibold">
                    @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}"><i class="fa-solid fa-chalkboard-user"></i> {{ $teacher->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-bold text-gray-700 mb-2">Subject:</label>
                <input type="text" name="subject" required placeholder="What is this about?"
                       class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-orange-400 focus:outline-none">
            </div>

            <div>
                <label class="block font-bold text-gray-700 mb-2">Message:</label>
                <textarea name="body" required rows="4" placeholder="Type your message here..."
                          class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-orange-400 focus:outline-none resize-none"></textarea>
            </div>

            <button type="submit"
                    class="btn-kid text-white w-full justify-center"
                    style="background: linear-gradient(135deg, #FF6B6B, #FF8E53);">
                <i class="fa-solid fa-paper-plane"></i> Send Message
            </button>
        </form>
    </div>

    {{-- Inbox --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm">
        <h2 class="font-fredoka text-2xl text-gray-800 mb-5"><i class="fa-solid fa-inbox" style="color:#4ECDC4;"></i> Inbox</h2>
        @if($messages->isEmpty())
        <div class="text-center py-10">
            <div class="mb-3" style="font-size:3.5rem; color:#d1d5db;"><i class="fa-solid fa-envelope-open"></i></div>
            <p class="text-gray-500 font-semibold">No messages yet.</p>
        </div>
        @else
        <div class="space-y-3 max-h-96 overflow-y-auto">
            @foreach($messages as $msg)
            <div class="p-4 rounded-2xl {{ $msg->receiver_id == $user->id && !$msg->read_at ? 'bg-orange-50 border-2 border-orange-200' : 'bg-gray-50' }}">
                <div class="flex items-center justify-between mb-1">
                    <span class="font-bold text-gray-800 text-sm">
                        {{ $msg->sender_id == $user->id ? '→ You to ' . $msg->receiver->name : '← ' . $msg->sender->name }}
                    </span>
                    @if($msg->receiver_id == $user->id && !$msg->read_at)
                    <span class="bg-orange-500 text-white text-xs px-2 py-0.5 rounded-full font-bold">New</span>
                    @endif
                </div>
                <div class="font-semibold text-gray-700">{{ $msg->subject }}</div>
                <p class="text-sm text-gray-500 mt-1">{{ Str::limit($msg->body, 80) }}</p>
                <div class="text-xs text-gray-400 mt-2">{{ $msg->created_at->diffForHumans() }}</div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
