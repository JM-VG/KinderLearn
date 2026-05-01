@extends('layouts.student')
@section('title', 'Review — ' . $activity->title)

@push('styles')
<style>
.review-card {
    border-radius: 20px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 1rem;
    border-width: 2px;
    border-style: solid;
}
.review-card.correct  { background:#f0fdf4; border-color:#86efac; }
.review-card.wrong    { background:#fff7f7; border-color:#fca5a5; }
.review-card.skipped  { background:#fafafa; border-color:#e5e7eb; }

.opt-tag {
    display:inline-block;
    padding:4px 14px;
    border-radius:50px;
    font-size:0.85rem;
    font-weight:700;
    margin:2px 3px 2px 0;
}
.opt-given-right { background:#dcfce7; color:#15803d; }
.opt-given-wrong { background:#fee2e2; color:#b91c1c; }
.opt-correct     { background:#dbeafe; color:#1d4ed8; }
.opt-other       { background:#f3f4f6; color:#6b7280; }
</style>
@endpush

@section('student-content')

{{-- Header --}}
<div class="flex items-center gap-4 mb-6">
    <a href="{{ route('student.modules.show', $activity->module_id) }}"
       class="w-10 h-10 rounded-full flex items-center justify-center bg-white shadow-sm hover:shadow transition text-gray-500">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
    <div>
        <h1 class="font-fredoka text-3xl text-gray-800 leading-tight">{{ $activity->title }}</h1>
        <p class="text-sm text-gray-400 font-semibold mt-0.5">Answer Review</p>
    </div>
</div>

{{-- Score banner --}}
@php
    $total   = count($review);
    $correct = collect($review)->where('is_right', true)->count();
    $wrong   = collect($review)->where('is_right', false)->where('given', '!=', null)->count();
    $skipped = collect($review)->whereNull('given')->count();
    $pct     = $total > 0 ? round($correct / $total * 100) : 0;
@endphp

<div class="bg-white rounded-3xl p-5 shadow-sm mb-6 flex flex-wrap items-center gap-5">
    {{-- Score ring --}}
    <div class="relative w-20 h-20 flex-shrink-0">
        <svg class="w-20 h-20 -rotate-90" viewBox="0 0 80 80">
            <circle cx="40" cy="40" r="34" fill="none" stroke="#f3f4f6" stroke-width="8"/>
            <circle cx="40" cy="40" r="34" fill="none"
                    stroke="{{ $pct >= 70 ? '#22c55e' : ($pct >= 40 ? '#f59e0b' : '#ef4444') }}"
                    stroke-width="8"
                    stroke-dasharray="{{ round(2 * pi() * 34, 1) }}"
                    stroke-dashoffset="{{ round(2 * pi() * 34 * (1 - $pct / 100), 1) }}"
                    stroke-linecap="round"/>
        </svg>
        <span class="absolute inset-0 flex items-center justify-center font-fredoka text-xl text-gray-800">
            {{ $pct }}%
        </span>
    </div>

    <div class="flex gap-5 flex-wrap">
        <div class="text-center">
            <div class="font-fredoka text-2xl text-green-500">{{ $correct }}</div>
            <div class="text-xs text-gray-400 font-semibold">Correct</div>
        </div>
        <div class="text-center">
            <div class="font-fredoka text-2xl text-red-400">{{ $wrong }}</div>
            <div class="text-xs text-gray-400 font-semibold">Wrong</div>
        </div>
        @if($skipped > 0)
        <div class="text-center">
            <div class="font-fredoka text-2xl text-gray-400">{{ $skipped }}</div>
            <div class="text-xs text-gray-400 font-semibold">Skipped</div>
        </div>
        @endif
        <div class="text-center">
            <div class="flex gap-0.5 justify-center">
                @for($s = 1; $s <= 3; $s++)
                    <i class="{{ $s <= $submission->stars_earned ? 'fa-solid' : 'fa-regular' }} fa-star"
                       style="font-size:1.4rem; color:#FFD700;"></i>
                @endfor
            </div>
            <div class="text-xs text-gray-400 font-semibold mt-0.5">Stars earned</div>
        </div>
    </div>
</div>

{{-- Question-by-question review --}}
@foreach($review as $i => $r)
@php
    $state = $r['given'] === null ? 'skipped' : ($r['is_right'] ? 'correct' : 'wrong');

    // Image lookup (same map as the quiz)
    $imgMap = [
        'Square'=>'ShapesEverywhere/shape-square','Triangle'=>'ShapesEverywhere/shape-triangle',
        'Circle'=>'ShapesEverywhere/shape-circle','Rectangle'=>'ShapesEverywhere/shape-rectangle',
        'Diamond'=>'ShapesEverywhere/shape-diamond','Pentagon'=>'ShapesEverywhere/shape-pentagon',
        'Hexagon'=>'ShapesEverywhere/shape-hexagon','Octagon'=>'ShapesEverywhere/shape-octagon',
        'Oval'=>'ShapesEverywhere/shape-oval','Star'=>'ShapesEverywhere/shape-star',
        'Sphere'=>'ShapesEverywhere/shape-sphere','Cube'=>'ShapesEverywhere/shape-cube',
        'Cone'=>'ShapesEverywhere/shape-cone','Cylinder'=>'ShapesEverywhere/shape-cylinder',
        'Cat'=>'MyFirstWords/animal-cat','Dog'=>'MyFirstWords/animal-dog',
        'Fish'=>'MyFirstWords/animal-fish','Bird'=>'MyFirstWords/animal-bird',
        'Rabbit'=>'MyFirstWords/animal-rabbit','Elephant'=>'MyFirstWords/animal-elephant',
        'Lion'=>'MyFirstWords/animal-lion','Monkey'=>'MyFirstWords/animal-monkey',
        'Crocodile'=>'MyFirstWords/animal-crocodile','Giraffe'=>'MyFirstWords/animal-giraffe',
        'Pizza'=>'MyFirstWords/food-pizza','Apple'=>'MyFirstWords/food-apple',
        'Banana'=>'MyFirstWords/food-banana','Carrot'=>'MyFirstWords/food-carrot',
        'Cake'=>'MyFirstWords/food-cake',
        'Run'=>'MyFirstWords/action-run','Sleep'=>'MyFirstWords/action-sleep',
        'Jump'=>'MyFirstWords/action-jump','Swim'=>'MyFirstWords/action-swim',
        'Fly'=>'MyFirstWords/action-fly',
        'CAT'=>'MyFirstWords/word-cat','DOG'=>'MyFirstWords/word-dog',
        'SUN'=>'MyFirstWords/word-sun','HAT'=>'MyFirstWords/word-hat',
        'BEE'=>'MyFirstWords/word-bee','BUS'=>'MyFirstWords/word-bus',
        'MOM'=>'MyFirstWords/word-mom','RUN'=>'MyFirstWords/word-run',
        'RED'=>'MyFirstWords/word-red','TEN'=>'MyFirstWords/word-ten',
    ];
    // Teacher-uploaded image stored in review data takes priority
    $imgPath = !empty($r['image']) ? asset('storage/' . $r['image']) : (isset($imgMap[$r['correct']]) ? '/images/quiz/' . $imgMap[$r['correct']] . '.png' : null);
    // Fallback: scan question text for shape name
    if (!$imgPath) {
        $shapeNames = ['square','triangle','circle','rectangle','diamond','pentagon','hexagon','octagon','oval','star','sphere','cube','cone','cylinder'];
        foreach ($shapeNames as $sn) {
            if (str_contains(strtolower($r['text']), $sn)) {
                $imgPath = '/images/quiz/ShapesEverywhere/shape-' . $sn . '.png';
                break;
            }
        }
    }
    $displayText = $imgPath
        ? preg_replace('/[\x{1F000}-\x{1FAFF}\x{2600}-\x{27BF}\x{2300}-\x{23FF}]/u', '', $r['text'])
        : $r['text'];
    $displayText = trim(preg_replace('/\s{2,}/', ' ', $displayText));
@endphp

<div class="review-card {{ $state }}">
    <div class="flex items-start gap-4">
        {{-- Status icon --}}
        <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold
                    {{ $state === 'correct' ? 'bg-green-500' : ($state === 'wrong' ? 'bg-red-400' : 'bg-gray-300') }}">
            @if($state === 'correct') <i class="fa-solid fa-check"></i>
            @elseif($state === 'wrong') <i class="fa-solid fa-xmark"></i>
            @else <i class="fa-solid fa-minus"></i>
            @endif
        </div>

        <div class="flex-1 min-w-0">
            {{-- Question number + text --}}
            <div class="text-xs font-bold text-gray-400 mb-1">Question {{ $i + 1 }}</div>

            <div class="flex items-start gap-4 mb-3">
                @if($imgPath && file_exists(public_path($imgPath)))
                <img src="{{ $imgPath }}" alt="" class="w-16 h-16 object-contain rounded-xl flex-shrink-0">
                @endif
                <p class="font-fredoka text-lg text-gray-800 leading-snug">{{ $displayText }}</p>
            </div>

            {{-- Options --}}
            <div class="flex flex-wrap gap-1 mb-2">
                @foreach($r['options'] as $opt)
                    @if($opt === $r['correct'] && $opt === $r['given'])
                        <span class="opt-tag opt-given-right"><i class="fa-solid fa-check mr-1"></i>{{ $opt }}</span>
                    @elseif($opt === $r['given'] && !$r['is_right'])
                        <span class="opt-tag opt-given-wrong"><i class="fa-solid fa-xmark mr-1"></i>{{ $opt }}</span>
                    @elseif($opt === $r['correct'] && !$r['is_right'])
                        <span class="opt-tag opt-correct"><i class="fa-solid fa-circle-check mr-1"></i>{{ $opt }}</span>
                    @else
                        <span class="opt-tag opt-other">{{ $opt }}</span>
                    @endif
                @endforeach
            </div>

            {{-- Wrong answer callout --}}
            @if($state === 'wrong')
            <div class="flex items-center gap-2 mt-2 text-sm">
                <span class="text-red-500 font-bold">Your answer:</span>
                <span class="text-red-600 font-semibold">{{ $r['given'] }}</span>
                <span class="text-gray-300 mx-1">|</span>
                <span class="text-blue-500 font-bold">Correct answer:</span>
                <span class="text-blue-600 font-semibold">{{ $r['correct'] }}</span>
            </div>
            @elseif($state === 'skipped')
            <div class="text-sm text-gray-400 font-semibold mt-1">
                Not answered &nbsp;·&nbsp; Correct answer: <span class="text-blue-600">{{ $r['correct'] }}</span>
            </div>
            @endif
        </div>
    </div>
</div>
@endforeach

<div class="mt-6 flex justify-center">
    <a href="{{ route('student.modules.show', $activity->module_id) }}"
       class="btn-kid text-white"
       style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
        <i class="fa-solid fa-arrow-left"></i> Back to Module
    </a>
</div>

@endsection
