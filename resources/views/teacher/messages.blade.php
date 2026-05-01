@extends('layouts.teacher')
@section('title', 'Messages')
@section('teacher-content')
<h1 class="font-fredoka text-4xl text-gray-800 mb-6">Messages <i class="fa-solid fa-envelope" style="color:#4A90D9;"></i></h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Send message --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm">
        <h2 class="font-fredoka text-2xl text-gray-800 mb-5"><i class="fa-solid fa-paper-plane" style="color:#4A90D9;"></i> Send a Message</h2>
        <form method="POST" action="{{ route('teacher.messages.send') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block font-bold text-gray-700 mb-2">Send to:</label>
                <select name="receiver_id" required
                        class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none font-semibold">
                    <option value="">-- Select student/parent --</option>
                    @foreach($students as $s)
                    <option value="{{ $s->id }}"><i class="fa-solid fa-child"></i> {{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-bold text-gray-700 mb-2">Subject:</label>
                <input type="text" name="subject" required placeholder="e.g., Great progress this week!"
                       class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none">
            </div>
            <div>
                <label class="block font-bold text-gray-700 mb-2">Message:</label>
                <textarea name="body" required rows="5" placeholder="Write your message..."
                          class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none resize-none"></textarea>
            </div>
            <button type="submit"
                    class="btn-kid text-white w-full justify-center"
                    style="background: linear-gradient(135deg, #2C3E7A, #4A90D9)">
                <i class="fa-solid fa-paper-plane"></i> Send Message
            </button>
        </form>
    </div>

    {{-- Inbox --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm">
        <h2 class="font-fredoka text-2xl text-gray-800 mb-5"><i class="fa-solid fa-inbox" style="color:#4A90D9;"></i> Inbox</h2>
        @if($messages->isEmpty())
        <div class="text-center py-10">
            <div class="mb-3" style="font-size:3.5rem; color:#d1d5db;"><i class="fa-solid fa-envelope-open"></i></div>
            <p class="text-gray-500 font-semibold">No messages yet.</p>
        </div>
        @else
        <div class="space-y-3 max-h-[500px] overflow-y-auto pr-1">
            @foreach($messages as $msg)
            <div class="p-4 rounded-2xl {{ !$msg->read_at && $msg->receiver_id == $teacher->id ? 'bg-blue-50 border-2 border-blue-200' : 'bg-gray-50' }}">
                <div class="flex items-center justify-between mb-1">
                    <span class="font-bold text-gray-800 text-sm">
                        @if($msg->sender_id == $teacher->id)
                            → You to {{ $msg->receiver->name }}
                        @else
                            ← {{ $msg->sender->name }}
                        @endif
                    </span>
                    @if(!$msg->read_at && $msg->receiver_id == $teacher->id)
                    <span class="bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full font-bold">New</span>
                    @endif
                </div>
                <div class="font-semibold text-gray-700">{{ $msg->subject }}</div>
                <p class="text-sm text-gray-500 mt-1">{{ Str::limit($msg->body, 100) }}</p>
                <div class="text-xs text-gray-400 mt-2">{{ $msg->created_at->diffForHumans() }}</div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
