<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\User;
use App\Models\UserNotification;

class SupportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:150',
            'body'    => 'required|string|max:2000',
        ]);

        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            return back()->with('error', 'Support is unavailable right now. Please try again later.');
        }

        Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $admin->id,
            'subject'     => $request->subject,
            'body'        => $request->body,
        ]);

        // Notify the admin
        UserNotification::create([
            'user_id' => $admin->id,
            'title'   => 'New Support Message',
            'message' => Auth::user()->name . ' sent a support request: "' . \Str::limit($request->subject, 60) . '"',
            'type'    => 'message',
            'link'    => route('admin.support'),
        ]);

        return back()->with('support_success', 'Your message has been sent! We\'ll get back to you soon.');
    }
}
