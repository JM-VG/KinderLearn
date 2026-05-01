@extends('layouts.teacher')
@section('title', $module ? 'Edit Module' : 'Create Module')
@section('teacher-content')

<div class="mb-6">
    <a href="{{ route('teacher.modules') }}" class="text-gray-400 font-bold hover:underline">
        <i class="fa-solid fa-arrow-left mr-1"></i> Modules
    </a>
</div>

@if(session('success'))
<div class="mb-5 px-5 py-3 bg-green-50 border border-green-200 text-green-700 rounded-2xl font-semibold flex items-center gap-2">
    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="bg-red-50 border-2 border-red-200 rounded-2xl p-4 mb-5">
    @foreach($errors->all() as $e)
    <p class="text-red-600 font-semibold text-sm"><i class="fa-solid fa-triangle-exclamation mr-1"></i>{{ $e }}</p>
    @endforeach
</div>
@endif

{{-- ═══════════════════════════════════════════════════
     MODULE DETAILS FORM
═══════════════════════════════════════════════════ --}}
<div class="bg-white rounded-3xl p-8 shadow-sm mb-6">
    <h1 class="font-fredoka text-3xl text-gray-800 mb-6">
        @if($module)
            <i class="fa-solid fa-pen" style="color:#4A90D9;"></i> Edit Module
        @else
            <i class="fa-solid fa-plus" style="color:#4ECDC4;"></i> Create Module
        @endif
    </h1>

    <form method="POST"
          action="{{ $module ? route('teacher.modules.update', $module->id) : route('teacher.modules.store') }}"
          enctype="multipart/form-data"
          class="space-y-5">
        @csrf
        @if($module) @method('PUT') @endif

        {{-- Title --}}
        <div>
            <label class="block font-bold text-gray-700 mb-2">Module Title</label>
            <input type="text" name="title" value="{{ old('title', $module->title ?? '') }}" required
                   class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none"
                   placeholder="e.g., Learn the Alphabet">
        </div>

        {{-- Subject --}}
        <div>
            <label class="block font-bold text-gray-700 mb-2">Subject</label>
            <select name="subject" required class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none font-semibold">
                @foreach(['alphabet'=>'Alphabet','numbers'=>'Numbers','colors'=>'Colors','shapes'=>'Shapes','words'=>'Words'] as $val=>$label)
                <option value="{{ $val }}" {{ old('subject', $module->subject ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        {{-- Description --}}
        <div>
            <label class="block font-bold text-gray-700 mb-2">Description</label>
            <textarea name="description" rows="3"
                      class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none resize-none"
                      placeholder="What will students learn?">{{ old('description', $module->description ?? '') }}</textarea>
        </div>

        {{-- Cover image + icon + color --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Cover image upload --}}
            <div class="md:col-span-1">
                <label class="block font-bold text-gray-700 mb-2">Cover Photo <span class="font-normal text-gray-400 text-sm">(optional)</span></label>
                <div class="relative border-2 border-dashed border-gray-200 rounded-2xl p-4 flex flex-col items-center justify-center gap-2 hover:border-blue-400 transition cursor-pointer"
                     onclick="document.getElementById('cover-input').click()">
                    @if($module && $module->cover_image)
                    <img id="cover-preview" src="{{ asset('storage/' . $module->cover_image) }}"
                         class="w-full h-28 object-cover rounded-xl">
                    @else
                    <img id="cover-preview" src="" class="w-full h-28 object-cover rounded-xl hidden">
                    <div id="cover-placeholder" class="flex flex-col items-center text-gray-400">
                        <i class="fa-solid fa-image text-3xl mb-1"></i>
                        <span class="text-xs font-semibold">Click to upload</span>
                        <span class="text-xs">JPG, PNG, WebP</span>
                    </div>
                    @endif
                    <input type="file" id="cover-input" name="cover_image" accept="image/*" class="sr-only"
                           onchange="previewCover(this)">
                </div>
                @if($module && $module->cover_image)
                <p class="text-xs text-gray-400 mt-1 text-center">Click above to replace</p>
                @endif
            </div>

            <div class="md:col-span-2 grid grid-cols-2 gap-4 content-start">
                {{-- Icon (emoji) --}}
                <div>
                    <label class="block font-bold text-gray-700 mb-2">Icon (emoji)</label>
                    <input type="text" name="icon" value="{{ old('icon', $module->icon ?? '📚') }}"
                           class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none text-2xl text-center"
                           maxlength="4">
                    <p class="text-xs text-gray-400 mt-1">Used as fallback if no cover photo</p>
                </div>

                {{-- Card color --}}
                <div>
                    <label class="block font-bold text-gray-700 mb-2">Card Color</label>
                    <input type="color" name="color" value="{{ old('color', $module->color ?? '#FF6B6B') }}"
                           class="w-full h-[50px] rounded-2xl border-2 border-gray-200 cursor-pointer">
                </div>

                {{-- Active toggle --}}
                @if($module)
                <div class="col-span-2 flex items-center gap-3 pt-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                           {{ old('is_active', $module->is_active) ? 'checked' : '' }}
                           class="w-5 h-5 accent-blue-500">
                    <label for="is_active" class="font-semibold text-gray-700">Show this module to students</label>
                </div>
                @endif
            </div>
        </div>

        <button type="submit"
                class="btn-kid text-white w-full justify-center text-lg"
                style="background: linear-gradient(135deg, #2C3E7A, #4A90D9)">
            @if($module)
                <i class="fa-solid fa-floppy-disk"></i> Save Changes
            @else
                <i class="fa-solid fa-circle-check"></i> Create Module
            @endif
        </button>
    </form>
</div>

{{-- ═══════════════════════════════════════════════════
     LEVELS (only on edit)
═══════════════════════════════════════════════════ --}}
@if($module)

<div class="mb-4 flex items-center justify-between">
    <h2 class="font-fredoka text-2xl text-gray-800">
        <i class="fa-solid fa-layer-group" style="color:#4ECDC4;"></i> Levels
    </h2>
    <button onclick="document.getElementById('add-level-modal').classList.remove('hidden')"
            class="btn-kid text-white text-sm"
            style="background: linear-gradient(135deg, #4ECDC4, #45B7D1)">
        <i class="fa-solid fa-plus"></i> Add Level
    </button>
</div>

@forelse($levelNumbers as $levelNum)
@php $levelActivities = $activitiesByLevel[$levelNum]; @endphp

<div class="bg-white rounded-3xl shadow-sm mb-4 overflow-hidden">

    {{-- Level header --}}
    <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100">
        <div class="w-9 h-9 rounded-full flex items-center justify-center font-fredoka text-white text-lg"
             style="background: linear-gradient(135deg, {{ $module->color }}, {{ $module->color }}bb);">
            {{ $levelNum }}
        </div>
        <div class="font-fredoka text-xl text-gray-800 flex-1">Level {{ $levelNum }}</div>
        <span class="text-xs text-gray-400 font-semibold">{{ $levelActivities->count() }} {{ Str::plural('activity', $levelActivities->count()) }}</span>
    </div>

    {{-- Level schedule --}}
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
        <form method="POST" action="{{ route('teacher.modules.levels.schedule', [$module->id, $levelNum]) }}"
              class="flex flex-wrap items-end gap-4">
            @csrf
            @php
                $firstAct = $levelActivities->first();
            @endphp
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-bold text-gray-500 mb-1">
                    <i class="fa-solid fa-clock text-blue-400"></i> Opens At
                    <span class="font-normal text-gray-400">(students can start)</span>
                </label>
                <input type="datetime-local" name="opens_at"
                       value="{{ $firstAct->opens_at ? $firstAct->opens_at->format('Y-m-d\TH:i') : '' }}"
                       class="w-full px-3 py-2 rounded-xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none text-sm">
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-bold text-gray-500 mb-1">
                    <i class="fa-solid fa-hourglass-half text-red-400"></i> Deadline
                    <span class="font-normal text-gray-400">(auto-scores 0 after this)</span>
                </label>
                <input type="datetime-local" name="deadline"
                       value="{{ $firstAct->deadline ? $firstAct->deadline->format('Y-m-d\TH:i') : '' }}"
                       class="w-full px-3 py-2 rounded-xl border-2 border-gray-200 focus:border-red-300 focus:outline-none text-sm">
            </div>
            <button type="submit"
                    class="px-5 py-2 rounded-xl font-bold text-white text-sm transition hover:opacity-90"
                    style="background: linear-gradient(135deg, #2C3E7A, #4A90D9)">
                <i class="fa-solid fa-floppy-disk"></i> Save Schedule
            </button>
        </form>
    </div>

    {{-- Activities in this level --}}
    <div class="divide-y divide-gray-100">
        @foreach($levelActivities as $activity)
        <div class="flex items-center gap-4 px-6 py-4">
            @php
                $typeColors = ['video'=>'#FF6B6B','quiz'=>'#BB8FCE','matching'=>'#4ECDC4','drag_drop'=>'#45B7D1','coloring'=>'#FF8E53','audio'=>'#52BE80'];
                $typeIcons  = ['video'=>'fa-film','quiz'=>'fa-brain','matching'=>'fa-shuffle','drag_drop'=>'fa-hand','coloring'=>'fa-palette','audio'=>'fa-volume-high'];
                $tColor = $typeColors[$activity->type] ?? '#9ca3af';
                $tIcon  = $typeIcons[$activity->type] ?? 'fa-file-pen';
            @endphp
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                 style="background: {{ $tColor }}22;">
                <i class="fa-solid {{ $tIcon }}" style="color:{{ $tColor }};"></i>
            </div>

            <div class="flex-1 min-w-0">
                <div class="font-bold text-gray-800 text-sm truncate">{{ $activity->title }}</div>
                <div class="text-xs text-gray-400 capitalize mt-0.5">
                    {{ str_replace('_',' ',$activity->type) }} &bull; {{ $activity->stars_reward }}
                    <i class="fa-solid fa-star" style="color:#FFD700; font-size:0.7rem;"></i>
                    @if(!$activity->is_active)
                        &bull; <span class="text-orange-400 font-semibold">Hidden</span>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2 flex-shrink-0">
                @if($activity->type === 'quiz')
                <a href="{{ route('teacher.activities.questions', $activity->id) }}"
                   class="px-3 py-1.5 rounded-xl font-bold text-white text-xs transition hover:opacity-90"
                   style="background: linear-gradient(135deg, #6366f1, #8b5cf6)">
                    <i class="fa-solid fa-pen-to-square mr-1"></i> Edit Questions
                </a>
                @elseif($activity->type === 'tracing')
                <a href="{{ route('teacher.activities.grade', $activity->id) }}"
                   class="px-3 py-1.5 rounded-xl font-bold text-white text-xs transition hover:opacity-90"
                   style="background: linear-gradient(135deg, #8b5cf6, #ec4899)">
                    <i class="fa-solid fa-pen-nib mr-1"></i> Grade Submissions
                </a>
                @endif
                <a href="{{ route('teacher.activities.edit', $activity->id) }}"
                   class="w-8 h-8 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-500 hover:bg-blue-50 transition">
                    <i class="fa-solid fa-gear text-sm"></i>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@empty
<div class="bg-white rounded-3xl p-8 text-center text-gray-400 shadow-sm">
    <i class="fa-solid fa-layer-group text-4xl mb-3 opacity-30"></i>
    <p class="font-semibold">No levels yet. Click <strong>Add Level</strong> to get started.</p>
</div>
@endforelse

{{-- Add Level Modal (2-step) --}}
<div id="add-level-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl w-full mx-4 p-7" style="max-width:420px;">

        {{-- Step 1: choose activity type --}}
        <div id="al-step1">
            <h3 class="font-fredoka text-2xl text-gray-800 mb-1">
                <i class="fa-solid fa-layer-group" style="color:#4ECDC4;"></i> Add New Level
            </h3>
            <p class="text-sm text-gray-400 font-semibold mb-5">What kind of activity is this level?</p>

            <div class="grid grid-cols-2 gap-3 mb-5">
                @foreach([
                    ['quiz',    'fa-brain',      '#8b5cf6', 'Quiz'],
                    ['tracing', 'fa-pen-nib',    '#ec4899', 'Tracing'],
                    ['video',   'fa-film',        '#ef4444', 'Video'],
                    ['audio',   'fa-volume-high', '#3b82f6', 'Audio'],
                    ['matching','fa-shuffle',     '#06b6d4', 'Matching'],
                    ['coloring','fa-palette',     '#f59e0b', 'Coloring'],
                ] as [$val, $ico, $col, $lbl])
                <button type="button" onclick="alSelectType('{{ $val }}')"
                        class="al-type-btn flex flex-col items-center gap-2 p-4 rounded-2xl border-2 border-gray-200 hover:border-[{{ $col }}] hover:bg-gray-50 transition font-bold text-gray-600 text-sm"
                        data-type="{{ $val }}" data-color="{{ $col }}">
                    <i class="fa-solid {{ $ico }} text-2xl" style="color:{{ $col }};"></i>
                    {{ $lbl }}
                </button>
                @endforeach
            </div>

            <button type="button"
                    onclick="document.getElementById('add-level-modal').classList.add('hidden')"
                    class="w-full py-2.5 rounded-2xl font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 transition">
                Cancel
            </button>
        </div>

        {{-- Step 2: fill in details --}}
        <div id="al-step2" class="hidden">
            <button type="button" onclick="alBack()"
                    class="flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-gray-700 mb-4 transition">
                <i class="fa-solid fa-arrow-left"></i> Back
            </button>

            <h3 class="font-fredoka text-2xl text-gray-800 mb-1">
                <i id="al-step2-icon" class="fa-solid fa-brain" style="color:#8b5cf6;"></i>
                <span id="al-step2-label">Quiz</span> Level
            </h3>
            <p class="text-sm text-gray-400 font-semibold mb-5">Fill in the details below.</p>

            <form method="POST" action="{{ route('teacher.modules.levels.add', $module->id) }}"
                  enctype="multipart/form-data" class="space-y-4" id="al-form">
                @csrf
                <input type="hidden" name="type" id="al-type-input" value="quiz">

                {{-- Title --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Activity Title</label>
                    <input type="text" name="title" required
                           class="w-full px-4 py-2.5 rounded-2xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none text-sm"
                           placeholder="e.g., Shapes Quiz Level 2">
                </div>

                {{-- Class selector --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Assign to Class <span class="text-red-500">*</span></label>
                    @if(isset($sections) && $sections->count())
                    <select name="section_id" required
                            class="w-full px-4 py-2.5 rounded-2xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none text-sm font-semibold">
                        <option value="">— Select a class —</option>
                        @foreach($sections as $sec)
                        <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                        @endforeach
                    </select>
                    @else
                    <p class="text-xs text-yellow-700 bg-yellow-50 border border-yellow-200 rounded-xl px-3 py-2 font-semibold">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        No classes yet. <a href="{{ route('teacher.classes') }}" class="underline">Create one first.</a>
                    </p>
                    @endif
                </div>

                {{-- Tracing: JPG file upload --}}
                <div id="al-tracing-field" class="hidden">
                    <label class="block text-sm font-bold text-gray-700 mb-1">
                        Tracing Template <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-start gap-2 text-xs font-semibold text-yellow-700 bg-yellow-50 border border-yellow-200 rounded-xl px-3 py-2 mb-2">
                        <i class="fa-solid fa-triangle-exclamation flex-shrink-0 mt-0.5"></i>
                        Only <strong>JPG</strong> files. Use a clear image at least 800×600 px.
                    </div>
                    <input type="file" name="file" id="al-file-input" accept=".jpg,.jpeg"
                           class="w-full text-sm px-3 py-2 rounded-2xl border-2 border-gray-200"
                           onchange="alPreviewFile(this)">
                    <img id="al-file-preview" src="" class="hidden mt-2 rounded-xl max-h-32 object-contain border border-gray-200 w-full">
                </div>

                {{-- Video: embed URL --}}
                <div id="al-video-field" class="hidden">
                    <label class="block text-sm font-bold text-gray-700 mb-1">YouTube Embed URL</label>
                    <input type="text" name="video_url"
                           class="w-full px-4 py-2.5 rounded-2xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none text-sm"
                           placeholder="https://www.youtube.com/embed/VIDEO_ID">
                </div>

                <div class="flex gap-3 pt-1">
                    <button type="button"
                            onclick="document.getElementById('add-level-modal').classList.add('hidden')"
                            class="flex-1 py-2.5 rounded-2xl font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 transition text-sm">
                        Cancel
                    </button>
                    <button type="submit" id="al-submit-btn"
                            class="flex-1 py-2.5 rounded-2xl font-bold text-white transition hover:opacity-90 text-sm"
                            style="background: linear-gradient(135deg, #4ECDC4, #45B7D1)">
                        <i class="fa-solid fa-plus"></i> Add Level
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endif

<script>
function previewCover(input) {
    if (!input.files || !input.files[0]) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        var preview = document.getElementById('cover-preview');
        var placeholder = document.getElementById('cover-placeholder');
        preview.src = e.target.result;
        preview.classList.remove('hidden');
        if (placeholder) placeholder.classList.add('hidden');
    };
    reader.readAsDataURL(input.files[0]);
}

// ── Add Level modal: 2-step flow ────────────────────────────────
var AL_TYPES = {
    quiz:     { icon:'fa-brain',       color:'#8b5cf6', label:'Quiz'    },
    tracing:  { icon:'fa-pen-nib',     color:'#ec4899', label:'Tracing' },
    video:    { icon:'fa-film',         color:'#ef4444', label:'Video'   },
    audio:    { icon:'fa-volume-high',  color:'#3b82f6', label:'Audio'   },
    matching: { icon:'fa-shuffle',      color:'#06b6d4', label:'Matching'},
    coloring: { icon:'fa-palette',      color:'#f59e0b', label:'Coloring'},
};

function alSelectType(type) {
    var t = AL_TYPES[type];
    document.getElementById('al-type-input').value = type;

    // Update step-2 header
    var ico = document.getElementById('al-step2-icon');
    ico.className = 'fa-solid ' + t.icon;
    ico.style.color = t.color;
    document.getElementById('al-step2-label').textContent = t.label;

    // Show/hide conditional fields
    document.getElementById('al-tracing-field').classList.toggle('hidden', type !== 'tracing');
    document.getElementById('al-video-field').classList.toggle('hidden', type !== 'video');

    // Make tracing file required only for tracing
    var fileInput = document.getElementById('al-file-input');
    if (fileInput) fileInput.required = (type === 'tracing');

    // Colour the submit button
    document.getElementById('al-submit-btn').style.background =
        'linear-gradient(135deg, ' + t.color + ', ' + t.color + 'cc)';

    // Switch steps
    document.getElementById('al-step1').classList.add('hidden');
    document.getElementById('al-step2').classList.remove('hidden');
}

function alBack() {
    document.getElementById('al-step2').classList.add('hidden');
    document.getElementById('al-step1').classList.remove('hidden');
}

// Reset modal when opened
document.getElementById('add-level-modal').addEventListener('click', function(e){
    if (e.target === this) {
        this.classList.add('hidden');
        alBack();
    }
});

function alPreviewFile(input) {
    if (!input.files || !input.files[0]) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        var img = document.getElementById('al-file-preview');
        img.src = e.target.result;
        img.classList.remove('hidden');
    };
    reader.readAsDataURL(input.files[0]);
}

// Re-reset step when modal is opened via the "Add Level" button
document.querySelectorAll('[onclick*="add-level-modal"]').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.getElementById('al-step1').classList.remove('hidden');
        document.getElementById('al-step2').classList.add('hidden');
        var f = document.getElementById('al-form');
        if (f) f.reset();
        var prev = document.getElementById('al-file-preview');
        if (prev) { prev.src = ''; prev.classList.add('hidden'); }
    });
});
</script>
@endsection
