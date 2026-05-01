<?php $__env->startSection('title', 'Edit Questions — ' . $activity->title); ?>

<?php $__env->startPush('styles'); ?>
<style>
.q-card { background:#fff; border-radius:20px; padding:1.25rem 1.5rem; margin-bottom:1rem; box-shadow:0 1px 6px rgba(0,0,0,.07); }
.opt-input { display:flex; align-items:center; gap:8px; margin-bottom:8px; }
.opt-input input[type=text] { flex:1; padding:8px 14px; border-radius:12px; border:2px solid #e5e7eb; font-size:.9rem; outline:none; }
.opt-input input[type=text]:focus { border-color:#6366f1; }
.opt-radio { width:18px; height:18px; accent-color:#6366f1; cursor:pointer; flex-shrink:0; }
.img-preview { width:100%; max-height:140px; object-fit:contain; border-radius:12px; margin-top:8px; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('teacher-content'); ?>

<div class="flex items-center gap-4 mb-6">
    <a href="<?php echo e(route('teacher.modules.edit', $activity->module_id)); ?>"
       class="w-10 h-10 rounded-full flex items-center justify-center bg-white shadow-sm hover:shadow transition text-gray-500">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
    <div>
        <h1 class="font-fredoka text-3xl text-gray-800">Edit Questions</h1>
        <p class="text-sm text-gray-400 font-semibold mt-0.5"><?php echo e($activity->title); ?></p>
    </div>
</div>

<?php if(session('success')): ?>
<div class="mb-5 px-5 py-3 bg-green-50 border border-green-200 text-green-700 rounded-2xl font-semibold flex items-center gap-2">
    <i class="fa-solid fa-circle-check"></i> <?php echo e(session('success')); ?>

</div>
<?php endif; ?>

<form method="POST" action="<?php echo e(route('teacher.activities.questions.save', $activity->id)); ?>"
      enctype="multipart/form-data" id="qform">
<?php echo csrf_field(); ?>

<div id="questions-list">
<?php $__empty_1 = true; $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<div class="q-card" data-index="<?php echo e($i); ?>">
    <div class="flex items-center justify-between mb-3">
        <span class="font-fredoka text-lg text-gray-700">Question <span class="q-num"><?php echo e($i + 1); ?></span></span>
        <button type="button" onclick="removeQuestion(this)"
                class="w-8 h-8 rounded-full bg-red-50 text-red-400 hover:bg-red-100 transition flex items-center justify-center">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    
    <div class="mb-3">
        <label class="block text-xs font-bold text-gray-500 mb-1">Question Text</label>
        <input type="text" name="questions[<?php echo e($i); ?>][text]" value="<?php echo e($q['text']); ?>" required
               class="w-full px-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-indigo-400 focus:outline-none text-sm">
    </div>

    
    <div class="mb-3">
        <label class="block text-xs font-bold text-gray-500 mb-1">
            Question Image <span class="font-normal text-gray-400">(optional — replaces emoji in the quiz)</span>
        </label>
        <input type="hidden" name="questions[<?php echo e($i); ?>][image]" value="<?php echo e($q['image'] ?? ''); ?>">
        <?php if(!empty($q['image'])): ?>
        <img src="<?php echo e(asset('storage/' . $q['image'])); ?>" class="img-preview mb-2" id="img-preview-<?php echo e($i); ?>">
        <?php else: ?>
        <img src="" class="img-preview hidden mb-2" id="img-preview-<?php echo e($i); ?>">
        <?php endif; ?>
        <label class="flex items-center gap-2 text-xs font-semibold text-indigo-600 cursor-pointer hover:underline">
            <i class="fa-solid fa-upload"></i> <?php echo e(empty($q['image']) ? 'Upload image' : 'Replace image'); ?>

            <input type="file" name="question_images[<?php echo e($i); ?>]" accept="image/*" class="sr-only"
                   onchange="previewQImg(this, <?php echo e($i); ?>)">
        </label>
    </div>

    
    <div class="mb-3">
        <label class="block text-xs font-bold text-gray-500 mb-2">
            Answer Options &nbsp;<span class="font-normal text-gray-400">(select the correct one)</span>
        </label>
        <div class="options-list">
            <?php $__currentLoopData = $q['options']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $oi => $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="opt-input">
                <input type="radio" name="questions[<?php echo e($i); ?>][correct]"
                       value="<?php echo e($opt); ?>"
                       class="opt-radio"
                       <?php echo e(($q['correct'] ?? '') === $opt ? 'checked' : ''); ?>

                       required>
                <input type="text" name="questions[<?php echo e($i); ?>][options][]"
                       value="<?php echo e($opt); ?>" placeholder="Option <?php echo e($oi + 1); ?>"
                       oninput="syncRadioValue(this)">
                <button type="button" onclick="removeOption(this)"
                        class="w-7 h-7 rounded-full bg-gray-100 text-gray-400 hover:bg-red-100 hover:text-red-400 transition flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-minus text-xs"></i>
                </button>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <button type="button" onclick="addOption(this)"
                class="mt-1 text-xs font-bold text-indigo-500 hover:underline flex items-center gap-1">
            <i class="fa-solid fa-plus"></i> Add option
        </button>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<p class="text-center text-gray-400 font-semibold py-6" id="empty-msg">No questions yet. Add one below.</p>
<?php endif; ?>
</div>


<button type="button" onclick="addQuestion()"
        class="w-full py-3 rounded-2xl border-2 border-dashed border-indigo-200 text-indigo-500 font-bold hover:border-indigo-400 hover:bg-indigo-50 transition mb-6 flex items-center justify-center gap-2">
    <i class="fa-solid fa-plus"></i> Add Question
</button>

<div class="flex gap-3">
    <a href="<?php echo e(route('teacher.modules.edit', $activity->module_id)); ?>"
       class="flex-1 py-3 rounded-2xl font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 transition text-center">
        Cancel
    </a>
    <button type="submit"
            class="flex-1 btn-kid text-white justify-center"
            style="background: linear-gradient(135deg, #6366f1, #8b5cf6)">
        <i class="fa-solid fa-floppy-disk"></i> Save Questions
    </button>
</div>

</form>

<script>
var qIndex = <?php echo e(count($questions)); ?>;

function previewQImg(input, idx) {
    if (!input.files || !input.files[0]) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        var img = document.getElementById('img-preview-' + idx);
        img.src = e.target.result;
        img.classList.remove('hidden');
    };
    reader.readAsDataURL(input.files[0]);
}

function syncRadioValue(input) {
    // Keep the radio value in sync with the text input
    var row  = input.closest('.opt-input');
    var radio = row.querySelector('input[type=radio]');
    if (radio) radio.value = input.value;
}

function removeOption(btn) {
    var list = btn.closest('.options-list');
    if (list.querySelectorAll('.opt-input').length <= 2) {
        alert('A question needs at least 2 options.');
        return;
    }
    btn.closest('.opt-input').remove();
}

function addOption(btn) {
    var list     = btn.previousElementSibling;
    var qCard    = btn.closest('.q-card');
    var qi       = qCard.dataset.index;
    var optCount = list.querySelectorAll('.opt-input').length;
    var div      = document.createElement('div');
    div.className = 'opt-input';
    div.innerHTML =
        '<input type="radio" name="questions[' + qi + '][correct]" value="" class="opt-radio" required>' +
        '<input type="text" name="questions[' + qi + '][options][]" value="" placeholder="Option ' + (optCount + 1) + '" oninput="syncRadioValue(this)">' +
        '<button type="button" onclick="removeOption(this)" class="w-7 h-7 rounded-full bg-gray-100 text-gray-400 hover:bg-red-100 hover:text-red-400 transition flex items-center justify-center flex-shrink-0">' +
            '<i class="fa-solid fa-minus text-xs"></i></button>';
    list.appendChild(div);
}

function removeQuestion(btn) {
    var card = btn.closest('.q-card');
    card.remove();
    renumberQuestions();
    var empty = document.getElementById('empty-msg');
    if (empty && document.querySelectorAll('.q-card').length === 0) {
        empty.classList.remove('hidden');
    }
}

function renumberQuestions() {
    document.querySelectorAll('.q-card').forEach(function(card, i) {
        card.dataset.index = i;
        card.querySelector('.q-num').textContent = i + 1;
        // Re-index all input names
        card.querySelectorAll('[name]').forEach(function(el) {
            el.name = el.name.replace(/questions\[\d+\]/, 'questions[' + i + ']');
        });
        // Re-index file inputs
        card.querySelectorAll('input[type=file]').forEach(function(el) {
            el.name = el.name.replace(/question_images\[\d+\]/, 'question_images[' + i + ']');
        });
        // Re-index img preview id
        var img = card.querySelector('[id^=img-preview-]');
        if (img) img.id = 'img-preview-' + i;
    });
    qIndex = document.querySelectorAll('.q-card').length;
}

function addQuestion() {
    var empty = document.getElementById('empty-msg');
    if (empty) empty.classList.add('hidden');

    var i   = qIndex;
    var div = document.createElement('div');
    div.className = 'q-card';
    div.dataset.index = i;
    div.innerHTML = `
        <div class="flex items-center justify-between mb-3">
            <span class="font-fredoka text-lg text-gray-700">Question <span class="q-num">${i + 1}</span></span>
            <button type="button" onclick="removeQuestion(this)"
                    class="w-8 h-8 rounded-full bg-red-50 text-red-400 hover:bg-red-100 transition flex items-center justify-center">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="mb-3">
            <label class="block text-xs font-bold text-gray-500 mb-1">Question Text</label>
            <input type="text" name="questions[${i}][text]" required
                   class="w-full px-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-indigo-400 focus:outline-none text-sm"
                   placeholder="e.g., What shape is this?">
        </div>
        <div class="mb-3">
            <label class="block text-xs font-bold text-gray-500 mb-1">Question Image <span class="font-normal text-gray-400">(optional)</span></label>
            <input type="hidden" name="questions[${i}][image]" value="">
            <img src="" class="img-preview hidden mb-2" id="img-preview-${i}">
            <label class="flex items-center gap-2 text-xs font-semibold text-indigo-600 cursor-pointer hover:underline">
                <i class="fa-solid fa-upload"></i> Upload image
                <input type="file" name="question_images[${i}]" accept="image/*" class="sr-only"
                       onchange="previewQImg(this, ${i})">
            </label>
        </div>
        <div class="mb-3">
            <label class="block text-xs font-bold text-gray-500 mb-2">Answer Options <span class="font-normal text-gray-400">(select the correct one)</span></label>
            <div class="options-list">
                <div class="opt-input">
                    <input type="radio" name="questions[${i}][correct]" value="" class="opt-radio" required>
                    <input type="text" name="questions[${i}][options][]" value="" placeholder="Option 1" oninput="syncRadioValue(this)">
                    <button type="button" onclick="removeOption(this)" class="w-7 h-7 rounded-full bg-gray-100 text-gray-400 hover:bg-red-100 hover:text-red-400 transition flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-minus text-xs"></i></button>
                </div>
                <div class="opt-input">
                    <input type="radio" name="questions[${i}][correct]" value="" class="opt-radio" required>
                    <input type="text" name="questions[${i}][options][]" value="" placeholder="Option 2" oninput="syncRadioValue(this)">
                    <button type="button" onclick="removeOption(this)" class="w-7 h-7 rounded-full bg-gray-100 text-gray-400 hover:bg-red-100 hover:text-red-400 transition flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-minus text-xs"></i></button>
                </div>
            </div>
            <button type="button" onclick="addOption(this)" class="mt-1 text-xs font-bold text-indigo-500 hover:underline flex items-center gap-1">
                <i class="fa-solid fa-plus"></i> Add option
            </button>
        </div>`;
    document.getElementById('questions-list').appendChild(div);
    qIndex++;
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\kinderlearn\resources\views/teacher/question-editor.blade.php ENDPATH**/ ?>