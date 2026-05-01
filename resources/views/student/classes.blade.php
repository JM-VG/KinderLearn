{{-- ============================================================
     STUDENT — MY CLASSES PAGE
     Shows the student's current class and a dashed card
     that opens a modal to join a new class via code.
     ============================================================ --}}
@extends('layouts.student')
@section('title', 'My Classes')

@section('student-content')

{{-- ---- Page header ---- --}}
<div class="mb-8">
    <h1 class="font-fredoka text-3xl text-gray-800">Active Classes</h1>
    <p class="text-gray-400 text-sm mt-1">View your enrolled class or join a new one with a code.</p>
</div>

{{-- ============================================================
     CURRENT CLASS CARD
     Shown only when the student has already joined a class.
     ============================================================ --}}
@if($section)

<div class="mb-8">
    {{-- Section label --}}
    <p class="text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-3">
        Your Current Class
    </p>

    {{-- Class info card --}}
    <div class="bg-white rounded-2xl p-5 flex items-center gap-4"
         style="border: 1px solid #f0f0f0; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">

        {{-- Coloured icon box --}}
        <div class="w-14 h-14 rounded-xl flex items-center justify-center flex-shrink-0"
             style="background: linear-gradient(135deg, #fff0ed, #ffe4d6);">
            <i class="ri-school-line" style="font-size: 1.7rem; color: #FF6B6B;"></i>
        </div>

        {{-- Class name / description / teacher --}}
        <div class="flex-1 min-w-0">
            <p class="font-extrabold text-gray-800 text-lg leading-tight truncate">
                {{ $section->name }}
            </p>
            @if($section->description)
                <p class="text-gray-400 text-sm mt-0.5 truncate">{{ $section->description }}</p>
            @endif
            @if($section->teacher)
                <div class="flex items-center gap-1 text-xs text-gray-400 mt-1.5">
                    <i class="ri-user-star-line"></i>
                    <span>{{ $section->teacher->name }}</span>
                </div>
            @endif
        </div>

        {{-- Active badge --}}
        <span class="flex-shrink-0 px-3 py-1 rounded-full text-xs font-bold"
              style="background: #dcfce7; color: #16a34a;">
            Active
        </span>
    </div>
</div>

@else

{{-- Empty state when no class is joined yet --}}
<div class="bg-white rounded-2xl p-8 text-center mb-8"
     style="border: 1px solid #f0f0f0; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
    <div class="w-16 h-16 rounded-2xl mx-auto flex items-center justify-center mb-3"
         style="background: #f5f7fb;">
        <i class="ri-inbox-line" style="font-size: 2rem; color: #c0c0c0;"></i>
    </div>
    <p class="font-bold text-gray-500 text-lg">No class yet</p>
    <p class="text-gray-400 text-sm mt-1">Ask your teacher for a join code, then tap the card below.</p>
</div>

@endif

{{-- ============================================================
     JOIN A CLASS — DASHED CARD
     Clicking anywhere on the card opens the join modal.
     ============================================================ --}}
<div class="mb-2">
    <p class="text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-3">
        Join a Class
    </p>

    {{-- The dashed card (acts as a button) --}}
    <button onclick="openJoinModal()"
            class="w-full flex flex-col items-center justify-center gap-3 py-12 rounded-2xl
                   border-2 border-dashed border-gray-300 text-gray-400 bg-white
                   hover:border-orange-400 hover:text-orange-500 hover:bg-orange-50
                   transition-all duration-200 cursor-pointer group">

        {{-- Plus icon circle --}}
        <div class="w-12 h-12 rounded-full flex items-center justify-center
                    bg-gray-100 group-hover:bg-orange-100 transition-colors duration-200">
            <i class="ri-add-line" style="font-size: 1.5rem;"></i>
        </div>

        <div>
            <p class="font-extrabold text-base">+ Join class</p>
            <p class="text-xs mt-0.5">Enter the 6-letter code your teacher gave you</p>
        </div>
    </button>
</div>

{{-- ============================================================
     JOIN CLASS MODAL
     Opens when the dashed card is clicked.
     Closes when the X button or the dark backdrop is clicked.
     ============================================================ --}}
<div id="join-modal"
     class="fixed inset-0 z-50 flex items-center justify-center hidden"
     style="background: rgba(0,0,0,0.45); backdrop-filter: blur(3px);">

    {{-- Modal card --}}
    <div class="bg-white rounded-3xl w-full max-w-sm mx-4 p-8 relative"
         style="box-shadow: 0 20px 60px rgba(0,0,0,0.2);"
         onclick="event.stopPropagation()">

        {{-- X (close) button --}}
        <button onclick="closeJoinModal()"
                class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center
                       rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition">
            <i class="ri-close-line" style="font-size: 1.2rem;"></i>
        </button>

        {{-- Modal icon + title --}}
        <div class="text-center mb-6">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4"
                 style="background: linear-gradient(135deg, #FF6B6B, #FF8E53);">
                <i class="ri-door-open-line" style="font-size: 2rem; color: white;"></i>
            </div>
            <h2 class="font-fredoka text-2xl text-gray-800">Join a Class</h2>
            <p class="text-gray-400 text-sm mt-1">Enter the 6-letter code from your teacher</p>
        </div>

        {{-- Validation error (shown if the form was submitted with an invalid code) --}}
        @error('join_code')
        <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-600
                    rounded-xl px-4 py-3 mb-4 text-sm font-semibold">
            <i class="ri-error-warning-line flex-shrink-0"></i>
            <span>{{ $message }}</span>
        </div>
        @enderror

        {{-- Join form --}}
        <form method="POST" action="{{ route('student.classes.join') }}">
            @csrf

            {{-- Code input --}}
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-600 mb-2">Class Code</label>
                <input type="text"
                       id="join_code_input"
                       name="join_code"
                       value="{{ old('join_code') }}"
                       placeholder="e.g. XK92TL"
                       maxlength="6"
                       autocomplete="off"
                       class="w-full px-5 py-4 rounded-2xl border-2 border-gray-200
                              font-extrabold text-xl uppercase text-center
                              focus:outline-none focus:border-orange-400 transition"
                       style="letter-spacing: 0.4em;" />
            </div>

            {{-- Submit button --}}
            <button type="submit"
                    class="w-full py-4 rounded-2xl font-bold text-white text-base
                           transition hover:opacity-90 active:scale-95 flex items-center justify-center gap-2"
                    style="background: linear-gradient(135deg, #FF6B6B, #FF8E53);">
                <i class="ri-door-open-line"></i> Join Class
            </button>
        </form>
    </div>
</div>

{{-- ============================================================
     MODAL JAVASCRIPT
     ============================================================ --}}
@push('scripts')
<script>
    /** Open the join-class modal and focus the input. */
    function openJoinModal() {
        document.getElementById('join-modal').classList.remove('hidden');
        setTimeout(function () {
            document.getElementById('join_code_input').focus();
        }, 80);
    }

    /** Close the join-class modal. */
    function closeJoinModal() {
        document.getElementById('join-modal').classList.add('hidden');
    }

    /* Close when clicking the dark backdrop (outside the card). */
    document.getElementById('join-modal').addEventListener('click', function (e) {
        if (e.target === this) closeJoinModal();
    });

    /* Auto-open the modal if the form was submitted with a validation error,
       so the student can see the error message without having to click again. */
    @if($errors->has('join_code'))
        document.addEventListener('DOMContentLoaded', openJoinModal);
    @endif
</script>
@endpush

@endsection
