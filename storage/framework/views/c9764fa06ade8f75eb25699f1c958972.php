<?php $__env->startSection('title', 'My Dashboard'); ?>

<?php $__env->startSection('student-content'); ?>


<div class="mb-8">
    <h1 class="font-fredoka text-4xl md:text-5xl text-gray-800">
        Hello, <?php echo e($student->name); ?>! <i class="fa-solid fa-hand-wave" style="color:#FF6B6B;"></i>
    </h1>
    <p class="text-gray-500 text-lg mt-1">Ready to learn something new today?</p>
</div>


<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <?php
        $statCards = [
            ['fa' => 'fa-solid fa-star',      'value' => $totalStars,                    'label' => 'Total Stars', 'color' => '#FFD700', 'bg' => '#fffdf0'],
            ['fa' => 'fa-solid fa-book-open', 'value' => $completedCount,                'label' => 'Completed',   'color' => '#4ECDC4', 'bg' => '#f0fffe'],
            ['fa' => 'fa-solid fa-trophy',    'value' => $recentAchievements->count(),   'label' => 'Badges',      'color' => '#FF6B6B', 'bg' => '#fff0f0'],
            ['fa' => 'fa-solid fa-gamepad',   'value' => count($modules),                'label' => 'Lessons',     'color' => '#BB8FCE', 'bg' => '#f8f0ff'],
        ];
    ?>

    <?php $__currentLoopData = $statCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="rounded-3xl p-5 text-center shadow-sm card-hover"
         style="background: <?php echo e($s['bg']); ?>; border: 2px solid <?php echo e($s['color']); ?>30;">
        <div class="mb-2" style="font-size:2.2rem; color:<?php echo e($s['color']); ?>;"><i class="<?php echo e($s['fa']); ?>"></i></div>
        <div class="font-fredoka text-3xl" style="color: <?php echo e($s['color']); ?>"><?php echo e($s['value']); ?></div>
        <div class="text-gray-500 font-semibold text-sm"><?php echo e($s['label']); ?></div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<div class="mb-10">
    <div class="flex items-center justify-between mb-5">
        <h2 class="font-fredoka text-3xl text-gray-800">My Lessons <i class="fa-solid fa-book-open" style="color:#FF6B6B;"></i></h2>
        <a href="<?php echo e(route('student.modules')); ?>" class="text-orange-500 font-bold hover:underline">See all →</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
        <?php $__currentLoopData = $modules->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $progress = $progresses[$module->id] ?? null;
            $percent  = $progress && $progress->stars_earned > 0 ? min(100, ($progress->stars_earned / ($module->activities()->count() * 3)) * 100) : 0;
        ?>
        <a href="<?php echo e(route('student.modules.show', $module->id)); ?>"
           class="block rounded-3xl p-6 shadow-md card-hover text-white relative overflow-hidden"
           style="background: linear-gradient(135deg, <?php echo e($module->color); ?>, <?php echo e($module->color); ?>cc);">

            <?php if($progress && $progress->completed): ?>
            <div class="absolute top-3 right-3 bg-white bg-opacity-30 rounded-full px-3 py-1 text-sm font-bold">
                <i class="fa-solid fa-circle-check"></i> Done!
            </div>
            <?php endif; ?>

            <div class="text-5xl mb-3"><?php echo e($module->icon); ?></div>
            <div class="font-fredoka text-2xl mb-1"><?php echo e($module->title); ?></div>
            <div class="opacity-80 text-sm mb-4"><?php echo e($module->description); ?></div>

            <div class="bg-white bg-opacity-30 rounded-full h-3">
                <div class="bg-white rounded-full h-3 transition-all" style="width: <?php echo e($percent); ?>%"></div>
            </div>
            <div class="text-right text-xs opacity-80 mt-1 font-bold">
                <?php echo e($progress ? $progress->stars_earned : 0); ?> <i class="fa-solid fa-star"></i>
            </div>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>


<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    
    <div class="bg-white rounded-3xl p-6 shadow-sm">
        <h3 class="font-fredoka text-2xl text-gray-800 mb-4"><i class="fa-solid fa-trophy" style="color:#FFD700;"></i> Recent Badges</h3>
        <?php if($recentAchievements->isEmpty()): ?>
        <div class="text-center py-8">
            <div class="mb-3" style="font-size:3.5rem; color:#86efac;"><i class="fa-solid fa-seedling"></i></div>
            <p class="text-gray-500 font-semibold">Complete a lesson to earn your first badge!</p>
        </div>
        <?php else: ?>
        <div class="space-y-3">
            <?php $__currentLoopData = $recentAchievements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-center gap-4 p-3 rounded-2xl bg-yellow-50">
                <div class="text-4xl"><?php echo e($a->icon); ?></div>
                <div>
                    <div class="font-bold text-gray-800"><?php echo e($a->title); ?></div>
                    <div class="text-sm text-gray-500"><?php echo e($a->description); ?></div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
    </div>

    
    <div class="bg-white rounded-3xl p-6 shadow-sm">
        <h3 class="font-fredoka text-2xl text-gray-800 mb-4"><i class="fa-solid fa-bullhorn" style="color:#FF6B6B;"></i> From Your Teacher</h3>
        <?php if($announcements->isEmpty()): ?>
        <div class="text-center py-8">
            <div class="mb-3" style="font-size:3.5rem; color:#d1d5db;"><i class="fa-solid fa-envelope-open"></i></div>
            <p class="text-gray-500 font-semibold">No announcements yet.</p>
        </div>
        <?php else: ?>
        <div class="space-y-3">
            <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ann): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="p-4 rounded-2xl <?php echo e($ann->pinned ? 'bg-orange-50 border-2 border-orange-200' : 'bg-gray-50'); ?>">
                <?php if($ann->pinned): ?>
                <span class="text-xs bg-orange-500 text-white px-2 py-0.5 rounded-full font-bold mb-2 inline-block">
                    <i class="fa-solid fa-thumbtack"></i> Pinned
                </span>
                <?php endif; ?>
                <div class="font-bold text-gray-800"><?php echo e($ann->title); ?></div>
                <p class="text-sm text-gray-500 mt-1"><?php echo e(Str::limit($ann->body, 80)); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="mt-4 text-center">
    <button onclick="window.__klTourReset && window.__klTourReset()"
            class="text-xs text-gray-400 hover:text-orange-500 transition font-semibold">
        <i class="fa-solid fa-route"></i> Restart tour
    </button>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\kinderlearn\resources\views/student/dashboard.blade.php ENDPATH**/ ?>