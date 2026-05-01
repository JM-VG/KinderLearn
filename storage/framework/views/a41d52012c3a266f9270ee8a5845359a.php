<?php $__env->startSection('title', 'Notifications'); ?>

<?php $__env->startSection('student-content'); ?>
<div class="mb-8">
    <h1 class="font-fredoka text-4xl text-gray-800">Notifications <i class="fa-solid fa-bell" style="color:#FF6B6B;"></i></h1>
</div>

<div class="max-w-2xl mx-auto">
<?php if($notifications->isEmpty()): ?>
<div class="bg-white rounded-3xl p-12 text-center shadow-sm">
    <div class="mb-4" style="font-size:4.5rem; color:#d1d5db;"><i class="fa-solid fa-bell-slash"></i></div>
    <p class="font-fredoka text-2xl text-gray-600">No notifications yet!</p>
</div>
<?php else: ?>
<div class="space-y-3">
    <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="bg-white rounded-2xl p-5 shadow-sm flex items-start gap-4">
        <div style="font-size:1.8rem;">
            <?php switch($n->type):
                case ('achievement'): ?> <i class="fa-solid fa-trophy" style="color:#FFD700;"></i> <?php break; ?>
                <?php case ('message'): ?> <i class="fa-solid fa-envelope" style="color:#4ECDC4;"></i> <?php break; ?>
                <?php case ('announcement'): ?> <i class="fa-solid fa-bullhorn" style="color:#FF6B6B;"></i> <?php break; ?>
                <?php default: ?> <i class="fa-solid fa-bell" style="color:#BB8FCE;"></i>
            <?php endswitch; ?>
        </div>
        <div class="flex-1">
            <div class="font-bold text-gray-800"><?php echo e($n->title); ?></div>
            <p class="text-gray-500 text-sm mt-1"><?php echo e($n->message); ?></p>
            <div class="text-xs text-gray-400 mt-2"><?php echo e($n->created_at->diffForHumans()); ?></div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\kinderlearn\resources\views/student/notifications.blade.php ENDPATH**/ ?>