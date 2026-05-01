@extends('layouts.teacher')
@section('title', 'Grade Tracing — ' . $activity->title)

@push('styles')
<style>
.star-btn {
    font-size: 2.5rem;
    color: #d1d5db;
    cursor: pointer;
    transition: color .15s, transform .15s;
    background: none;
    border: none;
    padding: 0 4px;
    line-height: 1;
}
.star-btn:hover,
.star-btn.active { color: #FBBF24; transform: scale(1.15); }
.nav-btn {
    display: inline-flex; align-items: center; gap: .5rem;
    padding: .6rem 1.5rem; border-radius: 1rem; font-weight: 700; font-size: .9rem;
    transition: opacity .15s;
}
.nav-btn:disabled { opacity: .35; cursor: not-allowed; }
</style>
@endpush

@section('teacher-content')

{{-- Header --}}
<div class="flex items-center gap-4 mb-6">
    <a href="{{ route('teacher.activities') }}"
       class="w-10 h-10 rounded-full flex items-center justify-center bg-white shadow-sm hover:shadow transition text-gray-500">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
    <div class="flex-1 min-w-0">
        <h1 class="font-fredoka text-3xl text-gray-800 leading-tight">Grade Tracing</h1>
        <p class="text-sm text-gray-400 font-semibold mt-0.5 truncate">{{ $activity->title }}</p>
    </div>
    <div class="text-sm font-bold text-gray-400 flex-shrink-0">
        {{ $currentIndex + 1 }} / {{ $submissions->count() }} submissions
    </div>
</div>

@if(session('success'))
<div class="mb-4 px-5 py-3 bg-green-50 border border-green-200 text-green-700 rounded-2xl font-semibold flex items-center gap-2">
    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
@endif

{{-- Student info bar --}}
<div class="bg-white rounded-2xl px-5 py-3 shadow-sm mb-5 flex items-center gap-4">
    <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
        <i class="fa-solid fa-user text-purple-500"></i>
    </div>
    <div class="flex-1 min-w-0">
        <div class="font-bold text-gray-800 truncate">{{ $submission->user->name ?? 'Student' }}</div>
        <div class="text-xs text-gray-400">Submitted {{ $submission->completed_at?->format('M d, Y g:i A') ?? 'recently' }}</div>
    </div>
    @if($submission->stars_earned > 0)
    <div class="flex items-center gap-1 text-sm font-bold text-yellow-500">
        <i class="fa-solid fa-star"></i> {{ $submission->stars_earned }} star{{ $submission->stars_earned !== 1 ? 's' : '' }} (graded)
    </div>
    @else
    <span class="text-xs font-bold text-orange-500 bg-orange-50 border border-orange-200 rounded-full px-3 py-1">
        <i class="fa-solid fa-clock"></i> Needs grading
    </span>
    @endif
</div>

{{-- Images side by side --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
    {{-- Template --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-4 py-2.5 border-b border-gray-100 flex items-center gap-2">
            <i class="fa-solid fa-image text-gray-400"></i>
            <span class="font-bold text-gray-600 text-sm">Original Template</span>
        </div>
        <div class="p-3">
            <img src="{{ asset('storage/' . $activity->file_path) }}"
                 alt="Template"
                 class="w-full rounded-xl object-contain max-h-80">
        </div>
    </div>

    {{-- Student submission --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-4 py-2.5 border-b border-gray-100 flex items-center gap-2">
            <i class="fa-solid fa-pen-nib text-purple-400"></i>
            <span class="font-bold text-gray-600 text-sm">Student's Tracing</span>
        </div>
        <div class="p-3">
            @if($submission->file_path)
            <img src="{{ asset('storage/' . $submission->file_path) }}"
                 alt="Submission"
                 class="w-full rounded-xl object-contain max-h-80">
            @else
            <div class="flex items-center justify-center h-40 text-gray-300 text-sm font-semibold">
                No image submitted
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Grading form --}}
<div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
    <form method="POST" action="{{ route('teacher.activities.grade.save', [$activity, $submission]) }}">
        @csrf

        {{-- Star rating --}}
        <div class="mb-5">
            <label class="block font-bold text-gray-700 mb-3">Rating</label>
            <div class="flex items-center gap-1" id="star-row">
                @for($s = 1; $s <= 3; $s++)
                <button type="button" class="star-btn {{ $s <= $submission->stars_earned ? 'active' : '' }}"
                        data-stars="{{ $s }}" onclick="setStar({{ $s }})">
                    <i class="{{ $s <= $submission->stars_earned ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                </button>
                @endfor
            </div>
            <input type="hidden" name="stars" id="stars-input" value="{{ max(1, $submission->stars_earned) }}">
            <p class="text-xs text-gray-400 mt-2">
                <i class="fa-solid fa-star" style="color:#FBBF24;"></i> = Needs more practice &nbsp;|&nbsp;
                <i class="fa-solid fa-star" style="color:#FBBF24;"></i><i class="fa-solid fa-star" style="color:#FBBF24;"></i> = Good effort &nbsp;|&nbsp;
                <i class="fa-solid fa-star" style="color:#FBBF24;"></i><i class="fa-solid fa-star" style="color:#FBBF24;"></i><i class="fa-solid fa-star" style="color:#FBBF24;"></i> = Excellent!
            </p>
        </div>

        {{-- Feedback --}}
        <div class="mb-5">
            <label class="block font-bold text-gray-700 mb-2">Feedback <span class="font-normal text-gray-400">(optional)</span></label>
            <textarea name="feedback" rows="3" maxlength="500"
                      class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-purple-400 focus:outline-none text-sm resize-none"
                      placeholder="e.g., Great job staying inside the lines! Keep practising the curves.">{{ old('feedback', $submission->feedback) }}</textarea>
        </div>

        {{-- Actions --}}
        <div class="flex gap-3">
            <button type="submit"
                    class="flex-1 py-3 rounded-2xl font-bold text-white text-base hover:opacity-90 transition flex items-center justify-center gap-2"
                    style="background: linear-gradient(135deg, #8b5cf6, #6366f1);">
                <i class="fa-solid fa-floppy-disk"></i>
                Save Grade{{ $next ? ' & Next →' : '' }}
            </button>
        </div>
    </form>
</div>

{{-- Previous / Next navigation --}}
<div class="flex justify-between gap-3">
    @if($prev)
    <a href="{{ route('teacher.activities.grade.show', [$activity, $prev]) }}"
       class="nav-btn bg-white border-2 border-gray-200 text-gray-600 hover:border-gray-400">
        <i class="fa-solid fa-chevron-left"></i> Previous
    </a>
    @else
    <span></span>
    @endif

    @if($next)
    <a href="{{ route('teacher.activities.grade.show', [$activity, $next]) }}"
       class="nav-btn bg-white border-2 border-gray-200 text-gray-600 hover:border-gray-400">
        Next <i class="fa-solid fa-chevron-right"></i>
    </a>
    @else
    <a href="{{ route('teacher.activities') }}"
       class="nav-btn bg-gray-100 text-gray-500 hover:bg-gray-200">
        <i class="fa-solid fa-list"></i> Back to Activities
    </a>
    @endif
</div>

<script>
var currentStars = {{ max(1, $submission->stars_earned) }};

function setStar(n) {
    currentStars = n;
    document.getElementById('stars-input').value = n;
    document.querySelectorAll('.star-btn').forEach(function (btn) {
        var s   = parseInt(btn.dataset.stars, 10);
        var ico = btn.querySelector('i');
        if (s <= n) {
            btn.classList.add('active');
            ico.className = 'fa-solid fa-star';
        } else {
            btn.classList.remove('active');
            ico.className = 'fa-regular fa-star';
        }
    });
}

// Hover preview
document.querySelectorAll('.star-btn').forEach(function (btn) {
    btn.addEventListener('mouseenter', function () {
        var n = parseInt(btn.dataset.stars, 10);
        document.querySelectorAll('.star-btn').forEach(function (b) {
            b.querySelector('i').className = parseInt(b.dataset.stars, 10) <= n
                ? 'fa-solid fa-star' : 'fa-regular fa-star';
        });
    });
    btn.addEventListener('mouseleave', function () { setStar(currentStars); });
});
</script>

@endsection
