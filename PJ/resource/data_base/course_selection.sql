-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2019-12-05 13:57:06
-- 服务器版本： 10.1.32-MariaDB
-- PHP Version: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `course_selection`
--

-- --------------------------------------------------------

--
-- 表的结构 `account`
--

CREATE TABLE `account` (
  `uid` varchar(16) NOT NULL,
  `password` varchar(16) NOT NULL,
  `acc_type` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `account`
--

INSERT INTO `account` (`uid`, `password`, `acc_type`) VALUES
('root', '115', 'admin'),
('S15222', 'S15222', 'student'),
('S15223', 'S15223', 'student'),
('T112', 'T112', 'teacher'),
('T344', 'T344', 'teacher');

-- --------------------------------------------------------

--
-- 表的结构 `admin`
--

CREATE TABLE `admin` (
  `teacher_id` varchar(32) NOT NULL,
  `admin_id` varchar(32) DEFAULT NULL,
  `name` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- 表的结构 `application_transaction`
--

CREATE TABLE `application_transaction` (
  `app_id` int(11) NOT NULL,
  `student_id` varchar(32) CHARACTER SET latin1 NOT NULL,
  `course_id` varchar(16) NOT NULL,
  `apply_reason` varchar(400) NOT NULL,
  `state` varchar(8) NOT NULL,
  `handle_reason` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `assessment`
--

CREATE TABLE `assessment` (
  `assessment_id` int(11) NOT NULL,
  `type` varchar(8) NOT NULL,
  `date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `location` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `classroom`
--

CREATE TABLE `classroom` (
  `classroom_code` varchar(16) NOT NULL,
  `capacity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `course`
--

CREATE TABLE `course` (
  `course_id` varchar(32) NOT NULL,
  `course_name` varchar(32) NOT NULL,
  `course_credit` float(2,1) NOT NULL,
  `course_type` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `department`
--

CREATE TABLE `department` (
  `depart_name` varchar(32) NOT NULL,
  `depart_code` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `department`
--

INSERT INTO `department` (`depart_name`, `depart_code`) VALUES
('computer', 'cs111');

-- --------------------------------------------------------

--
-- 表的结构 `major`
--

CREATE TABLE `major` (
  `major_name` varchar(32) NOT NULL,
  `department` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `major`
--

INSERT INTO `major` (`major_name`, `department`) VALUES
('SS', 'computer');

-- --------------------------------------------------------

--
-- 表的结构 `section`
--

CREATE TABLE `section` (
  `course_id` varchar(32) NOT NULL,
  `section_id` int(3) NOT NULL,
  `year` year(4) NOT NULL,
  `semester` varchar(16) NOT NULL,
  `classroom_code` varchar(16) NOT NULL,
  `max_stu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `sec_time`
--

CREATE TABLE `sec_time` (
  `course_id` varchar(32) NOT NULL,
  `section_id` int(3) NOT NULL,
  `year` year(4) NOT NULL,
  `semester` varchar(16) NOT NULL,
  `day_of_week` varchar(8) NOT NULL,
  `lesson_seq` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `student`
--

CREATE TABLE `student` (
  `student_id` varchar(32) CHARACTER SET latin1 NOT NULL,
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `enrollment` year(4) NOT NULL,
  `major` varchar(32) CHARACTER SET latin1 NOT NULL,
  `credit` float(4,1) DEFAULT NULL,
  `gpa` float(3,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `student`
--

INSERT INTO `student` (`student_id`, `name`, `enrollment`, `major`, `credit`, `gpa`) VALUES
('S15222', 'é™†äºŒå‡¡', 2017, 'SS', 321.0, 3.98),
('S15223', 'é™†ä¸‰å‡¡', 2017, 'SS', 310.0, 3.97);

-- --------------------------------------------------------

--
-- 表的结构 `teacher`
--

CREATE TABLE `teacher` (
  `teacher_id` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL,
  `title` varchar(16) DEFAULT NULL,
  `department` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `teacher`
--

INSERT INTO `teacher` (`teacher_id`, `name`, `title`, `department`) VALUES
('T112', 'é™†ä¸€å¸†', 'è®²å¸ˆ', 'computer'),
('T344', 'é™†äºŒå¸†', 'è®²å¸ˆ', 'computer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`teacher_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `application_transaction`
--
ALTER TABLE `application_transaction`
  ADD PRIMARY KEY (`app_id`),
  ADD KEY `fk_ap_co` (`course_id`),
  ADD KEY `fk_ap_st` (`student_id`);

--
-- Indexes for table `assessment`
--
ALTER TABLE `assessment`
  ADD PRIMARY KEY (`assessment_id`),
  ADD KEY `fk_as_cl` (`location`);

--
-- Indexes for table `classroom`
--
ALTER TABLE `classroom`
  ADD PRIMARY KEY (`classroom_code`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`depart_name`);

--
-- Indexes for table `major`
--
ALTER TABLE `major`
  ADD PRIMARY KEY (`major_name`),
  ADD KEY `fk_tb_dept` (`department`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`course_id`,`section_id`,`year`,`semester`),
  ADD KEY `fk_se_cl` (`classroom_code`);

--
-- Indexes for table `sec_time`
--
ALTER TABLE `sec_time`
  ADD KEY `fk_se_ti` (`course_id`,`section_id`,`year`,`semester`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `major` (`major`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`teacher_id`),
  ADD KEY `department` (`department`);

--
-- 限制导出的表
--

--
-- 限制表 `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `account` (`uid`);

--
-- 限制表 `application_transaction`
--
ALTER TABLE `application_transaction`
  ADD CONSTRAINT `fk_ap_co` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`),
  ADD CONSTRAINT `fk_ap_st` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`);

--
-- 限制表 `assessment`
--
ALTER TABLE `assessment`
  ADD CONSTRAINT `fk_as_cl` FOREIGN KEY (`location`) REFERENCES `classroom` (`classroom_code`);

--
-- 限制表 `major`
--
ALTER TABLE `major`
  ADD CONSTRAINT `fk_tb_dept` FOREIGN KEY (`department`) REFERENCES `department` (`depart_name`);

--
-- 限制表 `section`
--
ALTER TABLE `section`
  ADD CONSTRAINT `fk_se_cl` FOREIGN KEY (`classroom_code`) REFERENCES `classroom` (`classroom_code`),
  ADD CONSTRAINT `section_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`);

--
-- 限制表 `sec_time`
--
ALTER TABLE `sec_time`
  ADD CONSTRAINT `fk_se_ti` FOREIGN KEY (`course_id`,`section_id`,`year`,`semester`) REFERENCES `section` (`course_id`, `section_id`, `year`, `semester`);

--
-- 限制表 `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `account` (`uid`),
  ADD CONSTRAINT `student_ibfk_2` FOREIGN KEY (`major`) REFERENCES `major` (`major_name`);

--
-- 限制表 `teacher`
--
ALTER TABLE `teacher`
  ADD CONSTRAINT `teacher_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `account` (`uid`),
  ADD CONSTRAINT `teacher_ibfk_2` FOREIGN KEY (`department`) REFERENCES `department` (`depart_name`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
