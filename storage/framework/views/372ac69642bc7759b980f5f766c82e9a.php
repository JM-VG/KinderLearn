<?php $__env->startSection('title', $activity->title); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ── shared ── */
.game-card { background:#fff; border-radius:1.5rem; box-shadow:0 2px 12px rgba(0,0,0,.06); padding:1.75rem; margin-bottom:1.5rem; }

/* ── quiz ── */
.q-option {
    width:100%; padding:1rem 1.25rem; border-radius:1rem; border:2.5px solid #e5e7eb;
    font-size:1.05rem; font-weight:700; color:#374151; background:#fff;
    cursor:pointer; transition:all .15s ease; text-align:left;
    display:flex; align-items:center; gap:.75rem;
}
.q-option:hover  { border-color:var(--mod-color,#FF6B6B); background:rgba(var(--mod-rgb,255,107,107),.06); }
.q-option.chosen { border-color:var(--mod-color,#FF6B6B); background:rgba(var(--mod-rgb,255,107,107),.1); transform:scale(1.02); }
.q-option.correct { border-color:#10b981; background:#f0fdf4; color:#065f46; }
.q-option.wrong   { border-color:#ef4444; background:#fff1f0; color:#991b1b; animation:shake .35s; }
@keyframes shake { 0%,100%{transform:translateX(0)} 25%{transform:translateX(-6px)} 75%{transform:translateX(6px)} }

/* ── matching ── */
.match-card {
    padding:.75rem 1rem; border-radius:.875rem; border:2.5px solid #e5e7eb;
    font-weight:700; font-size:1rem; color:#374151; background:#fff;
    cursor:pointer; transition:all .15s ease; text-align:center;
}
.match-card:hover   { border-color:var(--mod-color,#FF6B6B); background:rgba(var(--mod-rgb,255,107,107),.06); }
.match-card.active  { border-color:var(--mod-color,#FF6B6B); background:rgba(var(--mod-rgb,255,107,107),.12); transform:scale(1.04); }
.match-card.matched { border-color:#10b981; background:#f0fdf4; color:#065f46; cursor:default; }
.match-card.bad     { border-color:#ef4444; background:#fff1f0; animation:shake .35s; }
.match-card.hidden  { visibility:hidden; }

/* ── word builder ── */
.wb-blank { width:3rem; height:3.5rem; border-bottom:3px solid var(--mod-color,#FF6B6B); display:flex; align-items:flex-end; justify-content:center; font-size:1.75rem; font-weight:800; color:#1A212F; }
.wb-letter { padding:.6rem .9rem; border-radius:.75rem; border:2.5px solid #e5e7eb; font-size:1.3rem; font-weight:800; color:#374151; background:#fff; cursor:pointer; transition:all .15s ease; min-width:3rem; }
.wb-letter:hover:not(:disabled) { border-color:var(--mod-color,#FF6B6B); background:rgba(var(--mod-rgb,255,107,107),.08); }
.wb-letter:disabled { opacity:.3; cursor:not-allowed; }

/* ── celebration ── */
.celebrate { animation:pop .5s cubic-bezier(.34,1.56,.64,1) both; }
@keyframes pop { 0%{transform:scale(0);opacity:0} 100%{transform:scale(1);opacity:1} }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('student-content'); ?>

<?php
    $style      = $activity->content['style'] ?? 'default';
    $questions  = $activity->content['questions'] ?? [];
    $modColor   = $activity->module?->color ?? '#FF6B6B';
    // Convert hex to rgb for CSS rgba()
    [$r,$g,$b]  = sscanf(ltrim($modColor,'#'), '%02x%02x%02x');
    $modRgb     = "$r,$g,$b";
?>

<style>:root{--mod-color:<?php echo e($modColor); ?>;--mod-rgb:<?php echo e($modRgb); ?>;}</style>


<div class="mb-5">
    <a href="<?php echo e(route('student.modules.show', $activity->module_id)); ?>"
       class="inline-flex items-center gap-1 text-gray-500 font-bold hover:text-gray-800 transition">
        <i class="ri-arrow-left-line"></i> Back to <?php echo e($activity->module?->title ?? 'Module'); ?>

    </a>
</div>

<div class="max-w-2xl mx-auto">


<div class="game-card flex items-center gap-5 mb-6">
    <?php
    $actIcons = ['video'=>['fa-film','#FF6B6B'],'quiz'=>['fa-brain','#BB8FCE'],'matching'=>['fa-shuffle','#4ECDC4'],'word_builder'=>['fa-pen','#45B7D1'],'coloring'=>['fa-palette','#FF8E53'],'audio'=>['fa-volume-high','#52BE80'],'tracing'=>['fa-pen-nib','#8b5cf6']];
    $actKey = $style !== 'default' ? $style : $activity->type;
    [$actIco, $actCol] = $actIcons[$actKey] ?? ['fa-file-pen','#9ca3af'];
?>
    <div style="font-size:3rem; color:<?php echo e($actCol); ?>;"><i class="fa-solid <?php echo e($actIco); ?>"></i></div>
    <div class="flex-1">
        <h1 class="font-fredoka text-2xl text-gray-800 leading-tight"><?php echo e($activity->title); ?></h1>
        <p class="text-gray-400 text-sm mt-0.5"><?php echo e($activity->module?->title ?? 'Module'); ?>

            <?php if($submission): ?>
                &nbsp;·&nbsp;
                <span class="text-green-600 font-bold"><i class="fa-solid fa-circle-check"></i> Done · <?php echo e($submission->score); ?>%</span>
            <?php endif; ?>
        </p>
    </div>
    <?php if($submission): ?>
        <div class="flex gap-0.5">
            <?php for($s = 1; $s <= 3; $s++): ?>
                <i class="<?php echo e($s <= $submission->stars_earned ? 'fa-solid' : 'fa-regular'); ?> fa-star" style="font-size:1.5rem; color:#FFD700;"></i>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>


<?php if($submission): ?>

<?php if($activity->type === 'tracing' && $submission->stars_earned == 0): ?>

<div class="game-card text-center celebrate">
    <div class="mb-4" style="font-size:4.5rem; color:#8b5cf6;">
        <i class="fa-solid fa-clock"></i>
    </div>
    <div class="font-fredoka text-3xl mb-2 text-gray-700">Submitted!</div>
    <p class="text-gray-500 font-semibold mb-2">Your tracing is waiting to be graded by your teacher.</p>
    <?php if($submission->file_path): ?>
    <img src="<?php echo e(asset('storage/' . $submission->file_path)); ?>"
         class="mx-auto rounded-2xl max-h-48 object-contain border border-gray-200 mb-4">
    <?php endif; ?>
    <?php if($submission->feedback): ?>
    <div class="bg-purple-50 border border-purple-200 rounded-2xl px-5 py-3 mb-4 text-left">
        <p class="text-xs font-bold text-purple-500 mb-1">Teacher Feedback</p>
        <p class="text-gray-700 font-semibold"><?php echo e($submission->feedback); ?></p>
    </div>
    <?php endif; ?>
    <a href="<?php echo e(route('student.modules.show', $activity->module_id)); ?>"
       class="inline-flex items-center gap-2 px-7 py-3 rounded-2xl font-bold text-white text-base hover:opacity-90 transition"
       style="background:#8b5cf6;">
        <i class="ri-arrow-left-line"></i> Back to Module
    </a>
</div>
<?php elseif($activity->type === 'tracing'): ?>

<div class="game-card text-center celebrate">
    <div class="mb-4" style="font-size:4.5rem; color:#8b5cf6;"><i class="fa-solid fa-pen-nib"></i></div>
    <div class="font-fredoka text-3xl mb-2" style="color:#8b5cf6;">Graded!</div>
    <div class="flex gap-1 justify-center mb-3">
        <?php for($s = 1; $s <= 3; $s++): ?>
            <i class="<?php echo e($s <= $submission->stars_earned ? 'fa-solid' : 'fa-regular'); ?> fa-star"
               style="font-size:2rem; color:#FFD700;"></i>
        <?php endfor; ?>
    </div>
    <?php if($submission->file_path): ?>
    <img src="<?php echo e(asset('storage/' . $submission->file_path)); ?>"
         class="mx-auto rounded-2xl max-h-48 object-contain border border-gray-200 mb-4">
    <?php endif; ?>
    <?php if($submission->feedback): ?>
    <div class="bg-purple-50 border border-purple-200 rounded-2xl px-5 py-3 mb-4 text-left">
        <p class="text-xs font-bold text-purple-500 mb-1">Teacher Feedback</p>
        <p class="text-gray-700 font-semibold"><?php echo e($submission->feedback); ?></p>
    </div>
    <?php endif; ?>
    <a href="<?php echo e(route('student.modules.show', $activity->module_id)); ?>"
       class="inline-flex items-center gap-2 px-7 py-3 rounded-2xl font-bold text-white text-base hover:opacity-90 transition"
       style="background:#8b5cf6;">
        <i class="ri-arrow-left-line"></i> Back to Module
    </a>
</div>
<?php else: ?>
<div class="game-card text-center celebrate">
    <div class="mb-4" style="font-size:4.5rem; color:var(--mod-color);">
        <?php if($submission->score >= 90): ?>
            <i class="fa-solid fa-trophy"></i>
        <?php else: ?>
            <i class="fa-solid fa-fist-raised"></i>
        <?php endif; ?>
    </div>
    <div class="font-fredoka text-5xl mb-2" style="color:var(--mod-color)"><?php echo e($submission->score); ?>%</div>
    <div class="font-bold text-gray-600 mb-4 text-lg">
        You earned <?php echo e($submission->stars_earned); ?> <i class="fa-solid fa-star" style="color:#FFD700;"></i>
        <?php if($submission->score >= 90): ?> — Amazing!
        <?php elseif($submission->score >= 60): ?> — Great job!
        <?php else: ?> — Keep practising!
        <?php endif; ?>
    </div>
    <a href="<?php echo e(route('student.modules.show', $activity->module_id)); ?>"
       class="inline-flex items-center gap-2 px-7 py-3 rounded-2xl font-bold text-white text-base hover:opacity-90 transition"
       style="background:var(--mod-color);">
        <i class="ri-arrow-left-line"></i> Back to Module
    </a>
</div>
<?php endif; ?>


<?php elseif($style === 'matching' && !empty($activity->content['pairs'])): ?>

<?php
    $pairs = $activity->content['pairs'];
    $lefts  = collect($pairs)->pluck('left')->toArray();
    $rights = collect($pairs)->pluck('right')->shuffle()->toArray();
    $correct_map = collect($pairs)->pluck('right','left')->toArray();
?>

<div class="game-card">
    <h2 class="font-fredoka text-2xl text-gray-800 mb-2">Match the Pairs! <i class="fa-solid fa-shuffle" style="color:#4ECDC4;"></i></h2>
    <p class="text-gray-400 text-sm mb-6">Tap an item on the left, then tap its match on the right.</p>

    <div id="match-score-bar" class="flex items-center gap-3 mb-5">
        <div class="flex-1 h-3 rounded-full bg-gray-100 overflow-hidden">
            <div id="match-bar" class="h-full rounded-full transition-all duration-500"
                 style="width:0%;background:var(--mod-color);"></div>
        </div>
        <span id="match-count" class="text-sm font-bold text-gray-500">0 / <?php echo e(count($pairs)); ?></span>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div class="flex flex-col gap-3" id="left-col">
            <?php $__currentLoopData = $lefts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $left): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button class="match-card" data-side="left" data-index="<?php echo e($i); ?>" data-value="<?php echo e($left); ?>"
                    onclick="matchClick(this)"><?php echo e($left); ?></button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="flex flex-col gap-3" id="right-col">
            <?php $__currentLoopData = $rights; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $right): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button class="match-card" data-side="right" data-index="<?php echo e($i); ?>" data-value="<?php echo e($right); ?>"
                    onclick="matchClick(this)"><?php echo e($right); ?></button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>

<form id="match-form" method="POST" action="<?php echo e(route('student.activities.submit', $activity->id)); ?>" class="hidden">
    <?php echo csrf_field(); ?>
    <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <input type="hidden" name="answers[<?php echo e($i); ?>]" id="match-ans-<?php echo e($i); ?>" value="">
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</form>


<?php elseif($style === 'word_builder' && !empty($activity->content['word'])): ?>

<?php
    $word    = $activity->content['word'];
    $hint    = $activity->content['hint'];
    $letters = $activity->content['letters'];
?>

<div class="game-card text-center">
    <h2 class="font-fredoka text-2xl text-gray-800 mb-1">Spell the Word! <i class="fa-solid fa-pen" style="color:#45B7D1;"></i></h2>
    <p class="text-gray-400 text-sm mb-6">Tap the letters in the right order to spell the word.</p>

    <div class="text-7xl mb-4"><?php echo e($hint); ?></div>

    
    <div class="flex items-end justify-center gap-3 mb-8" id="wb-slots">
        <?php for($i = 0; $i < strlen($word); $i++): ?>
            <div class="wb-blank" id="slot-<?php echo e($i); ?>"></div>
        <?php endfor; ?>
    </div>

    
    <div class="flex flex-wrap justify-center gap-3 mb-6" id="wb-letters">
        <?php $__currentLoopData = $letters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $li => $letter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button class="wb-letter" data-index="<?php echo e($li); ?>" data-letter="<?php echo e($letter); ?>"
                    onclick="wbPick(this)"><?php echo e($letter); ?></button>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="flex justify-center gap-3">
        <button onclick="wbErase()"
                class="px-5 py-2.5 rounded-xl border-2 border-gray-200 font-bold text-gray-500 hover:border-red-300 hover:text-red-500 transition">
            ⌫ Erase
        </button>
        <button id="wb-check" onclick="wbCheck()" disabled
                class="px-7 py-2.5 rounded-xl font-bold text-white opacity-50 transition"
                style="background:var(--mod-color);">
            <i class="fa-solid fa-check"></i> Check!
        </button>
    </div>
    <p id="wb-msg" class="mt-4 font-bold text-lg hidden"></p>
</div>

<form id="word-form" method="POST" action="<?php echo e(route('student.activities.submit', $activity->id)); ?>" class="hidden">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="answers[0]" id="wb-answer" value="">
</form>


<?php elseif($activity->type === 'tracing' && !empty($activity->file_path) && !$submission): ?>

<?php $templateUrl = asset('storage/' . $activity->file_path); ?>

<div class="game-card">
    <h2 class="font-fredoka text-2xl text-gray-800 mb-1">
        <i class="fa-solid fa-pen-nib" style="color:#8b5cf6;"></i> Trace It!
    </h2>
    <p class="text-gray-400 text-sm mb-4">Use your finger or mouse to trace over the image below. When you're happy, tap <strong>Submit</strong>.</p>

    
    <div class="flex flex-wrap items-center gap-3 mb-3">
        
        <div class="flex items-center gap-2">
            <label class="text-xs font-bold text-gray-500">Color</label>
            <input type="color" id="brush-color" value="#1e40af"
                   class="w-9 h-9 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5">
        </div>
        
        <div class="flex items-center gap-2">
            <label class="text-xs font-bold text-gray-500">Size</label>
            <input type="range" id="brush-size" min="2" max="30" value="5" class="w-24">
        </div>
        
        <button id="eraser-btn" onclick="toggleEraser()"
                class="px-4 py-1.5 rounded-xl border-2 border-gray-200 text-sm font-bold text-gray-600 hover:border-purple-400 hover:text-purple-600 transition">
            <i class="fa-solid fa-eraser"></i> Eraser
        </button>
        
        <button onclick="clearCanvas()"
                class="px-4 py-1.5 rounded-xl border-2 border-red-200 text-sm font-bold text-red-400 hover:border-red-400 hover:bg-red-50 transition">
            <i class="fa-solid fa-trash"></i> Clear
        </button>
    </div>

    
    <div class="relative rounded-2xl overflow-hidden border-2 border-gray-200 mb-4" id="canvas-wrap"
         style="touch-action:none;">
        
        <canvas id="template-canvas" class="absolute inset-0 w-full h-full"></canvas>
        
        <canvas id="draw-canvas"     class="absolute inset-0 w-full h-full cursor-crosshair"></canvas>
        
        <img src="<?php echo e($templateUrl); ?>" alt="template" class="w-full opacity-0 pointer-events-none" id="template-img">
    </div>

    <div class="text-center">
        <button id="submit-btn" onclick="submitTracing()"
                class="px-10 py-3 rounded-2xl font-bold text-white text-base hover:opacity-90 transition"
                style="background: linear-gradient(135deg, #8b5cf6, #6366f1);">
            <i class="fa-solid fa-paper-plane"></i> Submit My Tracing
        </button>
        <p id="submit-msg" class="hidden mt-2 text-sm text-gray-400 font-semibold">Uploading…</p>
    </div>
</div>

<form id="tracing-form" method="POST" action="<?php echo e(route('student.activities.tracing.submit', $activity->id)); ?>" class="hidden">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="drawing" id="drawing-data">
</form>


<?php elseif(!empty($questions)): ?>

<div id="quiz-wrap" class="game-card">
    
    <div class="flex items-center gap-3 mb-5">
        <div class="flex-1 h-3 rounded-full bg-gray-100 overflow-hidden">
            <div id="q-bar" class="h-full rounded-full transition-all duration-500"
                 style="width:0%;background:var(--mod-color);"></div>
        </div>
        <span id="q-counter" class="text-sm font-bold text-gray-500">1 / <?php echo e(count($questions)); ?></span>
    </div>

    
    <div id="q-img-wrap" class="hidden mb-4 flex justify-center">
        <img id="q-img" src="" alt=""
             class="rounded-2xl object-contain"
             style="max-height:180px; max-width:100%;">
    </div>

    
    <div class="flex items-start gap-3 mb-6">
        <p id="q-text" class="font-fredoka text-2xl text-gray-800 leading-tight flex-1 min-h-[3rem]"></p>
        <button onclick="speakText(document.getElementById('q-text').textContent)"
                class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center transition hover:opacity-80"
                style="background:rgba(var(--mod-rgb),0.12); color:var(--mod-color);"
                title="Read aloud">
            <i class="ri-volume-up-line text-lg"></i>
        </button>
    </div>

    
    <div id="q-options" class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6"></div>

    
    <div class="text-center mt-2 hidden" id="q-next-wrap">
        <button id="q-next-btn" onclick="qNext()"
                class="px-8 py-3 rounded-2xl font-bold text-white text-base hover:opacity-90 transition"
                style="background:var(--mod-color);">
            Next →
        </button>
    </div>
</div>

<div id="q-finish" class="game-card text-center hidden">
    <div class="mb-3 animate-bounce" style="font-size:4rem; color:#FFD700;"><i class="fa-solid fa-star"></i></div>
    <p class="font-fredoka text-2xl text-gray-700 mb-2">All done! Submitting…</p>
</div>

<form id="quiz-form" method="POST" action="<?php echo e(route('student.activities.submit', $activity->id)); ?>" class="hidden">
    <?php echo csrf_field(); ?>
    <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <input type="hidden" name="answers[<?php echo e($i); ?>]" id="q-ans-<?php echo e($i); ?>" value="">
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</form>


<?php elseif($activity->type === 'video' && !empty($activity->content['video_url'])): ?>

<div class="game-card">
    <h2 class="font-fredoka text-2xl text-gray-800 mb-4"><i class="fa-solid fa-film" style="color:#FF6B6B;"></i> Watch and Learn!</h2>
    <div class="rounded-2xl overflow-hidden mb-5" style="aspect-ratio:16/9;">
        <iframe src="<?php echo e($activity->content['video_url']); ?>" class="w-full h-full"
                frameborder="0" allowfullscreen></iframe>
    </div>
    <div class="text-center">
        <form method="POST" action="<?php echo e(route('student.activities.submit', $activity->id)); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit"
                    class="px-8 py-3 rounded-2xl font-bold text-white hover:opacity-90 transition"
                    style="background:var(--mod-color);">
                <i class="fa-solid fa-circle-check"></i> I Watched It!
            </button>
        </form>
    </div>
</div>


<?php else: ?>

<div class="game-card text-center">
    <div class="mb-4" style="font-size:4rem; color:var(--mod-color);"><i class="fa-solid fa-bullseye"></i></div>
    <h2 class="font-fredoka text-2xl text-gray-700 mb-4">Complete this activity!</h2>
    <form method="POST" action="<?php echo e(route('student.activities.submit', $activity->id)); ?>">
        <?php echo csrf_field(); ?>
        <button type="submit"
                class="px-8 py-3 rounded-2xl font-bold text-white hover:opacity-90 transition"
                style="background:var(--mod-color);">
            <i class="fa-solid fa-circle-check"></i> Mark as Done
        </button>
    </form>
</div>

<?php endif; ?>

</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://unpkg.com/tone"></script>
<script>
// Modules that always show images — emojis are stripped from all question text
var EMOJI_FREE_MODULES = ['Shapes Everywhere', 'My First Words'];
var CURRENT_MODULE     = '<?php echo e($activity->module?->title ?? ''); ?>';

// Shared helper — strips emoji Unicode from question/answer text
function stripEmoji(text) {
    return (text || '').replace(
        /[\u{1F000}-\u{1FAFF}\u{2600}-\u{27BF}\u{2300}-\u{23FF}\u{2B50}\u{2B55}\u{FE00}-\u{FEFF}\u{1F900}-\u{1F9FF}]/gu,
        ''
    ).replace(/\s{2,}/g, ' ').trim();
}

// Local image map — keyed by exact correct-answer string (case-sensitive)
var QUIZ_IMAGES = {
    // ── Shapes Everywhere ──────────────────────────────────────────
    'Square':     '/images/quiz/ShapesEverywhere/shape-square.png',
    'Triangle':   '/images/quiz/ShapesEverywhere/shape-triangle.png',
    'Circle':     '/images/quiz/ShapesEverywhere/shape-circle.png',
    'Rectangle':  '/images/quiz/ShapesEverywhere/shape-rectangle.png',
    'Diamond':    '/images/quiz/ShapesEverywhere/shape-diamond.png',
    'Pentagon':   '/images/quiz/ShapesEverywhere/shape-pentagon.png',
    'Hexagon':    '/images/quiz/ShapesEverywhere/shape-hexagon.png',
    'Octagon':    '/images/quiz/ShapesEverywhere/shape-octagon.png',
    'Oval':       '/images/quiz/ShapesEverywhere/shape-oval.png',
    'Star':       '/images/quiz/ShapesEverywhere/shape-star.png',
    'Sphere':     '/images/quiz/ShapesEverywhere/shape-sphere.png',
    'Cube':       '/images/quiz/ShapesEverywhere/shape-cube.png',
    'Cone':       '/images/quiz/ShapesEverywhere/shape-cone.png',
    'Cylinder':   '/images/quiz/ShapesEverywhere/shape-cylinder.png',
    // ── My First Words — Animals ───────────────────────────────────
    'Cat':        '/images/quiz/MyFirstWords/animal-cat.png',
    'Dog':        '/images/quiz/MyFirstWords/animal-dog.png',
    'Fish':       '/images/quiz/MyFirstWords/animal-fish.png',
    'Bird':       '/images/quiz/MyFirstWords/animal-bird.png',
    'Rabbit':     '/images/quiz/MyFirstWords/animal-rabbit.png',
    'Elephant':   '/images/quiz/MyFirstWords/animal-elephant.png',
    'Lion':       '/images/quiz/MyFirstWords/animal-lion.png',
    'Monkey':     '/images/quiz/MyFirstWords/animal-monkey.png',
    'Crocodile':  '/images/quiz/MyFirstWords/animal-crocodile.png',
    'Giraffe':    '/images/quiz/MyFirstWords/animal-giraffe.png',
    // ── My First Words — Food ──────────────────────────────────────
    'Pizza':      '/images/quiz/MyFirstWords/food-pizza.png',
    'Apple':      '/images/quiz/MyFirstWords/food-apple.png',
    'Banana':     '/images/quiz/MyFirstWords/food-banana.png',
    'Carrot':     '/images/quiz/MyFirstWords/food-carrot.png',
    'Cake':       '/images/quiz/MyFirstWords/food-cake.png',
    // ── My First Words — Actions ───────────────────────────────────
    'Run':        '/images/quiz/MyFirstWords/action-run.png',
    'Sleep':      '/images/quiz/MyFirstWords/action-sleep.png',
    'Jump':       '/images/quiz/MyFirstWords/action-jump.png',
    'Swim':       '/images/quiz/MyFirstWords/action-swim.png',
    'Fly':        '/images/quiz/MyFirstWords/action-fly.png',
    // ── My First Words — Spelling (correct answers are ALL-CAPS) ───
    'CAT':        '/images/quiz/MyFirstWords/word-cat.png',
    'DOG':        '/images/quiz/MyFirstWords/word-dog.png',
    'SUN':        '/images/quiz/MyFirstWords/word-sun.png',
    'HAT':        '/images/quiz/MyFirstWords/word-hat.png',
    'BEE':        '/images/quiz/MyFirstWords/word-bee.png',
    'BUS':        '/images/quiz/MyFirstWords/word-bus.png',
    'MOM':        '/images/quiz/MyFirstWords/word-mom.png',
    'RUN':        '/images/quiz/MyFirstWords/word-run.png',
    'RED':        '/images/quiz/MyFirstWords/word-red.png',
    'TEN':        '/images/quiz/MyFirstWords/word-ten.png',
};
// Shape names to scan in question text for number-answer questions
// e.g. "How many sides does a triangle have?" → shows triangle image
var SHAPE_SCAN = ['square','triangle','circle','rectangle','diamond',
                  'pentagon','hexagon','octagon','oval','star',
                  'sphere','cube','cone','cylinder'];

/* ══════════════════════════════════════════════════════════════════
   QUIZ — one question at a time
══════════════════════════════════════════════════════════════════ */
(function () {
    const questions = <?php echo json_encode($questions, 15, 512) ?>;
    if (!questions.length || document.getElementById('quiz-wrap') === null) return;

    let current = 0;
    let picked  = false;

    function render(i) {
        const q       = questions[i];
        const qText   = document.getElementById('q-text');
        const qOpts   = document.getElementById('q-options');
        const qBar    = document.getElementById('q-bar');
        const qCount  = document.getElementById('q-counter');
        const nxtWrap = document.getElementById('q-next-wrap');

        qOpts.innerHTML   = '';
        nxtWrap.classList.add('hidden');
        picked = false;

        qBar.style.width  = (i / questions.length * 100) + '%';
        qCount.textContent = (i + 1) + ' / ' + questions.length;

        // Resolve image first so we know whether to strip emojis from question text
        var imgWrap = document.getElementById('q-img-wrap');
        var imgEl   = document.getElementById('q-img');
        imgWrap.classList.add('hidden');
        imgEl.src = '';
        // Teacher-uploaded image stored in the question JSON takes priority
        var imgUrl = (q.image ? '/storage/' + q.image : null) || QUIZ_IMAGES[q.correct] || null;
        if (!imgUrl) {
            var qLower = (q.text || q.question || '').toLowerCase();
            for (var si = 0; si < SHAPE_SCAN.length; si++) {
                if (qLower.indexOf(SHAPE_SCAN[si]) !== -1) {
                    imgUrl = '/images/quiz/ShapesEverywhere/shape-' + SHAPE_SCAN[si] + '.png';
                    break;
                }
            }
        }

        // Strip emojis: always for image-first modules, otherwise only when an image replaces them
        var rawText       = q.text ?? q.question ?? '';
        var alwaysStrip   = EMOJI_FREE_MODULES.indexOf(CURRENT_MODULE) !== -1;
        qText.textContent = (alwaysStrip || imgUrl) ? stripEmoji(rawText) : rawText;

        if (imgUrl) {
            imgEl.onerror = function () { imgWrap.classList.add('hidden'); };
            imgEl.onload  = function () { imgWrap.classList.remove('hidden'); };
            imgEl.src = imgUrl;
        }

        q.options.forEach(function (opt, oi) {
            const btn = document.createElement('button');
            btn.className   = 'q-option';
            btn.textContent = opt;
            btn.onclick     = function () { choose(opt, q.correct, btn, i); };
            qOpts.appendChild(btn);
        });
    }

    function choose(val, correct, btn, qi) {
        if (picked) return;
        picked = true;

        document.getElementById('q-ans-' + qi).value = val;

        document.querySelectorAll('.q-option').forEach(function (b) { b.disabled = true; });

        if (val === correct) {
            btn.classList.add('correct');
            if (window.sfxCorrect) sfxCorrect();
            setTimeout(advance, 900);
        } else {
            btn.classList.add('wrong');
            if (window.sfxWrong) sfxWrong();
            // Reveal correct
            document.querySelectorAll('.q-option').forEach(function (b) {
                const txt = b.textContent.trim().replace(/^[^\s]+\s/,'');
                if (txt === correct) b.classList.add('correct');
            });
            document.getElementById('q-next-wrap').classList.remove('hidden');
        }
    }

    function advance() {
        current++;
        if (current < questions.length) {
            render(current);
        } else {
            finish();
        }
    }

    window.qNext = function () { advance(); };

    function finish() {
        document.getElementById('quiz-wrap').classList.add('hidden');
        document.getElementById('q-finish').classList.remove('hidden');
        document.getElementById('q-bar').style.width = '100%';
        if (window.sfxComplete) sfxComplete();
        setTimeout(function () {
            document.getElementById('quiz-form').submit();
        }, 1200);
    }

    render(0);
}());

/* ══════════════════════════════════════════════════════════════════
   MATCHING GAME
══════════════════════════════════════════════════════════════════ */
(function () {
    const game = document.getElementById('match-form');
    if (!game) return;

    const questions  = <?php echo json_encode($questions, 15, 512) ?>;
    const correctMap = <?php echo json_encode($correct_map ?? [], 15, 512) ?>;
    const total      = Object.keys(correctMap).length;
    let matched      = 0;
    let selLeft      = null;

    window.matchClick = function (btn) {
        if (btn.classList.contains('matched') || btn.classList.contains('hidden')) return;

        if (btn.dataset.side === 'left') {
            document.querySelectorAll('[data-side="left"]').forEach(function (b) { b.classList.remove('active'); });
            btn.classList.add('active');
            selLeft = btn;

        } else {
            if (!selLeft) return;

            const lv = selLeft.dataset.value;
            const rv = btn.dataset.value;

            if (correctMap[lv] === rv) {
                // Correct match
                selLeft.classList.replace('active','matched');
                btn.classList.add('matched');
                if (window.sfxCorrect) sfxCorrect();

                // Record answer for grading
                const qi = questions.findIndex(function (q) { return q.text === lv || q.correct === rv; });
                if (qi >= 0) {
                    const inp = document.getElementById('match-ans-' + qi);
                    if (inp) inp.value = rv;
                }
                // Simpler: fill in order
                questions.forEach(function (q, i) {
                    if ((q.text === lv) && document.getElementById('match-ans-' + i)) {
                        document.getElementById('match-ans-' + i).value = rv;
                    }
                });

                matched++;
                selLeft = null;

                const pct = Math.round(matched / total * 100);
                document.getElementById('match-bar').style.width  = pct + '%';
                document.getElementById('match-count').textContent = matched + ' / ' + total;

                if (matched >= total) {
                    setTimeout(function () { document.getElementById('match-form').submit(); }, 700);
                }

            } else {
                // Wrong match
                if (window.sfxWrong) sfxWrong();
                selLeft.classList.add('bad');
                btn.classList.add('bad');
                const sl = selLeft;
                setTimeout(function () {
                    sl.classList.remove('active','bad');
                    btn.classList.remove('bad');
                    selLeft = null;
                }, 500);
            }
        }
    };
}());

/* ══════════════════════════════════════════════════════════════════
   WORD BUILDER
══════════════════════════════════════════════════════════════════ */
(function () {
    const wForm = document.getElementById('word-form');
    if (!wForm) return;

    const word    = <?php echo json_encode($activity->content['word'] ?? '', 15, 512) ?>;
    const slots   = word.split('').map(function (_, i) { return document.getElementById('slot-' + i); });
    let filled    = [];          // {slotIndex, letterIndex}
    let nextSlot  = 0;

    window.wbPick = function (btn) {
        if (nextSlot >= slots.length) return;
        const letter = btn.dataset.letter;
        slots[nextSlot].textContent = letter;
        filled.push({ slot: nextSlot, btnIndex: btn.dataset.index, btn: btn });
        btn.disabled = true;
        nextSlot++;
        if (nextSlot >= slots.length) {
            document.getElementById('wb-check').disabled = false;
            document.getElementById('wb-check').classList.remove('opacity-50');
        }
    };

    window.wbErase = function () {
        if (!filled.length) return;
        const last = filled.pop();
        slots[last.slot].textContent = '';
        last.btn.disabled = false;
        nextSlot--;
        document.getElementById('wb-check').disabled = true;
        document.getElementById('wb-check').classList.add('opacity-50');
        document.getElementById('wb-msg').classList.add('hidden');
    };

    window.wbCheck = function () {
        const built = filled.map(function (f) { return f.btn.dataset.letter; }).join('');
        const msg   = document.getElementById('wb-msg');
        if (built === word) {
            msg.innerHTML = '<i class="fa-solid fa-star" style="color:#f59e0b;"></i> Correct! Well done!';
            msg.style.color = '#059669';
            msg.classList.remove('hidden');
            document.getElementById('wb-answer').value = word;
            setTimeout(function () { document.getElementById('word-form').submit(); }, 900);
        } else {
            msg.innerHTML = '<i class="fa-solid fa-rotate-left"></i> Try again!';
            msg.style.color = '#dc2626';
            msg.classList.remove('hidden');
            // Shake slots
            slots.forEach(function (s) {
                s.style.animation = 'none';
                s.offsetHeight;
                s.style.animation = 'shake .35s';
            });
        }
    };
}());

/* ══════════════════════════════════════════════════════════════════
   TRACING CANVAS
══════════════════════════════════════════════════════════════════ */
(function () {
    var templateCanvas = document.getElementById('template-canvas');
    if (!templateCanvas) return;

    var drawCanvas  = document.getElementById('draw-canvas');
    var tCtx        = templateCanvas.getContext('2d');
    var dCtx        = drawCanvas.getContext('2d');
    var img         = document.getElementById('template-img');
    var colorInput  = document.getElementById('brush-color');
    var sizeInput   = document.getElementById('brush-size');
    var isDrawing   = false;
    var erasing     = false;

    function resize() {
        var wrap = document.getElementById('canvas-wrap');
        var w    = wrap.clientWidth;
        var h    = img.naturalHeight > 0
                   ? Math.round(w * img.naturalHeight / img.naturalWidth)
                   : Math.round(w * 0.75);

        templateCanvas.width  = w;
        templateCanvas.height = h;
        drawCanvas.width      = w;
        drawCanvas.height     = h;
        drawTemplate();
    }

    function drawTemplate() {
        if (img.naturalWidth === 0) return;
        tCtx.clearRect(0, 0, templateCanvas.width, templateCanvas.height);
        tCtx.globalAlpha = 0.55;
        tCtx.drawImage(img, 0, 0, templateCanvas.width, templateCanvas.height);
        tCtx.globalAlpha = 1;
    }

    img.onload = function () { resize(); };
    if (img.complete && img.naturalWidth) resize();
    window.addEventListener('resize', resize);

    function getPos(e) {
        var r  = drawCanvas.getBoundingClientRect();
        var sx = drawCanvas.width  / r.width;
        var sy = drawCanvas.height / r.height;
        var src = e.touches ? e.touches[0] : e;
        return { x: (src.clientX - r.left) * sx, y: (src.clientY - r.top) * sy };
    }

    function startDraw(e) {
        e.preventDefault();
        isDrawing = true;
        var p = getPos(e);
        dCtx.beginPath();
        dCtx.moveTo(p.x, p.y);
    }

    function draw(e) {
        if (!isDrawing) return;
        e.preventDefault();
        var p    = getPos(e);
        var size = parseInt(sizeInput.value, 10);
        dCtx.lineWidth   = size;
        dCtx.lineCap     = 'round';
        dCtx.lineJoin    = 'round';
        if (erasing) {
            dCtx.globalCompositeOperation = 'destination-out';
            dCtx.strokeStyle = 'rgba(0,0,0,1)';
        } else {
            dCtx.globalCompositeOperation = 'source-over';
            dCtx.strokeStyle = colorInput.value;
        }
        dCtx.lineTo(p.x, p.y);
        dCtx.stroke();
        dCtx.beginPath();
        dCtx.moveTo(p.x, p.y);
    }

    function stopDraw(e) {
        if (!isDrawing) return;
        isDrawing = false;
        dCtx.beginPath();
    }

    drawCanvas.addEventListener('mousedown',  startDraw);
    drawCanvas.addEventListener('mousemove',  draw);
    drawCanvas.addEventListener('mouseup',    stopDraw);
    drawCanvas.addEventListener('mouseleave', stopDraw);
    drawCanvas.addEventListener('touchstart', startDraw, { passive: false });
    drawCanvas.addEventListener('touchmove',  draw,      { passive: false });
    drawCanvas.addEventListener('touchend',   stopDraw);

    window.toggleEraser = function () {
        erasing = !erasing;
        var btn = document.getElementById('eraser-btn');
        btn.style.borderColor = erasing ? '#8b5cf6' : '';
        btn.style.color       = erasing ? '#8b5cf6' : '';
        btn.style.background  = erasing ? 'rgba(139,92,246,.08)' : '';
    };

    window.clearCanvas = function () {
        dCtx.clearRect(0, 0, drawCanvas.width, drawCanvas.height);
    };

    window.submitTracing = function () {
        // Merge template + drawing into one canvas for export
        var out  = document.createElement('canvas');
        out.width  = templateCanvas.width;
        out.height = templateCanvas.height;
        var oCtx = out.getContext('2d');
        oCtx.fillStyle = '#ffffff';
        oCtx.fillRect(0, 0, out.width, out.height);
        oCtx.drawImage(templateCanvas, 0, 0);
        oCtx.globalAlpha = 1;
        oCtx.drawImage(drawCanvas, 0, 0);

        document.getElementById('drawing-data').value = out.toDataURL('image/png');
        document.getElementById('submit-btn').disabled = true;
        document.getElementById('submit-msg').classList.remove('hidden');
        document.getElementById('tracing-form').submit();
    };
}());

/* ══════════════════════════════════════════════════════════════════
   TONE.JS SOUND EFFECTS
   AudioContext must be resumed inside a user gesture — we pre-warm
   it on the first click, then all sfx calls use Tone.start().then()
   so the synth is created only after the context is actually running.
══════════════════════════════════════════════════════════════════ */
(function () {
    function getS(k, d) { try { return JSON.parse(localStorage.getItem('kl_' + k)); } catch(e) { return d; } }

    // Pre-warm AudioContext on the very first user click (capture phase
    // so it fires before any child handler, giving the quickest resume).
    document.addEventListener('click', function warmTone() {
        if (window.Tone) Tone.start().catch(function(){});
    }, { once: true, capture: true });

    function playNotes(synthClass, synthOpts, volume, notes, disposeMs) {
        if (!getS('sfx', true) || !window.Tone) return;
        Tone.start().then(function () {
            try {
                var s = new synthClass(synthOpts).toDestination();
                s.volume.value = volume;
                var t = Tone.now();
                notes.forEach(function (n) { s.triggerAttackRelease(n[0], n[1], t + (n[2] || 0)); });
                setTimeout(function () { try { s.dispose(); } catch (e) {} }, disposeMs || 1500);
            } catch (e) {}
        }).catch(function () {});
    }

    var BASE_ENV = { oscillator: { type: 'triangle' },
                     envelope:   { attack: 0.01, decay: 0.15, sustain: 0.1, release: 0.3 } };

    window.sfxCorrect = function () {
        playNotes(Tone.Synth, BASE_ENV, -8,
            [['E5','16n',0], ['G5','16n',0.13], ['C6','8n',0.26]], 1400);
    };

    window.sfxWrong = function () {
        playNotes(Tone.Synth,
            { oscillator: { type: 'sawtooth' },
              envelope:   { attack: 0.01, decay: 0.18, sustain: 0, release: 0.1 } },
            -12, [['C4','16n',0], ['A3','16n',0.1]], 700);
    };

    window.sfxClick = function () {
        playNotes(Tone.Synth,
            { oscillator: { type: 'sine' },
              envelope:   { attack: 0.001, decay: 0.06, sustain: 0, release: 0.04 } },
            -16, [['A4','32n',0]], 400);
    };

    window.sfxComplete = function () {
        if (!getS('sfx', true) || !window.Tone) return;
        Tone.start().then(function () {
            try {
                var ps = new Tone.PolySynth(Tone.Synth).toDestination();
                ps.volume.value = -10;
                var t = Tone.now();
                ps.triggerAttackRelease(['C4','E4','G4'], '8n', t);
                ps.triggerAttackRelease(['E4','G4','B4'], '8n', t + 0.25);
                ps.triggerAttackRelease(['C5','E5','G5'], '4n', t + 0.5);
                setTimeout(function () { try { ps.dispose(); } catch (e) {} }, 2500);
            } catch (e) {}
        }).catch(function () {});
    };
}());

/* ══════════════════════════════════════════════════════════════════
   TEXT-TO-SPEECH — Puter.js (playful voice) + Web Speech fallback
══════════════════════════════════════════════════════════════════ */
(function () {
    function getS(k, d) { try { return JSON.parse(localStorage.getItem('kl_' + k)); } catch(e) { return d; } }

    var currentAudio = null;

    function stopTTS() {
        if (currentAudio) { try { currentAudio.pause(); } catch(e){} currentAudio = null; }
        if (window.speechSynthesis) window.speechSynthesis.cancel();
    }

    function speakFallback(text, vol) {
        if (!window.speechSynthesis) return;
        window.speechSynthesis.cancel();
        var utter = new SpeechSynthesisUtterance(text);
        utter.rate   = 1.05;
        utter.pitch  = 1.5;
        utter.volume = Math.min(1, Math.max(0, vol));
        function doSpeak() {
            var voices = window.speechSynthesis.getVoices();
            var preferred = voices.find(function(v) {
                var n = v.name.toLowerCase();
                return n.includes('samantha') || n.includes('karen') ||
                       n.includes('alice')    || n.includes('moira');
            });
            if (preferred) utter.voice = preferred;
            try { window.speechSynthesis.speak(utter); } catch(e) {}
        }
        // Voices may not be loaded yet on first call
        if (window.speechSynthesis.getVoices().length) {
            doSpeak();
        } else {
            window.speechSynthesis.addEventListener('voiceschanged', doSpeak, { once: true });
        }
    }

    // Expand math symbols to spoken words for TTS
    function mathToWords(text) {
        return text
            .replace(/(\d)\s*[-−–]\s*(\d)/g, '$1 minus $2')   // 10 - 5 → 10 minus 5
            .replace(/\s+-\s+/g, ' minus ')                    // standalone minus
            .replace(/(\d)\s*\+\s*(\d)/g, '$1 plus $2')
            .replace(/(\d)\s*[×x\*]\s*(\d)/g, '$1 times $2')
            .replace(/(\d)\s*÷\s*(\d)/g, '$1 divided by $2')
            .replace(/(\d)\s*=\s*/g, '$1 equals ')
            .replace(/\s*=\s*(\d)/g, ' equals $1')
            .replace(/\?/g, '');
    }

    window.speakText = function (text) {
        if (!text || !text.trim()) return;
        stopTTS();
        var vol = getS('volume', 80) / 100;
        var clean = mathToWords(stripEmoji(text.trim())).replace(/\s{2,}/g, ' ').trim();
        if (!clean) return;
        if (typeof puter !== 'undefined' && puter.ai && puter.ai.txt2speech) {
            puter.ai.txt2speech(clean).then(function (audio) {
                audio.volume = Math.min(1, Math.max(0, vol));
                audio.playbackRate = 1.05;
                currentAudio = audio;
                audio.play().catch(function () { speakFallback(clean, vol); });
            }).catch(function () { speakFallback(clean, vol); });
        } else {
            speakFallback(clean, vol);
        }
    };

    // Auto-read new quiz questions when the DOM changes (next question rendered)
    var observer = new MutationObserver(function () {
        if (!getS('tts', true)) return;
        var el = document.getElementById('q-text');
        if (el && el.textContent.trim()) speakText(el.textContent.trim());
    });
    var qWrap = document.getElementById('quiz-wrap');
    if (qWrap) {
        observer.observe(qWrap, { childList: true, subtree: true });
        // Read the first question after a short delay (puter.js needs time to init)
        if (getS('tts', true)) {
            setTimeout(function () {
                var el = document.getElementById('q-text');
                if (el && el.textContent.trim()) speakText(el.textContent.trim());
            }, 1200);
        }
    }
}());
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\kinderlearn\resources\views/student/activity-detail.blade.php ENDPATH**/ ?>