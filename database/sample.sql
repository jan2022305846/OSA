-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 01, 2024 at 02:27 AM
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
-- Database: `sample`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(10) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `first_name`, `last_name`, `email`) VALUES
(2024123456, 'Admin', 'Administrator', 'admin@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `admincredentials`
--

CREATE TABLE `admincredentials` (
  `cred_id` int(11) NOT NULL,
  `admin_id` int(10) NOT NULL,
  `password` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admincredentials`
--

INSERT INTO `admincredentials` (`cred_id`, `admin_id`, `password`) VALUES
(1, 2024123456, '$2y$10$o5THnlY1NqFOTQsh/PAi5eGG2CGmT1puz3daJ1uUAKNb6tTWIzSay');

-- --------------------------------------------------------

--
-- Table structure for table `complaintfiles`
--

CREATE TABLE `complaintfiles` (
  `file_id` int(11) NOT NULL,
  `complaint_id` int(10) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` enum('document','evidence') NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaintfiles`
--

INSERT INTO `complaintfiles` (`file_id`, `complaint_id`, `file_path`, `file_type`, `upload_date`) VALUES
(1, 1, '../../records/674bbaba9cf40-cor.jpg', 'document', '2024-12-01 01:24:10'),
(2, 1, '../../records/674bbaba9d31c-user.jpg', 'evidence', '2024-12-01 01:24:10');

-- --------------------------------------------------------

--
-- Table structure for table `complaintnotes`
--

CREATE TABLE `complaintnotes` (
  `note_id` int(11) NOT NULL,
  `complaint_id` int(10) NOT NULL,
  `admin_id` int(10) NOT NULL,
  `note` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `complaint_id` int(11) NOT NULL,
  `student_id` int(10) NOT NULL,
  `dept_id` int(3) NOT NULL,
  `type` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`complaint_id`, `student_id`, `dept_id`, `type`, `description`, `status_id`, `date`) VALUES
(1, 2022121212, 1, 'Academic', 'sample', 1, '2024-12-01 01:24:10');

-- --------------------------------------------------------

--
-- Table structure for table `complaintstatus`
--

CREATE TABLE `complaintstatus` (
  `status_id` int(11) NOT NULL,
  `status_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaintstatus`
--

INSERT INTO `complaintstatus` (`status_id`, `status_name`) VALUES
(2, 'In Progress'),
(1, 'Pending'),
(3, 'Resolved');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `dept_id` int(3) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`dept_id`, `name`, `description`) VALUES
(1, 'BSIT', 'Bachelor of Science in Information Technology'),
(2, 'BTLE-IA', 'Bachelor of Technology and Livelihood Education - Major in Industrial Arts'),
(3, 'BTLE-HE', 'Bachelor of Technology and Livelihood Education - Major in Home Economics'),
(4, 'BSMB', 'Bachelor of Science in Marine Biology');

-- --------------------------------------------------------

--
-- Table structure for table `studentcredentials`
--

CREATE TABLE `studentcredentials` (
  `cred_id` int(11) NOT NULL,
  `student_id` int(10) NOT NULL,
  `password` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `studentcredentials`
--

INSERT INTO `studentcredentials` (`cred_id`, `student_id`, `password`) VALUES
(1, 2022121212, '$2y$10$mjJP5lyDGrsYKcdpuaGEqOaw0iWUjiIP4EWwa/0gV2G0hDtnR5I.O');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(10) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `section` varchar(2) DEFAULT NULL,
  `c_num` varchar(11) DEFAULT NULL,
  `dept_id` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `first_name`, `last_name`, `email`, `section`, `c_num`, `dept_id`) VALUES
(2022121212, 'Luka', 'Washington', 'hanzleemar@gmail.com', '3B', '987654321', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `admincredentials`
--
ALTER TABLE `admincredentials`
  ADD PRIMARY KEY (`cred_id`),
  ADD UNIQUE KEY `admin_id` (`admin_id`);

--
-- Indexes for table `complaintfiles`
--
ALTER TABLE `complaintfiles`
  ADD PRIMARY KEY (`file_id`),
  ADD KEY `complaint_id` (`complaint_id`);

--
-- Indexes for table `complaintnotes`
--
ALTER TABLE `complaintnotes`
  ADD PRIMARY KEY (`note_id`),
  ADD KEY `complaint_id` (`complaint_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`complaint_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `dept_id` (`dept_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `complaintstatus`
--
ALTER TABLE `complaintstatus`
  ADD PRIMARY KEY (`status_id`),
  ADD UNIQUE KEY `status_name` (`status_name`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`dept_id`);

--
-- Indexes for table `studentcredentials`
--
ALTER TABLE `studentcredentials`
  ADD PRIMARY KEY (`cred_id`),
  ADD UNIQUE KEY `student_id` (`student_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `dept_id` (`dept_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2024123457;

--
-- AUTO_INCREMENT for table `admincredentials`
--
ALTER TABLE `admincredentials`
  MODIFY `cred_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `complaintfiles`
--
ALTER TABLE `complaintfiles`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `complaintnotes`
--
ALTER TABLE `complaintnotes`
  MODIFY `note_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `complaint_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `complaintstatus`
--
ALTER TABLE `complaintstatus`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `dept_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `studentcredentials`
--
ALTER TABLE `studentcredentials`
  MODIFY `cred_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2022121213;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admincredentials`
--
ALTER TABLE `admincredentials`
  ADD CONSTRAINT `admincredentials_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`);

--
-- Constraints for table `complaintfiles`
--
ALTER TABLE `complaintfiles`
  ADD CONSTRAINT `complaintfiles_ibfk_1` FOREIGN KEY (`complaint_id`) REFERENCES `complaints` (`complaint_id`) ON DELETE CASCADE;

--
-- Constraints for table `complaintnotes`
--
ALTER TABLE `complaintnotes`
  ADD CONSTRAINT `complaintnotes_ibfk_1` FOREIGN KEY (`complaint_id`) REFERENCES `complaints` (`complaint_id`),
  ADD CONSTRAINT `complaintnotes_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`);

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `complaints_ibfk_2` FOREIGN KEY (`dept_id`) REFERENCES `department` (`dept_id`),
  ADD CONSTRAINT `complaints_ibfk_3` FOREIGN KEY (`status_id`) REFERENCES `complaintstatus` (`status_id`);

--
-- Constraints for table `studentcredentials`
--
ALTER TABLE `studentcredentials`
  ADD CONSTRAINT `studentcredentials_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`dept_id`) REFERENCES `department` (`dept_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
