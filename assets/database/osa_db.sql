-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 05, 2024 at 03:08 AM
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
-- Database: `osa_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `complaint_id` int(11) NOT NULL,
  `student_id` varchar(10) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `complaint_type` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Pending','In Progress','Resolved') DEFAULT 'Pending',
  `complaint_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `resolution_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complaint_notes`
--

CREATE TABLE `complaint_notes` (
  `note_id` int(11) NOT NULL,
  `complaint_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `note` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `name`, `description`, `created_at`) VALUES
(1, 'BSIT', 'Bachelor of Science in Information Technology', '2024-11-04 20:25:31'),
(2, 'BTLE-IA', 'Bachelor of Technology and Livelihood Education - Major in Industrial Arts', '2024-11-04 20:25:31'),
(3, 'BTLE-HE', 'Bachelor of Technology and Livelihood Education - Major in Home Economics', '2024-11-04 20:25:31'),
(4, 'BSMB', 'Bachelor of Science in Marine Biology', '2024-11-04 20:25:31');

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `program_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` varchar(10) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `program` varchar(50) DEFAULT NULL,
  `section` varchar(10) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `first_name`, `middle_name`, `last_name`, `email`, `mobile_number`, `program`, `section`, `password`) VALUES
('2022123454', 'Douglas', 'D', 'mcArthur', 'mcdogie@gmail.com', '0987654321', 'BSIT', '3B', '$2y$10$0HFsweo.7OK/hECTgv5FI.94TJpUuEXVtHBS57FSSjSMdAeCtSUYK'),
('2022654321', 'Josie', 'D', 'rizzale', 'george@gmail.com', '0987654321', 'BSMB', '1C', '$2y$10$GMbwTunBDaHnHcut8VXrR.JG4L8eX71pGtQHJDXvswPJOC3wOJON2'),
('2023300095', 'patries', 'liwanag', 'parejo', 'patriesparejo@gmail.com', '09700982254', 'BSIT', '2C', '$2y$10$2coiu4kkM0a5UOvod0S07eLcJFyUkgv2OUAdPRWm3Ar.m0uGSHeze'),
('2023301329', 'rochellene', 'dano', 'albino', 'rochellenealbino@gmail.com', '09092480607', 'BSIT', '2A', '$2y$10$8cF6cBBjAOvKjaC7K47wmulT.5e2Wdra39hSUM64uApwb2UUmfxlC'),
('2023301370', 'Mark Ismael', 'ybot', 'Libut', 'markismael@gmail.com', '09502196462', 'BSIT', '2C', '$2y$10$sZeox8VlVrPIAKyIurovwuULDs0lAdVPYz4udDhcwRVUcCEtlziK.'),
('2023301375', 'Jerico', 'Maghuyop', 'Maghanoy', 'jericomaghanoy10@gmail.com', '931818692', 'BSIT', '2C', '$2y$10$QJCF0hwR1fDYimshdMAJ7.4RwXTmLERAGoZzjw0BS7WlneyJvcXD6'),
('2023303143', 'Johnlie', 'Abapo', 'Mamawe', 'johnliemamawe@gmail.com', '4559094945538', 'BSIT', '2C', '$2y$10$rMqkQLSvR6iGEPdD9d86lOivj8zL58Qsb52kYqb4bPqjyjBgVQt8u');

-- --------------------------------------------------------

--
-- Table structure for table `user_information`
--

CREATE TABLE `user_information` (
  `Firstname` text NOT NULL,
  `Middlename` text NOT NULL,
  `Lastname` text NOT NULL,
  `Student_ID` int(10) NOT NULL,
  `Program` varchar(10) NOT NULL,
  `Section` varchar(5) NOT NULL,
  `Mobilenumber` text NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_information`
--

INSERT INTO `user_information` (`Firstname`, `Middlename`, `Lastname`, `Student_ID`, `Program`, `Section`, `Mobilenumber`, `Email`, `Username`, `Password`) VALUES
('', '', '', 0, '', '', '', '', '', ''),
('patries ', 'liwanag', 'parejo', 2023300095, 'BSIT', '2C', '09700982254', 'patriesparejo@gmail.com', 'pathyieee', 'ohlordjesus'),
('rochellene', 'dano', 'albino', 2023301329, 'BSIT', '2A', '09092480607', 'rochellenealbino@gmail.com', 'lintoy', 'lintoy'),
('Mark Ismael', 'ybot', 'Libut', 2023301370, 'BSIT', '2C', '09502196462', 'markismael@gmail.com', 'markyy', 'sdsmlkcvkv'),
('Jerico', 'Maghuyop', 'Maghanoy', 2023301375, 'BSIT', '2C', '931818692', 'jericomaghanoy10@gmail.com', 'jekolokoy', 'lerieco'),
('Johnlie', 'Abapo', 'Mamawe', 2023303143, 'BSIT', '2C', '4559094945538', 'johnliemamawe@gmail.com', 'janjan', 'ahasadadsd');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`complaint_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `complaint_notes`
--
ALTER TABLE `complaint_notes`
  ADD PRIMARY KEY (`note_id`),
  ADD KEY `complaint_id` (`complaint_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`program_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_information`
--
ALTER TABLE `user_information`
  ADD PRIMARY KEY (`Student_ID`),
  ADD UNIQUE KEY `UNIQUE` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `complaint_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `complaint_notes`
--
ALTER TABLE `complaint_notes`
  MODIFY `note_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `program_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `complaints_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`);

--
-- Constraints for table `complaint_notes`
--
ALTER TABLE `complaint_notes`
  ADD CONSTRAINT `complaint_notes_ibfk_1` FOREIGN KEY (`complaint_id`) REFERENCES `complaints` (`complaint_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `complaint_notes_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`admin_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
