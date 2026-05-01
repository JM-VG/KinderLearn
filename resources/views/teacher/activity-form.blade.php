@extends('layouts.teacher')
@section('title', $activity ? 'Edit Activity' : 'Create Activity')
@section('teacher-content')

<div class="mb-6">
    <a href="{{ route('teacher.activities') }}" class="text-gray-400 font-bold hover:underline">← Activities</a>
</div>

<div class="max-w-2xl mx-auto bg-white rounded-3xl p-8 shadow-sm">
    <h1 class="font-fredoka text-3xl text-gray-800 mb-6">
        @if($activity)
            <i class="fa-solid fa-pen" style="color:#FF6B6B;"></i> Edit Activity
        @else
            <i class="fa-solid fa-plus" style="color:#FF6B6B;"></i> Create Activity
        @endif
    </h1>

    @if($errors->any())
    <div class="bg-red-50 border-2 border-red-200 rounded-2xl p-4 mb-5">
        @foreach($errors->all() as $e)
        <p class="text-red-600 font-semibold"><i class="fa-solid fa-triangle-exclamation"></i> {{ $e }}</p>
        @endforeach
    </div>
    @endif

    <form method="POST"
          action="{{ $activity ? route('teacher.activities.update', $activity->id) : route('teacher.activities.store') }}"
          enctype="multipart/form-data"
          class="space-y-5">
        @csrf
        @if($activity) @method('PUT') @endif

        {{-- Title --}}
        <div>
            <label class="block font-bold text-gray-700 mb-2">Activity Title</label>
            <input type="text" name="title" value="{{ old('title', $activity->title ?? '') }}" required
                   class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none"
                   placeholder="e.g., Alphabet Quiz">
        </div>

        {{-- Module --}}
        <div>
            <label class="block font-bold text-gray-700 mb-2">Module</label>
            <select name="module_id" required class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none font-semibold">
                @foreach($modules as $m)
                <option value="{{ $m->id }}" {{ old('module_id', $activity->module_id ?? '') == $m->id ? 'selected' : '' }}>
                    {{ $m->icon }} {{ $m->title }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- Class --}}
        <div>
            <label class="block font-bold text-gray-700 mb-2">Assign to Class <span class="text-red-500">*</span></label>
            @if(isset($sections) && $sections->count())
            <select name="section_id" required class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none font-semibold">
                <option value="">— Select a class —</option>
                @foreach($sections as $sec)
                <option value="{{ $sec->id }}" {{ old('section_id', $activity->section_id ?? '') == $sec->id ? 'selected' : '' }}>
                    {{ $sec->name }}
                </option>
                @endforeach
            </select>
            @else
            <div class="px-4 py-3 rounded-2xl border-2 border-yellow-200 bg-yellow-50 text-yellow-700 text-sm font-semibold">
                <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                You have no classes yet. <a href="{{ route('teacher.classes') }}" class="underline">Create a class first</a>.
            </div>
            @endif
        </div>

        {{-- Type --}}
        <div>
            <label class="block font-bold text-gray-700 mb-2">Activity Type</label>
            <select name="type" required id="type-select"
                    class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none font-semibold">
                @foreach(['video' => 'Video', 'quiz' => 'Quiz', 'matching' => 'Matching', 'drag_drop' => 'Drag & Drop', 'coloring' => 'Coloring/Upload', 'audio' => 'Audio', 'tracing' => 'Tracing / Drawing'] as $val => $label)
                <option value="{{ $val }}" {{ old('type', $activity->type ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        {{-- Stars reward --}}
        <div>
            <label class="block font-bold text-gray-700 mb-2">Stars Reward (1-5)</label>
            <input type="number" name="stars_reward" min="1" max="5"
                   value="{{ old('stars_reward', $activity->stars_reward ?? 3) }}"
                   class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none">
        </div>

        {{-- Opens At --}}
        <div>
            <label class="block font-bold text-gray-700 mb-1">Opens At <span class="font-normal text-gray-400">(optional — leave blank to open immediately)</span></label>
            <input type="datetime-local" name="opens_at"
                   value="{{ old('opens_at', isset($activity->opens_at) ? $activity->opens_at->format('Y-m-d\TH:i') : '') }}"
                   class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none">
            <p class="text-xs text-gray-400 mt-1">Students cannot access the activity before this time.</p>
        </div>

        {{-- Deadline --}}
        <div>
            <label class="block font-bold text-gray-700 mb-1">Deadline <span class="font-normal text-gray-400">(optional)</span></label>
            <input type="datetime-local" name="deadline"
                   value="{{ old('deadline', isset($activity->deadline) ? $activity->deadline->format('Y-m-d\TH:i') : '') }}"
                   class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none">
        </div>

        {{-- Video URL (shown for video type) --}}
        <div id="video-field" class="hidden">
            <label class="block font-bold text-gray-700 mb-2">YouTube Embed URL</label>
            <input type="text" name="content" placeholder="https://www.youtube.com/embed/VIDEO_ID"
                   class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none">
            <p class="text-xs text-gray-400 mt-1">Use the embed format: https://www.youtube.com/embed/VIDEO_ID</p>
        </div>

        {{-- File upload (for coloring/audio types) --}}
        <div id="file-field" class="hidden">
            <label class="block font-bold text-gray-700 mb-2">Upload File (PDF, MP3, or image)</label>
            <input type="file" name="file" accept=".pdf,.mp3,.jpg,.jpeg,.png"
                   class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200">
        </div>

        {{-- Tracing template upload --}}
        <div id="tracing-field" class="hidden">
            <label class="block font-bold text-gray-700 mb-2">
                Tracing Template <span class="text-red-500">*</span>
            </label>
            <div class="flex items-start gap-2 text-xs font-semibold text-yellow-700 bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-2.5 mb-3">
                <i class="fa-solid fa-triangle-exclamation mt-0.5 flex-shrink-0"></i>
                <span>Only <strong>JPG</strong> files are accepted. Use a large, clear image (at least 800&times;600 px) so students can trace easily.</span>
            </div>
            <input type="file" name="file" id="tracing-file-input" accept=".jpg,.jpeg"
                   class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200"
                   onchange="previewTemplate(this)">
            @if($activity && $activity->type === 'tracing' && $activity->file_path)
            <div class="mt-3" id="tracing-preview-wrap">
                <p class="text-xs font-bold text-gray-500 mb-1">Current Template:</p>
                <img src="{{ asset('storage/' . $activity->file_path) }}" id="tracing-preview"
                     class="rounded-xl max-h-40 object-contain border border-gray-200">
            </div>
            @else
            <div class="mt-3 hidden" id="tracing-preview-wrap">
                <p class="text-xs font-bold text-gray-500 mb-1">Preview:</p>
                <img src="" id="tracing-preview" class="rounded-xl max-h-40 object-contain border border-gray-200">
            </div>
            @endif
        </div>

        {{-- Active toggle (edit only) --}}
        @if($activity)
        <div class="flex items-center gap-3 pt-1">
            <input type="checkbox" name="is_active" id="is_active" value="1"
                   {{ old('is_active', $activity->is_active) ? 'checked' : '' }}
                   class="w-5 h-5 accent-blue-500">
            <label for="is_active" class="font-semibold text-gray-700">Show to students (active)</label>
        </div>
        @endif

        <button type="submit"
                class="btn-kid text-white w-full justify-center text-lg"
                style="background: linear-gradient(135deg, #FF6B6B, #FF8E53)">
            @if($activity)
                <i class="fa-solid fa-floppy-disk"></i> Save Changes
            @else
                <i class="fa-solid fa-circle-check"></i> Create Activity
            @endif
        </button>
    </form>
</div>

<script>
    const typeSelect    = document.getElementById('type-select');
    const videoField    = document.getElementById('video-field');
    const fileField     = document.getElementById('file-field');
    const tracingField  = document.getElementById('tracing-field');

    function updateFields() {
        const val = typeSelect.value;
        videoField.classList.toggle('hidden', val !== 'video');
        fileField.classList.toggle('hidden', !['coloring', 'audio'].includes(val));
        tracingField.classList.toggle('hidden', val !== 'tracing');
    }

    function previewTemplate(input) {
        if (!input.files || !input.files[0]) return;
        var reader = new FileReader();
        reader.onload = function (e) {
            var wrap = document.getElementById('tracing-preview-wrap');
            var img  = document.getElementById('tracing-preview');
            img.src  = e.target.result;
            wrap.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }

    typeSelect.addEventListener('change', updateFields);
    updateFields();
</script>
@endsection
