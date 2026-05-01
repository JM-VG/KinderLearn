<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\Activity;
use App\Models\ActivitySubmission;
use App\Models\Module;
use App\Models\Progress;
use App\Models\User;
use App\Models\UserNotification;

class BadgeService
{
    private static array $BADGES = [
        // Star milestones
        ['key' => 'first_star',       'title' => 'First Star',       'icon' => '⭐', 'type' => 'milestone',    'desc' => 'Earned your very first star!'],
        ['key' => 'star_collector',   'title' => 'Star Collector',   'icon' => '🌟', 'type' => 'milestone',    'desc' => 'Earned 10 total stars.'],
        ['key' => 'star_hunter',      'title' => 'Star Hunter',      'icon' => '💫', 'type' => 'milestone',    'desc' => 'Earned 25 total stars.'],
        ['key' => 'star_champion',    'title' => 'Star Champion',    'icon' => '✨', 'type' => 'milestone',    'desc' => 'Earned 50 total stars.'],
        ['key' => 'star_legend',      'title' => 'Star Legend',      'icon' => '🏆', 'type' => 'milestone',    'desc' => 'Earned 100 total stars!'],
        // Module count milestones
        ['key' => 'first_module',     'title' => 'First Step',       'icon' => '🐾', 'type' => 'milestone',    'desc' => 'Completed your first module!'],
        ['key' => 'three_modules',    'title' => 'Explorer',         'icon' => '🔭', 'type' => 'milestone',    'desc' => 'Completed 3 modules.'],
        ['key' => 'five_modules',     'title' => 'Knowledge Seeker', 'icon' => '📚', 'type' => 'milestone',    'desc' => 'Completed 5 modules.'],
        // Perfect scores
        ['key' => 'perfect_module',   'title' => 'Perfectionist',    'icon' => '💎', 'type' => 'perfect_score','desc' => 'Completed a module with 3 stars on every activity!'],
        // Subject completion
        ['key' => 'subject_alphabet', 'title' => 'Alphabet Master',  'icon' => '🔤', 'type' => 'badge',        'desc' => 'Completed all Alphabet modules!'],
        ['key' => 'subject_numbers',  'title' => 'Number Wizard',    'icon' => '🔢', 'type' => 'badge',        'desc' => 'Completed all Numbers modules!'],
        ['key' => 'subject_colors',   'title' => 'Color Expert',     'icon' => '🎨', 'type' => 'badge',        'desc' => 'Completed all Colors modules!'],
        ['key' => 'subject_shapes',   'title' => 'Shape Genius',     'icon' => '🔷', 'type' => 'badge',        'desc' => 'Completed all Shapes modules!'],
        ['key' => 'subject_words',    'title' => 'Word Whiz',        'icon' => '📝', 'type' => 'badge',        'desc' => 'Completed all Words modules!'],
        // Perfect subject (all modules in a subject with full stars)
        ['key' => 'perfect_alphabet', 'title' => 'Alphabet Legend',  'icon' => '🌠', 'type' => 'perfect_score','desc' => 'Completed all Alphabet modules with full stars!'],
        ['key' => 'perfect_numbers',  'title' => 'Math Legend',      'icon' => '🧮', 'type' => 'perfect_score','desc' => 'Completed all Numbers modules with full stars!'],
        ['key' => 'perfect_colors',   'title' => 'Color Legend',     'icon' => '🖌️', 'type' => 'perfect_score','desc' => 'Completed all Colors modules with full stars!'],
        ['key' => 'perfect_shapes',   'title' => 'Shape Legend',     'icon' => '🎯', 'type' => 'perfect_score','desc' => 'Completed all Shapes modules with full stars!'],
        ['key' => 'perfect_words',    'title' => 'Word Legend',      'icon' => '✍️', 'type' => 'perfect_score','desc' => 'Completed all Words modules with full stars!'],
    ];

    /**
     * Evaluate all badge conditions for a student and grant any newly-earned badges.
     * Returns the array of newly awarded badge definitions.
     */
    public static function award(User $student): array
    {
        $alreadyEarned = Achievement::where('user_id', $student->id)
            ->pluck('title')
            ->flip()
            ->all();

        $totalStars = $student->getTotalStars();

        $completedProgress = Progress::where('user_id', $student->id)
            ->where('completed', true)
            ->get();
        $completedCount     = $completedProgress->count();
        $completedModuleIds = $completedProgress->pluck('module_id')->all();

        $conditions = [
            'first_star'     => $totalStars >= 1,
            'star_collector' => $totalStars >= 10,
            'star_hunter'    => $totalStars >= 25,
            'star_champion'  => $totalStars >= 50,
            'star_legend'    => $totalStars >= 100,
            'first_module'   => $completedCount >= 1,
            'three_modules'  => $completedCount >= 3,
            'five_modules'   => $completedCount >= 5,
        ];

        // Perfectionist: any completed module where every activity earned 3 stars
        $conditions['perfect_module'] = false;
        foreach ($completedModuleIds as $moduleId) {
            $activityIds = Activity::where('module_id', $moduleId)
                ->where('is_active', true)
                ->pluck('id');
            if ($activityIds->isEmpty()) continue;
            $submissions = ActivitySubmission::where('user_id', $student->id)
                ->whereIn('activity_id', $activityIds)
                ->get();
            if ($submissions->count() >= $activityIds->count()
                && $submissions->min('stars_earned') >= 3) {
                $conditions['perfect_module'] = true;
                break;
            }
        }

        // Subject completion + perfect subject
        foreach (['alphabet', 'numbers', 'colors', 'shapes', 'words'] as $subject) {
            $subjectModuleIds = Module::where('subject', $subject)
                ->where('is_active', true)
                ->pluck('id');

            if ($subjectModuleIds->isEmpty()) {
                $conditions['subject_' . $subject]  = false;
                $conditions['perfect_' . $subject]  = false;
                continue;
            }

            $allCompleted = $subjectModuleIds->every(fn($id) => in_array($id, $completedModuleIds));
            $conditions['subject_' . $subject] = $allCompleted;

            $isPerfect = false;
            if ($allCompleted) {
                $isPerfect = true;
                foreach ($subjectModuleIds as $moduleId) {
                    $activityIds = Activity::where('module_id', $moduleId)
                        ->where('is_active', true)
                        ->pluck('id');
                    if ($activityIds->isEmpty()) continue;
                    $submissions = ActivitySubmission::where('user_id', $student->id)
                        ->whereIn('activity_id', $activityIds)
                        ->get();
                    if ($submissions->count() < $activityIds->count()
                        || $submissions->min('stars_earned') < 3) {
                        $isPerfect = false;
                        break;
                    }
                }
            }
            $conditions['perfect_' . $subject] = $isPerfect;
        }

        $newBadges = [];
        foreach (self::$BADGES as $badge) {
            $key = $badge['key'];
            if (empty($conditions[$key])) continue;
            if (isset($alreadyEarned[$badge['title']])) continue;

            Achievement::create([
                'user_id'     => $student->id,
                'title'       => $badge['title'],
                'description' => $badge['desc'],
                'icon'        => $badge['icon'],
                'type'        => $badge['type'],
                'earned_at'   => now(),
            ]);

            UserNotification::create([
                'user_id' => $student->id,
                'title'   => 'New Badge: ' . $badge['title'] . '!',
                'message' => $badge['desc'],
                'type'    => 'achievement',
            ]);

            $newBadges[] = $badge;
        }

        if (!empty($newBadges)) {
            session()->flash('new_badges', $newBadges);
        }

        return $newBadges;
    }

    /**
     * Returns the full badge catalogue with earned status for a student.
     * Used for the "all badges" display with locked/unlocked state.
     */
    public static function catalogue(User $student): array
    {
        $earned = Achievement::where('user_id', $student->id)
            ->orderBy('earned_at')
            ->get()
            ->keyBy('title');

        return array_map(function ($badge) use ($earned) {
            $badge['earned']    = isset($earned[$badge['title']]);
            $badge['earned_at'] = $badge['earned'] ? $earned[$badge['title']]->earned_at : null;
            return $badge;
        }, self::$BADGES);
    }
}
