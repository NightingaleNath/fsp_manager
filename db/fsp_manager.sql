-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 15, 2024 at 03:38 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fsp_manager`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_name`, `department_id`) VALUES
(1, 'B.Sc. Computer Science', 1),
(2, 'B.Sc. Computer Engineering', 1),
(3, 'BSc. Mathematics', 4);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(100) NOT NULL,
  `faculty_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`, `faculty_id`) VALUES
(1, 'Computer Science and Engineering', 1),
(2, 'Electrical and Electronics Engineering', 1),
(3, 'Mechanical Engineering', 1),
(4, 'Mathematical Science', 4);

-- --------------------------------------------------------

--
-- Table structure for table `faculties`
--

CREATE TABLE `faculties` (
  `faculty_id` int(11) NOT NULL,
  `faculty_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `faculties`
--

INSERT INTO `faculties` (`faculty_id`, `faculty_name`) VALUES
(1, 'Faculty of Engineering'),
(2, 'Faculty of Integrated Management Science'),
(3, 'GNPC School of Petroleum Studies'),
(4, 'Faculty of Sciences');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `project_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `year_id` int(11) DEFAULT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `author_name` varchar(200) NOT NULL,
  `co_author_name` varchar(100) NOT NULL,
  `supervisor_name` varchar(100) NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `file_path` text NOT NULL,
  `is_public` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`project_id`, `name`, `description`, `user_id`, `faculty_id`, `department_id`, `course_id`, `year_id`, `upload_date`, `author_name`, `co_author_name`, `supervisor_name`, `file_type`, `file_path`, `is_public`) VALUES
(3, 'Our company updated', 'Design and Implementation of Lighting System', 1, 1, 1, 2, 1, '2024-07-14 14:23:47', 'Bridget Gafa', 'Anthony Arthur', 'Dr. Nathaniel Nkrumah', 'pdf', '1720966980_Our company updated.pdf', 1),
(4, 'advocacy letter laboratory', 'Design and Implementation of Crime System', 1, 4, 4, 3, 5, '2024-07-15 00:31:37', 'Stephen Annan', 'Gifty Arthur', 'Dr. Bridget Gafa', 'pdf', '1721003460_advocacy letter laboratory.pdf', 1);

-- --------------------------------------------------------

--
-- Table structure for table `requestmessages`
--

CREATE TABLE `requestmessages` (
  `request_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `reviewed` varchar(200) NOT NULL,
  `location` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `requestmessages`
--

INSERT INTO `requestmessages` (`request_id`, `name`, `email`, `message`, `reviewed`, `location`) VALUES
(1, 'Bridget Gafa', 'codehacker768@gmail.com', 'I want to request for the project ', '', ''),
(2, 'Rachel Arthur', 'rachel@gmail.com', 'Please I want to download the project by Bridget Gafa and Anthony Arthur. Kindly get it for me.\r\nThank you', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(150) NOT NULL,
  `last_name` varchar(150) NOT NULL,
  `school_id` varchar(50) NOT NULL,
  `school_name` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(255) NOT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `phone_number` char(11) NOT NULL,
  `location` varchar(200) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','student','staff') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `school_id`, `school_name`, `email`, `password`, `faculty_id`, `department_id`, `course_id`, `phone_number`, `location`, `reg_date`, `role`) VALUES
(1, 'Nathaniel', 'Nkrumah', 'Administrator', 'All', 'nat@gmail.com', 'e9b59046bfad66983177acea12045cb9', NULL, NULL, NULL, '0221199221', 'NO-IMAGE-AVAILABLE.jpg', '2024-07-14 08:33:50', 'admin'),
(2, 'Bridget', 'Gafa', '151511', '', 'codehacker768@gmail.com', '8a2808b850d578d17131ea19a958cd7c', 1, 1, 2, '1229900991', 'NO-IMAGE-AVAILABLE.jpg', '2024-07-14 19:56:45', 'student'),
(3, 'Patience', 'Sam', '252525', '', 'patience@gmail.com', 'ae1d1ea3807f6f002a10f17c063ea6d0', 1, 1, 1, '0112211212', 'NO-IMAGE-AVAILABLE.jpg', '2024-07-14 20:02:49', 'student'),
(4, 'Rachel', 'Arthur', '363636', '', 'rachel@gmail.com', 'e9b59046bfad66983177acea12045cb9', 1, 1, 2, '0123837261', 'NO-IMAGE-AVAILABLE.jpg', '2024-07-15 00:33:31', 'student');

-- --------------------------------------------------------

--
-- Table structure for table `years`
--

CREATE TABLE `years` (
  `year_id` int(11) NOT NULL,
  `year` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `years`
--

INSERT INTO `years` (`year_id`, `year`) VALUES
(1, 2021),
(2, 2022),
(3, 2023),
(4, 2024),
(5, 2020);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`),
  ADD KEY `faculty_id` (`faculty_id`);

--
-- Indexes for table `faculties`
--
ALTER TABLE `faculties`
  ADD PRIMARY KEY (`faculty_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`project_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `faculty_id` (`faculty_id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `year_id` (`year_id`);

--
-- Indexes for table `requestmessages`
--
ALTER TABLE `requestmessages`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `faculty_id` (`faculty_id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `years`
--
ALTER TABLE `years`
  ADD PRIMARY KEY (`year_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `faculties`
--
ALTER TABLE `faculties`
  MODIFY `faculty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `requestmessages`
--
ALTER TABLE `requestmessages`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `years`
--
ALTER TABLE `years`
  MODIFY `year_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`);

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`faculty_id`);

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `projects_ibfk_2` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`faculty_id`),
  ADD CONSTRAINT `projects_ibfk_3` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`),
  ADD CONSTRAINT `projects_ibfk_4` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`),
  ADD CONSTRAINT `projects_ibfk_5` FOREIGN KEY (`year_id`) REFERENCES `years` (`year_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`faculty_id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`),
  ADD CONSTRAINT `users_ibfk_3` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
