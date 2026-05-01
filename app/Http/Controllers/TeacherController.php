<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Section;
use App\Models\Module;
use App\Models\Progress;
use App\Models\ActivitySubmission;
use App\Models\Announcement;

class TeacherController extends Controller
{
    /**
     * Teacher dashboard - overview of class and student activity.
     */
    public function profile()
    {
        $teacher      = Auth::user();
        $sections     = Section::where('teacher_id', $teacher->id)->withCount('students')->get();
        $totalStudents = $sections->sum('students_count');
        $totalModules  = Module::where('teacher_id', $teacher->id)->count();

        return view('teacher.profile', compact('teacher', 'sections', 'totalStudents', 'totalModules'));
    }

    public function updateProfile(Request $request)
    {
        $teacher = Auth::user();

        $rules = [
            'name' => 'required|string|max:100',
            'bio'  => 'nullable|string|max:300',
        ];

        if ($request->filled('current_password')) {
            $rules['current_password'] = ['required', function ($attr, $value, $fail) use ($teacher) {
                if (!Hash::check($value, $teacher->password)) {
                    $fail('Current password is incorrect.');
                }
            }];
            $rules['new_password'] = 'required|min:6|confirmed';
        }

        $request->validate($rules);

        $teacher->name = $request->name;
        $teacher->bio  = $request->bio;

        if ($request->filled('current_password')) {
            $teacher->password = Hash::make($request->new_password);
        }

        $teacher->save();

        return back()->with('success', 'Profile updated!');
    }

    public function settings()
    {
        return view('teacher.settings');
    }

    public function avatar()
    {
        return view('teacher.avatar', ['teacher' => Auth::user()]);
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate(['photo' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:3072']);

        $teacher = Auth::user();
        if (str_starts_with($teacher->avatar ?? '', 'avatars/')) {
            Storage::disk('public')->delete($teacher->avatar);
        }

        $path = $request->file('photo')->store('avatars', 'public');
        $teacher->avatar = $path;
        $teacher->save();

        return back()->with('success', 'Profile photo updated!');
    }

    public function dashboard()
    {
        $teacher = Auth::user();

        // Get all sections (classes) taught by this teacher
        $sections = Section::where('teacher_id', $teacher->id)
            ->withCount('students')
            ->get();

        // Total student count
        $totalStudents = User::where('role', 'student')
            ->whereIn('section_id', $sections->pluck('id'))
            ->count();

        // Recent student submissions (last 10)
        $recentSubmissions = ActivitySubmission::with(['user', 'activity.module'])
            ->whereHas('user', function ($q) use ($sections) {
                $q->whereIn('section_id', $sections->pluck('id'));
            })
            ->latest()
            ->take(10)
            ->get();

        // Module completion stats
        $modules = Module::where('teacher_id', $teacher->id)
            ->orWhereNull('teacher_id')
            ->withCount('activities')
            ->get();

        return view('teacher.dashboard', compact(
            'teacher',
            'sections',
            'totalStudents',
            'recentSubmissions',
            'modules'
        ));
    }

    /**
     * Show all classes this teacher manages.
     */
    public function classes()
    {
        $teacher = Auth::user();

        $sections = Section::where('teacher_id', $teacher->id)
            ->with('students')
            ->get();

        return view('teacher.classes', compact('teacher', 'sections'));
    }

    /**
     * Create a new class.
     */
    public function createClass(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        Section::create([
            'name'        => $request->name,
            'teacher_id'  => Auth::id(),
            'join_code'   => strtoupper(Str::random(6)), // e.g., "XK92TL"
            'description' => $request->description,
        ]);

        return back()->with('success', 'Class created! Share the join code with students.');
    }

    /**
     * Show one class and its students.
     */
    public function showClass(Section $class)
    {
        // Make sure this teacher owns this class
        if ($class->teacher_id !== Auth::id()) {
            abort(403, 'You do not have access to this class.');
        }

        $students = User::where('section_id', $class->id)->get();

        return view('teacher.class-detail', compact('class', 'students'));
    }

    /**
     * Remove a student from a class.
     */
    public function removeStudent(Section $class, Request $request)
    {
        if ($class->teacher_id !== Auth::id()) {
            abort(403);
        }

        $request->validate(['student_id' => 'required|exists:users,id']);

        User::where('id', $request->student_id)
            ->where('section_id', $class->id)
            ->update(['section_id' => null]);

        return back()->with('success', 'Student removed from class.');
    }

    public function destroyClass(Section $class)
    {
        if ($class->teacher_id !== Auth::id()) {
            abort(403);
        }

        // Unlink students before deleting
        User::where('section_id', $class->id)->update(['section_id' => null]);
        $class->delete();

        return redirect()->route('teacher.classes')->with('success', 'Class deleted.');
    }

    /**
     * Show all students across all classes.
     */
    public function students()
    {
        $teacher = Auth::user();

        $sectionIds = Section::where('teacher_id', $teacher->id)->pluck('id');

        $students = User::where('role', 'student')
            ->whereIn('section_id', $sectionIds)
            ->with(['section', 'progresses', 'achievements'])
            ->get();

        return view('teacher.students', compact('students'));
    }

    /**
     * Show one student's full profile and progress.
     */
    public function showStudent(User $student)
    {
        $teacher = Auth::user();

        // Make sure this student is in one of the teacher's classes
        $sectionIds = Section::where('teacher_id', $teacher->id)->pluck('id');
        if (!in_array($student->section_id, $sectionIds->toArray())) {
            abort(403);
        }

        $modules    = Module::where('is_active', true)->get();
        $progresses = Progress::where('user_id', $student->id)->get()->keyBy('module_id');
        $submissions = ActivitySubmission::where('user_id', $student->id)
            ->with('activity.module')
            ->latest()
            ->get();

        return view('teacher.student-detail', compact('student', 'modules', 'progresses', 'submissions'));
    }

    /**
     * Class progress and analytics overview.
     */
    public function progress()
    {
        $teacher = Auth::user();
        $sectionIds = Section::where('teacher_id', $teacher->id)->pluck('id');

        $students = User::where('role', 'student')
            ->whereIn('section_id', $sectionIds)
            ->with('progresses')
            ->get();

        $modules = Module::where('is_active', true)->get();

        $progresses = Progress::whereIn('user_id', $students->pluck('id'))
            ->get()
            ->groupBy('user_id')
            ->map(fn($g) => $g->keyBy('module_id'));

        return view('teacher.progress', compact('students', 'modules', 'progresses'));
    }

    /**
     * Analytics page with charts.
     */
    public function analytics()
    {
        $teacher = Auth::user();
        $sectionIds = Section::where('teacher_id', $teacher->id)->pluck('id');

        // Completion rates per module
        $modules = Module::where('is_active', true)->get()->map(function ($module) use ($sectionIds) {
            $totalStudents = User::whereIn('section_id', $sectionIds)->where('role', 'student')->count();
            $completed = Progress::where('module_id', $module->id)
                ->whereHas('user', fn($q) => $q->whereIn('section_id', $sectionIds))
                ->where('completed', true)
                ->count();

            $module->completion_rate = $totalStudents > 0 ? round(($completed / $totalStudents) * 100) : 0;
            return $module;
        });

        return view('teacher.analytics', compact('modules'));
    }

    public function notificationsPreview()
    {
        $teacher = auth()->user();
        $announcements = Announcement::where('teacher_id', $teacher->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($a) => [
                'icon'  => '📢',
                'title' => 'Your announcement: ' . $a->title,
                'body'  => \Str::limit($a->body, 60),
            ]);

        return response()->json($announcements->values());
    }
}
