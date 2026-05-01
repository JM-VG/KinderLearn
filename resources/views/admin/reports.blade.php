@extends('layouts.admin')
@section('title', 'Reports')
@section('admin-content')
<h1 class="font-fredoka text-4xl text-gray-800 mb-6">System Reports</h1>

{{-- Stats cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    @php $cards = [
        ['fa-solid fa-child',           'Students', $stats['students'], '#FF6B6B'],
        ['fa-solid fa-chalkboard-user', 'Teachers', $stats['teachers'], '#4ECDC4'],
        ['fa-solid fa-school',          'Classes',  $stats['classes'],  '#45B7D1'],
        ['fa-solid fa-book-open',       'Modules',  $stats['modules'],  '#BB8FCE'],
    ]; @endphp
    @foreach($cards as [$fa, $label, $val, $color])
    <div class="bg-white rounded-3xl p-5 text-center shadow-sm" style="border-top: 4px solid {{ $color }}">
        <div class="mb-1" style="font-size:2rem; color:{{ $color }};"><i class="{{ $fa }}"></i></div>
        <div class="font-fredoka text-3xl" style="color: {{ $color }}">{{ $val }}</div>
        <div class="text-gray-500 font-semibold text-sm">{{ $label }}</div>
    </div>
    @endforeach
</div>

{{-- Download report panel --}}
<div class="bg-white rounded-3xl p-6 shadow-sm mb-6">
    <h2 class="font-fredoka text-2xl text-gray-800 mb-5">Download Report</h2>
    <form action="{{ route('admin.reports.download') }}" method="GET"
          class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

        <div>
            <label class="block text-sm font-bold text-gray-600 mb-1">Report Type</label>
            <select name="type" class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-indigo-400 focus:outline-none font-semibold">
                <option value="full">Full Report (All Users)</option>
                <option value="students">Students Only</option>
                <option value="teachers">Teachers Only</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-600 mb-1">From Date</label>
            <input type="date" name="from"
                   class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-indigo-400 focus:outline-none font-semibold">
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-600 mb-1">To Date</label>
            <input type="date" name="to" value="{{ now()->format('Y-m-d') }}"
                   class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-indigo-400 focus:outline-none font-semibold">
        </div>

        <button type="submit"
                class="btn-kid text-white justify-center"
                style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
            <i class="ri-download-line"></i> Download CSV
        </button>
    </form>
</div>

{{-- Users table preview --}}
<div class="bg-white rounded-3xl p-6 shadow-sm">
    <h2 class="font-fredoka text-2xl text-gray-800 mb-4">All Users</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left font-bold text-gray-500 pb-3 pr-4">Name</th>
                    <th class="text-left font-bold text-gray-500 pb-3 pr-4">Email</th>
                    <th class="text-left font-bold text-gray-500 pb-3 pr-4">Role</th>
                    <th class="text-left font-bold text-gray-500 pb-3 pr-4">Class</th>
                    <th class="text-left font-bold text-gray-500 pb-3">Joined</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($users as $u)
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-3 pr-4 font-semibold text-gray-800">{{ $u->name }}</td>
                    <td class="py-3 pr-4 text-gray-500">{{ $u->email }}</td>
                    <td class="py-3 pr-4">
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold
                            {{ $u->role === 'admin' ? 'bg-indigo-100 text-indigo-700' :
                               ($u->role === 'teacher' ? 'bg-sky-100 text-sky-700' : 'bg-orange-100 text-orange-700') }}">
                            {{ ucfirst($u->role) }}
                        </span>
                    </td>
                    <td class="py-3 pr-4 text-gray-500">{{ $u->section->name ?? '—' }}</td>
                    <td class="py-3 text-gray-400 text-xs">{{ $u->created_at->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
