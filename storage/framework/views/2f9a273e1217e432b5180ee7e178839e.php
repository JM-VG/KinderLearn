<?php $__env->startSection('title', 'Teacher Dashboard'); ?>

<?php $__env->startSection('teacher-content'); ?>

<div class="mb-8">
    <h1 class="font-fredoka text-4xl text-gray-800">Welcome, <?php echo e($teacher->name); ?>! <i class="fa-solid fa-chalkboard-user" style="color:#4ECDC4;"></i></h1>
    <p class="text-gray-500 text-lg mt-1">Here's what's happening in your classes today.</p>
</div>


<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <?php
        $stats = [
            ['fa' => 'fa-solid fa-child',           'value' => $totalStudents,             'label' => 'My Students',        'color' => '#FF6B6B', 'bg' => '#fff0f0'],
            ['fa' => 'fa-solid fa-school',           'value' => $sections->count(),         'label' => 'My Classes',         'color' => '#4ECDC4', 'bg' => '#f0fffe'],
            ['fa' => 'fa-solid fa-book-open',        'value' => $modules->count(),          'label' => 'Modules',            'color' => '#45B7D1', 'bg' => '#f0f8ff'],
            ['fa' => 'fa-solid fa-pen-to-square',    'value' => $recentSubmissions->count(),'label' => 'Recent Submissions', 'color' => '#BB8FCE', 'bg' => '#f8f0ff'],
        ];
    ?>

    <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="rounded-3xl p-5 text-center shadow-sm" style="background: <?php echo e($s['bg']); ?>; border: 2px solid <?php echo e($s['color']); ?>30;">
        <div class="mb-2" style="font-size:2.2rem; color:<?php echo e($s['color']); ?>;"><i class="<?php echo e($s['fa']); ?>"></i></div>
        <div class="font-fredoka text-3xl" style="color: <?php echo e($s['color']); ?>"><?php echo e($s['value']); ?></div>
        <div class="text-gray-500 font-semibold text-sm"><?php echo e($s['label']); ?></div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    
    <div class="bg-white rounded-3xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-fredoka text-2xl text-gray-800"><i class="fa-solid fa-school" style="color:#4ECDC4;"></i> My Classes</h2>
            <a href="<?php echo e(route('teacher.classes')); ?>" class="text-blue-500 font-bold text-sm hover:underline">Manage →</a>
        </div>
        <?php if($sections->isEmpty()): ?>
        <div class="text-center py-8">
            <div class="mb-3" style="font-size:3rem; color:#d1d5db;"><i class="fa-solid fa-school"></i></div>
            <p class="text-gray-500 font-semibold">No classes yet.</p>
            <a href="<?php echo e(route('teacher.classes')); ?>" class="text-blue-500 font-bold hover:underline">Create your first class →</a>
        </div>
        <?php else: ?>
        <div class="space-y-3">
            <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl">
                <div style="font-size:1.8rem; color:#4ECDC4;"><i class="fa-solid fa-school"></i></div>
                <div class="flex-1">
                    <div class="font-bold text-gray-800"><?php echo e($section->name); ?></div>
                    <div class="text-sm text-gray-500"><?php echo e($section->students_count); ?> students &bull; Code: <span class="font-bold text-blue-500"><?php echo e($section->join_code); ?></span></div>
                </div>
                <a href="<?php echo e(route('teacher.classes.show', $section->id)); ?>" class="text-sm font-bold text-blue-500 hover:underline">View</a>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
    </div>

    
    <div class="bg-white rounded-3xl p-6 shadow-sm">
        <h2 class="font-fredoka text-2xl text-gray-800 mb-5"><i class="fa-solid fa-pen-to-square" style="color:#BB8FCE;"></i> Recent Submissions</h2>
        <?php if($recentSubmissions->isEmpty()): ?>
        <div class="text-center py-8">
            <div class="mb-3" style="font-size:3rem; color:#d1d5db;"><i class="fa-solid fa-envelope-open"></i></div>
            <p class="text-gray-500 font-semibold">No submissions yet.</p>
        </div>
        <?php else: ?>
        <div class="space-y-3 max-h-80 overflow-y-auto">
            <?php $__currentLoopData = $recentSubmissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-2xl">
                <div style="font-size:1.6rem; color:#BB8FCE;"><i class="fa-solid fa-pen-to-square"></i></div>
                <div class="flex-1 min-w-0">
                    <div class="font-bold text-gray-800 truncate"><?php echo e($sub->user->name); ?></div>
                    <div class="text-sm text-gray-500 truncate"><?php echo e($sub->activity->title); ?></div>
                </div>
                <div class="text-right">
                    <div class="font-bold text-sm" style="color: <?php echo e($sub->score >= 80 ? '#27ae60' : ($sub->score >= 60 ? '#f39c12' : '#e74c3c')); ?>">
                        <?php echo e($sub->score); ?>%
                    </div>
                    <div class="text-xs text-gray-400"><?php echo e($sub->created_at->diffForHumans()); ?></div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="mt-4 text-center">
    <button onclick="window.__klTeacherTourReset && window.__klTeacherTourReset()"
            class="text-xs text-gray-400 hover:text-sky-500 transition font-semibold">
        <i class="fa-solid fa-route"></i> Restart tour
    </button>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\kinderlearn\resources\views/teacher/dashboard.blade.php ENDPATH**/ ?>