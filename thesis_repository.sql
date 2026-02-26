-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 26, 2026 at 04:29 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `thesis_repository`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_status`
--

CREATE TABLE `academic_status` (
  `id` int(11) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academic_status`
--

INSERT INTO `academic_status` (`id`, `status`) VALUES
(1, 'Undergraduate'),
(2, 'Graduate'),
(3, 'Postgraduate'),
(4, 'Doctorate (PhD)'),
(5, 'Alumni'),
(6, 'Faculty'),
(7, 'Staff'),
(8, 'Researcher'),
(9, 'Visiting Scholar'),
(10, 'Retired'),
(11, 'Exchange Student'),
(12, 'Continuing Education'),
(13, 'Enrolled (Non-degree)'),
(14, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `colleges`
--

CREATE TABLE `colleges` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `colleges`
--

INSERT INTO `colleges` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'COT', '2025-06-21 14:09:25', '2025-06-21 14:09:25'),
(2, 'CIR', '2025-06-21 14:09:25', '2025-06-21 14:09:25'),
(3, 'CAS', '2025-06-21 14:09:25', '2025-06-21 14:09:25'),
(4, 'CBA', '2025-06-21 14:09:25', '2025-06-21 14:09:25'),
(5, 'CITHM', '2025-06-21 14:09:25', '2025-06-21 14:09:25'),
(6, 'VPAA', '2025-06-21 14:09:25', '2025-06-21 14:09:25'),
(7, 'OUR', '2025-06-21 14:09:25', '2025-06-21 14:09:25'),
(8, 'PMO', '2025-06-21 14:09:25', '2025-06-21 14:09:25');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Computer Science', '2025-05-27 17:17:55', '2025-05-27 17:17:55'),
(2, 'Information Technology', '2025-05-27 17:17:55', '2025-05-27 17:17:55'),
(3, 'Engineering', '2025-05-27 17:17:55', '2025-05-27 17:17:55'),
(4, 'Business Administration', '2025-05-27 17:17:55', '2025-05-27 17:17:55'),
(5, 'Education', '2025-05-27 17:17:55', '2025-05-27 17:17:55'),
(6, 'Health Sciences', '2025-05-27 17:17:55', '2025-05-27 17:17:55'),
(7, 'Law', '2025-05-27 17:17:55', '2025-05-27 17:17:55'),
(8, 'Arts and Humanities', '2025-05-27 17:17:55', '2025-05-27 17:17:55'),
(9, 'Social Sciences', '2025-05-27 17:17:55', '2025-05-27 17:17:55'),
(10, 'Natural Sciences', '2025-05-27 17:17:55', '2025-05-27 17:17:55');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `authors` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `type` enum('graduate_thesis','dissertation','faculty_research') NOT NULL,
  `status` enum('submitted','endorsed','published','revise') DEFAULT 'submitted',
  `adviser_id` int(11) DEFAULT NULL,
  `department_id` int(11) NOT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `view_count` int(11) DEFAULT 0,
  `download_count` int(11) DEFAULT 0,
  `uploaded_at` datetime DEFAULT current_timestamp(),
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `user_id`, `authors`, `title`, `file_path`, `type`, `status`, `adviser_id`, `department_id`, `tags`, `view_count`, `download_count`, `uploaded_at`, `is_deleted`) VALUES
(1, 3, 'Jake Napay, Mark, Aldren', 'Cyber Security Protocols', 'assets/uploads/graduates/1760249197_94eb8b55f795c832e469.pdf', 'graduate_thesis', 'published', 4, 1, 'Cyber crime, Cyber security', 76, 40, '2025-10-12 13:48:03', 0),
(2, 2, 'Jay Perez', 'New Algorithm', 'assets/uploads/graduates/1763381131_8cd8f9c6257e18f82992.pdf', 'graduate_thesis', 'published', 4, 1, 'Adviser John David, Computer Science, New Algorithm', 44, 29, '2025-11-17 20:05:31', 0),
(3, 2, 'Jay Perez', 'New Coding AI Assistant', 'assets/uploads/graduates/1768109238_b6cce8bd46ed4607db10.pdf', 'graduate_thesis', 'published', 4, 1, 'AI, Algorithm, Assistant', 11, 8, '2026-01-11 13:27:18', 0),
(4, 7, 'rustico perez', 'Document Classification', 'assets/uploads/graduates/1768143941_4289461cfb9a138172bc.pdf', 'graduate_thesis', 'submitted', 4, 1, 'Document classification', 17, 0, '2026-01-11 23:05:41', 0);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `resource_type` varchar(100) DEFAULT NULL,
  `resource_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `document_id`, `user_id`, `content`, `created_at`) VALUES
(1, 1, 4, 'This is good and now endorsed. But we need to revise some citations', '2025-10-12 13:57:12'),
(2, 1, 4, 'failed to update on time', '2025-10-12 14:01:36'),
(3, 1, 4, 'OK everything was fixed', '2025-10-12 14:02:15'),
(4, 1, 4, 'there is need to be revise again', '2025-10-12 14:05:42'),
(5, 1, 4, 'create a revision', '2025-10-12 14:05:55'),
(6, 1, 4, 'ok done', '2025-10-12 14:07:30'),
(7, 1, 6, 'turn into submitted', '2025-10-12 20:55:04'),
(8, 1, 6, 'this is now published', '2025-10-12 20:55:24'),
(9, 2, 4, 'Okay good job', '2025-12-06 00:55:59'),
(10, 2, 6, 'good to go for publishing', '2025-12-06 01:54:55'),
(11, 3, 4, 'Very impressive, good work, i am going to endorse this', '2026-01-11 13:28:55'),
(12, 3, 6, 'Okay this is done, nice work!', '2026-01-11 13:32:35'),
(13, 4, 4, 'Need to revise', '2026-01-11 23:09:39'),
(14, 4, 4, 'Great, ready to published', '2026-01-11 23:21:15'),
(15, 4, 6, 'Published', '2026-01-11 23:24:11');

-- --------------------------------------------------------

--
-- Table structure for table `job_title`
--

CREATE TABLE `job_title` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_title`
--

INSERT INTO `job_title` (`id`, `title`) VALUES
(1, 'Professor'),
(2, 'Assistant Professor'),
(3, 'Associate Professor'),
(4, 'Lecturer'),
(5, 'Research Assistant'),
(6, 'Teaching Assistant'),
(7, 'Administrative Staff'),
(8, 'Academic Advisor'),
(9, 'Program Coordinator'),
(10, 'Department Chair'),
(11, 'Dean'),
(12, 'IT Support Specialist'),
(13, 'Librarian'),
(14, 'Student Intern'),
(15, 'Lab Technician'),
(16, 'Graduate Assistant'),
(17, 'Office Assistant'),
(18, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `suffix` varchar(20) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `academic_status` int(11) NOT NULL,
  `employment_status` int(11) NOT NULL,
  `college` int(11) DEFAULT NULL,
  `department_id` int(11) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `agreed_terms` tinyint(1) DEFAULT 0,
  `user_level` enum('student','faculty','librarian','admin','masters') NOT NULL,
  `is_adviser` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(255) DEFAULT NULL,
  `remember_token_expires` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `middle_name`, `last_name`, `suffix`, `email`, `password`, `academic_status`, `employment_status`, `college`, `department_id`, `profile_image`, `agreed_terms`, `user_level`, `is_adviser`, `remember_token`, `remember_token_expires`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'Admin', 'Admin', '', 'admin@gmail.com', '$2y$10$K5/wqHr1V/Pn8tUPsFq0t.ZwglXcjV0BAnZvZWPRVUrnPv1oDOrmy', 2, 12, 3, 1, 'http://localhost:8080/assets/images/users/1759605929_ade5bd592199b543ec40.png', 1, 'admin', 0, NULL, NULL, 1, '2025-10-04 14:59:45', '2025-12-05 13:10:26'),
(2, 'Jay', 'Pamitan', 'Perez', '', 'jayperez@gmail.com', '$2y$10$K5/wqHr1V/Pn8tUPsFq0t.ZwglXcjV0BAnZvZWPRVUrnPv1oDOrmy', 2, 1, 3, 1, 'http://localhost:8080/assets/images/default.png', 1, 'masters', 0, NULL, NULL, 1, '2025-10-04 15:18:32', '2025-12-05 13:10:26'),
(3, 'jake', '', 'napay', '', 'jakenapay@gmail.com', '$2y$10$K5/wqHr1V/Pn8tUPsFq0t.ZwglXcjV0BAnZvZWPRVUrnPv1oDOrmy', 2, 18, 3, 1, 'http://localhost:8080/assets/images/default.png', 1, 'masters', 0, NULL, NULL, 1, '2025-10-12 05:23:04', '2025-12-05 13:10:26'),
(4, 'John', '', 'David', '', 'johndavid@gmail.com', '$2y$10$K5/wqHr1V/Pn8tUPsFq0t.ZwglXcjV0BAnZvZWPRVUrnPv1oDOrmy', 8, 6, 3, 1, 'http://localhost:8080/assets/images/default.png', 1, 'faculty', 1, NULL, NULL, 1, '2025-10-12 05:40:14', '2025-12-05 13:10:26'),
(5, 'jane', '', 'doe', '', 'janedoe@gmail.com', '$2y$10$K5/wqHr1V/Pn8tUPsFq0t.ZwglXcjV0BAnZvZWPRVUrnPv1oDOrmy', 4, 11, 3, 1, 'http://localhost:8080/assets/images/default.png', 1, 'masters', 0, NULL, NULL, 1, '2025-10-12 05:55:38', '2025-12-05 13:10:26'),
(6, 'mary', '', 'grace', '', 'marygrace@gmail.com', '$2y$10$K5/wqHr1V/Pn8tUPsFq0t.ZwglXcjV0BAnZvZWPRVUrnPv1oDOrmy', 7, 13, 3, 1, 'http://localhost:8080/assets/images/default.png', 1, 'librarian', 0, NULL, NULL, 1, '2025-10-12 06:12:10', '2025-12-05 13:10:26'),
(7, 'Rustico', 'Pamitan', 'Perez', '', 'rusticoperez@gmail.com', '$2y$10$2YBQlL4xl4KS2STRtNJZh.oK00/HiWeAtLcWO9eEUK2HJGqUOvX7K', 2, 18, 3, 1, 'http://localhost:8080/assets/images/default.png', 1, 'masters', 0, NULL, NULL, 1, '2026-01-11 14:52:19', '2026-01-11 15:01:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_status`
--
ALTER TABLE `academic_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `colleges`
--
ALTER TABLE `colleges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `adviser_id` (`adviser_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_id` (`document_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `job_title`
--
ALTER TABLE `job_title`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `academic_status` (`academic_status`),
  ADD KEY `employment_status` (`employment_status`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `college` (`college`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_status`
--
ALTER TABLE `academic_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `colleges`
--
ALTER TABLE `colleges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `job_title`
--
ALTER TABLE `job_title`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `documents_ibfk_2` FOREIGN KEY (`adviser_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `documents_ibfk_3` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`);

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`),
  ADD CONSTRAINT `feedbacks_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`academic_status`) REFERENCES `academic_status` (`id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`employment_status`) REFERENCES `job_title` (`id`),
  ADD CONSTRAINT `users_ibfk_3` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  ADD CONSTRAINT `users_ibfk_4` FOREIGN KEY (`college`) REFERENCES `colleges` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
