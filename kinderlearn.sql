-- =====================================================
-- KinderLearn Database Setup
-- Run this in phpMyAdmin or MySQL CLI
-- =====================================================

CREATE DATABASE IF NOT EXISTS kinderlearn CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE kinderlearn;

-- Users table
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','teacher','student') NOT NULL DEFAULT 'student',
  `avatar` varchar(255) DEFAULT 'avatar1.png',
  `pin` varchar(4) DEFAULT NULL,
  `section_id` bigint(20) UNSIGNED DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sections (Classes) table
CREATE TABLE IF NOT EXISTS `sections` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `join_code` varchar(8) NOT NULL UNIQUE,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`teacher_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add foreign key for section_id on users AFTER sections table exists
ALTER TABLE `users` ADD CONSTRAINT `users_section_id_foreign`
  FOREIGN KEY (`section_id`) REFERENCES `sections`(`id`) ON DELETE SET NULL;

-- Modules table
CREATE TABLE IF NOT EXISTS `modules` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `subject` enum('alphabet','numbers','colors','shapes','words') NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(10) DEFAULT '📚',
  `color` varchar(20) DEFAULT '#FF6B6B',
  `order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `teacher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Activities table
CREATE TABLE IF NOT EXISTS `activities` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `module_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` enum('video','quiz','matching','drag_drop','coloring','audio') NOT NULL,
  `content` json DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `deadline` timestamp NULL DEFAULT NULL,
  `stars_reward` int(11) DEFAULT 3,
  `order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`module_id`) REFERENCES `modules`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Progresses table
CREATE TABLE IF NOT EXISTS `progresses` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `module_id` bigint(20) UNSIGNED NOT NULL,
  `completed` tinyint(1) DEFAULT 0,
  `stars_earned` int(11) DEFAULT 0,
  `time_spent` int(11) DEFAULT 0,
  `last_activity_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `progresses_user_module_unique` (`user_id`,`module_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`module_id`) REFERENCES `modules`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Activity submissions table
CREATE TABLE IF NOT EXISTS `activity_submissions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `activity_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `score` int(11) DEFAULT 0,
  `stars_earned` int(11) DEFAULT 0,
  `answers` json DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`activity_id`) REFERENCES `activities`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Achievements table
CREATE TABLE IF NOT EXISTS `achievements` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(10) DEFAULT '🏆',
  `type` enum('badge','milestone','perfect_score') DEFAULT 'badge',
  `earned_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Messages table
CREATE TABLE IF NOT EXISTS `messages` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`sender_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`receiver_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Announcements table
CREATE TABLE IF NOT EXISTS `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `pinned` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`teacher_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`section_id`) REFERENCES `sections`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Attendances table
CREATE TABLE IF NOT EXISTS `attendances` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `status` enum('present','absent','late') DEFAULT 'present',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attendances_user_date_unique` (`user_id`,`date`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`section_id`) REFERENCES `sections`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- User notifications table
CREATE TABLE IF NOT EXISTS `user_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('achievement','message','announcement','activity') DEFAULT 'activity',
  `link` varchar(255) DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Laravel sessions table (needed for session storage)
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL PRIMARY KEY,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  INDEX `sessions_user_id_index` (`user_id`),
  INDEX `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Cache table (needed for database cache driver)
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) NOT NULL PRIMARY KEY,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) NOT NULL PRIMARY KEY,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Jobs table (for queued jobs, optional)
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- SAMPLE DATA (same as DatabaseSeeder.php)
-- Password for all = "password" (bcrypt hashed)
-- =====================================================

INSERT INTO `users` (`name`, `email`, `password`, `role`, `avatar`, `created_at`, `updated_at`) VALUES
('Admin User',   'admin@kinderlearn.com',   '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin',   'avatar1.png', NOW(), NOW()),
('Ms. Sarah',    'teacher@kinderlearn.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', 'avatar2.png', NOW(), NOW()),
('Mr. Juan',     'teacher2@kinderlearn.com','$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', 'avatar3.png', NOW(), NOW());

INSERT INTO `sections` (`name`, `teacher_id`, `join_code`, `description`, `created_at`, `updated_at`) VALUES
('Sunflower Class', 2, 'SUN2024', 'Our wonderful kindergarten class!', NOW(), NOW());

INSERT INTO `users` (`name`, `email`, `password`, `role`, `avatar`, `pin`, `section_id`, `created_at`, `updated_at`) VALUES
('Maria Clara',   'student@kinderlearn.com',  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'avatar4.png', '1234', 1, NOW(), NOW()),
('Jose Rizal Jr.','student2@kinderlearn.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'avatar5.png', '5678', 1, NOW(), NOW());

INSERT INTO `modules` (`title`, `subject`, `description`, `icon`, `color`, `order`, `teacher_id`, `created_at`, `updated_at`) VALUES
('Learn the Alphabet',    'alphabet', 'Learn all 26 letters with fun songs and activities!', '🔤', '#FF6B6B', 1, 2, NOW(), NOW()),
('Fun with Numbers',      'numbers',  'Count from 1 to 20 and learn basic math!',            '🔢', '#4ECDC4', 2, 2, NOW(), NOW()),
('Colors of the Rainbow', 'colors',   'Learn all the beautiful colors around us!',            '🌈', '#45B7D1', 3, 2, NOW(), NOW()),
('Shapes Everywhere',     'shapes',   'Circles, squares, triangles, and more!',              '⭐', '#F7DC6F', 4, 2, NOW(), NOW()),
('My First Words',        'words',    'Learn simple words like cat, dog, sun!',              '💬', '#BB8FCE', 5, 2, NOW(), NOW());

INSERT INTO `activities` (`module_id`, `title`, `type`, `content`, `stars_reward`, `order`, `created_at`, `updated_at`) VALUES
(1, 'Watch and Learn', 'video', '{"video_url":"https://www.youtube.com/embed/hq3yfQnllfQ"}', 2, 1, NOW(), NOW()),
(1, 'Alphabet Quiz',   'quiz',  '{"questions":[{"question":"What letter comes first?","options":["A","B","C","D"],"answer":"A"},{"question":"How many letters in the alphabet?","options":["24","25","26","27"],"answer":"26"}]}', 3, 2, NOW(), NOW()),
(2, 'Count with Me',   'video', '{"video_url":"https://www.youtube.com/embed/DR-cfDsHCGA"}', 2, 1, NOW(), NOW()),
(2, 'Numbers Quiz',    'quiz',  '{"questions":[{"question":"What comes after 5?","options":["4","5","6","7"],"answer":"6"},{"question":"How many fingers on one hand?","options":["4","5","6","7"],"answer":"5"}]}', 3, 2, NOW(), NOW());

INSERT INTO `announcements` (`teacher_id`, `section_id`, `title`, `body`, `pinned`, `created_at`, `updated_at`) VALUES
(2, 1, 'Welcome to KinderLearn!', 'Hello Sunflower Class! We are so excited to start learning together. Please complete the Alphabet module first!', 1, NOW(), NOW());
