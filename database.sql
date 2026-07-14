-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 29, 2025 at 12:33 PM
-- Server version: 11.4.5-MariaDB
-- PHP Version: 8.3.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `crimeand_ss`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$0NiDxSqiiaaTtekIBjltAedCUikp/Bc3xkuK4URsDW1QEv91jEwXS');

-- --------------------------------------------------------

--
-- Table structure for table `admissions`
--

CREATE TABLE `admissions` (
  `id` int(11) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `student_dob` date NOT NULL,
  `student_gender` varchar(50) NOT NULL,
  `grade_applying` varchar(50) NOT NULL,
  `previous_school` varchar(255) DEFAULT NULL,
  `guardian_name` varchar(255) NOT NULL,
  `guardian_relationship` varchar(100) NOT NULL,
  `guardian_phone` varchar(50) NOT NULL,
  `guardian_email` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `profile_pic_path` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_submissions`
--

CREATE TABLE `contact_submissions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_submissions`
--

INSERT INTO `contact_submissions` (`id`, `name`, `email`, `subject`, `message`, `submitted_at`) VALUES
(1, 'Parent Inquiry', 'parent@email.com', 'Question about After-School Programs', 'Hello, I would like to know more about the after-school programs available for 4th graders. Thank you.', '2025-07-26 23:29:33'),
(2, 'Vendor Contact', 'vendor@supplies.com', 'School Supplies Partnership', 'We are a local vendor for school supplies and would like to discuss a potential partnership.', '2025-07-26 23:29:33'),
(3, 'Parent Inquiry', 'parent@email.com', 'Question about After-School Programs', 'Hello, I would like to know more about the after-school programs available for 4th graders. Thank you.', '2025-07-26 23:30:41'),
(4, 'Vendor Contact', 'vendor@supplies.com', 'School Supplies Partnership', 'We are a local vendor for school supplies and would like to discuss a potential partnership.', '2025-07-26 23:30:41'),
(5, 'Parent Inquiry', 'parent@email.com', 'Question about After-School Programs', 'Hello, I would like to know more about the after-school programs available for 4th graders. Thank you.', '2025-07-26 23:31:04'),
(6, 'Vendor Contact', 'vendor@supplies.com', 'School Supplies Partnership', 'We are a local vendor for school supplies and would like to discuss a potential partnership.', '2025-07-26 23:31:04'),
(8, 'Vendor Contact', 'vendor@supplies.com', 'School Supplies Partnership', 'We are a local vendor for school supplies and would like to discuss a potential partnership.', '2025-07-26 23:31:40');

-- --------------------------------------------------------

--
-- Table structure for table `downloads`
--

CREATE TABLE `downloads` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_size` varchar(50) NOT NULL,
  `file_type` enum('pdf','excel','word') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `downloads`
--

INSERT INTO `downloads` (`id`, `title`, `file_path`, `file_size`, `file_type`) VALUES
(1, '2024-2025 Academic Calendar', 'uploads/documents/academic-calendar.pdf', '1.2 MB', 'pdf'),
(2, 'Student Handbook', 'uploads/documents/student-handbook.pdf', '2.5 MB', 'pdf'),
(3, '2024-2025 Academic Calendar', 'uploads/documents/academic-calendar.pdf', '1.2 MB', 'pdf'),
(4, 'Student Handbook', 'uploads/documents/student-handbook.pdf', '2.5 MB', 'pdf'),
(5, '2024-2025 Academic Calendar', 'uploads/documents/academic-calendar.pdf', '1.2 MB', 'pdf'),
(6, 'Student Handbook', 'uploads/documents/student-handbook.pdf', '2.5 MB', 'pdf');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `event_date` datetime NOT NULL,
  `location` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `event_date`, `location`) VALUES
(1, 'Spring Concert', '2025-07-24 19:00:00', 'class 6'),
(2, 'Annual Science Fair', '2024-10-15 18:00:00', 'School Gymnasium'),
(3, 'Parent-Teacher Conferences', '2024-10-22 16:00:00', 'Various Classrooms'),
(4, 'Spring Music Concert', '2024-11-10 19:00:00', 'Auditorium'),
(5, 'Annual Science Fair', '2024-10-15 18:00:00', 'School Gymnasium'),
(6, 'Parent-Teacher Conferences', '2024-10-22 16:00:00', 'Various Classrooms'),
(7, 'Spring Music Concert', '2024-11-10 19:00:00', 'Auditorium'),
(8, 'Annual Science Fair', '2024-10-15 18:00:00', 'School Gymnasium'),
(9, 'Parent-Teacher Conferences', '2024-10-22 16:00:00', 'Various Classrooms'),
(10, 'Spring Music Concert', '2024-11-10 19:00:00', 'Auditorium'),
(11, 'df', '2025-07-27 05:41:00', 'class3'),
(12, 'dfg', '2025-07-31 05:43:00', 'class2');

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `post_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`id`, `title`, `content`, `post_date`) VALUES
(1, 'School Closure', 'Due to weather, school will be closed.', '2025-03-09'),
(2, 'Library Closure', 'The school library will be closed this Friday for inventory.', '2024-10-08'),
(3, 'Picture Day Reminder', 'Student picture day is next Tuesday. Forms were sent home last week.', '2024-10-09'),
(4, 'Early Dismissal', 'There will be an early dismissal at 1:00 PM on Friday.', '2024-10-10'),
(5, 'Library Closure', 'The school library will be closed this Friday for inventory.', '2024-10-08'),
(6, 'Picture Day Reminder', 'Student picture day is next Tuesday. Forms were sent home last week.', '2024-10-09'),
(7, 'Early Dismissal', 'There will be an early dismissal at 1:00 PM on Friday.', '2024-10-10'),
(8, 'Library Closure', 'The school library will be closed this Friday for inventory.', '2024-10-08'),
(9, 'Picture Day Reminder', 'Student picture day is next Tuesday. Forms were sent home last week.', '2024-10-09'),
(10, 'Early Dismissal', 'There will be an early dismissal at 1:00 PM on Friday.', '2024-10-10');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `created_at`) VALUES
(1, 'About Our School', 'about-us', '<h1>About Us</h1><p>This is the about us page. You can edit this content from the admin panel.</p>', '2025-07-26 21:04:16');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `author` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `slug`, `content`, `author`, `created_at`) VALUES
(1, 'Welcome Back to School!', 'welcome-back', '<p>A warm welcome to all new and returning students! We are excited for a year of learning, growth, and fun.</p>', 'Principal Skinner', '2025-07-26 23:31:40'),
(2, 'Highlights from the Science Fair', 'science-fair-highlights', '<p>This year\'s science fair was a huge success! Congratulations to all our participants for their incredible projects.</p>', 'Science Dept.', '2025-07-26 23:31:40'),
(3, 'Registration for Sports Now Open', 'sports-registration', '<p>Students interested in participating in spring sports can now register online. The deadline is approaching.</p>', 'Coach Flanders', '2025-07-26 23:31:40');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`) VALUES
(2, 'Content Editor'),
(1, 'Super Admin'),
(3, 'Teacher');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_key` varchar(50) NOT NULL,
  `setting_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('ad_sidebar_code', '<div class=\"p-4 bg-yellow-200 text-center rounded-lg\"><p class=\"font-bold\">Advertisement</p><p>Your ad here!</p></div>'),
('contact_address', '123 Education Street, Springfield, ST 12345'),
('contact_email', 'info@springfieldelementary.edu'),
('contact_phone', '+1 (555) 123-4567'),
('emergency_email', 'emergency@springfield.edu'),
('emergency_phone', '(555) 911-HELP'),
('footer_copyright_text', 'ï¿½ 2025 Springfield Elementary. All rights reserved.'),
('hero_background_image', 'uploads/site_meta/hero_bg.png'),
('hero_button_text', 'Apply Now'),
('hero_button_url', 'admission.php'),
('minister_message_content', 'Education is the most powerful tool we have to change the world. I commend Springfield Elementary for its unwavering commitment to excellence and for shaping the leaders of tomorrow. Your focus on holistic development is a model for schools across the nation.'),
('minister_message_name', 'Hon. Jane Smith'),
('minister_message_title', 'A Word from the Minister of Education'),
('minister_photo_url', 'uploads/site_meta/minister.jpg'),
('primary_font', 'Montserrat'),
('principal_message_content', 'Welcome to Springfield Elementary! Our dedicated staff and I are committed to providing a nurturing and challenging environment where every child can thrive. We believe in a strong partnership between school and home, and we look forward to working with you to make this a successful and memorable school year.'),
('principal_message_name', 'Principal Seymour Skinner'),
('principal_message_title', 'A Message from Our Principal'),
('principal_photo_url', 'uploads/teachers/leader1.jpg'),
('school_name', 'Springfield Elementary'),
('school_tagline', 'Excellence in Education Since 1985'),
('site_favicon', 'uploads/logos/favicon.ico'),
('site_logo', 'uploads/logos/logo.png'),
('social_facebook', 'https://facebook.com'),
('social_instagram', ''),
('social_twitter', 'https://twitter.com'),
('social_youtube', '');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `experience` varchar(100) DEFAULT NULL,
  `education` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) NOT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_leadership` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `name`, `subject`, `experience`, `education`, `image_url`, `is_featured`, `is_leadership`) VALUES
(1, 'Principal Seymour Skinner', 'Principal', '25 years experience', 'M.Ed. Administration', 'uploads/teachers/leader1.jpg', 0, 1),
(2, 'Superintendent Chalmers', 'District Superintendent', '30 years experience', 'Ed.D. Educational Leadership', 'uploads/teachers/leader2.jpg', 0, 1),
(3, 'Willie MacDougal', 'Head Groundskeeper', '28 years experience', 'Honorary Doctorate of Maintenance', 'uploads/teachers/leader3.jpg', 0, 1),
(4, 'Doris Freedman', 'Lunchlady', '19 years experience', 'Culinary Arts Diploma', 'uploads/teachers/leader4.jpg', 0, 1),
(5, 'Edna Krabappel', '4th Grade Teacher', '22 years experience', 'M.A. Education', 'uploads/teachers/teacher1.jpg', 1, 0),
(6, 'Elizabeth Hoover', '2nd Grade Teacher', '15 years experience', 'B.S. Elementary Education', 'uploads/teachers/teacher3.jpg', 1, 0),
(7, 'Dewey Largo', 'Music Teacher', '18 years experience', 'B.A. Music Theory', 'uploads/teachers/teacher2.jpg', 1, 0),
(8, 'Coach Krupt', 'Physical Education', '12 years experience', 'B.S. Kinesiology', 'uploads/teachers/teacher4.jpg', 1, 0),
(9, 'Mr. Bergstrom', 'Substitute Teacher', '8 years experience', 'M.A. Comparative Literature', 'uploads/teachers/teacher5.jpg', 0, 0),
(10, 'Audrey McConnell', '3rd Grade Teacher', '10 years experience', 'B.A. Education', 'uploads/teachers/teacher6.jpg', 1, 0),
(11, 'Mike Morris', 'School Librarian', '14 years experience', 'Masters in Library Science', 'uploads/teachers/teacher7.jpg', 0, 0),
(12, 'Valerie Frizzle', 'Science Department Head', '20 years experience', 'Ph.D. Astrophysics', 'uploads/teachers/teacher8.jpg', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role_id`, `created_at`) VALUES
(1, 'admin', 'admin@school.com', '$2y$10$9sZ.NlK2.lJ5s/T3zG5bYOu7fG.b5X.5dI6G.q8jU-3bF3cW4eY8aK', 1, '2025-07-26 23:20:35');

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `youtube_video_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `title`, `youtube_video_id`) VALUES
(1, 'School Tour', 'dQw4w9WgXcQ'),
(2, 'cvb', 'fghjkl');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `admissions`
--
ALTER TABLE `admissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `downloads`
--
ALTER TABLE `downloads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admissions`
--
ALTER TABLE `admissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `downloads`
--
ALTER TABLE `downloads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
