-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 15, 2023 at 06:57 PM
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
-- Database: `aci_leave`
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `UserName`, `Password`, `updationDate`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', '2020-11-03 05:55:30');

-- --------------------------------------------------------

--
-- Table structure for table `tbldepartments`
--

CREATE TABLE `tbldepartments` (
  `id` int(11) NOT NULL,
  `DepartmentName` varchar(150) DEFAULT NULL,
  `DepartmentShortName` varchar(100) NOT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbldepartments`
--

INSERT INTO `tbldepartments` (`id`, `DepartmentName`, `DepartmentShortName`, `CreationDate`) VALUES
(2, 'Information Technologies', 'ICT', '2017-11-01 07:19:37'),
(3, 'Library', 'LIb', '2021-05-21 08:27:45');

-- --------------------------------------------------------

--
-- Table structure for table `tblemployees`
--

CREATE TABLE `tblemployees` (
  `emp_id` int(11) NOT NULL,
  `FirstName` varchar(150) NOT NULL,
  `LastName` varchar(150) NOT NULL,
  `Staff_ID` varchar(50) NOT NULL,
  `Position_Staff` varchar(100) NOT NULL,
  `EmailId` varchar(200) NOT NULL,
  `Password` varchar(180) NOT NULL,
  `Gender` varchar(100) NOT NULL,
  `Dob` varchar(100) NOT NULL,
  `Department` varchar(255) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Av_leave` varchar(150) NOT NULL,
  `Phonenumber` char(11) NOT NULL,
  `Status` varchar(10) NOT NULL,
  `RegDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(30) NOT NULL,
  `location` varchar(200) NOT NULL,
  `signature` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblemployees`
--

INSERT INTO `tblemployees` (`emp_id`, `FirstName`, `LastName`, `Staff_ID`, `Position_Staff`, `EmailId`, `Password`, `Gender`, `Dob`, `Department`, `Address`, `Av_leave`, `Phonenumber`, `Status`, `RegDate`, `role`, `location`, `signature`) VALUES
(2, 'Edem', 'Mcwilliams', '124', 'Registra', 'admin@gmail.com', '0192023a7bbd73250516f069df18b500', 'Male', '3 February, 1990', 'ICT', 'N NEPO', '30', '8587944255', 'Online', '2017-11-10 13:40:02', 'Admin', 'photo2.jpg', 'reg_de_8587944255_2.png'),
(4, 'Nathaniel', 'Nkrumah', '125', 'ICT Director', 'rk@gmail.com', 'e9b59046bfad66983177acea12045cb9', 'Male', '3 February, 1990', 'ICT', 'N NEPO', '30', '587944255', 'Online', '2017-11-10 13:40:02', 'Admin', 'NO-IMAGE-AVAILABLE.jpg', 'reg_at_587944255_4.png'),
(9, 'Richard', 'Awuni', 'TK-324', 'Senior Lecturer', 'nath.frema2@gmail.com', '6ae199a93c381bf6d5de27491139d3f9', 'male', '10 April 1981', 'ICT', 'Abas Station', '24', '0546607474', 'Offline', '2022-08-04 18:06:27', 'HOD', 'photo8.jpg', 'hod_ic_0546607474_9.png'),
(10, 'Bridget', 'Gafa', 'TK-222', 'Lecturer', 'codehacker768@gmail.com', 'f5c0c4da1f91f20f9bb3a0e0fe376d4f', 'female', '24 November 1998', 'ICT', 'Abas Road', '5', '0592226981', 'Offline', '2022-08-04 18:18:50', 'Staff', 'photo4.jpg', '');

-- --------------------------------------------------------

--
-- Table structure for table `tblleave`
--

CREATE TABLE `tblleave` (
  `id` int(11) NOT NULL,
  `LeaveType` varchar(110) NOT NULL,
  `RequestedDays` int(11) NOT NULL,
  `DaysOutstand` int(11) NOT NULL,
  `FromDate` varchar(120) NOT NULL,
  `ToDate` varbinary(120) DEFAULT NULL,
  `Sign` varchar(120) DEFAULT NULL,
  `PostingDate` date DEFAULT NULL,
  `WorkCovered` varchar(120) DEFAULT NULL,
  `HodRemarks` int(11) NOT NULL DEFAULT 0,
  `HodSign` varchar(200) NOT NULL,
  `HodDate` varchar(120) NOT NULL,
  `RegRemarks` int(1) NOT NULL DEFAULT 0,
  `RegSign` varchar(200) NOT NULL,
  `RegDate` varchar(120) NOT NULL,
  `IsRead` int(1) NOT NULL,
  `empid` int(11) DEFAULT NULL,
  `num_days` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblleave`
--

INSERT INTO `tblleave` (`id`, `LeaveType`, `RequestedDays`, `DaysOutstand`, `FromDate`, `ToDate`, `Sign`, `PostingDate`, `WorkCovered`, `HodRemarks`, `HodSign`, `HodDate`, `RegRemarks`, `RegSign`, `RegDate`, `IsRead`, `empid`, `num_days`) VALUES
(41, 'Casual Leave', 5, 10, '12-10-2022', 0x31382d31302d32303232, 'sig_ri_0592226981_10.png', '2022-10-10', 'Richard', 0, '', '', 0, '', '', 0, 10, 5),
(42, 'Casual Leave', 5, 5, '12-10-2022', 0x31382d31302d32303232, 'sig_ri_0592226981_10.png', '2022-10-10', 'Richard', 1, 'hod_ic_0546607474_9.png', '2022-10-11 ', 1, 'reg_de_8587944255_2.png', '2023-05-06 ', 1, 10, 5);

-- --------------------------------------------------------

--
-- Table structure for table `tblleavetype`
--

CREATE TABLE `tblleavetype` (
  `id` int(11) NOT NULL,
  `LeaveType` varchar(200) DEFAULT NULL,
  `Description` mediumtext DEFAULT NULL,
  `assign_days` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `CreationDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblleavetype`
--

INSERT INTO `tblleavetype` (`id`, `LeaveType`, `Description`, `assign_days`, `status`, `CreationDate`) VALUES
(5, 'Casual Leave', 'Casual Leave', 30, 1, '2021-05-19 14:32:03'),
(6, 'Medical Leave', 'Medical Leave', 30, 1, '2021-05-19 15:29:05'),
(8, 'Other', 'Leave all staff', 31, 1, '2021-05-20 17:17:43');

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
-- Indexes for table `tbldepartments`
--
ALTER TABLE `tbldepartments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblemployees`
--
ALTER TABLE `tblemployees`
  ADD PRIMARY KEY (`emp_id`);

--
-- Indexes for table `tblleave`
--
ALTER TABLE `tblleave`
  ADD PRIMARY KEY (`id`),
  ADD KEY `UserEmail` (`empid`);

--
-- Indexes for table `tblleavetype`
--
ALTER TABLE `tblleavetype`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `tbldepartments`
--
ALTER TABLE `tbldepartments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblemployees`
--
ALTER TABLE `tblemployees`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tblleave`
--
ALTER TABLE `tblleave`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `tblleavetype`
--
ALTER TABLE `tblleavetype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_message`
--
ALTER TABLE `tbl_message`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
