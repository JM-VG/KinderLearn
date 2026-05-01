<?php $__env->startSection('title', $module->title); ?>

<?php $__env->startSection('student-content'); ?>


<div class="rounded-3xl text-white p-6 mb-6 flex items-center gap-5"
     style="background: linear-gradient(135deg, <?php echo e($module->color); ?>, <?php echo e($module->color); ?>bb);">
    <div class="text-6xl flex-shrink-0"><?php echo e($module->icon); ?></div>
    <div class="flex-1 min-w-0">
        <div class="font-fredoka text-3xl leading-tight"><?php echo e($module->title); ?></div>
        <div class="opacity-80 text-sm mt-1"><?php echo e($module->description); ?></div>
        <div class="flex items-center gap-1 mt-2">
            <?php for($i = 1; $i <= 5; $i++): ?>
                <i class="<?php echo e($i <= $progress->stars_earned ? 'fa-solid' : 'fa-regular'); ?> fa-star" style="font-size:1rem; color:#FFD700;"></i>
            <?php endfor; ?>
        </div>
        <?php if(isset($progressPct) && $progressPct > 0): ?>
        <div class="mt-2 flex items-center gap-2">
            <div class="flex-1 bg-white bg-opacity-30 rounded-full h-2">
                <div class="h-2 rounded-full bg-white bg-opacity-90 transition-all"
                     style="width: <?php echo e($progressPct); ?>%"></div>
            </div>
            <span class="text-xs font-bold opacity-80"><?php echo e($progressPct); ?>% done</span>
        </div>
        <?php endif; ?>
    </div>
    <a href="<?php echo e(route('student.modules')); ?>"
       class="flex-shrink-0 flex items-center gap-1 bg-white bg-opacity-20 hover:bg-opacity-30
              transition rounded-xl px-4 py-2 text-sm font-bold">
        <i class="ri-arrow-left-line"></i> Back
    </a>
</div>


<?php if(session('success')): ?>
<div class="mb-5 px-5 py-3 bg-green-50 border border-green-200 text-green-700 rounded-2xl font-semibold flex items-center gap-2">
    <i class="ri-checkbox-circle-line text-lg"></i> <?php echo e(session('success')); ?>

</div>
<?php endif; ?>
<?php if(session('error')): ?>
<div class="mb-5 px-5 py-3 bg-red-50 border border-red-200 text-red-600 rounded-2xl font-semibold flex items-center gap-2">
    <i class="ri-error-warning-line text-lg"></i> <?php echo e(session('error')); ?>

</div>
<?php endif; ?>


<?php if($levelNumbers->isEmpty()): ?>
<div class="bg-white rounded-3xl p-12 text-center shadow-sm">
    <div class="mb-3" style="font-size:4rem; color:#d1d5db;"><i class="fa-solid fa-triangle-exclamation"></i></div>
    <p class="font-fredoka text-2xl text-gray-500">No activities yet — check back soon!</p>
</div>
<?php else: ?>

<?php
    $levelFaIcons = ['fa-seedling','fa-rocket','fa-trophy','fa-star','fa-gem','fa-fire','fa-rainbow','fa-paw'];
    $levelColors  = ['#52BE80','#FF6B6B','#FFD700','#FF8E53','#BB8FCE','#FF4500','#45B7D1','#4ECDC4'];
?>

<div class="flex flex-col gap-5">
<?php $__currentLoopData = $levelNumbers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $levelNum): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php
    $levelActivities = $activitiesByLevel[$levelNum];
    $isUnlocked      = $unlockedLevels[$levelNum] ?? false;
    $lvlProgress     = $levelProgresses->get($levelNum);
    $doneCount       = $levelActivities->filter(fn($a) => isset($submissions[$a->id]))->count();
    $totalCount      = $levelActivities->count();
    $allDone         = $doneCount >= $totalCount && $totalCount > 0;
    // Use actual submission count for completion badge — LevelProgress can be stale if activities were added later
    $isComplete      = $allDone;
    $lvlIdx          = ($levelNum - 1) % count($levelFaIcons);
    $lvlIcon         = $levelFaIcons[$lvlIdx];
    $lvlIconColor    = $levelColors[$lvlIdx];
?>

<div class="rounded-3xl overflow-hidden shadow-sm border
            <?php echo e($isUnlocked ? 'bg-white border-gray-100' : 'bg-gray-50 border-gray-200'); ?>">

    
    <div class="flex items-center gap-4 px-5 py-4"
         style="background: <?php echo e($isUnlocked ? ($module->color . '14') : '#f3f4f6'); ?>;
                border-bottom: 1px solid <?php echo e($isUnlocked ? ($module->color . '28') : '#e5e7eb'); ?>;">

        <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 text-xl text-white shadow-sm"
             style="background: <?php echo e($isUnlocked ? $module->color : '#9ca3af'); ?>;">
            <?php if($isUnlocked): ?>
                <i class="fa-solid <?php echo e($lvlIcon); ?>"></i>
            <?php else: ?>
                <i class="fa-solid fa-lock"></i>
            <?php endif; ?>
        </div>

        <div class="flex-1">
            <div class="font-fredoka text-xl text-gray-800">
                Level <?php echo e($levelNum); ?>

                <?php if($isComplete): ?>
                    <span class="ml-2 text-sm font-bold text-green-600">— Complete!</span>
                <?php endif; ?>
            </div>
            <div class="text-xs text-gray-400 mt-0.5">
                <?php if($isUnlocked): ?>
                    <?php echo e($doneCount); ?> of <?php echo e($totalCount); ?> activities done
                <?php else: ?>
                    Complete Level <?php echo e($levelNum - 1); ?> to unlock
                <?php endif; ?>
            </div>
        </div>

        <?php if($isComplete): ?>
            <div class="flex gap-0.5 flex-shrink-0">
                <?php for($s = 1; $s <= 3; $s++): ?>
                    <i class="<?php echo e($s <= ($lvlProgress->stars_earned ?? 0) ? 'fa-solid' : 'fa-regular'); ?> fa-star" style="font-size:1.1rem; color:#FFD700;"></i>
                <?php endfor; ?>
            </div>
        <?php elseif(!$isUnlocked): ?>
            <i class="ri-lock-line text-gray-300 text-2xl flex-shrink-0"></i>
        <?php else: ?>
            <div class="hidden sm:flex flex-col items-end gap-1 flex-shrink-0">
                <div class="w-24 h-2 rounded-full bg-gray-200 overflow-hidden">
                    <div class="h-full rounded-full"
                         style="width: <?php echo e($totalCount > 0 ? round($doneCount / $totalCount * 100) : 0); ?>%;
                                background: <?php echo e($module->color); ?>;"></div>
                </div>
                <span class="text-xs text-gray-400"><?php echo e($totalCount > 0 ? round($doneCount / $totalCount * 100) : 0); ?>%</span>
            </div>
        <?php endif; ?>
    </div>

    
    <?php if($isUnlocked): ?>
    <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-3">
        <?php $__currentLoopData = $levelActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $sub = $submissions[$activity->id] ?? null; $done = $sub !== null; ?>

        <div class="flex items-center gap-4 p-4 rounded-2xl border transition-all
                    <?php echo e($done ? 'bg-green-50 border-green-100' : 'bg-gray-50 border-gray-100 hover:bg-orange-50 hover:border-orange-200'); ?>">

            <?php
                $actTypeIcons = ['video'=>['fa-film','#FF6B6B'],'quiz'=>['fa-brain','#BB8FCE'],'matching'=>['fa-shuffle','#4ECDC4'],'drag_drop'=>['fa-hand','#45B7D1'],'coloring'=>['fa-palette','#FF8E53'],'audio'=>['fa-volume-high','#52BE80'],'tracing'=>['fa-pen-nib','#8b5cf6']];
                [$actIcon, $actColor] = $actTypeIcons[$activity->type] ?? ['fa-file-pen','#9ca3af'];
            ?>
            <div class="flex-shrink-0 w-10 text-center" style="font-size:1.8rem; color:<?php echo e($actColor); ?>;">
                <i class="fa-solid <?php echo e($actIcon); ?>"></i>
            </div>

            <div class="flex-1 min-w-0">
                <div class="font-bold text-gray-800 text-sm truncate"><?php echo e($activity->title); ?></div>
                <?php if($done): ?>
                    <div class="flex items-center gap-0.5 mt-1">
                        <?php for($s = 1; $s <= 3; $s++): ?>
                            <i class="<?php echo e($s <= $sub->stars_earned ? 'fa-solid' : 'fa-regular'); ?> fa-star" style="font-size:0.85rem; color:#FFD700;"></i>
                        <?php endfor; ?>
                        <span class="text-xs text-gray-400 ml-1"><?php echo e($sub->score); ?>%</span>
                    </div>
                <?php else: ?>
                    <div class="text-xs text-gray-400 mt-0.5 capitalize">
                        <?php echo e(str_replace('_', ' ', $activity->type)); ?> &bull; <?php echo e($activity->stars_reward); ?> <i class="fa-solid fa-star" style="color:#FFD700;font-size:0.75rem;"></i> possible
                    </div>
                <?php endif; ?>
            </div>

            <?php if($done && $activity->type === 'quiz'): ?>
            <a href="<?php echo e(route('student.activities.review', $activity->id)); ?>"
               class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center
                      text-white transition hover:scale-110 active:scale-95 shadow-sm"
               style="background:#6366f1;" title="Review answers">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
            </a>
            <?php else: ?>
            <a href="<?php echo e(route('student.activities.show', $activity->id)); ?>"
               class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center
                      text-white transition hover:scale-110 active:scale-95 shadow-sm"
               style="background: <?php echo e($done ? '#10b981' : $module->color); ?>;">
                <i class="<?php echo e($done ? 'ri-refresh-line' : 'ri-play-fill'); ?>"></i>
            </a>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <?php endif; ?>

</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\kinderlearn\resources\views/student/module-detail.blade.php ENDPATH**/ ?>