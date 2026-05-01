<?php $__env->startSection('title', 'Welcome'); ?>

<?php $__env->startSection('content'); ?>


<nav class="sticky top-0 z-50 bg-white shadow-sm px-6 py-4 flex items-center justify-between">
    <div class="flex items-center gap-2">
        <span class="font-fredoka text-2xl"><span style="color:#1A212F">Kinder</span><span style="color:#F4654D">Learn</span></span>
    </div>

    
    <div class="hidden md:flex items-center gap-6 text-gray-600 font-semibold">
        <a href="#features" class="hover:text-orange-500 transition">Features</a>
        <a href="#how-it-works" class="hover:text-orange-500 transition">How It Works</a>
        <a href="#subjects" class="hover:text-orange-500 transition">Subjects</a>
        <a href="#roles" class="hover:text-orange-500 transition">Roles</a>
    </div>

    <div class="flex items-center gap-3">
        <a href="<?php echo e(route('login')); ?>"
           class="hidden md:inline-block px-5 py-2 rounded-full border-2 border-orange-400 text-orange-500 font-bold hover:bg-orange-50 transition">
            Sign In
        </a>
        <a href="<?php echo e(route('register')); ?>"
           class="px-5 py-2 rounded-full text-white font-bold transition hover:opacity-90"
           style="background: linear-gradient(135deg, #FF6B6B, #FF8E53);">
            Get Started
        </a>
    </div>
</nav>


<section class="px-6 py-16 md:py-24"
         style="background: linear-gradient(135deg, #fff9f0 0%, #fff0f8 100%);">
    <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center gap-12">

        
        <div class="flex-1 text-center md:text-left">
            <div class="inline-block bg-orange-100 text-orange-600 font-bold px-4 py-2 rounded-full text-sm mb-4">
                <i class="fa-solid fa-graduation-cap"></i> For Kids Ages 3-7
            </div>
            <h1 class="font-fredoka text-5xl md:text-6xl text-gray-800 leading-tight mb-6">
                The Fun Way for<br>
                <span class="text-orange-500">Kids to Learn</span> &amp; Grow
            </h1>
            <p class="text-gray-600 text-xl mb-8 leading-relaxed">
                Interactive lessons, fun games, and colorful activities that help young learners master the basics — Alphabet, Numbers, Colors, Shapes, and more!
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                <a href="<?php echo e(route('register')); ?>"
                   class="btn-kid text-white text-xl shadow-lg"
                   style="background: linear-gradient(135deg, #FF6B6B, #FF8E53);">
                    <i class="fa-solid fa-rocket"></i> Start Learning Free
                </a>
                <a href="#features"
                   class="btn-kid bg-white text-gray-700 border-2 border-gray-200 text-xl shadow-sm">
                    <i class="fa-solid fa-star"></i> See Features
                </a>
            </div>
        </div>

        
        <div class="flex-1 grid grid-cols-2 gap-4" id="subjects">
            <?php
                $subjectCards = [
                    ['fa' => 'fa-solid fa-font',    'title' => 'Alphabet', 'desc' => 'Learn A to Z',  'color' => '#FF6B6B', 'bg' => '#fff0f0'],
                    ['fa' => 'fa-solid fa-hashtag', 'title' => 'Numbers',  'desc' => 'Count to 20',   'color' => '#4ECDC4', 'bg' => '#f0fffe'],
                    ['fa' => 'fa-solid fa-palette', 'title' => 'Colors',   'desc' => 'See the world', 'color' => '#45B7D1', 'bg' => '#f0f8ff'],
                    ['fa' => 'fa-solid fa-shapes',  'title' => 'Shapes',   'desc' => 'Circles & more','color' => '#F7DC6F', 'bg' => '#fffdf0'],
                ];
            ?>

            <?php $__currentLoopData = $subjectCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card-hover rounded-3xl p-6 text-center shadow-md cursor-pointer"
                 style="background: <?php echo e($card['bg']); ?>; border: 3px solid <?php echo e($card['color']); ?>30;">
                <div class="mb-3" style="font-size:3rem; color:<?php echo e($card['color']); ?>;"><i class="<?php echo e($card['fa']); ?>"></i></div>
                <div class="font-fredoka text-xl" style="color: <?php echo e($card['color']); ?>"><?php echo e($card['title']); ?></div>
                <div class="text-gray-500 text-sm mt-1"><?php echo e($card['desc']); ?></div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<section class="py-12 px-6" style="background: linear-gradient(135deg, #FF6B6B, #FF8E53);">
    <div class="max-w-4xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-6 text-center text-white">
        <?php
            $stats = [
                ['fa' => 'fa-solid fa-face-smile',       'value' => '30+',  'label' => 'Happy Students'],
                ['fa' => 'fa-solid fa-chalkboard-user',  'value' => 'Many', 'label' => 'Great Teachers'],
                ['fa' => 'fa-solid fa-school',           'value' => '1',    'label' => 'School'],
                ['fa' => 'fa-solid fa-gamepad',          'value' => '∞',    'label' => 'Fun Activities'],
            ];
        ?>

        <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white bg-opacity-20 rounded-3xl p-6">
            <div class="mb-2" style="font-size:2.5rem;"><i class="<?php echo e($stat['fa']); ?>"></i></div>
            <div class="font-fredoka text-4xl mb-1"><?php echo e($stat['value']); ?></div>
            <div class="font-semibold opacity-90"><?php echo e($stat['label']); ?></div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</section>


<section id="features" class="py-20 px-6 bg-white">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-14">
            <h2 class="font-fredoka text-4xl md:text-5xl text-gray-800 mb-4">
                Everything a Young Learner Needs
            </h2>
            <p class="text-gray-500 text-lg">Designed to make learning exciting, safe, and effective</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php
                $features = [
                    ['fa' => 'fa-solid fa-gamepad',          'title' => 'Fun Mini Games',    'desc' => 'Drag & drop, matching, quizzes — learning disguised as play!',  'color' => '#FF6B6B'],
                    ['fa' => 'fa-solid fa-trophy',           'title' => 'Stars & Badges',    'desc' => 'Earn stars and unlock badges as you complete lessons.',          'color' => '#FFD700'],
                    ['fa' => 'fa-solid fa-chart-column',     'title' => 'Progress Tracking', 'desc' => 'Teachers see exactly how kids are doing.',                       'color' => '#4ECDC4'],
                    ['fa' => 'fa-solid fa-volume-high',      'title' => 'Audio Instructions','desc' => 'Every activity has audio support for non-reading learners.',     'color' => '#45B7D1'],
                    ['fa' => 'fa-solid fa-chalkboard-user',  'title' => 'Teacher Dashboard', 'desc' => 'Manage classes, assign activities, and track student scores.',  'color' => '#BB8FCE'],
                    ['fa' => 'fa-solid fa-mobile-screen',    'title' => 'Works Everywhere',  'desc' => 'Mobile, tablet, and desktop friendly — learn from any device.', 'color' => '#52BE80'],
                ];
            ?>

            <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card-hover bg-gray-50 rounded-3xl p-8 text-center">
                <div class="mb-4" style="font-size:3rem; color:<?php echo e($f['color']); ?>;"><i class="<?php echo e($f['fa']); ?>"></i></div>
                <h3 class="font-fredoka text-2xl mb-3" style="color: <?php echo e($f['color']); ?>"><?php echo e($f['title']); ?></h3>
                <p class="text-gray-500 leading-relaxed"><?php echo e($f['desc']); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<section id="how-it-works" class="py-20 px-6" style="background: linear-gradient(135deg, #f0f8ff, #fff0f8);">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="font-fredoka text-4xl md:text-5xl text-gray-800 mb-14">How It Works</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php
                $steps = [
                    ['num' => '1', 'fa' => 'fa-solid fa-pen',       'title' => 'Create an Account',   'desc' => 'Sign up as a teacher or student in seconds.',          'color' => '#FF6B6B'],
                    ['num' => '2', 'fa' => 'fa-solid fa-book-open', 'title' => 'Pick a Lesson',       'desc' => 'Choose from Alphabet, Numbers, Colors, Shapes, Words.', 'color' => '#4ECDC4'],
                    ['num' => '3', 'fa' => 'fa-solid fa-medal',     'title' => 'Learn & Earn Badges', 'desc' => 'Complete activities and collect badges!',               'color' => '#FFD700'],
                ];
            ?>

            <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-3xl p-8 shadow-md relative">
                <div class="absolute -top-5 left-1/2 -translate-x-1/2 w-10 h-10 rounded-full text-white font-fredoka text-xl flex items-center justify-center shadow-md"
                     style="background: <?php echo e($step['color']); ?>">
                    <?php echo e($step['num']); ?>

                </div>
                <div class="mb-4 mt-4" style="font-size:3rem; color:<?php echo e($step['color']); ?>;"><i class="<?php echo e($step['fa']); ?>"></i></div>
                <h3 class="font-fredoka text-2xl mb-3 text-gray-800"><?php echo e($step['title']); ?></h3>
                <p class="text-gray-500"><?php echo e($step['desc']); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<section id="roles" class="py-20 px-6 bg-white">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="font-fredoka text-4xl md:text-5xl text-gray-800 mb-4">Built for Everyone</h2>
        <p class="text-gray-500 text-lg mb-14">A place for students and teachers</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-2xl mx-auto">
            <?php
                $roles = [
                    ['fa' => 'fa-solid fa-child',           'title' => 'Students', 'items' => ['Watch lessons', 'Play fun games', 'Earn badges', 'Pick your avatar'],       'color' => '#FF6B6B', 'bg' => '#fff0f0'],
                    ['fa' => 'fa-solid fa-chalkboard-user', 'title' => 'Teachers', 'items' => ['Manage classes', 'Assign activities', 'Track progress', 'Post announcements'], 'color' => '#4ECDC4', 'bg' => '#f0fffe'],
                ];
            ?>

            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="rounded-3xl p-8 text-left shadow-sm" style="background: <?php echo e($role['bg']); ?>; border: 2px solid <?php echo e($role['color']); ?>30;">
                <div class="mb-4" style="font-size:3rem; color:<?php echo e($role['color']); ?>;"><i class="<?php echo e($role['fa']); ?>"></i></div>
                <h3 class="font-fredoka text-2xl mb-4" style="color: <?php echo e($role['color']); ?>"><?php echo e($role['title']); ?></h3>
                <ul class="space-y-2">
                    <?php $__currentLoopData = $role['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="flex items-center gap-2 text-gray-600 font-semibold">
                        <i class="fa-solid fa-check" style="color: <?php echo e($role['color']); ?>"></i> <?php echo e($item); ?>

                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<section class="py-20 px-6 text-center text-white"
         style="background: linear-gradient(135deg, #FF6B6B, #FF8E53);">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6" style="font-size:4rem;"><i class="fa-solid fa-rocket"></i></div>
        <h2 class="font-fredoka text-4xl md:text-5xl mb-6">Ready to Start Learning?</h2>
        <p class="text-xl opacity-90 mb-10">Join KinderLearn today and make every lesson an adventure!</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?php echo e(route('register')); ?>"
               class="btn-kid bg-white text-orange-500 text-xl shadow-lg">
                <i class="fa-solid fa-graduation-cap"></i> Create Free Account
            </a>
            <a href="<?php echo e(route('login')); ?>"
               class="btn-kid border-2 border-white text-white text-xl hover:bg-white hover:bg-opacity-10">
                <i class="fa-solid fa-key"></i> Sign In
            </a>
        </div>
    </div>
</section>


<footer class="bg-gray-800 text-gray-400 py-8 px-6 text-center">
    <div class="font-fredoka text-2xl text-white mb-2">KinderLearn</div>
    <p class="text-sm">Making learning fun for every child. &copy; <?php echo e(date('Y')); ?> KinderLearn.</p>
</footer>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\kinderlearn\resources\views/welcome.blade.php ENDPATH**/ ?>