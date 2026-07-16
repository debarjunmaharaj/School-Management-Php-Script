-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 16, 2026 at 04:34 AM
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
-- Database: `school`
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

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `post_date`) VALUES
(1, 'Demo Announcement', 'demo Announcement', '2026-07-14 09:56:08');

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
(2, 'Student Handbook', 'uploads/documents/student-handbook.pdf', '2.5 MB', 'pdf'),
(3, '2024-2025 Academic Calendar', 'uploads/documents/academic-calendar.pdf', '1.2 MB', 'pdf'),
(4, 'Student Handbook', 'uploads/documents/student-handbook.pdf', '2.5 MB', 'pdf'),
(5, '2024-2025 Academic Calendar', 'uploads/documents/academic-calendar.pdf', '1.2 MB', 'pdf'),
(6, 'Student Handbook', 'uploads/documents/student-handbook.pdf', '2.5 MB', 'pdf'),
(7, '2024-2025 Academic Calendar', 'uploads/documents/1784168048_file-sample_150kB.pdf', '139.44 KB', 'pdf');

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
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `caption`, `image_path`, `created_at`) VALUES
(1, 'caption', 'uploads/gallery/gal_6a584155c7434.png', '2026-07-16 02:26:29');

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
-- Table structure for table `research_publications`
--

CREATE TABLE `research_publications` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `volume` varchar(50) DEFAULT NULL,
  `year` varchar(10) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `research_publications`
--

INSERT INTO `research_publications` (`id`, `title`, `content`, `volume`, `year`, `image_path`, `created_at`) VALUES
(1, 'demo', 'xx', '12', '2026', '', '2026-07-16 02:12:03');

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
('active_homepage', 'home3.php'),
('ad_sidebar_code', '<div class=\"p-4 bg-yellow-200 text-center rounded-lg\"><p class=\"font-bold\">Advertisement</p><p>Your ad here!</p></div>'),
('best_student_class_roll', 'শ্রেণি: পঞ্চম, রোল: ০১'),
('best_student_desc', 'চমৎকার উপস্থিতি ও ক্লাসে মনোযোগী থাকার জন্য এ মাসের সেরা নির্বাচিত হয়েছে।'),
('best_student_name', 'সুমাইয়া জাহান মিম'),
('contact_address', '123 Education Street, Springfield, ST 12345'),
('contact_email', 'netfieofficial@gmail.com'),
('contact_phone', '+1 (555) xxxxxxx'),
('emergency_email', 'emergency@springfield.edu'),
('emergency_phone', '(555) 911-HELP'),
('footer_about_text', 'Authorized by the Government of Bangladesh and recognized by the University Grants Commission (UGC), Springfield Elementary emphasizes research, innovation, and ethical development.'),
('footer_academic_units', '[{\"title\":\"Computer Science & Engineering\",\"url\":\"#\"},{\"title\":\"Electrical & Electronic Engineering\",\"url\":\"#\"}]'),
('footer_copyright_text', '©️2026 Netfie Elementary. All rights reserved.'),
('footer_designed_text', 'Designed and monitored by the ICT Cell, Dhaka Metropolitan University.'),
('footer_quick_portals', '[{\"title\":\"Student Online Registration\",\"url\":\"#\"},{\"title\":\"Faculty Directory\",\"url\":\"#\"}]'),
('hero_background_image', 'uploads/site_meta/hero_bg.png'),
('hero_button_text', 'Apply Now'),
('hero_button_url', 'admission.php'),
('infra_c1_desc', 'Housing over 50,000 reference volumes alongside access to premium research databases.'),
('infra_c1_img', 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=600&auto=format&fit=crop'),
('infra_c1_title', 'Central Digital Library'),
('infra_c2_desc', 'Fully updated instrumentation arrays designed for practical academic evaluations.'),
('infra_c2_img', 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?q=80&w=600&auto=format&fit=crop'),
('infra_c2_title', 'Modern Physics & CSE Labs'),
('infra_c3_desc', 'Fostering teamwork, health, and leadership skills through competitive tournaments.'),
('infra_c3_img', 'https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=600&auto=format&fit=crop'),
('infra_c3_title', 'Sports & Physical Rec Center'),
('minister_message_content', 'Education is the most powerful tool we have to change the world. I commend Springfield Elementary for its unwavering commitment to excellence and for shaping the leaders of tomorrow. Your focus on holistic development is a model for schools across the nation.'),
('minister_message_name', 'Hon. Jane Smith'),
('minister_message_title', 'A Word from the Minister of Education'),
('minister_photo_url', 'uploads/site_meta/minister.jpg'),
('primary_font', 'Montserrat'),
('principal_message_content', 'Welcome to Springfield Elementary! Our dedicated staff and I are committed to providing a nurturing and challenging environment where every child can thrive. We believe in a strong partnership between school and home, and we look forward to working with you to make this a successful and memorable school year.'),
('principal_message_name', 'Principal Seymour Skinner'),
('principal_message_title', 'A Message from Our Principal'),
('principal_photo_url', 'uploads/teachers/leader1.jpg'),
('research_publications_content', '<div class=\"research-item\">\r\n    <div class=\"research-meta\">\r\n        <span>Vol. 14</span>\r\n        <strong>2025</strong>\r\n    </div>\r\n    <div class=\"research-info\">\r\n        <h4>Impact of Machine Learning on Climate Data Processing in the Bay of Bengal</h4>\r\n        <p>Published in the DMU Journal of Science and Information Technology by Dr. Tariqul Islam.</p>\r\n    </div>\r\n</div>\r\n<div class=\"research-item\">\r\n    <div class=\"research-meta\">\r\n        <span>Vol. 09</span>\r\n        <strong>2025</strong>\r\n    </div>\r\n    <div class=\"research-info\">\r\n        <h4>Macroeconomic Indicators and Export-Oriented Industrialization Trends in South Asia</h4>\r\n        <p>Published in the DMU Journal of Business and Economics by Prof. Sarah Karim.</p>\r\n    </div>\r\n</div>'),
('school_eiin', '১০২২৩৪'),
('school_name', 'Netfie Elementary'),
('school_tagline', 'Excellence in Education Since 1985'),
('site_favicon', 'uploads/logos/favicon.ico'),
('site_logo', 'uploads/logos/logo.png'),
('social_facebook', 'https://www.facebook.com/netfieofficial'),
('social_instagram', ''),
('social_twitter', 'https://twitter.com'),
('social_youtube', ''),
('stats_classrooms', '১০টি'),
('stats_pass_rate', '১০০%'),
('stats_students', '৪৫০+'),
('stats_teachers', '১২ জন');

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
(1, 'admin', 'admin@school.com', '$2y$10$0NiDxSqiiaaTtekIBjltAedCUikp/Bc3xkuK4URsDW1QEv91jEwXS', 1, '2025-07-26 23:20:35');

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
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
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
-- Indexes for table `research_publications`
--
ALTER TABLE `research_publications`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `downloads`
--
ALTER TABLE `downloads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- AUTO_INCREMENT for table `research_publications`
--
ALTER TABLE `research_publications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
