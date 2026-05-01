@extends('layouts.admin')
@section('title', 'All Users')
@section('admin-content')
<h1 class="font-fredoka text-4xl text-gray-800 mb-6">All Users <i class="fa-solid fa-users" style="color:#BB8FCE;"></i></h1>

{{-- Create user form --}}
<div class="bg-white rounded-3xl p-6 shadow-sm mb-6">
    <h2 class="font-fredoka text-2xl text-gray-800 mb-4">
        <i class="fa-solid fa-plus" style="color:#BB8FCE;"></i> Create New User
    </h2>
    <form method="POST" action="{{ route('admin.users.create') }}"
          class="grid grid-cols-1 md:grid-cols-4 gap-4">
        @csrf
        <input type="text" name="name" placeholder="Full name" required
               class="px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-purple-400 focus:outline-none">
        <input type="email" name="email" placeholder="Email address" required
               class="px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-purple-400 focus:outline-none">
        <input type="password" name="password" placeholder="Password (min 6)" required
               class="px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-purple-400 focus:outline-none">
        <div class="flex gap-2">
            <select name="role" required class="flex-1 px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-purple-400 focus:outline-none font-semibold">
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit" class="px-5 py-3 rounded-2xl font-bold text-white" style="background: linear-gradient(135deg, #9B59B6, #BB8FCE)">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
    </form>
</div>

<div class="bg-white rounded-3xl shadow-sm overflow-hidden">
    <table class="w-full">
        <thead style="background: linear-gradient(90deg, #1a1a2e, #16213e);">
            <tr class="text-white text-left">
                <th class="px-6 py-4 font-bold">Name</th>
                <th class="px-6 py-4 font-bold">Email</th>
                <th class="px-6 py-4 font-bold">Role</th>
                <th class="px-6 py-4 font-bold">Joined</th>
                <th class="px-6 py-4 font-bold">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($users as $u)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 font-bold text-gray-800">{{ $u->name }}</td>
                <td class="px-6 py-4 text-gray-500 text-sm">{{ $u->email }}</td>
                <td class="px-6 py-4">
                    @php $rc = ['admin' => 'bg-purple-100 text-purple-600', 'teacher' => 'bg-blue-100 text-blue-600', 'student' => 'bg-green-100 text-green-600']; @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $rc[$u->role] ?? '' }}">{{ ucfirst($u->role) }}</span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-400">{{ $u->created_at->format('M d') }}</td>
                <td class="px-6 py-4">
                    @if($u->id !== auth()->id())
                    <form id="delete-form-{{ $u->id }}" method="POST" action="{{ route('admin.users.delete', $u->id) }}">
                        @csrf @method('DELETE')
                    </form>
                    <button onclick="confirmDelete({{ $u->id }}, '{{ addslashes($u->name) }}', '{{ $u->email }}')"
                            class="text-red-400 font-bold text-sm hover:text-red-600 transition">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                    @else
                    <span class="text-gray-300 text-sm">You</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-4">{{ $users->links() }}</div>
</div>
{{-- Delete confirmation modal --}}
<div id="delete-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
    <div class="relative bg-white rounded-3xl shadow-2xl p-8 w-full max-w-sm mx-4 text-center">
        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-trash text-red-500 text-2xl"></i>
        </div>
        <h3 class="font-fredoka text-2xl text-gray-800 mb-1">Delete Account?</h3>
        <p class="text-gray-500 text-sm mb-1" id="modal-name"></p>
        <p class="text-gray-400 text-xs mb-6" id="modal-email"></p>
        <p class="text-gray-500 text-sm mb-6">This action <strong>cannot be undone.</strong> All data linked to this account will be removed.</p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()"
                    class="flex-1 px-4 py-3 rounded-2xl border-2 border-gray-200 font-bold text-gray-600 hover:bg-gray-50 transition">
                Cancel
            </button>
            <button onclick="submitDelete()"
                    class="flex-1 px-4 py-3 rounded-2xl font-bold text-white transition hover:opacity-90"
                    style="background:linear-gradient(135deg,#ef4444,#dc2626);">
                <i class="fa-solid fa-trash mr-1"></i> Delete
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let pendingDeleteId = null;

    function confirmDelete(id, name, email) {
        pendingDeleteId = id;
        document.getElementById('modal-name').textContent = name;
        document.getElementById('modal-email').textContent = email;
        document.getElementById('delete-modal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        pendingDeleteId = null;
        document.getElementById('delete-modal').classList.add('hidden');
    }

    function submitDelete() {
        if (pendingDeleteId) {
            document.getElementById('delete-form-' + pendingDeleteId).submit();
        }
    }
</script>
@endpush
@endsection
