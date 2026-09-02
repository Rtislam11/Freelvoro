-- =======================================================
-- Database Schema for Integrated Freelance Marketplace
-- Target Database: freelance_platform
-- =======================================================

CREATE DATABASE IF NOT EXISTS `freelance_platform` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `freelance_platform`;

-- 1. Users Table
DROP TABLE IF EXISTS `reports`;
DROP TABLE IF EXISTS `proposals`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'client', 'freelancer') NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Jobs Table
CREATE TABLE `jobs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `client_id` INT NOT NULL,
    `title` VARCHAR(150) NOT NULL,
    `description` TEXT NOT NULL,
    `category` VARCHAR(50) NOT NULL,
    `budget` DECIMAL(10,2) NOT NULL,
    `deadline` DATE NOT NULL,
    `status` ENUM('open', 'in_progress', 'completed', 'closed') DEFAULT 'open',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`client_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Proposals Table (Integration Hook for Freelancer Module)
CREATE TABLE `proposals` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `job_id` INT NOT NULL,
    `freelancer_id` INT NOT NULL,
    `bid_amount` DECIMAL(10,2) NOT NULL,
    `proposal_text` TEXT NOT NULL,
    `status` ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    `submitted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`job_id`) REFERENCES `jobs`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`freelancer_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Reports Table (Dispute Resolution & Moderation)
CREATE TABLE `reports` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `reporter_id` INT NOT NULL,
    `reported_user_id` INT NOT NULL,
    `job_id` INT NULL,
    `reason` VARCHAR(150) NOT NULL,
    `details` TEXT NOT NULL,
    `status` ENUM('pending', 'resolved', 'dismissed') DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`reporter_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`reported_user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`job_id`) REFERENCES `jobs`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================================================
-- Seed Data
-- =======================================================

-- Seed Users with valid bcrypt hashes
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'System Administrator', 'admin@marketplace.com', '$2y$10$/M14osYfY/9ZGMbWA38sReeinRre0t9be4ri29dxs7X..XVrpENue', 'admin', NOW()),
(2, 'Tanveer Ahmed (Client)', 'tanveer@client.com', '$2y$10$E4ro9yzdxykwzX5HangfJO8HDqBEfOe1zcC3orI5DS/6A9rAux4n2', 'client', NOW()),
(3, 'Rahim Chowdhury (Client)', 'rahim@client.com', '$2y$10$E4ro9yzdxykwzX5HangfJO8HDqBEfOe1zcC3orI5DS/6A9rAux4n2', 'client', NOW()),
(4, 'Karim Developer (Freelancer)', 'karim@dev.com', '$2y$10$OUvX3r96oEM/kta0RNQdk.yTPLB1KuhC7J9fzPM3YwZBiY4ClKujy', 'freelancer', NOW()),
(5, 'Fatima Designer (Freelancer)', 'fatima@designer.com', '$2y$10$OUvX3r96oEM/kta0RNQdk.yTPLB1KuhC7J9fzPM3YwZBiY4ClKujy', 'freelancer', NOW());

-- Seed Jobs
INSERT INTO `jobs` (`id`, `client_id`, `title`, `description`, `category`, `budget`, `deadline`, `status`, `created_at`) VALUES
(1, 2, 'Build an E-Commerce Backend in Native PHP', 'Looking for an experienced Bangladeshi PHP developer to build a secure shopping cart, checkout system, and bKash/Nagad payment gateway webhook handler using native PDO and clean MVC.', 'Web Development', 25000.00, DATE_ADD(CURRENT_DATE, INTERVAL 15 DAY), 'open', NOW()),
(2, 2, 'UI/UX Redesign for Peer-to-Peer Delivery App', 'Need a sleek modern Figma UI/UX design and design system for an on-demand logistics mobile app. Must include high-fidelity wireframes, interactive prototypes, and typography guidelines.', 'UI/UX Design', 18000.00, DATE_ADD(CURRENT_DATE, INTERVAL 20 DAY), 'open', NOW()),
(3, 3, 'SEO Optimization & Technical Audit for Local Business', 'Perform comprehensive on-page SEO, speed optimization, core web vitals tuning, and schema markup injection for a business directory with 500+ pages.', 'Digital Marketing', 12000.00, DATE_ADD(CURRENT_DATE, INTERVAL 10 DAY), 'open', NOW());

-- Seed Proposals (Integration testing for Feature C2: Proposal Evaluation & Hiring)
INSERT INTO `proposals` (`id`, `job_id`, `freelancer_id`, `bid_amount`, `proposal_text`, `status`, `submitted_at`) VALUES
(1, 1, 4, 24000.00, 'Hello Tanveer! I have over 5 years of experience in native PHP and PDO architectural engineering. I have integrated bKash and Nagad payment APIs for several Dhaka-based e-commerce clients. I can deliver this within 10 days with automated unit tests and clean documentation.', 'pending', NOW()),
(2, 1, 5, 25000.00, 'Hi, I can assist with both the PHP architecture and building an ultra-clean, mobile-first responsive checkout interface. Let us discuss the exact deliverables.', 'pending', NOW()),
(3, 2, 5, 17500.00, 'I specialize in fintech and delivery app UI/UX design. I will provide 3 initial concepts, interactive Figma prototypes, and complete component auto-layout specs ready for your frontend team.', 'pending', NOW());

-- Seed Reports (Integration testing for Feature A2 & C3: Dispute & Moderation)
INSERT INTO `reports` (`id`, `reporter_id`, `reported_user_id`, `job_id`, `reason`, `details`, `status`, `created_at`) VALUES
(1, 2, 4, 1, 'Communication Delay', 'Freelancer was unresponsive for 48 hours prior to bid clarification. Requesting admin review on profile activity.', 'pending', NOW()),
(2, 3, 5, 3, 'Off-Platform Payment Solicitation', 'User requested to move discussion outside the platform prior to contract award, violating platform terms of service.', 'pending', NOW());
