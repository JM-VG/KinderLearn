@extends('layouts.teacher')
@section('title', 'Profile Photo')

@section('teacher-content')
<h1 class="font-fredoka text-4xl text-gray-800 mb-2">Profile Photo</h1>
<p class="text-gray-400 mb-6">Upload a photo to show on the top navigation bar.</p>

@if(session('success'))
<div class="mb-5 px-5 py-3 bg-green-50 border border-green-200 text-green-700 rounded-2xl font-semibold flex items-center gap-2">
    <i class="ri-checkbox-circle-line"></i> {{ session('success') }}
</div>
@endif

<div class="max-w-md space-y-5">

    {{-- Current photo --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm flex items-center gap-5">
        @if(str_starts_with($teacher->avatar ?? '', 'avatars/'))
        <img src="{{ asset('storage/' . $teacher->avatar) }}"
             class="w-20 h-20 rounded-full object-cover ring-4 ring-sky-200 flex-shrink-0">
        @else
        <div class="w-20 h-20 rounded-full flex items-center justify-center font-bold text-white text-3xl flex-shrink-0"
             style="background: linear-gradient(135deg, #0ea5e9, #6366f1);">
            {{ strtoupper(substr($teacher->name, 0, 1)) }}
        </div>
        @endif
        <div>
            <div class="font-fredoka text-xl text-gray-800">{{ $teacher->name }}</div>
            <div class="text-sm text-sky-500 font-semibold mt-0.5">Teacher</div>
        </div>
    </div>

    {{-- Upload form --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm">
        <h2 class="font-fredoka text-xl text-gray-800 mb-1 flex items-center gap-2">
            <i class="ri-camera-line text-sky-400"></i> Upload New Photo
        </h2>
        <p class="text-xs text-gray-400 mb-4">JPG, PNG, GIF or WebP · max 3 MB</p>

        <form method="POST" action="{{ route('teacher.avatar.upload') }}" enctype="multipart/form-data">
            @csrf
            <label class="block cursor-pointer">
                <div id="drop-zone"
                     class="border-2 border-dashed border-gray-200 rounded-2xl p-8 text-center hover:border-sky-400 hover:bg-sky-50 transition"
                     ondragover="event.preventDefault();this.classList.add('border-sky-400','bg-sky-50')"
                     ondragleave="this.classList.remove('border-sky-400','bg-sky-50')"
                     ondrop="handleDrop(event)">
                    <div id="drop-preview" class="mb-2" style="font-size:2.5rem; color:#0ea5e9;"><i class="fa-solid fa-camera"></i></div>
                    <p class="font-bold text-gray-600 text-sm">Click to choose or drag & drop</p>
                    <p id="file-name" class="text-xs text-gray-400 mt-1">No file chosen</p>
                </div>
                <input type="file" name="photo" id="photo-input" accept="image/*" class="sr-only"
                       onchange="previewPhoto(this)">
            </label>
            @error('photo')<p class="text-red-500 text-xs mt-2 font-semibold"><i class="fa-solid fa-triangle-exclamation"></i> {{ $message }}</p>@enderror
            <button type="submit"
                    class="mt-4 w-full py-3 rounded-2xl font-bold text-white transition"
                    style="background: linear-gradient(135deg, #0ea5e9, #6366f1);">
                <i class="ri-upload-2-line"></i> Save Profile Photo
            </button>
        </form>
    </div>

</div>

<script>
function previewPhoto(input) {
    if (!input.files || !input.files[0]) return;
    document.getElementById('file-name').textContent = input.files[0].name;
    var reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('drop-preview').innerHTML =
            '<img src="' + e.target.result + '" class="w-20 h-20 rounded-full object-cover mx-auto mb-2">';
    };
    reader.readAsDataURL(input.files[0]);
}
function handleDrop(e) {
    e.preventDefault();
    document.getElementById('drop-zone').classList.remove('border-sky-400','bg-sky-50');
    var files = e.dataTransfer.files;
    if (files.length) {
        var input = document.getElementById('photo-input');
        input.files = files;
        previewPhoto(input);
    }
}
</script>
@endsection
