@extends('layouts.admin')
@section('title', 'Settings')
@section('admin-content')
<h1 class="font-fredoka text-4xl text-gray-800 mb-6">Settings <i class="fa-solid fa-gear" style="color:#BB8FCE;"></i></h1>
<div class="max-w-xl bg-white rounded-3xl p-8 shadow-sm">
    <p class="text-gray-500 font-semibold mb-6">System settings can be configured here.</p>
    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
        @csrf
        <div>
            <label class="block font-bold text-gray-700 mb-2">App Name</label>
            <input type="text" name="app_name" value="KinderLearn"
                   class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-purple-400 focus:outline-none">
        </div>
        <div>
            <label class="block font-bold text-gray-700 mb-2">Contact Email</label>
            <input type="email" name="contact_email" placeholder="admin@kinderlearn.com"
                   class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-purple-400 focus:outline-none">
        </div>
        <button type="submit" class="btn-kid text-white w-full justify-center" style="background: linear-gradient(135deg, #9B59B6, #BB8FCE)">
            <i class="fa-solid fa-floppy-disk"></i> Save Settings
        </button>
    </form>
</div>
@endsection
