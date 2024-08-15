-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 15, 2024 at 10:57 PM
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
-- Database: `leave_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `UserName` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `updationDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `UserName`, `Password`, `updationDate`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', '2020-11-03 05:55:30');

-- --------------------------------------------------------

--
-- Table structure for table `employee_leave_types`
--

CREATE TABLE `employee_leave_types` (
  `id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `available_days` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblattendance`
--

CREATE TABLE `tblattendance` (
  `attendance_id` int(11) NOT NULL,
  `staff_id` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `time_in` time NOT NULL,
  `time_out` time DEFAULT NULL,
  `total_hours` time GENERATED ALWAYS AS (timediff(`time_out`,`time_in`)) STORED,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblattendance`
--

INSERT INTO `tblattendance` (`attendance_id`, `staff_id`, `date`, `time_in`, `time_out`, `status`) VALUES
(2, 'LLM 001', '2024-08-15', '05:43:39', NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `tbldepartments`
--

CREATE TABLE `tbldepartments` (
  `id` int(11) NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `department_desc` text DEFAULT NULL,
  `creation_date` datetime NOT NULL,
  `last_modified_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbldepartments`
--

INSERT INTO `tbldepartments` (`id`, `department_name`, `department_desc`, `creation_date`, `last_modified_date`) VALUES
(1, 'Engineering', 'Developing and building solutions', '2024-08-05 21:59:01', NULL),
(2, 'Human Resources', 'Managing the workflows', '2024-08-05 21:59:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblemployees`
--

CREATE TABLE `tblemployees` (
  `emp_id` int(11) NOT NULL,
  `department` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `email_id` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `staff_id` varchar(20) NOT NULL,
  `is_supervisor` int(11) NOT NULL DEFAULT 0,
  `password_reset` tinyint(1) NOT NULL DEFAULT 0,
  `lock_unlock` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime DEFAULT current_timestamp(),
  `supervisor_id` int(11) DEFAULT NULL,
  `can_be_assigned` enum('YES','NO') NOT NULL DEFAULT 'YES'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblemployees`
--

INSERT INTO `tblemployees` (`emp_id`, `department`, `first_name`, `last_name`, `middle_name`, `phone_number`, `designation`, `email_id`, `password`, `gender`, `image_path`, `role`, `staff_id`, `is_supervisor`, `password_reset`, `lock_unlock`, `date_created`, `supervisor_id`, `can_be_assigned`) VALUES
(1, 2, 'Nathaniel', 'Nkrumah', '', '000000000', 'Managing Director', 'admin@gmail.com', '44ffe44097bbce02fbaa42734e92ae04', 'Male', '../uploads/images/LLM 001_f-1.jpg', 'Admin', 'LLM 001', 1, 1, 0, '2024-08-05 22:02:37', NULL, 'NO'),
(2, 1, 'Micheal', 'Nkrumah', '', '0000000001', 'Mobile App Developer', 'mike@gmail.com', '44ffe44097bbce02fbaa42734e92ae04', 'Male', '../uploads/images/LLM 002_f-2.jpg', 'Staff', 'LLM 002', 0, 1, 0, '2024-08-11 09:21:32', 3, 'YES'),
(3, 1, 'Bridget', 'Gafa', '', '0000000011', 'Senior Mobile App Developer', 'bridget@gmail.com', '44ffe44097bbce02fbaa42734e92ae04', 'Female', '../uploads/images/LLM 003_f-3.jpg', 'Staff', 'LLM 003', 1, 1, 0, '2024-08-11 19:56:20', NULL, 'YES');

-- --------------------------------------------------------

--
-- Table structure for table `tblleave`
--

CREATE TABLE `tblleave` (
  `id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `requested_days` int(11) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `leave_status` int(11) NOT NULL DEFAULT 0,
  `empid` int(11) NOT NULL,
  `remarks` text DEFAULT NULL,
  `sick_file` varchar(255) DEFAULT NULL,
  `reviewed_by` int(11) DEFAULT NULL,
  `reviewed_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblleavetype`
--

CREATE TABLE `tblleavetype` (
  `id` int(11) NOT NULL,
  `leave_type` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `creation_date` datetime NOT NULL,
  `assign_days` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblleavetype`
--

INSERT INTO `tblleavetype` (`id`, `leave_type`, `description`, `creation_date`, `assign_days`, `status`) VALUES
(1, 'Annual Leave', 'Paid time off from work', '2024-08-05 22:25:08', 24, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbltask`
--

CREATE TABLE `tbltask` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `assigned_to` int(11) NOT NULL,
  `assigned_by` int(11) NOT NULL,
  `status` enum('Pending','In Progress','Completed') DEFAULT 'Pending',
  `priority` enum('Low','Medium','High') DEFAULT 'Medium',
  `start_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbltask`
--

INSERT INTO `tbltask` (`id`, `title`, `description`, `assigned_to`, `assigned_by`, `status`, `priority`, `start_date`, `due_date`, `created_at`, `updated_at`) VALUES
(2, 'Demo Title', '<p><b>Demo </b>app is breaking at <b>login</b></p>', 2, 1, 'Pending', 'Medium', '2024-08-12', '2024-08-16', '2024-08-11 09:42:01', '2024-08-12 18:48:48'),
(3, 'Login Issue', '<p><b>Login&nbsp;</b>app is breaking at <b>login</b></p>', 2, 1, 'Completed', 'Medium', '2024-08-13', '2024-08-19', '2024-08-11 09:42:01', '2024-08-12 18:48:36'),
(5, 'Login Issue', '<p><b>Login&nbsp;</b>app is breaking at <b>login</b></p>', 1, 1, 'Completed', 'Low', '2024-08-13', '2024-08-19', '2024-08-11 09:42:01', '2024-08-11 18:26:53'),
(6, 'App Crashing', '<p><span style=\"color: rgb(100, 107, 107); font-family: __fontSerif_c35935, __fontSerif_Fallback_c35935, serif; font-size: 18px;\"><b>Lorem </b>ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</span><br></p>', 3, 1, 'Pending', 'High', '2024-08-13', '2024-08-19', '2024-08-11 19:58:20', '2024-08-11 19:58:20'),
(7, 'Cashout Implementation', '<p><span style=\"color: rgb(100, 107, 107); font-family: __fontSerif_c35935, __fontSerif_Fallback_c35935, serif; font-size: 18px;\">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</span><span style=\"font-size: 18px;\">﻿</span><br></p>', 2, 3, 'Pending', 'Medium', '2024-08-14', '2024-08-15', '2024-08-11 20:00:24', '2024-08-12 19:09:33');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_message`
--

CREATE TABLE `tbl_message` (
  `msg_id` int(11) NOT NULL,
  `incoming_msg_id` text NOT NULL,
  `outgoing_msg_id` text NOT NULL,
  `text_message` text NOT NULL,
  `curr_date` text NOT NULL,
  `curr_time` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_message`
--

INSERT INTO `tbl_message` (`msg_id`, `incoming_msg_id`, `outgoing_msg_id`, `text_message`, `curr_date`, `curr_time`) VALUES
(1, '10', '9', 'Hello', 'October 10, 2022 ', '6:04 am'),
(2, '9', '10', 'Hi', 'October 13, 2023 ', '8:37 pm');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_leave_types`
--
ALTER TABLE `employee_leave_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblattendance`
--
ALTER TABLE `tblattendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `tbldepartments`
--
ALTER TABLE `tbldepartments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblemployees`
--
ALTER TABLE `tblemployees`
  ADD PRIMARY KEY (`emp_id`),
  ADD UNIQUE KEY `staff_id` (`staff_id`);

--
-- Indexes for table `tblleave`
--
ALTER TABLE `tblleave`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblleavetype`
--
ALTER TABLE `tblleavetype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbltask`
--
ALTER TABLE `tbltask`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assigned_to` (`assigned_to`),
  ADD KEY `assigned_by` (`assigned_by`);

--
-- Indexes for table `tbl_message`
--
ALTER TABLE `tbl_message`
  ADD PRIMARY KEY (`msg_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee_leave_types`
--
ALTER TABLE `employee_leave_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblattendance`
--
ALTER TABLE `tblattendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbldepartments`
--
ALTER TABLE `tbldepartments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblemployees`
--
ALTER TABLE `tblemployees`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblleave`
--
ALTER TABLE `tblleave`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblleavetype`
--
ALTER TABLE `tblleavetype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbltask`
--
ALTER TABLE `tbltask`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_message`
--
ALTER TABLE `tbl_message`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblattendance`
--
ALTER TABLE `tblattendance`
  ADD CONSTRAINT `tblattendance_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `tblemployees` (`staff_id`);

--
-- Constraints for table `tbltask`
--
ALTER TABLE `tbltask`
  ADD CONSTRAINT `tbltask_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `tblemployees` (`emp_id`),
  ADD CONSTRAINT `tbltask_ibfk_2` FOREIGN KEY (`assigned_by`) REFERENCES `tblemployees` (`emp_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
