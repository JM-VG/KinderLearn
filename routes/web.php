<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ReportController;

// -----------------------------------------------
// PUBLIC ROUTES (No login needed)
// -----------------------------------------------

// Homepage - show the landing page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Ping endpoint for WiFi latency indicator
Route::get('/ping', function () {
    return response()->json(['ok' => true]);
});

// TEMP: check and create default accounts
Route::get('/setup', function () {
    $sessionsTableExists = \Illuminate\Support\Facades\Schema::hasTable('sessions');
    $users = \App\Models\User::all(['id', 'email', 'role'])->toArray();
    if (request('seed')) {
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        return response()->json(['seeded' => true, 'users_after' => \App\Models\User::all(['id','email','role'])]);
    }
    if (request('migrate')) {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        return response()->json(['migrated' => true, 'sessions_table' => \Illuminate\Support\Facades\Schema::hasTable('sessions')]);
    }
    if (request('verify')) {
        $count = \App\Models\User::whereNull('email_verified_at')->update(['email_verified_at' => now()]);
        return response()->json(['verified_users' => $count, 'message' => 'All users marked as email-verified']);
    }
    if (request('delete')) {
        $deleted = \App\Models\User::where('email', request('delete'))->delete();
        return response()->json(['deleted' => $deleted, 'email' => request('delete')]);
    }
    return response()->json([
        'users' => $users,
        'sessions_table_exists' => $sessionsTableExists,
        'session_driver' => config('session.driver'),
        'app_env' => config('app.env'),
    ]);
});

// -----------------------------------------------
// AUTHENTICATION ROUTES
// -----------------------------------------------

// Show login page
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

// Handle login form submission
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Show register page
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

// Handle register form submission
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Email verification
Route::get('/verify-email',         [AuthController::class, 'showVerifyEmail'])->name('auth.verify-email');
Route::post('/verify-email',        [AuthController::class, 'verify'])->name('auth.verify');
Route::post('/verify-email/resend', [AuthController::class, 'resendVerification'])->name('auth.verify.resend');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// -----------------------------------------------
// STUDENT ROUTES (Must be logged in as student)
// -----------------------------------------------

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {

    // Student dashboard (home page after login)
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');

    // Student profile
    Route::get('/profile', [StudentController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [StudentController::class, 'updateProfile'])->name('profile.update');

    // Profile photo upload
    Route::post('/avatar/upload', [StudentController::class, 'uploadAvatar'])->name('avatar.upload');

    // Completion certificates
    Route::get('/certificate/{module}', [StudentController::class, 'certificate'])->name('certificate');
    Route::get('/certificate/{module}/download', [StudentController::class, 'downloadCertificate'])->name('certificate.download');
    Route::get('/certificate-template', [StudentController::class, 'certificateTemplate'])->name('certificate.template');

    // Learning Modules
    Route::get('/modules', [ModuleController::class, 'studentIndex'])->name('modules');
    Route::get('/modules/{module}', [ModuleController::class, 'show'])->name('modules.show');
    Route::post('/modules/{module}/complete', [ModuleController::class, 'markComplete'])->name('modules.complete');
    Route::post('/modules/{module}/levels/{level}/complete', [ModuleController::class, 'markLevelComplete'])->name('modules.level.complete');

    // Activities
    Route::get('/activities', [ActivityController::class, 'studentIndex'])->name('activities');
    Route::get('/activities/{activity}', [ActivityController::class, 'show'])->name('activities.show');
    Route::post('/activities/{activity}/submit', [ActivityController::class, 'submit'])->name('activities.submit');
    Route::get('/activities/{activity}/review', [ActivityController::class, 'review'])->name('activities.review');

    // Upload completed work
    Route::post('/activities/{activity}/upload', [ActivityController::class, 'upload'])->name('activities.upload');
    Route::post('/activities/{activity}/tracing/submit', [ActivityController::class, 'tracingSubmit'])->name('activities.tracing.submit');

    // Achievements and badges
    Route::get('/achievements', [StudentController::class, 'achievements'])->name('achievements');

    // Progress overview
    Route::get('/progress', [StudentController::class, 'progress'])->name('progress');

    // Messages (student to teacher)
    Route::get('/messages/conversations', [MessageController::class, 'conversations'])->name('messages.conversations');
    Route::get('/messages/thread/{user}', [MessageController::class, 'thread'])->name('messages.thread');
    Route::get('/messages/search', [MessageController::class, 'searchUsers'])->name('messages.search');
    Route::get('/messages', [MessageController::class, 'index'])->name('messages');
    Route::post('/messages/send', [MessageController::class, 'send'])->name('messages.send');

    // Settings
    Route::get('/settings', [StudentController::class, 'settings'])->name('settings');

    // Notifications
    Route::get('/notifications', [StudentController::class, 'notifications'])->name('notifications');
    Route::get('/notifications/preview', [StudentController::class, 'notificationsPreview'])->name('notifications.preview');

    // Classes
    Route::get('/classes', [StudentController::class, 'classes'])->name('classes');
    Route::post('/classes/join', [StudentController::class, 'joinClass'])->name('classes.join');

    // Support / Feedback
    Route::post('/support', [App\Http\Controllers\SupportController::class, 'store'])->name('support.send');

});

// -----------------------------------------------
// TEACHER ROUTES (Must be logged in as teacher)
// -----------------------------------------------

Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {

    // Teacher dashboard
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');

    // Profile
    Route::get('/profile', [TeacherController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [TeacherController::class, 'updateProfile'])->name('profile.update');

    // Settings (personal UI preferences)
    Route::get('/settings', [TeacherController::class, 'settings'])->name('settings');

    // Profile photo
    Route::get('/avatar', [TeacherController::class, 'avatar'])->name('avatar');
    Route::post('/avatar/upload', [TeacherController::class, 'uploadAvatar'])->name('avatar.upload');

    // Class management
    Route::get('/classes', [TeacherController::class, 'classes'])->name('classes');
    Route::post('/classes/create', [TeacherController::class, 'createClass'])->name('classes.create');
    Route::get('/classes/{class}', [TeacherController::class, 'showClass'])->name('classes.show');
    Route::delete('/classes/{class}', [TeacherController::class, 'destroyClass'])->name('classes.destroy');
    Route::post('/classes/{class}/remove-student', [TeacherController::class, 'removeStudent'])->name('classes.remove-student');

    // Student management
    Route::get('/students', [TeacherController::class, 'students'])->name('students');
    Route::get('/students/{student}', [TeacherController::class, 'showStudent'])->name('students.show');

    // Module management
    Route::get('/modules', [ModuleController::class, 'teacherIndex'])->name('modules');
    Route::get('/modules/create', [ModuleController::class, 'create'])->name('modules.create');
    Route::post('/modules', [ModuleController::class, 'store'])->name('modules.store');
    Route::get('/modules/{module}/edit', [ModuleController::class, 'edit'])->name('modules.edit');
    Route::put('/modules/{module}', [ModuleController::class, 'update'])->name('modules.update');
    Route::delete('/modules/{module}', [ModuleController::class, 'destroy'])->name('modules.destroy');
    Route::post('/modules/{module}/levels', [ModuleController::class, 'addLevel'])->name('modules.levels.add');
    Route::post('/modules/{module}/levels/{level}/schedule', [ModuleController::class, 'updateLevelSchedule'])->name('modules.levels.schedule');

    // Activity management
    Route::get('/activities', [ActivityController::class, 'teacherIndex'])->name('activities');
    Route::get('/activities/create', [ActivityController::class, 'create'])->name('activities.create');
    Route::post('/activities', [ActivityController::class, 'store'])->name('activities.store');
    Route::get('/activities/{activity}/edit', [ActivityController::class, 'edit'])->name('activities.edit');
    Route::put('/activities/{activity}', [ActivityController::class, 'update'])->name('activities.update');
    Route::delete('/activities/{activity}', [ActivityController::class, 'destroy'])->name('activities.destroy');
    Route::get('/activities/{activity}/questions', [ActivityController::class, 'editQuestions'])->name('activities.questions');
    Route::post('/activities/{activity}/questions', [ActivityController::class, 'updateQuestions'])->name('activities.questions.save');
    Route::get('/activities/{activity}/grade', [ActivityController::class, 'gradeIndex'])->name('activities.grade');
    Route::get('/activities/{activity}/grade/{submission}', [ActivityController::class, 'gradeShow'])->name('activities.grade.show');
    Route::post('/activities/{activity}/grade/{submission}', [ActivityController::class, 'gradeSave'])->name('activities.grade.save');

    // Progress reports
    Route::get('/progress', [TeacherController::class, 'progress'])->name('progress');
    Route::get('/analytics', [TeacherController::class, 'analytics'])->name('analytics');

    // Attendance
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance');
    Route::post('/attendance/record', [AttendanceController::class, 'record'])->name('attendance.record');
    Route::get('/attendance/history', [AttendanceController::class, 'history'])->name('attendance.history');

    // Announcements
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

    // Messages
    Route::get('/messages/conversations', [MessageController::class, 'conversations'])->name('messages.conversations');
    Route::get('/messages/thread/{user}', [MessageController::class, 'thread'])->name('messages.thread');
    Route::get('/messages/search', [MessageController::class, 'searchUsers'])->name('messages.search');
    Route::get('/messages', [MessageController::class, 'teacherIndex'])->name('messages');
    Route::post('/messages/send', [MessageController::class, 'send'])->name('messages.send');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/reports/download/{type}', [ReportController::class, 'download'])->name('reports.download');

    // Announcements edit
    Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');

    // Notifications preview for dropdown
    Route::get('/notifications/preview', [TeacherController::class, 'notificationsPreview'])->name('notifications.preview');

    // Support / Feedback
    Route::post('/support', [App\Http\Controllers\SupportController::class, 'store'])->name('support.send');

});

// -----------------------------------------------
// ADMIN ROUTES (Must be logged in as admin)
// -----------------------------------------------

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Admin dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Manage users
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');

    // Manage teachers
    Route::get('/teachers', [AdminController::class, 'teachers'])->name('teachers');

    // Manage students
    Route::get('/students', [AdminController::class, 'students'])->name('students');

    // Admin profile
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [AdminController::class, 'updateProfile'])->name('profile.update');

    // Personal UI settings (localStorage-based, no DB)
    Route::get('/my-settings', [AdminController::class, 'mySettings'])->name('my-settings');

    // System settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');

    // System reports with date-range download
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/reports/download', [AdminController::class, 'downloadReport'])->name('reports.download');

    // Notifications preview
    Route::get('/notifications/preview', [AdminController::class, 'notificationsPreview'])->name('notifications.preview');

    // Support inbox
    Route::get('/support', [AdminController::class, 'supportInbox'])->name('support');
    Route::get('/support/{message}', [AdminController::class, 'supportView'])->name('support.view');
    Route::post('/support/{message}/reply', [AdminController::class, 'supportReply'])->name('support.reply');
    Route::delete('/support/{message}', [AdminController::class, 'supportDelete'])->name('support.delete');

});

// -----------------------------------------------
// SHARED ROUTE: Redirect after login based on role
// -----------------------------------------------

Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'teacher') {
        return redirect()->route('teacher.dashboard');
    } else {
        return redirect()->route('student.dashboard');
    }
})->middleware('auth')->name('dashboard');
