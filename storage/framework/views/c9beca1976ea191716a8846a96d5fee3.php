<?php $__env->startSection('title', 'Activities'); ?>

<?php $__env->startSection('student-content'); ?>
<div class="mb-8">
    <h1 class="font-fredoka text-4xl text-gray-800">Activities <i class="fa-solid fa-gamepad" style="color:#FF6B6B;"></i></h1>
    <p class="text-gray-500 text-lg mt-1">All your assigned tasks in one place</p>
</div>

<?php if($activities->isEmpty()): ?>
<div class="bg-white rounded-3xl p-12 text-center shadow-sm">
    <div class="mb-4" style="font-size:4.5rem; color:#d1d5db;"><i class="fa-solid fa-bullseye"></i></div>
    <p class="font-fredoka text-2xl text-gray-600">No activities assigned yet!</p>
    <p class="text-gray-500 mt-2">Check back soon — your teacher will add activities here.</p>
</div>
<?php else: ?>
<div data-tour="activities-list" class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $act): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $sub    = $submissions[$act->id] ?? null;
        $locked = $act->opens_at && now()->lt($act->opens_at);
        $typeIcons = [
            'video'    => 'fa-solid fa-film',
            'quiz'     => 'fa-solid fa-brain',
            'matching' => 'fa-solid fa-shuffle',
            'drag_drop'=> 'fa-solid fa-hand',
            'coloring' => 'fa-solid fa-palette',
            'audio'    => 'fa-solid fa-volume-high',
        ];
        $iconClass = $typeIcons[$act->type] ?? 'fa-solid fa-file-pen';
        $iconColor = ['video'=>'#FF6B6B','quiz'=>'#BB8FCE','matching'=>'#4ECDC4','drag_drop'=>'#45B7D1','coloring'=>'#FF8E53','audio'=>'#52BE80'][$act->type] ?? '#9ca3af';
    ?>
    <div class="bg-white rounded-3xl p-5 shadow-sm <?php echo e($locked ? 'opacity-70' : 'card-hover'); ?>">
        <div class="flex items-start gap-4">
            <div style="font-size:2.5rem; color:<?php echo e($locked ? '#9ca3af' : $iconColor); ?>;"><i class="<?php echo e($iconClass); ?>"></i></div>
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <span class="font-fredoka text-xl text-gray-800"><?php echo e($act->title); ?></span>
                    <?php if($locked): ?>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">
                        <i class="fa-solid fa-lock"></i> Locked
                    </span>
                    <?php endif; ?>
                </div>
                <div class="text-sm text-gray-500 mb-1 capitalize"><?php echo e($act->module?->title ?? 'Module'); ?> — <?php echo e(str_replace('_', ' ', $act->type)); ?></div>

                <?php if($locked): ?>
                <div class="text-sm text-orange-500 font-semibold mb-2">
                    <i class="fa-solid fa-clock"></i> Opens <?php echo e($act->opens_at->format('M d, Y \a\t g:i A')); ?>

                </div>
                <?php else: ?>
                    <?php if($act->deadline): ?>
                    <div class="text-sm <?php echo e(now()->gt($act->deadline) ? 'text-red-500' : 'text-gray-400'); ?> font-semibold mb-2">
                        <i class="fa-solid fa-calendar"></i> Due: <?php echo e($act->deadline->format('M d, Y')); ?>

                    </div>
                    <?php endif; ?>
                    <?php if($sub): ?>
                    <div class="flex items-center gap-1 mb-2">
                        <?php for($i=1;$i<=3;$i++): ?>
                        <i class="<?php echo e($i<=$sub->stars_earned ? 'fa-solid' : 'fa-regular'); ?> fa-star" style="font-size:1.1rem; color:#FFD700;"></i>
                        <?php endfor; ?>
                        <span class="text-sm text-gray-500 font-bold ml-1"><?php echo e($sub->score); ?>%</span>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if($locked): ?>
                <span class="inline-block px-4 py-2 rounded-full font-bold text-sm bg-gray-100 text-gray-400 cursor-not-allowed">
                    <i class="fa-solid fa-lock"></i> Not yet available
                </span>
                <?php else: ?>
                <a href="<?php echo e(route('student.activities.show', $act->id)); ?>"
                   class="inline-block px-4 py-2 rounded-full font-bold text-white text-sm"
                   style="background: linear-gradient(135deg, #FF6B6B, #FF8E53)">
                    <?php if($sub): ?>
                        <i class="fa-solid fa-rotate"></i> Review
                    <?php else: ?>
                        <i class="fa-solid fa-play"></i> Start
                    <?php endif; ?>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\kinderlearn\resources\views/student/activities.blade.php ENDPATH**/ ?>