<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Activity;
use App\Models\ActivitySubmission;
use App\Models\Progress;
use App\Models\Module;
use App\Models\Section;
use App\Models\User;
use App\Models\LevelProgress;
use App\Models\Achievement;
use App\Models\UserNotification;
use App\Services\BadgeService;

class ActivityController extends Controller
{
    /** Student: see all assigned activities (filtered to their section) */
    public function studentIndex()
    {
        $student    = Auth::user();
        $activities = Activity::where('is_active', true)
            ->where(function ($q) use ($student) {
                $q->whereNull('section_id')->orWhere('section_id', $student->section_id);
            })
            ->with('module')
            ->orderBy('deadline')
            ->get();

        $submissions = ActivitySubmission::where('user_id', $student->id)
            ->get()->keyBy('activity_id');

        return view('student.activities', compact('activities', 'submissions', 'student'));
    }

    /** Student: view and do one activity */
    public function show(Activity $activity)
    {
        $student = Auth::user();

        if ($activity->opens_at && now()->lt($activity->opens_at)) {
            return redirect()->route('student.activities')
                ->with('error', 'This activity opens on ' . $activity->opens_at->format('M d, Y \a\t g:i A') . '.');
        }

        $submission = ActivitySubmission::where('activity_id', $activity->id)
            ->where('user_id', $student->id)
            ->first();

        return view('student.activity-detail', compact('activity', 'submission', 'student'));
    }

    /** Student: submit quiz answers */
    public function submit(Request $request, Activity $activity)
    {
        $student = Auth::user();

        if ($activity->opens_at && now()->lt($activity->opens_at)) {
            return redirect()->route('student.activities')
                ->with('error', 'This activity is not yet available.');
        }

        // Don't allow double submissions
        $existing = ActivitySubmission::where('activity_id', $activity->id)
            ->where('user_id', $student->id)->first();

        if ($existing) {
            return back()->with('info', 'You already completed this activity!');
        }

        // Calculate score for quiz type
        $score       = 0;
        $starsEarned = 0;

        if ($activity->type === 'quiz' && isset($activity->content['questions'])) {
            $questions = $activity->content['questions'];
            $answers   = $request->input('answers', []);
            $correct   = 0;

            foreach ($questions as $i => $question) {
                $correctKey = $question['correct'] ?? $question['answer'] ?? null;
                if (isset($answers[$i]) && $answers[$i] === $correctKey) {
                    $correct++;
                }
            }

            $score = count($questions) > 0
                ? round(($correct / count($questions)) * 100)
                : 0;

            // Award stars based on score
            if ($score >= 90)      $starsEarned = 3;
            elseif ($score >= 60)  $starsEarned = 2;
            else                   $starsEarned = 1;
        } else {
            // For non-quiz activities (video watching, drag-drop), give full stars
            $score       = 100;
            $starsEarned = $activity->stars_reward;
        }

        // Save submission
        ActivitySubmission::create([
            'activity_id'  => $activity->id,
            'user_id'      => $student->id,
            'score'        => $score,
            'stars_earned' => $starsEarned,
            'answers'      => $request->input('answers'),
            'completed_at' => now(),
        ]);

        // Update module progress stars
        $progress = Progress::firstOrCreate(
            ['user_id' => $student->id, 'module_id' => $activity->module_id],
            ['stars_earned' => 0, 'time_spent' => 0]
        );
        $progress->increment('stars_earned', $starsEarned);
        $progress->update(['last_activity_at' => now()]);

        // Auto-complete the level if every activity in it is now submitted
        $this->autoCompleteLevel($student, $activity);

        // Send notification
        UserNotification::create([
            'user_id' => $student->id,
            'title'   => 'Activity Complete!',
            'message' => "You earned {$starsEarned} stars on \"{$activity->title}\"!",
            'type'    => 'achievement',
        ]);

        BadgeService::award($student);

        return redirect()->route('student.modules.show', $activity->module_id)
            ->with('success', "You scored {$score}% and earned {$starsEarned} stars!");
    }

    /** Student: review their answers for a completed quiz */
    public function review(Activity $activity)
    {
        $student = Auth::user();

        $submission = ActivitySubmission::where('activity_id', $activity->id)
            ->where('user_id', $student->id)
            ->firstOrFail();

        $questions = $activity->content['questions'] ?? [];
        $answers   = $submission->answers ?? [];

        $review = [];
        foreach ($questions as $i => $q) {
            $given   = $answers[$i] ?? null;
            $correct = $q['correct'] ?? $q['answer'] ?? null;
            $review[] = [
                'text'      => $q['text'] ?? $q['question'] ?? '',
                'options'   => $q['options'] ?? [],
                'correct'   => $correct,
                'given'     => $given,
                'is_right'  => $given !== null && $given === $correct,
                'image'     => $q['image'] ?? null,
            ];
        }

        return view('student.activity-review', compact('activity', 'submission', 'review'));
    }

    /** Student: upload a completed worksheet or drawing */
    public function upload(Request $request, Activity $activity)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // max 5MB
        ]);

        $student = Auth::user();
        $path = $request->file('file')->store('submissions', 'public');

        ActivitySubmission::updateOrCreate(
            ['activity_id' => $activity->id, 'user_id' => $student->id],
            ['file_path' => $path, 'completed_at' => now(), 'score' => 100, 'stars_earned' => $activity->stars_reward]
        );

        return back()->with('success', 'Work uploaded successfully! Great job! 🎨');
    }

    /** Auto-complete a level (and module) when the last activity is submitted */
    private function autoCompleteLevel($student, Activity $activity): void
    {
        $level     = $activity->level_number;
        $moduleId  = $activity->module_id;

        $levelActivityIds = Activity::where('module_id', $moduleId)
            ->where('level_number', $level)
            ->where('is_active', true)
            ->pluck('id');

        $doneCount = ActivitySubmission::where('user_id', $student->id)
            ->whereIn('activity_id', $levelActivityIds)
            ->count();

        if ($doneCount < $levelActivityIds->count()) return;

        // All done — mark level complete (idempotent)
        $already = LevelProgress::where('user_id', $student->id)
            ->where('module_id', $moduleId)
            ->where('level_number', $level)
            ->where('completed', true)
            ->exists();

        if ($already) return;

        $stars = ActivitySubmission::where('user_id', $student->id)
            ->whereIn('activity_id', $levelActivityIds)
            ->sum('stars_earned');

        LevelProgress::updateOrCreate(
            ['user_id' => $student->id, 'module_id' => $moduleId, 'level_number' => $level],
            ['completed' => true, 'stars_earned' => $stars, 'completed_at' => now()]
        );

        // Check if every level in the module is now complete
        $allLevelNums = Activity::where('module_id', $moduleId)
            ->where('is_active', true)
            ->distinct()->pluck('level_number');

        $completedLevels = LevelProgress::where('user_id', $student->id)
            ->where('module_id', $moduleId)
            ->where('completed', true)
            ->whereIn('level_number', $allLevelNums)
            ->count();

        if ($completedLevels < $allLevelNums->count()) return;

        $module = Module::find($moduleId);
        $progress = Progress::where('user_id', $student->id)->where('module_id', $moduleId)->first();
        if ($progress && !$progress->completed) {
            $progress->update(['completed' => true, 'last_activity_at' => now()]);

            Achievement::create([
                'user_id'     => $student->id,
                'title'       => ($module->title ?? 'Module') . ' Complete!',
                'description' => 'You finished the ' . ($module->title ?? 'module') . '!',
                'icon'        => $module->icon ?? '🏆',
                'type'        => 'milestone',
                'earned_at'   => now(),
            ]);

            UserNotification::create([
                'user_id' => $student->id,
                'title'   => 'Module Complete!',
                'message' => 'You completed ' . ($module->title ?? 'the module') . '! Amazing work!',
                'type'    => 'achievement',
            ]);

            BadgeService::award($student);
        }
    }

    /** Student: submit tracing drawing (base64 PNG) */
    public function tracingSubmit(Request $request, Activity $activity)
    {
        $request->validate(['drawing' => 'required|string']);

        $student = Auth::user();

        $dataUri = $request->input('drawing');
        if (!preg_match('/^data:image\/png;base64,/', $dataUri)) {
            return back()->with('error', 'Invalid drawing data.');
        }

        $imageData = base64_decode(substr($dataUri, strpos($dataUri, ',') + 1));
        $filename  = 'tracing/' . $activity->id . '/' . $student->id . '_' . time() . '.png';
        Storage::disk('public')->put($filename, $imageData);

        ActivitySubmission::updateOrCreate(
            ['activity_id' => $activity->id, 'user_id' => $student->id],
            ['file_path' => $filename, 'completed_at' => now(), 'score' => 0, 'stars_earned' => 0]
        );

        $progress = Progress::firstOrCreate(
            ['user_id' => $student->id, 'module_id' => $activity->module_id],
            ['stars_earned' => 0, 'time_spent' => 0]
        );
        $progress->update(['last_activity_at' => now()]);

        UserNotification::create([
            'user_id' => $student->id,
            'title'   => 'Tracing Submitted!',
            'message' => "Your tracing for \"{$activity->title}\" has been submitted for review.",
            'type'    => 'info',
        ]);

        return redirect()->route('student.modules.show', $activity->module_id)
            ->with('success', 'Your tracing was submitted! Your teacher will grade it soon.');
    }

    /** Teacher: redirect to first submission for a tracing activity */
    public function gradeIndex(Activity $activity)
    {
        $first = ActivitySubmission::where('activity_id', $activity->id)
            ->orderBy('created_at')
            ->first();

        if (!$first) {
            return redirect()->route('teacher.activities')
                ->with('info', 'No submissions yet for this activity.');
        }

        return redirect()->route('teacher.activities.grade.show', [$activity, $first]);
    }

    /** Teacher: view one submission for grading */
    public function gradeShow(Activity $activity, ActivitySubmission $submission)
    {
        $submissions  = ActivitySubmission::where('activity_id', $activity->id)
            ->with('user')
            ->orderBy('created_at')
            ->get();

        $currentIndex = $submissions->search(fn($s) => $s->id === $submission->id);
        $prev = $currentIndex > 0 ? $submissions[$currentIndex - 1] : null;
        $next = $currentIndex < $submissions->count() - 1 ? $submissions[$currentIndex + 1] : null;

        return view('teacher.tracing-grade', compact('activity', 'submission', 'submissions', 'currentIndex', 'prev', 'next'));
    }

    /** Teacher: save a star rating and optional feedback for a tracing submission */
    public function gradeSave(Request $request, Activity $activity, ActivitySubmission $submission)
    {
        $request->validate([
            'stars'    => 'required|integer|min:1|max:3',
            'feedback' => 'nullable|string|max:500',
        ]);

        $wasGraded = $submission->score > 0;
        $oldStars  = $submission->stars_earned;
        $newStars  = (int) $request->input('stars');
        $score     = $newStars >= 3 ? 100 : ($newStars === 2 ? 67 : 33);

        $submission->update([
            'score'        => $score,
            'stars_earned' => $newStars,
            'feedback'     => $request->input('feedback'),
            'completed_at' => $submission->completed_at ?? now(),
        ]);

        $student  = $submission->user;
        $progress = Progress::firstOrCreate(
            ['user_id' => $student->id, 'module_id' => $activity->module_id],
            ['stars_earned' => 0, 'time_spent' => 0]
        );
        $delta = $newStars - ($wasGraded ? $oldStars : 0);
        if ($delta !== 0) $progress->increment('stars_earned', $delta);
        $progress->update(['last_activity_at' => now()]);

        UserNotification::create([
            'user_id' => $student->id,
            'title'   => 'Tracing Graded!',
            'message' => "Your tracing for \"{$activity->title}\" received {$newStars} " . ($newStars !== 1 ? 'stars' : 'star') . '!',
            'type'    => 'achievement',
        ]);

        $this->autoCompleteLevel($student, $activity);

        $next = ActivitySubmission::where('activity_id', $activity->id)
            ->where('id', '>', $submission->id)
            ->orderBy('id')
            ->first();

        if ($next) {
            return redirect()->route('teacher.activities.grade.show', [$activity, $next])
                ->with('success', 'Grade saved!');
        }

        return redirect()->route('teacher.activities')
            ->with('success', 'All submissions graded!');
    }

    // -----------------------------------------------
    // TEACHER METHODS
    // -----------------------------------------------

    /** Teacher: show question editor for a quiz activity */
    public function editQuestions(Activity $activity)
    {
        $questions = $activity->content['questions'] ?? [];
        return view('teacher.question-editor', compact('activity', 'questions'));
    }

    /** Teacher: save edited questions (text, options, correct, image) */
    public function updateQuestions(Request $request, Activity $activity)
    {
        $incoming  = $request->input('questions', []);
        $images    = $request->file('question_images', []);
        $questions = [];

        foreach ($incoming as $i => $q) {
            $question = [
                'text'    => $q['text'] ?? '',
                'options' => array_values(array_filter(array_map('trim', $q['options'] ?? []))),
                'correct' => $q['correct'] ?? '',
                'image'   => $q['image'] ?? null, // existing stored path
            ];

            // Handle newly uploaded image for this question
            if (isset($images[$i]) && $images[$i]->isValid()) {
                $path = $images[$i]->store("question-images/activity-{$activity->id}", 'public');
                $question['image'] = $path;
            }

            if ($question['text'] && count($question['options']) >= 2) {
                $questions[] = $question;
            }
        }

        $content               = $activity->content ?? [];
        $content['questions']  = $questions;
        $activity->update(['content' => $content]);

        return redirect()->route('teacher.activities.questions', $activity)
            ->with('success', 'Questions saved!');
    }

    public function teacherIndex()
    {
        $activities = Activity::with('module')->latest()->get();
        return view('teacher.activities', compact('activities'));
    }

    public function create()
    {
        $modules  = Module::where('is_active', true)->get();
        $sections = Section::where('teacher_id', Auth::id())->get();
        return view('teacher.activity-form', ['activity' => null, 'modules' => $modules, 'sections' => $sections]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'module_id'    => 'required|exists:modules,id',
            'section_id'   => 'required|exists:sections,id',
            'title'        => 'required|string|max:100',
            'type'         => 'required|in:video,quiz,matching,drag_drop,coloring,audio,tracing',
            'stars_reward' => 'nullable|integer|min:1|max:5',
        ]);

        // Ensure section belongs to this teacher
        $section = Section::where('id', $request->section_id)
            ->where('teacher_id', Auth::id())->firstOrFail();

        $data = $request->only('module_id', 'section_id', 'title', 'type', 'stars_reward', 'opens_at', 'deadline', 'order');

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('activities', 'public');
        }

        if ($request->has('content')) {
            $data['content'] = json_decode($request->content, true);
        }

        Activity::create($data);

        // Notify students in the selected class
        $module    = Module::find($request->module_id);
        $students  = User::where('section_id', $section->id)->where('role', 'student')->get();
        $moduleUrl = route('student.modules.show', $module->id);
        foreach ($students as $student) {
            UserNotification::create([
                'user_id' => $student->id,
                'title'   => 'New Activity Added!',
                'message' => "Your teacher added \"{$request->title}\" to {$module->title}!",
                'type'    => 'activity',
                'link'    => $moduleUrl,
            ]);
        }

        return redirect()->route('teacher.activities')->with('success', 'Activity created!');
    }

    public function edit(Activity $activity)
    {
        $modules  = Module::where('is_active', true)->get();
        $sections = Section::where('teacher_id', Auth::id())->get();
        return view('teacher.activity-form', compact('activity', 'modules', 'sections'));
    }

    public function update(Request $request, Activity $activity)
    {
        $request->validate(['title' => 'required|string|max:100']);
        $data = $request->only('module_id', 'section_id', 'title', 'type', 'stars_reward', 'opens_at', 'deadline', 'order');
        $data['is_active'] = $request->boolean('is_active');
        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('activities', 'public');
        }
        $activity->update($data);
        return redirect()->route('teacher.activities')->with('success', 'Activity updated!');
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();
        return back()->with('success', 'Activity deleted.');
    }
}
