<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\User;
use App\Models\UserNotification;

class MessageController extends Controller
{
    /** Student/Parent: view inbox */
    public function index()
    {
        $user     = Auth::user();
        $messages = Message::where('receiver_id', $user->id)
            ->orWhere('sender_id', $user->id)
            ->with(['sender', 'receiver'])
            ->latest()
            ->get();
        $teachers = User::where('role', 'teacher')->get();
        return view('student.messages', compact('messages', 'teachers', 'user'));
    }

    /** Teacher: view inbox */
    public function teacherIndex()
    {
        $teacher  = Auth::user();
        $messages = Message::where('receiver_id', $teacher->id)
            ->orWhere('sender_id', $teacher->id)
            ->with(['sender', 'receiver'])
            ->latest()
            ->get();
        Message::where('receiver_id', $teacher->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        $students = User::where('role', 'student')->get();
        return view('teacher.messages', compact('messages', 'students', 'teacher'));
    }

    /** JSON: list conversation partners with latest message + unread count */
    public function conversations()
    {
        $user = Auth::user();
        $messages = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver'])
            ->latest()
            ->get();

        $convos = [];
        foreach ($messages as $msg) {
            $partner = $msg->sender_id === $user->id ? $msg->receiver : $msg->sender;
            if (!$partner) continue;
            if (!isset($convos[$partner->id])) {
                $convos[$partner->id] = [
                    'user_id'  => $partner->id,
                    'name'     => $partner->name,
                    'role'     => $partner->role,
                    'avatar'   => $partner->avatar,
                    'latest'   => $msg->body,
                    'from_me'  => $msg->sender_id === $user->id,
                    'time'     => $msg->created_at->diffForHumans(null, true),
                    'unread'   => 0,
                ];
            }
            if ($msg->receiver_id === $user->id && !$msg->read_at) {
                $convos[$partner->id]['unread']++;
            }
        }

        return response()->json(array_values($convos));
    }

    /** JSON: full thread with one user, marks incoming as read */
    public function thread(User $user)
    {
        $me = Auth::user();
        $messages = Message::where(function ($q) use ($me, $user) {
                $q->where('sender_id', $me->id)->where('receiver_id', $user->id);
            })
            ->orWhere(function ($q) use ($me, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $me->id);
            })
            ->orderBy('created_at')
            ->get()
            ->map(fn($m) => [
                'id'      => $m->id,
                'from_me' => $m->sender_id === $me->id,
                'body'    => $m->body,
                'time'    => $m->created_at->format('M j, g:i A'),
                'read'    => (bool) $m->read_at,
            ]);

        Message::where('sender_id', $user->id)
            ->where('receiver_id', $me->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'partner'  => ['id' => $user->id, 'name' => $user->name, 'role' => $user->role],
            'messages' => $messages,
        ]);
    }

    /** JSON: search users to start a new conversation */
    public function searchUsers(Request $request)
    {
        $me   = Auth::user();
        $q    = $request->input('q', '');
        $role = $me->role === 'teacher' ? 'student' : 'teacher';
        $users = User::where('role', $role)
            ->where('name', 'like', "%{$q}%")
            ->limit(12)
            ->get(['id', 'name', 'role']);
        return response()->json($users);
    }

    /** Send a message — returns JSON if AJAX, redirect otherwise */
    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'body'        => 'required|string|max:2000',
        ]);

        $msg = Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'subject'     => $request->input('subject', 'Message'),
            'body'        => $request->body,
        ]);

        UserNotification::create([
            'user_id' => $request->receiver_id,
            'title'   => 'New Message',
            'message' => Auth::user()->name . ' sent you a message.',
            'type'    => 'message',
        ]);

        return ($request->expectsJson() || $request->ajax())
            ? response()->json(['ok' => true, 'id' => $msg->id, 'body' => $msg->body, 'time' => $msg->created_at->format('M j, g:i A'), 'from_me' => true])
            : back()->with('success', 'Message sent!');
    }
}
