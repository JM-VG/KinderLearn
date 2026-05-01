<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Module;
use App\Models\Progress;
use App\Models\Achievement;
use App\Models\Announcement;
use App\Models\UserNotification;
use App\Models\Section;
use App\Services\BadgeService;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentController extends Controller
{
    /**
     * Student dashboard - the first page they see after logging in.
     */
    public function dashboard()
    {
        $student = Auth::user();

        // Load all active modules with this student's progress
        $modules = Module::where('is_active', true)
            ->orderBy('order')
            ->get();

        // Get progress for each module
        $progresses = Progress::where('user_id', $student->id)
            ->get()
            ->keyBy('module_id'); // index by module_id for easy lookup

        // Get recent achievements (last 3)
        $recentAchievements = Achievement::where('user_id', $student->id)
            ->latest('earned_at')
            ->take(3)
            ->get();

        // Get announcements from the student's class
        $announcements = Announcement::where('section_id', $student->section_id)
            ->orWhereNull('section_id') // also get school-wide announcements
            ->orderByDesc('pinned')
            ->latest()
            ->take(5)
            ->get();

        // Calculate total stars
        $totalStars = $student->getTotalStars();
        $completedCount = $student->getCompletedModules();

        // Unread notification count for the bell icon
        $unreadCount = UserNotification::where('user_id', $student->id)
            ->whereNull('read_at')
            ->count();

        return view('student.dashboard', compact(
            'student',
            'modules',
            'progresses',
            'recentAchievements',
            'announcements',
            'totalStars',
            'completedCount',
            'unreadCount'
        ));
    }

    /**
     * Show student profile page.
     */
    public function profile()
    {
        $student = Auth::user();
        $achievements = Achievement::where('user_id', $student->id)
            ->orderByDesc('earned_at')
            ->get();
        $completedProgress = Progress::where('user_id', $student->id)
            ->where('completed', true)
            ->with('module')
            ->orderByDesc('updated_at')
            ->get();

        return view('student.profile', compact('student', 'achievements', 'completedProgress'));
    }

    /**
     * Update student name and PIN.
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'bio'  => 'nullable|string|max:300',
            'pin'  => 'nullable|digits:4',
        ]);

        $student = Auth::user();
        $student->name = $request->name;
        $student->bio  = $request->bio;

        if ($request->filled('pin')) {
            $student->pin = $request->pin;
        }

        $student->save();

        return back()->with('success', 'Profile updated!');
    }

    /**
     * Handle photo upload for avatar.
     */
    public function uploadAvatar(Request $request)
    {
        $request->validate(['photo' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:3072']);

        $student = Auth::user();
        // Delete old upload if exists
        if (str_starts_with($student->avatar ?? '', 'avatars/')) {
            Storage::disk('public')->delete($student->avatar);
        }

        $path = $request->file('photo')->store('avatars', 'public');
        $student->avatar = $path;
        $student->save();

        return back()->with('success', 'Profile photo updated!');
    }

    /**
     * Show printable certificate for a completed module.
     */
    public function certificate(Module $module)
    {
        $student = Auth::user();

        // Gate: all 5 core modules must be completed (test account is exempt)
        if ($student->email !== 'student@kinderlearn.com') {
            $requiredSubjects = ['alphabet', 'numbers', 'colors', 'shapes', 'words'];
            $completedSubjects = Progress::where('user_id', $student->id)
                ->where('completed', true)
                ->join('modules', 'progress.module_id', '=', 'modules.id')
                ->whereIn('modules.subject', $requiredSubjects)
                ->pluck('modules.subject')
                ->toArray();

            $missing = array_diff($requiredSubjects, $completedSubjects);
            if (!empty($missing)) {
                return redirect()->route('student.modules')
                    ->with('error', 'You need to complete all 5 modules before you can get a certificate!');
            }
        }

        $progress = Progress::where('user_id', $student->id)
            ->where('module_id', $module->id)
            ->where('completed', true)
            ->firstOrFail();

        $hasTemplate = file_exists(resource_path('templates/KinderLearn.pdf'));

        return view('student.certificate', compact('student', 'module', 'progress', 'hasTemplate'));
    }

    public function certificateTemplate()
    {
        $path = resource_path('templates/KinderLearn.pdf');
        abort_unless(file_exists($path), 404);
        return response()->file($path, ['Content-Type' => 'application/pdf']);
    }

    public function downloadCertificate(Module $module)
    {
        $student = Auth::user();

        // Gate: all 5 core modules must be completed (test account is exempt)
        if ($student->email !== 'student@kinderlearn.com') {
            $requiredSubjects = ['alphabet', 'numbers', 'colors', 'shapes', 'words'];
            $completedSubjects = Progress::where('user_id', $student->id)
                ->where('completed', true)
                ->join('modules', 'progress.module_id', '=', 'modules.id')
                ->whereIn('modules.subject', $requiredSubjects)
                ->pluck('modules.subject')
                ->toArray();

            $missing = array_diff($requiredSubjects, $completedSubjects);
            if (!empty($missing)) {
                return redirect()->route('student.modules')
                    ->with('error', 'You need to complete all 5 modules before you can get a certificate!');
            }
        }

        $progress = Progress::where('user_id', $student->id)
            ->where('module_id', $module->id)
            ->where('completed', true)
            ->firstOrFail();

        $templatePath = resource_path('templates/KinderLearn.pdf');
        $safeFilename = preg_replace('/[^a-zA-Z0-9 ._-]/', '_', $student->name)
                        . ' - Certificate.pdf';

        // Use the actual PDF template and stamp the student name on top
        if (file_exists($templatePath)) {
            try {
                $fpdi = new \setasign\Fpdi\Fpdi('L', 'mm', 'A4');
                $fpdi->SetAutoPageBreak(false);
                $fpdi->AddPage();
                $fpdi->setSourceFile($templatePath);
                $tplId = $fpdi->importPage(1);
                $fpdi->useTemplate($tplId, 0, 0, 297, 210);

                // ── Student name on the name line ──
                $fpdi->SetFont('Times', 'B', 32);
                $fpdi->SetTextColor(26, 26, 26);
                $name = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $student->name);
                $fpdi->SetXY(0, 104);
                $fpdi->Cell(297, 14, $name, 0, 0, 'C');

                $pdfBytes = $fpdi->Output('', 'S');

                return response($pdfBytes)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'attachment; filename="' . $safeFilename . '"');

            } catch (\Exception $e) {
                // Fall through to dompdf fallback if template can't be parsed
            }
        }

        // Fallback: generate from Blade template
        $pdf = Pdf::loadView('student.certificate-pdf', compact('student', 'module', 'progress'))
            ->setPaper('a4', 'landscape');

        return $pdf->download($safeFilename);
    }

    /**
     * Show all achievements.
     */
    public function achievements()
    {
        $student      = Auth::user();
        $achievements = Achievement::where('user_id', $student->id)
            ->orderByDesc('earned_at')
            ->get();
        $totalStars   = $student->getTotalStars();
        $catalogue    = BadgeService::catalogue($student);

        return view('student.achievements', compact('student', 'achievements', 'totalStars', 'catalogue'));
    }

    /**
     * Show progress overview across all modules.
     */
    public function progress()
    {
        $student = Auth::user();

        $modules = Module::where('is_active', true)->orderBy('order')->get();

        $progresses = Progress::where('user_id', $student->id)
            ->get()
            ->keyBy('module_id');

        $totalStars    = $student->getTotalStars();
        $completedCount = $student->getCompletedModules();

        return view('student.progress', compact('student', 'modules', 'progresses', 'totalStars', 'completedCount'));
    }

    /**
     * Show the student's joined class and allow joining a new one.
     */
    public function classes()
    {
        $student = Auth::user();
        $section = $student->section()->with('teacher')->first();

        return view('student.classes', compact('student', 'section'));
    }

    /**
     * Join a class by enter code.
     */
    public function joinClass(Request $request)
    {
        $request->validate([
            'join_code' => 'required|string|size:6',
        ]);

        $section = Section::where('join_code', strtoupper($request->join_code))->first();

        if (!$section) {
            return back()->withErrors(['join_code' => 'Invalid join code. Please try again.'])->withInput();
        }

        $student = Auth::user();
        $student->section_id = $section->id;
        $student->save();

        return back()->with('success', 'You joined ' . $section->name . '! 🎉');
    }

    /**
     * Show all notifications for this student.
     */
    public function notifications()
    {
        $student = Auth::user();

        $notifications = UserNotification::where('user_id', $student->id)
            ->latest()
            ->get();

        // Mark all as read when they open the notifications page
        UserNotification::where('user_id', $student->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('student.notifications', compact('student', 'notifications'));
    }

    public function settings()
    {
        return view('student.settings', ['student' => auth()->user()]);
    }

    public function notificationsPreview()
    {
        $notifications = UserNotification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($n) => [
                'icon'  => '🔔',
                'title' => $n->title ?? 'Notification',
                'body'  => $n->message ?? '',
                'link'  => $n->link,
            ]);

        // Also surface recent announcements (class-specific + school-wide)
        $announcements = Announcement::where(function ($q) {
                $q->whereNull('section_id')
                  ->orWhereHas('section', fn($sq) =>
                      $sq->whereHas('students', fn($s) => $s->where('id', auth()->id()))
                  );
            })
            ->latest()
            ->take(3)
            ->get()
            ->map(fn($a) => [
                'icon'  => '📢',
                'title' => $a->title,
                'body'  => \Str::limit($a->body, 60),
            ]);

        return response()->json($notifications->merge($announcements)->take(5)->values());
    }
}
