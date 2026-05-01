<?php $__env->startSection('title', 'Modules'); ?>
<?php $__env->startSection('teacher-content'); ?>
<div class="flex items-center justify-between mb-6">
    <h1 class="font-fredoka text-4xl text-gray-800">Modules <i class="fa-solid fa-book-open" style="color:#4A90D9;"></i></h1>
    <a href="<?php echo e(route('teacher.modules.create')); ?>"
       class="btn-kid text-white"
       style="background: linear-gradient(135deg, #4ECDC4, #45B7D1)">
        <i class="fa-solid fa-plus"></i> New Module
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-5" data-tour="modules-grid">
    <?php $__empty_1 = true; $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="bg-white rounded-3xl overflow-hidden shadow-sm card-hover">
        <div class="p-6 text-white text-center"
             style="background: linear-gradient(135deg, <?php echo e($module->color); ?>, <?php echo e($module->color); ?>cc);">
            <div class="text-5xl mb-2"><?php echo e($module->icon); ?></div>
            <div class="font-fredoka text-2xl"><?php echo e($module->title); ?></div>
            <div class="text-sm opacity-80 mt-1 capitalize"><?php echo e($module->subject); ?></div>
        </div>
        <div class="p-4">
            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                <span><i class="fa-solid fa-gamepad" style="color:#4ECDC4;"></i> <?php echo e($module->activities_count); ?> activities</span>
                <span class="<?php echo e($module->is_active ? 'text-green-500' : 'text-red-400'); ?> font-bold">
                    <?php if($module->is_active): ?>
                        <i class="fa-solid fa-circle-check"></i> Active
                    <?php else: ?>
                        <i class="fa-solid fa-ban"></i> Hidden
                    <?php endif; ?>
                </span>
            </div>
            <div class="flex gap-2">
                <a href="<?php echo e(route('teacher.modules.edit', $module->id)); ?>"
                   class="flex-1 text-center px-3 py-2 rounded-xl font-bold text-sm text-white"
                   style="background: #4A90D9">
                    <i class="fa-solid fa-pen"></i> Edit
                </a>
                <form method="POST" action="<?php echo e(route('teacher.modules.destroy', $module->id)); ?>"
                      onsubmit="return confirm('Delete this module?')" class="flex-1">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="w-full px-3 py-2 rounded-xl font-bold text-sm bg-red-100 text-red-500 hover:bg-red-200">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="col-span-3 bg-white rounded-3xl p-12 text-center shadow-sm">
        <div class="mb-4" style="font-size:4.5rem; color:#d1d5db;"><i class="fa-solid fa-book-open"></i></div>
        <p class="font-fredoka text-2xl text-gray-600">No modules yet. Create your first one!</p>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\kinderlearn\resources\views/teacher/modules.blade.php ENDPATH**/ ?>