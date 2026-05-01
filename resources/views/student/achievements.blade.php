@extends('layouts.student')
@section('title', 'My Badges')

@section('student-content')

<h1 class="font-fredoka text-4xl text-gray-800 mb-1">My Badges</h1>
<p class="text-gray-400 mb-6">Earn badges by learning, completing modules, and scoring full stars!</p>

{{-- Star total banner --}}
<div data-tour="badges-area" class="rounded-3xl p-6 mb-6 flex items-center gap-5 text-white"
     style="background: linear-gradient(135deg, #FFD700 0%, #FF8E53 100%);">
    <div style="font-size:3.5rem;"><i class="fa-solid fa-star"></i></div>
    <div>
        <div class="font-fredoka text-4xl leading-none">{{ $totalStars }} Stars</div>
        <div class="opacity-80 mt-1">
            {{ $achievements->count() }} badge{{ $achievements->count() !== 1 ? 's' : '' }} earned
            · {{ collect($catalogue)->where('earned', false)->count() }} still to unlock
        </div>
    </div>
</div>

@php
    $groups = [
        'Star Milestones'     => ['first_star','star_collector','star_hunter','star_champion','star_legend'],
        'Module Completion'   => ['first_module','three_modules','five_modules'],
        'Perfect Score'       => ['perfect_module'],
        'Subject Completion'  => ['subject_alphabet','subject_numbers','subject_colors','subject_shapes','subject_words'],
        'Subject Legends'     => ['perfect_alphabet','perfect_numbers','perfect_colors','perfect_shapes','perfect_words'],
    ];
    $byKey = collect($catalogue)->keyBy('key');

    $groupIcons = [
        'Star Milestones'    => '<i class="fa-solid fa-star" style="color:#FFD700;"></i>',
        'Module Completion'  => '<i class="fa-solid fa-book-open" style="color:#4ECDC4;"></i>',
        'Perfect Score'      => '<i class="fa-solid fa-gem" style="color:#BB8FCE;"></i>',
        'Subject Completion' => '<i class="fa-solid fa-school" style="color:#45B7D1;"></i>',
        'Subject Legends'    => '<i class="fa-solid fa-meteor" style="color:#FF6B6B;"></i>',
    ];

    $hints = [
        'first_star'       => 'Earn 1 total star',
        'star_collector'   => 'Earn 10 total stars',
        'star_hunter'      => 'Earn 25 total stars',
        'star_champion'    => 'Earn 50 total stars',
        'star_legend'      => 'Earn 100 total stars',
        'first_module'     => 'Complete 1 module',
        'three_modules'    => 'Complete 3 modules',
        'five_modules'     => 'Complete 5 modules',
        'perfect_module'   => 'Get 3 stars on every activity in any module',
        'subject_alphabet' => 'Complete all Alphabet modules',
        'subject_numbers'  => 'Complete all Numbers modules',
        'subject_colors'   => 'Complete all Colors modules',
        'subject_shapes'   => 'Complete all Shapes modules',
        'subject_words'    => 'Complete all Words modules',
        'perfect_alphabet' => 'Complete all Alphabet modules with full stars',
        'perfect_numbers'  => 'Complete all Numbers modules with full stars',
        'perfect_colors'   => 'Complete all Colors modules with full stars',
        'perfect_shapes'   => 'Complete all Shapes modules with full stars',
        'perfect_words'    => 'Complete all Words modules with full stars',
    ];
@endphp

@foreach($groups as $groupName => $keys)
@php $badges = array_filter(array_map(fn($k) => $byKey->get($k), $keys)); @endphp
@if(!empty($badges))
<div class="mb-8">
    <h2 class="font-fredoka text-2xl text-gray-700 mb-3 flex items-center gap-2">
        {!! $groupIcons[$groupName] !!} {{ $groupName }}
    </h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
        @foreach($badges as $badge)
        @php $earned = $badge['earned']; @endphp
        <div class="rounded-3xl p-5 text-center transition {{ $earned ? 'bg-white shadow-sm' : 'bg-gray-50 opacity-60' }}">
            <div class="text-5xl mb-2 {{ $earned ? '' : 'grayscale' }}" style="{{ $earned ? '' : 'filter:grayscale(1)' }}">
                {{ $badge['icon'] }}
            </div>
            <div class="font-bold text-sm {{ $earned ? 'text-gray-800' : 'text-gray-400' }}">
                {{ $badge['title'] }}
            </div>
            @if($earned)
                <div class="text-xs text-green-500 font-bold mt-1"><i class="fa-solid fa-check"></i> Earned</div>
                @if($badge['earned_at'])
                <div class="text-xs text-gray-400 mt-0.5">{{ $badge['earned_at']->format('M d, Y') }}</div>
                @endif
            @else
                <div class="text-xs text-gray-400 mt-1 leading-snug">{{ $hints[$badge['key']] ?? $badge['desc'] }}</div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif
@endforeach

@endsection
