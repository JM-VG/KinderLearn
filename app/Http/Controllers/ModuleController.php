<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Module;
use App\Models\Activity;
use App\Models\Progress;
use App\Models\ActivitySubmission;
use App\Models\LevelProgress;
use App\Models\Achievement;
use App\Models\UserNotification;
use App\Models\Section;
use App\Models\User;
use App\Services\BadgeService;

class ModuleController extends Controller
{
    /** Student: list all modules with per-section progress */
    public function studentIndex()
    {
        $student = Auth::user();

        if (!$student->section_id) {
            return redirect()->route('student.classes')
                ->with('error', 'You need to join a class before you can access lessons.');
        }

        $modules    = Module::where('is_active', true)->orderBy('order')->get();
        $progresses = Progress::where('user_id', $student->id)->get()->keyBy('module_id');

        // Per-module activity progress filtered to this student's section
        $activityProgress = [];
        foreach ($modules as $mod) {
            $actIds = Activity::where('module_id', $mod->id)
                ->where('is_active', true)
                ->where(function ($q) use ($student) {
                    $q->whereNull('section_id')->orWhere('section_id', $student->section_id);
                })
                ->pluck('id');

            $total = $actIds->count();
            $done  = $total > 0
                ? ActivitySubmission::where('user_id', $student->id)
                    ->whereIn('activity_id', $actIds)->count()
                : 0;

            $activityProgress[$mod->id] = [
                'total' => $total,
                'done'  => $done,
                'pct'   => $total > 0 ? (int) round($done / $total * 100) : 0,
            ];
        }

        return view('student.modules', compact('modules', 'progresses', 'student', 'activityProgress'));
    }

    /** Student: view one module — only shows activities for their section */
    public function show(Module $module)
    {
        $student = Auth::user();

        if (!$student->section_id) {
            return redirect()->route('student.classes')
                ->with('error', 'You need to join a class before you can access lessons.');
        }

        $allActivities = Activity::where('module_id', $module->id)
            ->where('is_active', true)
            ->where(function ($q) use ($student) {
                $q->whereNull('section_id')->orWhere('section_id', $student->section_id);
            })
            ->orderBy('level_number')
            ->orderBy('order')
            ->get();

        $activitiesByLevel = $allActivities->groupBy('level_number');
        $levelNumbers      = $activitiesByLevel->keys()->sort()->values();

        $submissions = ActivitySubmission::where('user_id', $student->id)
            ->whereIn('activity_id', $allActivities->pluck('id'))
            ->get()->keyBy('activity_id');

        $levelProgresses = LevelProgress::where('user_id', $student->id)
            ->where('module_id', $module->id)
            ->get()->keyBy('level_number');

        $unlockedLevels = [];
        foreach ($levelNumbers as $num) {
            $unlockedLevels[$num] = ($num == 1)
                || ($levelProgresses->get($num - 1)?->completed ?? false);
        }

        $progress = Progress::firstOrCreate(
            ['user_id' => $student->id, 'module_id' => $module->id],
            ['stars_earned' => 0, 'time_spent' => 0]
        );

        // Recalculate progress to reflect any newly added activities
        $totalActs     = $allActivities->count();
        $completedActs = $submissions->count();
        $progressPct   = $totalActs > 0 ? (int) round($completedActs / $totalActs * 100) : 0;

        return view('student.module-detail', compact(
            'module', 'activitiesByLevel', 'levelNumbers',
            'submissions', 'levelProgresses', 'unlockedLevels',
            'progress', 'student', 'progressPct'
        ));
    }

    /** Mark a single level complete and unlock the next */
    public function markLevelComplete(Module $module, int $level)
    {
        $student = Auth::user();

        $activityIds = Activity::where('module_id', $module->id)
            ->where('level_number', $level)
            ->where('is_active', true)
            ->where(function ($q) use ($student) {
                $q->whereNull('section_id')->orWhere('section_id', $student->section_id);
            })
            ->pluck('id');

        $doneCount = ActivitySubmission::where('user_id', $student->id)
            ->whereIn('activity_id', $activityIds)->count();

        if ($doneCount < $activityIds->count()) {
            return back()->with('error', 'Finish all activities in this level first!');
        }

        $stars = ActivitySubmission::where('user_id', $student->id)
            ->whereIn('activity_id', $activityIds)->sum('stars_earned');

        LevelProgress::updateOrCreate(
            ['user_id' => $student->id, 'module_id' => $module->id, 'level_number' => $level],
            ['completed' => true, 'stars_earned' => $stars, 'completed_at' => now()]
        );

        $allLevelNums = Activity::where('module_id', $module->id)->where('is_active', true)
            ->where(function ($q) use ($student) {
                $q->whereNull('section_id')->orWhere('section_id', $student->section_id);
            })
            ->distinct()->pluck('level_number');

        $completedLevels = LevelProgress::where('user_id', $student->id)
            ->where('module_id', $module->id)->where('completed', true)
            ->whereIn('level_number', $allLevelNums)->count();

        if ($completedLevels >= $allLevelNums->count()) {
            $progress = Progress::where('user_id', $student->id)->where('module_id', $module->id)->first();
            if ($progress && !$progress->completed) {
                $progress->update(['completed' => true, 'last_activity_at' => now()]);
                Achievement::create([
                    'user_id' => $student->id, 'title' => $module->title . ' Complete!',
                    'description' => 'You finished the ' . $module->title . ' module!',
                    'icon' => $module->icon, 'type' => 'milestone', 'earned_at' => now(),
                ]);
                UserNotification::create([
                    'user_id' => $student->id, 'title' => 'Module Complete!',
                    'message' => 'You completed ' . $module->title . '! Amazing work!',
                    'type' => 'achievement',
                ]);
                BadgeService::award($student);
            }
        }

        return back()->with('success', 'Level ' . $level . ' complete! Keep going!');
    }

    /** Mark a module as complete manually */
    public function markComplete(Module $module)
    {
        $student  = Auth::user();
        $progress = Progress::where('user_id', $student->id)->where('module_id', $module->id)->first();

        if ($progress && !$progress->completed) {
            $progress->update(['completed' => true, 'last_activity_at' => now()]);
            Achievement::create([
                'user_id' => $student->id, 'title' => $module->title . ' Complete!',
                'description' => 'You finished the ' . $module->title . ' module!',
                'icon' => $module->icon, 'type' => 'milestone', 'earned_at' => now(),
            ]);
            UserNotification::create([
                'user_id' => $student->id, 'title' => 'Module Complete!',
                'message' => 'You completed ' . $module->title . '! Amazing work!',
                'type' => 'achievement',
            ]);
            BadgeService::award($student);
        }

        return back()->with('success', 'Great job! Module complete!');
    }

    /** Teacher: list all modules */
    public function teacherIndex()
    {
        $modules = Module::withCount('activities')->orderBy('order')->get();
        return view('teacher.modules', compact('modules'));
    }

    public function create()
    {
        return view('teacher.module-form', ['module' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:100',
            'subject'     => 'required|in:alphabet,numbers,colors,shapes,words',
            'description' => 'nullable|string',
            'icon'        => 'nullable|string|max:10',
            'color'       => 'nullable|string|max:20',
        ]);

        $moduleData = $request->only('title', 'subject', 'description', 'icon', 'color');
        $moduleData['teacher_id'] = Auth::id();
        Module::create($moduleData);

        return redirect()->route('teacher.modules')->with('success', 'Module created!');
    }

    public function edit(Module $module)
    {
        $activitiesByLevel = Activity::where('module_id', $module->id)
            ->orderBy('level_number')->orderBy('order')
            ->get()->groupBy('level_number');

        $levelNumbers = $activitiesByLevel->keys()->sort()->values();
        $sections     = Section::where('teacher_id', Auth::id())->get();

        return view('teacher.module-form', compact('module', 'activitiesByLevel', 'levelNumbers', 'sections'));
    }

    public function update(Request $request, Module $module)
    {
        $request->validate(['title' => 'required|string|max:100']);

        $data = $request->only('title', 'subject', 'description', 'icon', 'color', 'is_active');
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('module-covers', 'public');
        }

        $module->update($data);
        return redirect()->route('teacher.modules.edit', $module)->with('success', 'Module updated!');
    }

    /** Set opens_at + deadline for every activity in a level */
    public function updateLevelSchedule(Request $request, Module $module, int $level)
    {
        $request->validate([
            'opens_at' => 'nullable|date',
            'deadline' => 'nullable|date',
        ]);

        Activity::where('module_id', $module->id)->where('level_number', $level)
            ->update(['opens_at' => $request->opens_at ?: null, 'deadline' => $request->deadline ?: null]);

        return back()->with('success', "Level {$level} schedule saved.");
    }

    /**
     * Add a new level — teacher picks type, class, and fills type-specific fields.
     * For tracing: uploads a JPG file.
     * For quiz: creates an empty quiz ready for question editing.
     */
    public function addLevel(Request $request, Module $module)
    {
        $request->validate([
            'type'       => 'required|in:quiz,tracing,video,audio,matching,coloring',
            'title'      => 'required|string|max:100',
            'section_id' => 'required|exists:sections,id',
        ]);

        // Ensure the section belongs to this teacher
        $section = Section::where('id', $request->section_id)
            ->where('teacher_id', Auth::id())->firstOrFail();

        // Section levels must come after all global (null section_id) levels so they appear separately
        $globalMax  = Activity::where('module_id', $module->id)->whereNull('section_id')->max('level_number') ?? 0;
        $sectionMax = Activity::where('module_id', $module->id)->where('section_id', $section->id)->max('level_number') ?? 0;
        $nextLevel  = max($globalMax, $sectionMax) + 1;

        $data = [
            'module_id'    => $module->id,
            'section_id'   => $section->id,
            'level_number' => $nextLevel,
            'title'        => $request->title,
            'type'         => $request->type,
            'stars_reward' => 3,
            'is_active'    => true,
            'content'      => ['style' => 'default', 'questions' => []],
        ];

        if ($request->type === 'tracing' && $request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('activities', 'public');
        }

        if ($request->type === 'video' && $request->filled('video_url')) {
            $data['content'] = ['video_url' => $request->video_url];
        }

        Activity::create($data);

        // Notify all students in the selected class
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

        $hint = $request->type === 'quiz'
            ? " Click \"Edit Questions\" to add your quiz questions."
            : '';

        return back()->with('success', "Level {$nextLevel} added to {$section->name}!{$hint}");
    }

    public function destroy(Module $module)
    {
        $module->activities()->delete();
        $module->delete();
        return back()->with('success', 'Module deleted.');
    }
}
