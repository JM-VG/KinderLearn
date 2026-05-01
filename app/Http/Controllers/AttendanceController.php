<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\Section;
use App\Models\User;

class AttendanceController extends Controller
{
    /** Show attendance form for today */
    public function index()
    {
        $teacher  = Auth::user();
        $sections = Section::where('teacher_id', $teacher->id)->with('students')->get();
        $today    = now()->toDateString();

        // Load today's attendance records
        $todayAttendance = Attendance::whereDate('date', $today)
            ->whereIn('section_id', $sections->pluck('id'))
            ->get()
            ->keyBy('user_id');

        return view('teacher.attendance', compact('sections', 'todayAttendance', 'today', 'teacher'));
    }

    /** Save attendance records */
    public function record(Request $request)
    {
        $request->validate([
            'section_id'    => 'required|exists:sections,id',
            'date'          => 'required|date',
            'attendance'    => 'required|array',
            'attendance.*'  => 'in:present,absent,late',
        ]);

        foreach ($request->attendance as $studentId => $status) {
            Attendance::updateOrCreate(
                ['user_id' => $studentId, 'date' => $request->date],
                ['section_id' => $request->section_id, 'status' => $status]
            );
        }

        return back()->with('success', 'Attendance saved!');
    }

    /** View attendance history */
    public function history()
    {
        $teacher    = Auth::user();
        $sectionIds = Section::where('teacher_id', $teacher->id)->pluck('id');

        $records = Attendance::whereIn('section_id', $sectionIds)
            ->with(['student', 'section'])
            ->orderByDesc('date')
            ->paginate(30);

        return view('teacher.attendance-history', compact('records', 'teacher'));
    }
}
