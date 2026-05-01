@extends('layouts.teacher')
@section('title', 'Reports')
@section('teacher-content')
<h1 class="font-fredoka text-4xl text-gray-800 mb-6">Reports</h1>

{{-- Date-range download panel --}}
<div class="bg-white rounded-3xl p-6 shadow-sm mb-6">
    <h2 class="font-fredoka text-2xl text-gray-800 mb-2">Download Report</h2>
    <p class="text-gray-400 text-sm mb-5">Select a date range and report type to export as CSV.</p>

    <form action="{{ route('teacher.reports.download', 'filtered') }}" method="GET"
          class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

        <div>
            <label class="block text-sm font-bold text-gray-600 mb-1">Report Type</label>
            <select name="type" class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-sky-400 focus:outline-none font-semibold">
                <option value="progress">Progress Report</option>
                <option value="full">Full Student Report</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-600 mb-1">From Date</label>
            <input type="date" name="from"
                   class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-sky-400 focus:outline-none font-semibold">
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-600 mb-1">To Date</label>
            <input type="date" name="to" value="{{ now()->format('Y-m-d') }}"
                   class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-sky-400 focus:outline-none font-semibold">
        </div>

        <button type="submit"
                class="btn-kid text-white justify-center"
                style="background: linear-gradient(135deg, #0ea5e9, #6366f1);">
            <i class="ri-download-line"></i> Download CSV
        </button>
    </form>
</div>

{{-- Quick download cards --}}
<div class="flex justify-center mb-6">
    <div class="bg-white rounded-3xl p-8 shadow-sm text-center card-hover w-full max-w-sm">
        <div class="mb-4" style="font-size:4rem; color:#6366f1;"><i class="fa-solid fa-chart-column"></i></div>
        <h2 class="font-fredoka text-2xl text-gray-800 mb-2">Full Progress Report</h2>
        <p class="text-gray-500 text-sm mb-5">Module completion and stars for all your students.</p>
        <a href="{{ route('teacher.reports.download', 'progress') }}"
           class="btn-kid text-white"
           style="background: linear-gradient(135deg, #4ECDC4, #45B7D1)">
            <i class="ri-download-line"></i> Download CSV
        </a>
    </div>

</div>

{{-- Student summary table --}}
<div class="bg-white rounded-3xl p-6 shadow-sm">
    <h2 class="font-fredoka text-2xl text-gray-800 mb-5">Student Summary</h2>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[500px]">
            <thead>
                <tr class="text-left border-b-2 border-gray-100">
                    <th class="pb-3 font-bold text-gray-700">Student</th>
                    <th class="pb-3 font-bold text-gray-700">Class</th>
                    <th class="pb-3 font-bold text-gray-700 text-center">Stars</th>
                    <th class="pb-3 font-bold text-gray-700 text-center">Completed</th>
                    <th class="pb-3 font-bold text-gray-700 text-center">Badges</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($students as $s)
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-3 font-bold text-gray-800">{{ $s->name }}</td>
                    <td class="py-3 text-gray-500">{{ $s->section->name ?? '—' }}</td>
                    <td class="py-3 text-center font-bold text-yellow-600">{{ $s->getTotalStars() }}</td>
                    <td class="py-3 text-center font-bold text-green-600">{{ $s->getCompletedModules() }}</td>
                    <td class="py-3 text-center font-bold text-purple-600">{{ $s->achievements->count() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
