-- Tasks for Today Management System: import in phpMyAdmin or MariaDB.
-- Sample dates are relative to the date of import.
CREATE DATABASE IF NOT EXISTS `Rikuuuv1db` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `Rikuuuv1db`;

CREATE TABLE IF NOT EXISTS `tasks` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(150) NOT NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'pending',
    `is_archived` TINYINT(1) NOT NULL DEFAULT 0,
    `task_date` DATE NOT NULL,
    `created_at` DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `full_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `password_hash` VARCHAR(255) NULL,
    `created_at` DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tasks` (`title`, `status`, `task_date`, `created_at`) VALUES
    ('Prepare cycling clothes and check the bike so the early ride is ready.', 'pending', CURDATE() - INTERVAL 1 DAY, NOW()),
    ('Wake up at 5:00 a.m. and get dressed for an early, productive start to the day.', 'pending', CURDATE(), NOW()),
    ('Go for a morning bike ride to get some exercise and enjoy the fresh air.', 'pending', CURDATE(), NOW()),
    ('Take a refreshing shower after cycling and change into clean, comfortable clothes.', 'pending', CURDATE(), NOW()),
    ('Do the laundry, then fold and put away the clothes once everything is dry.', 'pending', CURDATE(), NOW()),
    ('Eat a balanced lunch and take a short break to recharge for the afternoon.', 'pending', CURDATE(), NOW()),
    ('Head to Anytime Fitness for a workout, starting with a warm-up and finishing with a cooldown.', 'pending', CURDATE(), NOW()),
    ('Take another shower after the workout and change into fresh clothes.', 'pending', CURDATE(), NOW()),
    ('Spend some time playing and relaxing after completing the day’s responsibilities.', 'pending', CURDATE(), NOW()),
    ('Set out clean clothes and review the next day’s routine before going to bed.', 'pending', CURDATE() + INTERVAL 1 DAY, NOW());

INSERT INTO `users` (`username`, `full_name`, `email`, `password_hash`, `created_at`)
VALUES ('rikuuu', 'Rikuuu', 'rikuuu@example.com', '$2y$10$4pp2nFcH.mUe2EOFP9qN2uySgbBv67yPL4udCN4GcYX.EE66bjn9G', NOW());
