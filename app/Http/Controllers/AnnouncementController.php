<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Announcement;
use App\Models\Section;

class AnnouncementController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        $sections = Section::where('teacher_id', $teacher->id)->get();
        $announcements = Announcement::where('teacher_id', $teacher->id)
            ->with('section')
            ->latest()
            ->get();

        return view('teacher.announcements', compact('announcements', 'sections', 'teacher'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:200',
            'body'       => 'required|string|max:2000',
            'section_id' => 'nullable|exists:sections,id',
        ]);

        Announcement::create([
            'teacher_id' => Auth::id(),
            'section_id' => $request->section_id ?: null,
            'title'      => $request->title,
            'body'       => $request->body,
            'pinned'     => $request->boolean('pinned'),
        ]);

        return back()->with('success', 'Announcement posted!');
    }

    public function update(Request $request, Announcement $announcement)
    {
        if ($announcement->teacher_id !== Auth::id()) abort(403);

        $request->validate([
            'title' => 'required|string|max:200',
            'body'  => 'required|string|max:2000',
        ]);

        $announcement->update([
            'title'  => $request->title,
            'body'   => $request->body,
            'pinned' => $request->boolean('pinned'),
        ]);

        return back()->with('success', 'Announcement updated!');
    }

    public function destroy(Announcement $announcement)
    {
        if ($announcement->teacher_id !== Auth::id()) abort(403);
        $announcement->delete();
        return back()->with('success', 'Announcement deleted.');
    }
}
