@extends('layouts.teacher')
@section('title', 'Announcements')
@section('teacher-content')
<h1 class="font-fredoka text-4xl text-gray-800 mb-6">Announcements</h1>

@if(session('success'))
<div class="mb-5 px-5 py-3 bg-green-50 border border-green-200 text-green-700 rounded-2xl font-semibold flex items-center gap-2">
    <i class="ri-checkbox-circle-line text-lg"></i> {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Post form --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm">
        <h2 class="font-fredoka text-2xl text-gray-800 mb-5">Post Announcement</h2>
        <form method="POST" action="{{ route('teacher.announcements.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block font-bold text-gray-700 mb-2">Title</label>
                <input type="text" name="title" required
                       class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-sky-400 focus:outline-none"
                       placeholder="Announcement title">
            </div>
            <div>
                <label class="block font-bold text-gray-700 mb-2">Message</label>
                <textarea name="body" rows="4" required
                          class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-sky-400 focus:outline-none resize-none"
                          placeholder="Write your announcement..."></textarea>
            </div>
            <div>
                <label class="block font-bold text-gray-700 mb-2">Send to Class <span class="text-gray-400 font-normal">(optional)</span></label>
                <select name="section_id" class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-sky-400 focus:outline-none">
                    <option value="">All students</option>
                    @foreach($sections as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="pinned" class="w-5 h-5 accent-sky-500">
                <span class="font-semibold text-gray-700"><i class="fa-solid fa-thumbtack"></i> Pin this announcement</span>
            </label>
            <button type="submit" class="btn-kid text-white w-full justify-center"
                    style="background: linear-gradient(135deg, #0ea5e9, #6366f1)">
                <i class="ri-megaphone-line"></i> Post Announcement
            </button>
        </form>
    </div>

    {{-- Posted announcements --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm">
        <h2 class="font-fredoka text-2xl text-gray-800 mb-5">Posted Announcements</h2>
        @forelse($announcements as $ann)
        <div class="p-4 rounded-2xl mb-3 border-2 {{ $ann->pinned ? 'bg-sky-50 border-sky-200' : 'bg-gray-50 border-transparent' }}">
            @if($ann->pinned)
            <span class="text-xs font-bold px-2 py-0.5 rounded-full"
                  style="background:rgba(14,165,233,0.12);color:#0ea5e9;"><i class="fa-solid fa-thumbtack"></i> Pinned</span>
            @endif
            <div class="font-bold text-gray-800 mt-1">{{ $ann->title }}</div>
            <p class="text-sm text-gray-500 mt-1">{{ Str::limit($ann->body, 100) }}</p>
            <div class="flex items-center justify-between mt-3">
                <span class="text-xs text-gray-400">{{ $ann->created_at->diffForHumans() }}</span>
                <div class="flex items-center gap-2">
                    <button onclick="openEditModal({{ $ann->id }}, {{ json_encode($ann->title) }}, {{ json_encode($ann->body) }}, {{ $ann->pinned ? 'true' : 'false' }})"
                            class="text-xs font-bold text-sky-500 hover:text-sky-700 transition">
                        <i class="ri-edit-line"></i> Edit
                    </button>
                    <form method="POST" action="{{ route('teacher.announcements.destroy', $ann->id) }}"
                          onsubmit="return confirm('Delete this announcement?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs font-bold text-red-400 hover:text-red-600 transition">
                            <i class="ri-delete-bin-line"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-10">
            <i class="ri-megaphone-line text-4xl text-gray-200"></i>
            <p class="text-gray-400 font-semibold mt-2">No announcements yet.</p>
        </div>
        @endforelse
    </div>
</div>

{{-- Edit modal --}}
<div id="edit-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center"
     style="background:rgba(0,0,0,0.45);">
    <div class="bg-white rounded-3xl p-8 shadow-2xl w-full max-w-lg mx-4">
        <h3 class="font-fredoka text-2xl text-gray-800 mb-5">Edit Announcement</h3>
        <form id="edit-form" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block font-bold text-gray-700 mb-2">Title</label>
                <input type="text" name="title" id="edit-title" required
                       class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-sky-400 focus:outline-none">
            </div>
            <div>
                <label class="block font-bold text-gray-700 mb-2">Message</label>
                <textarea name="body" id="edit-body" rows="4" required
                          class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-sky-400 focus:outline-none resize-none"></textarea>
            </div>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="pinned" id="edit-pinned" class="w-5 h-5 accent-sky-500">
                <span class="font-semibold text-gray-700"><i class="fa-solid fa-thumbtack"></i> Pin this announcement</span>
            </label>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeEditModal()"
                        class="flex-1 py-3 rounded-2xl border-2 border-gray-200 font-bold text-gray-600 hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 py-3 rounded-2xl font-bold text-white transition"
                        style="background:linear-gradient(135deg,#0ea5e9,#6366f1);">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(id, title, body, pinned) {
    document.getElementById('edit-form').action = '/teacher/announcements/' + id;
    document.getElementById('edit-title').value = title;
    document.getElementById('edit-body').value = body;
    document.getElementById('edit-pinned').checked = pinned;
    document.getElementById('edit-modal').classList.remove('hidden');
}
function closeEditModal() {
    document.getElementById('edit-modal').classList.add('hidden');
}
document.getElementById('edit-modal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});
</script>
@endsection
