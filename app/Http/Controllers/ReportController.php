<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Progress;
use App\Models\Section;

class ReportController extends Controller
{
    public function index()
    {
        $teacher    = Auth::user();
        $sectionIds = Section::where('teacher_id', $teacher->id)->pluck('id');

        $students = User::where('role', 'student')
            ->whereIn('section_id', $sectionIds)
            ->with(['progresses', 'attendances', 'achievements'])
            ->get();

        return view('teacher.reports', compact('students', 'teacher'));
    }

    /**
     * Generate a simple CSV report for download.
     * Type: 'progress' or 'attendance'
     */
    public function download(string $type)
    {
        $teacher    = Auth::user();
        $sectionIds = Section::where('teacher_id', $teacher->id)->pluck('id');

        $students = User::where('role', 'student')
            ->whereIn('section_id', $sectionIds)
            ->with(['progresses'])
            ->get();

        $filename = "kinderlearn_{$type}_report_" . now()->format('Ymd') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($students, $type) {
            $file = fopen('php://output', 'w');

            if ($type === 'progress') {
                fputcsv($file, ['Student Name', 'Modules Completed', 'Total Stars', 'Section']);
                foreach ($students as $s) {
                    fputcsv($file, [
                        $s->name,
                        $s->getCompletedModules(),
                        $s->getTotalStars(),
                        $s->section->name ?? 'No class',
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
