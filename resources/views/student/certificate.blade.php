@extends('layouts.student')
@section('title', 'Certificate of Completion')

@section('student-content')
<div class="flex flex-col items-center justify-center p-6"
     style="background: linear-gradient(135deg, #fff9f0 0%, #f0f8ff 100%); min-height: calc(100vh - 56px);">

    {{-- Action bar --}}
    <div class="flex gap-3 mb-2">
        <a href="{{ route('student.profile') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-2xl border-2 border-gray-200 font-bold text-gray-600 text-sm hover:bg-gray-50 transition">
            <i class="ri-arrow-left-line"></i> Back to Profile
        </a>
        <a href="{{ route('student.certificate.download', $module->id) }}"
           class="flex items-center gap-2 px-6 py-2 rounded-2xl font-bold text-white text-sm transition"
           style="background: linear-gradient(135deg, #F4654D, #ff8c42);">
            <i class="ri-download-2-line"></i> Download Certificate
        </a>
    </div>

    {{-- Info strip --}}
    <div class="mb-2 text-center">
        <p class="text-gray-500 text-sm">
            Certificate for <strong class="text-gray-800">{{ $student->name }}</strong>
            · <strong class="text-gray-800">{{ $module->title }}</strong>
        </p>
        <p class="text-xs text-gray-400 mt-1">Preview shows the blank template — your name are stamped on the downloaded copy.</p>
    </div>

    {{-- PDF preview --}}
    @if($hasTemplate)
    <div class="rounded-2xl overflow-hidden shadow-2xl" style="width:min(700px,100%); border:3px solid #e5e7eb;">
        <iframe src="{{ route('student.certificate.template') }}#toolbar=0&navpanes=0&scrollbar=0&view=Fit"
                style="width:100%; height:495px; display:block; border:none;"
                title="Certificate Preview">
        </iframe>
    </div>
    @else
    <div class="bg-white rounded-3xl shadow-xl p-10 text-center" style="max-width:500px;">
        <div class="text-6xl mb-4">🏅</div>
        <h2 class="font-fredoka text-3xl text-gray-800 mb-2">{{ $student->name }}</h2>
        <p class="text-gray-500 mb-1">has completed</p>
        <h3 class="font-fredoka text-2xl text-gray-700 mb-4">{{ $module->title }}</h3>
        <p class="text-sm text-orange-500 font-semibold mt-3">
            Certificate template not found at <code>resources/templates/KinderLearn.pdf</code>.
        </p>
    </div>
    @endif

</div>
@endsection
