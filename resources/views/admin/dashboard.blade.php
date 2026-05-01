@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('admin-content')

<h1 class="font-fredoka text-4xl text-gray-800 mb-6">Admin Dashboard <i class="fa-solid fa-gear" style="color:#BB8FCE;"></i></h1>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    @php
        $cards = [
            ['fa' => 'fa-solid fa-child',           'label' => 'Students',  'value' => $stats['total_students'], 'color' => '#FF6B6B'],
            ['fa' => 'fa-solid fa-chalkboard-user', 'label' => 'Teachers',  'value' => $stats['total_teachers'], 'color' => '#4ECDC4'],
            ['fa' => 'fa-solid fa-school',          'label' => 'Classes',   'value' => $stats['total_classes'],  'color' => '#45B7D1'],
            ['fa' => 'fa-solid fa-book-open',       'label' => 'Modules',   'value' => $stats['total_modules'],  'color' => '#BB8FCE'],
        ];
    @endphp
    @foreach($cards as $c)
    <div class="bg-white rounded-3xl p-5 text-center shadow-sm" style="border-top: 4px solid {{ $c['color'] }}">
        <div class="mb-2" style="font-size:2rem; color:{{ $c['color'] }};"><i class="{{ $c['fa'] }}"></i></div>
        <div class="font-fredoka text-4xl" style="color: {{ $c['color'] }}">{{ $c['value'] }}</div>
        <div class="text-gray-500 font-semibold text-sm">{{ $c['label'] }}</div>
    </div>
    @endforeach
</div>

<div class="bg-white rounded-3xl p-6 shadow-sm">
    <h2 class="font-fredoka text-2xl text-gray-800 mb-5">Recent Users</h2>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left border-b-2 border-gray-100">
                    <th class="pb-3 font-bold text-gray-700">Name</th>
                    <th class="pb-3 font-bold text-gray-700">Email</th>
                    <th class="pb-3 font-bold text-gray-700">Role</th>
                    <th class="pb-3 font-bold text-gray-700">Joined</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($recentUsers as $u)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 font-bold text-gray-800">{{ $u->name }}</td>
                    <td class="py-3 text-gray-500 text-sm">{{ $u->email }}</td>
                    <td class="py-3">
                        @php $roleColors = ['admin' => 'bg-purple-100 text-purple-600', 'teacher' => 'bg-blue-100 text-blue-600', 'student' => 'bg-green-100 text-green-600']; @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $roleColors[$u->role] ?? '' }}">{{ ucfirst($u->role) }}</span>
                    </td>
                    <td class="py-3 text-sm text-gray-400">{{ $u->created_at->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
