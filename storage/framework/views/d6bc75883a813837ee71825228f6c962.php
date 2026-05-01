<?php $__env->startSection('title', 'Messages'); ?>
<?php $__env->startSection('teacher-content'); ?>
<h1 class="font-fredoka text-4xl text-gray-800 mb-6">Messages <i class="fa-solid fa-envelope" style="color:#4A90D9;"></i></h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    
    <div class="bg-white rounded-3xl p-6 shadow-sm">
        <h2 class="font-fredoka text-2xl text-gray-800 mb-5"><i class="fa-solid fa-paper-plane" style="color:#4A90D9;"></i> Send a Message</h2>
        <form method="POST" action="<?php echo e(route('teacher.messages.send')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block font-bold text-gray-700 mb-2">Send to:</label>
                <select name="receiver_id" required
                        class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none font-semibold">
                    <option value="">-- Select student/parent --</option>
                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($s->id); ?>"><i class="fa-solid fa-child"></i> <?php echo e($s->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="block font-bold text-gray-700 mb-2">Subject:</label>
                <input type="text" name="subject" required placeholder="e.g., Great progress this week!"
                       class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none">
            </div>
            <div>
                <label class="block font-bold text-gray-700 mb-2">Message:</label>
                <textarea name="body" required rows="5" placeholder="Write your message..."
                          class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none resize-none"></textarea>
            </div>
            <button type="submit"
                    class="btn-kid text-white w-full justify-center"
                    style="background: linear-gradient(135deg, #2C3E7A, #4A90D9)">
                <i class="fa-solid fa-paper-plane"></i> Send Message
            </button>
        </form>
    </div>

    
    <div class="bg-white rounded-3xl p-6 shadow-sm">
        <h2 class="font-fredoka text-2xl text-gray-800 mb-5"><i class="fa-solid fa-inbox" style="color:#4A90D9;"></i> Inbox</h2>
        <?php if($messages->isEmpty()): ?>
        <div class="text-center py-10">
            <div class="mb-3" style="font-size:3.5rem; color:#d1d5db;"><i class="fa-solid fa-envelope-open"></i></div>
            <p class="text-gray-500 font-semibold">No messages yet.</p>
        </div>
        <?php else: ?>
        <div class="space-y-3 max-h-[500px] overflow-y-auto pr-1">
            <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="p-4 rounded-2xl <?php echo e(!$msg->read_at && $msg->receiver_id == $teacher->id ? 'bg-blue-50 border-2 border-blue-200' : 'bg-gray-50'); ?>">
                <div class="flex items-center justify-between mb-1">
                    <span class="font-bold text-gray-800 text-sm">
                        <?php if($msg->sender_id == $teacher->id): ?>
                            → You to <?php echo e($msg->receiver->name); ?>

                        <?php else: ?>
                            ← <?php echo e($msg->sender->name); ?>

                        <?php endif; ?>
                    </span>
                    <?php if(!$msg->read_at && $msg->receiver_id == $teacher->id): ?>
                    <span class="bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full font-bold">New</span>
                    <?php endif; ?>
                </div>
                <div class="font-semibold text-gray-700"><?php echo e($msg->subject); ?></div>
                <p class="text-sm text-gray-500 mt-1"><?php echo e(Str::limit($msg->body, 100)); ?></p>
                <div class="text-xs text-gray-400 mt-2"><?php echo e($msg->created_at->diffForHumans()); ?></div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\kinderlearn\resources\views/teacher/messages.blade.php ENDPATH**/ ?>