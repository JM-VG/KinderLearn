<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Module;
use App\Models\Section;
use App\Models\Message;
use App\Models\UserNotification;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_students' => User::where('role', 'student')->count(),
            'total_teachers' => User::where('role', 'teacher')->count(),
            'total_classes'  => Section::count(),
            'total_modules'  => Module::count(),
        ];

        $recentUsers = User::latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers'));
    }

    public function users()
    {
        $users = User::orderBy('role')->latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,teacher,student',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return back()->with('success', 'User created!');
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate(['name' => 'required', 'role' => 'required|in:admin,teacher,student']);
        $user->update($request->only('name', 'role'));
        return back()->with('success', 'User updated!');
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return back()->with('success', 'User deleted.');
    }

    public function teachers()
    {
        $teachers = User::where('role', 'teacher')->with('sections')->get();
        return view('admin.teachers', compact('teachers'));
    }

    public function students()
    {
        $students = User::where('role', 'student')->with('section')->paginate(20);
        return view('admin.students', compact('students'));
    }

    public function profile()
    {
        $admin = Auth::user();
        $stats = [
            'total_students' => User::where('role', 'student')->count(),
            'total_teachers' => User::where('role', 'teacher')->count(),
            'total_classes'  => Section::count(),
            'total_modules'  => Module::count(),
        ];

        return view('admin.profile', compact('admin', 'stats'));
    }

    public function updateProfile(Request $request)
    {
        $admin = Auth::user();

        $rules = [
            'name' => 'required|string|max:100',
            'bio'  => 'nullable|string|max:300',
        ];

        if ($request->filled('current_password')) {
            $rules['current_password'] = ['required', function ($attr, $value, $fail) use ($admin) {
                if (!Hash::check($value, $admin->password)) {
                    $fail('Current password is incorrect.');
                }
            }];
            $rules['new_password'] = 'required|min:6|confirmed';
        }

        $request->validate($rules);

        $admin->name = $request->name;
        $admin->bio  = $request->bio;

        if ($request->filled('current_password')) {
            $admin->password = Hash::make($request->new_password);
        }

        $admin->save();

        return back()->with('success', 'Profile updated!');
    }

    public function mySettings()
    {
        return view('admin.my-settings');
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function updateSettings(Request $request)
    {
        // Settings can be stored in a config table or .env
        return back()->with('success', 'Settings saved!');
    }

    public function reports()
    {
        $stats = [
            'students'   => User::where('role', 'student')->count(),
            'teachers'   => User::where('role', 'teacher')->count(),
            'classes'    => Section::count(),
            'modules'    => Module::count(),
        ];
        $users = User::with('section')->orderBy('role')->get();
        return view('admin.reports', compact('stats', 'users'));
    }

    public function downloadReport(\Illuminate\Http\Request $request)
    {
        $from  = $request->input('from');
        $to    = $request->input('to');
        $type  = $request->input('type', 'full');

        $query = User::with(['section', 'activitySubmissions'])
            ->when($from, fn($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to,   fn($q) => $q->whereDate('created_at', '<=', $to));

        if ($type === 'students') $query->where('role', 'student');
        if ($type === 'teachers') $query->where('role', 'teacher');

        $users = $query->get();

        $csv = "Name,Email,Role,Class,Joined\n";
        foreach ($users as $u) {
            $csv .= "\"{$u->name}\",\"{$u->email}\",\"{$u->role}\",\"" .
                    ($u->section->name ?? 'N/A') . "\",\"{$u->created_at->format('Y-m-d')}\"\n";
        }

        $filename = 'kinderlearn-report-' . now()->format('Ymd-His') . '.csv';
        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    public function notificationsPreview()
    {
        $admin  = Auth::user();
        $unread = Message::where('receiver_id', $admin->id)->whereNull('read_at')->latest()->take(5)->get()
            ->map(fn($m) => [
                'icon'  => '💬',
                'title' => 'Support: ' . \Str::limit($m->subject, 40),
                'body'  => $m->sender->name . ': ' . \Str::limit($m->body, 50),
                'link'  => route('admin.support.view', $m->id),
            ]);

        $recent = User::latest()->take(3)->get()->map(fn($u) => [
            'icon'  => '👤',
            'title' => 'New user: ' . $u->name,
            'body'  => ucfirst($u->role) . ' joined ' . $u->created_at->diffForHumans(),
            'link'  => null,
        ]);

        return response()->json($unread->merge($recent)->take(5)->values());
    }

    public function supportInbox()
    {
        $admin    = Auth::user();
        $messages = Message::where('receiver_id', $admin->id)
            ->with('sender')
            ->latest()
            ->paginate(20);

        $unreadCount = Message::where('receiver_id', $admin->id)->whereNull('read_at')->count();

        return view('admin.support', compact('messages', 'unreadCount'));
    }

    public function supportView(Message $message)
    {
        $admin = Auth::user();
        abort_unless($message->receiver_id === $admin->id, 403);

        // Mark as read
        if (!$message->read_at) {
            $message->update(['read_at' => now()]);
        }

        return view('admin.support-view', compact('message'));
    }

    public function supportReply(\Illuminate\Http\Request $request, Message $message)
    {
        $admin = Auth::user();
        abort_unless($message->receiver_id === $admin->id, 403);

        $request->validate(['body' => 'required|string|max:2000']);

        Message::create([
            'sender_id'   => $admin->id,
            'receiver_id' => $message->sender_id,
            'subject'     => 'Re: ' . $message->subject,
            'body'        => $request->body,
        ]);

        UserNotification::create([
            'user_id' => $message->sender_id,
            'title'   => 'Support Reply',
            'message' => 'The admin replied to your support request: "' . \Str::limit($message->subject, 60) . '"',
            'type'    => 'message',
        ]);

        return back()->with('success', 'Reply sent!');
    }

    public function supportDelete(Message $message)
    {
        $admin = Auth::user();
        abort_unless($message->receiver_id === $admin->id, 403);
        $message->delete();
        return redirect()->route('admin.support')->with('success', 'Message deleted.');
    }
}
