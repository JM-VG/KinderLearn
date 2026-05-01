@extends('layouts.admin')
@section('title', 'Support Inbox')
@section('admin-content')

<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="font-fredoka text-4xl text-gray-800">
            Support Inbox <i class="ri-customer-service-2-line" style="color:#6366f1;"></i>
        </h1>
        <p class="text-gray-500 mt-1">
            @if($unreadCount > 0)
                <span class="font-bold text-red-500">{{ $unreadCount }} unread</span> message{{ $unreadCount !== 1 ? 's' : '' }}
            @else
                All messages read
            @endif
        </p>
    </div>
</div>

@if(session('success'))
<div class="mb-5 px-5 py-3 bg-green-50 border border-green-200 text-green-700 rounded-2xl font-semibold flex items-center gap-2">
    <i class="ri-checkbox-circle-line"></i> {{ session('success') }}
</div>
@endif

@if($messages->isEmpty())
<div class="bg-white rounded-3xl p-12 text-center shadow-sm">
    <div class="text-6xl mb-4 opacity-20"><i class="ri-customer-service-2-line"></i></div>
    <p class="font-fredoka text-2xl text-gray-400">No support messages yet</p>
    <p class="text-gray-400 text-sm mt-1">When teachers or students send feedback, it will appear here.</p>
</div>
@else
<div class="bg-white rounded-3xl shadow-sm overflow-hidden">
    <div class="divide-y divide-gray-100">
        @foreach($messages as $msg)
        @php $isUnread = is_null($msg->read_at); @endphp
        <div class="flex items-start gap-4 px-6 py-4 {{ $isUnread ? 'bg-blue-50' : '' }} hover:bg-gray-50 transition">

            {{-- Avatar --}}
            <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center font-bold text-white text-sm"
                 style="background: linear-gradient(135deg, {{ $msg->sender->role === 'teacher' ? '#0ea5e9,#6366f1' : '#FF6B6B,#FF8E53' }});">
                {{ strtoupper(substr($msg->sender->name, 0, 1)) }}
            </div>

            {{-- Content --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-0.5">
                    <span class="font-bold text-sm text-gray-800">{{ $msg->sender->name }}</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold capitalize"
                          style="background: {{ $msg->sender->role === 'teacher' ? '#e0f2fe' : '#fff7ed' }};
                                 color: {{ $msg->sender->role === 'teacher' ? '#0369a1' : '#c2410c' }};">
                        {{ $msg->sender->role }}
                    </span>
                    @if($isUnread)
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold" style="background:#fef3c7;color:#92400e;">New</span>
                    @endif
                </div>
                <p class="text-sm font-semibold text-gray-700 truncate">{{ $msg->subject }}</p>
                <p class="text-xs text-gray-400 truncate mt-0.5">{{ \Str::limit($msg->body, 80) }}</p>
            </div>

            {{-- Time + actions --}}
            <div class="flex-shrink-0 flex flex-col items-end gap-2">
                <span class="text-xs text-gray-400">{{ $msg->created_at->diffForHumans() }}</span>
                <div class="flex items-center gap-1">
                    <a href="{{ route('admin.support.view', $msg->id) }}"
                       class="px-3 py-1 rounded-xl text-xs font-bold text-white transition hover:opacity-90"
                       style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                        View
                    </a>
                    <form method="POST" action="{{ route('admin.support.delete', $msg->id) }}"
                          onsubmit="return confirm('Delete this message?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="w-7 h-7 rounded-xl flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 transition">
                            <i class="ri-delete-bin-line text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<div class="mt-4">{{ $messages->links() }}</div>
@endif

@endsection
