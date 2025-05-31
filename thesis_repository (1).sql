-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 31, 2025 at 01:39 PM
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
  `status` enum('for_submission','endorsed','published','rejected') DEFAULT 'for_submission',
  `adviser_id` int(11) DEFAULT NULL,
  `department_id` int(11) NOT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `view_count` int(11) DEFAULT 0,
  `download_count` int(11) DEFAULT 0,
  `uploaded_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `user_id`, `authors`, `title`, `file_path`, `type`, `status`, `adviser_id`, `department_id`, `tags`, `view_count`, `download_count`, `uploaded_at`) VALUES
(6, 3, NULL, 'Sample Graduate Thesis', 'assets/uploads/graduates/1748370011_bf7188e9efcf8de660f5.pdf', 'graduate_thesis', 'for_submission', NULL, 10, NULL, 0, 0, '2025-05-28 02:20:11'),
(7, 3, NULL, 'New Thesis', 'assets/uploads/graduates/1748370330_944a7948aa509132d0df.pdf', 'graduate_thesis', 'for_submission', NULL, 10, 'testing, research, experimentation', 0, 0, '2025-05-28 02:25:30'),
(8, 3, 'Jake Napay, Russ, Russ1', 'Testing Graduate Thesis', 'assets/uploads/graduates/1748370411_e5299d19afad7dd9f39d.pdf', 'graduate_thesis', 'for_submission', NULL, 10, 'sample, testing purposes, sample data', 0, 0, '2025-05-28 02:26:51');

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
  `college` varchar(150) DEFAULT NULL,
  `department_id` int(11) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `agreed_terms` tinyint(1) DEFAULT 0,
  `user_level` enum('student','faculty','librarian') NOT NULL,
  `is_adviser` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(255) DEFAULT NULL,
  `remember_token_expires` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `middle_name`, `last_name`, `suffix`, `email`, `password`, `academic_status`, `employment_status`, `college`, `department_id`, `profile_image`, `agreed_terms`, `user_level`, `is_adviser`, `remember_token`, `remember_token_expires`, `created_at`, `updated_at`) VALUES
(1, 'juan', 'a', 'dela cruz', '', 'user@gmail.com', '$2y$10$WWCygF1cI6CGSYrOxmQnLuTBZ0VidMy7xRDYX/YNra7wt/4xRk112', 5, 1, 'College of Arts', 1, NULL, 1, 'student', 0, 'e4870d0149779ed7708bc888cdb6eb00', '2025-06-20 02:05:15', '2025-05-20 17:17:19', '2025-05-27 17:17:26'),
(2, 'jake', '', 'developer', 'III', 'admin1@gmail.com', '$2y$10$4Ln5K9oG.JdFDVTz35vefe0cg2FzVlqBwh0t.4KYDPoqKwGGSh2Yy$2y$10$sySDpbPkwkOpB6Gl/KnPKejqkPJ5rOPvTmlLr9zookPmHk8ft2a6a$2y$10$kFsY4HmfNiCc37n7ydda/u1.8jJGH1m9FMv0poxpV042NODxQ1h/e', 5, 12, 'College of Arts and Science', 1, 'http://localhost:8080/assets/images/users/1747847871_3090c04d5aaac2dfa1e1.jpg', 1, 'student', 0, '3e3b6292c37deb71cb7e1fc0c06f0cde', '2025-06-20 02:05:10', '2025-05-20 17:41:21', '2025-05-27 17:17:28'),
(3, 'Jake', 'test', 'test', 'IV', 'admin@gmail.com', '$2y$10$KQ57AKmfGOl.5AdZqEHFpOclsOvVaTsC3gBLbTES.mAXhI0XpKXke', 9, 8, 'College of Arts and Science', 10, 'http://localhost:8080/assets/images/users/1747849371_7073a9704ec8892b08cc.png', 1, 'student', 0, NULL, NULL, '2025-05-21 17:28:59', '2025-05-27 17:43:17'),
(4, 'jake', 'test', 'testname', 'IV', 'user111@gmail.com', '$2y$10$BCyFNHbvNDdOXBgu7fYmmeJBbOqOzyvDfz2YALDmaf0cW2z2WpKka', 1, 13, 'College of Engineering', 1, NULL, 1, 'student', 0, NULL, NULL, '2025-05-22 12:37:44', '2025-05-27 17:17:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_status`
--
ALTER TABLE `academic_status`
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
  ADD KEY `department_id` (`department_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_status`
--
ALTER TABLE `academic_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_title`
--
ALTER TABLE `job_title`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  ADD CONSTRAINT `users_ibfk_3` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
