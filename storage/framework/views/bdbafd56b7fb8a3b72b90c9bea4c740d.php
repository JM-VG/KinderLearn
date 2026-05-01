<?php $__env->startSection('title', 'Student Progress'); ?>
<?php $__env->startSection('teacher-content'); ?>
<h1 class="font-fredoka text-4xl text-gray-800 mb-6">Student Progress <i class="fa-solid fa-chart-column" style="color:#4A90D9;"></i></h1>

<div class="bg-white rounded-3xl shadow-sm overflow-x-auto">
    <table class="w-full min-w-[700px]">
        <thead style="background: linear-gradient(90deg, #2C3E7A, #4A90D9);">
            <tr class="text-white text-left">
                <th class="px-6 py-4 font-bold">Student</th>
                <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <th class="px-4 py-4 font-bold text-center text-sm"><?php echo e($m->icon); ?> <?php echo e(Str::limit($m->title, 10)); ?></th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <th class="px-6 py-4 font-bold text-center"><i class="fa-solid fa-star" style="color:#FFD700;"></i> Total</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4">
                    <div class="font-bold text-gray-800"><?php echo e($s->name); ?></div>
                    <div class="text-xs text-gray-400"><?php echo e($s->section->name ?? ''); ?></div>
                </td>
                <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $p = $progresses[$s->id][$m->id] ?? null; ?>
                <td class="px-4 py-4 text-center">
                    <?php if($p && $p->completed): ?>
                        <i class="fa-solid fa-circle-check text-green-500 font-bold"></i>
                    <?php elseif($p && $p->stars_earned > 0): ?>
                        <span class="text-yellow-500 font-bold"><i class="fa-solid fa-hourglass-half"></i> <?php echo e($p->stars_earned); ?> <i class="fa-solid fa-star" style="color:#FFD700;"></i></span>
                    <?php else: ?>
                        <span class="text-gray-300">—</span>
                    <?php endif; ?>
                </td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <td class="px-6 py-4 text-center font-fredoka text-xl text-yellow-600">
                    <?php echo e($s->getTotalStars()); ?>

                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="<?php echo e($modules->count() + 2); ?>" class="px-6 py-12 text-center text-gray-400 font-semibold">No students found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\kinderlearn\resources\views/teacher/progress.blade.php ENDPATH**/ ?>