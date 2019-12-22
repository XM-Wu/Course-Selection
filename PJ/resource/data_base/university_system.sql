-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2019 at 02:51 PM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `university_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `uid` varchar(16) NOT NULL,
  `password` varchar(16) NOT NULL,
  `acc_type` enum('admin','student','teacher') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`uid`, `password`, `acc_type`) VALUES
('admin', '113', 'admin'),
('root', '115', 'admin'),
('S14003', 'S14003', 'student'),
('S14099', 'S14099', 'student'),
('S15001', 'S15001', 'student'),
('S15002', 'S15002', 'student'),
('S15009', 'S15009', 'student'),
('S15222', 'S15222', 'student'),
('S16006', 'S16006', 'student'),
('S16008', 'S16008', 'student'),
('S17053', 'S17053', 'student'),
('S17302', 'S17302', 'student'),
('T001', 'T001', 'teacher'),
('T002', 'T002', 'teacher'),
('T003', 'T003', 'teacher'),
('T004', 'T004', 'teacher'),
('T005', 'T005', 'teacher'),
('T006', 'T006', 'teacher'),
('T007', 'T007', 'teacher'),
('T008', 'T008', 'teacher'),
('T009', 'T009', 'teacher'),
('T010', 'T010', 'teacher'),
('T011', 'T011', 'teacher'),
('T012', 'T012', 'teacher'),
('T013', 'T013', 'teacher'),
('T014', 'T014', 'teacher'),
('T015', 'T015', 'teacher'),
('T016', 'T016', 'teacher'),
('T017', 'T017', 'teacher'),
('T018', 'T018', 'teacher'),
('T019', 'T019', 'teacher'),
('T020', 'T020', 'teacher'),
('T021', 'T021', 'teacher'),
('T022', 'T022', 'teacher'),
('T023', 'T023', 'teacher'),
('T024', 'T024', 'teacher'),
('T025', 'T025', 'teacher'),
('T026', 'T026', 'teacher'),
('T027', 'T027', 'teacher'),
('T028', 'T028', 'teacher'),
('T029', 'T029', 'teacher'),
('T030', 'T030', 'teacher'),
('T031', 'T031', 'teacher'),
('T032', 'T032', 'teacher'),
('T033', 'T033', 'teacher'),
('T034', 'T034', 'teacher'),
('T035', 'T035', 'teacher'),
('T2', 'T2', 'teacher'),
('T996', 'T996', 'teacher');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` varchar(32) NOT NULL,
  `name` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `name`) VALUES
('admin', '陆逸凡'),
('root', '吴新铭');

-- --------------------------------------------------------

--
-- Table structure for table `application_transaction`
--

CREATE TABLE `application_transaction` (
  `apply_id` int(11) NOT NULL,
  `student_id` varchar(32) NOT NULL,
  `course_id` varchar(32) NOT NULL,
  `section_id` int(3) NOT NULL,
  `year` year(4) NOT NULL,
  `semester` varchar(16) NOT NULL,
  `apply_reason` varchar(400) NOT NULL,
  `state` varchar(16) NOT NULL,
  `handle_reason` varchar(400) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `application_transaction`
--

INSERT INTO `application_transaction` (`apply_id`, `student_id`, `course_id`, `section_id`, `year`, `semester`, `apply_reason`, `state`, `handle_reason`) VALUES
(1, 'S15009', 'LAW1101', 1, 2019, '第一学期', '爸爸我错了', '未通过', '我是你爷爷'),
(2, 'S14003', 'LAW1101', 1, 2019, '第一学期', '爸爸我也选不到课，我要延毕了', '未通过', '选课冲突'),
(3, 'S14003', 'CS1002', 2, 2019, '第一学期', '老腊肉也要学编程www', '已提交', NULL),
(4, 'S15001', 'LAW1101', 1, 2019, '第一学期', '我要跟女票一节课', '通过', NULL),
(5, 'S15009', 'ECO1001', 1, 2019, '第一学期', '我要我要我要~~~', '通过', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `assessment`
--

CREATE TABLE `assessment` (
  `assessment_id` int(11) NOT NULL,
  `type` varchar(8) NOT NULL,
  `date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `location` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `assessment`
--

INSERT INTO `assessment` (`assessment_id`, `type`, `date`, `start_time`, `end_time`, `location`) VALUES
(1, 'exam', '2019-12-25', '09:00:00', '11:00:00', 'H2101'),
(25, 'exam', '2019-12-25', '09:00:00', '11:00:00', 'H2101'),
(26, 'paper', NULL, NULL, NULL, NULL),
(27, 'presenta', NULL, NULL, NULL, NULL),
(28, 'exam', '2019-12-28', '15:00:00', '17:00:00', 'H6206'),
(29, 'practice', NULL, NULL, NULL, NULL),
(30, 'exam', '2019-12-30', '09:00:00', '11:00:00', 'HGX301'),
(31, 'exam', '2019-12-31', '15:00:00', '17:00:00', 'H3101'),
(32, 'exam', '2020-01-01', '13:00:00', '15:00:00', 'Z2101'),
(33, 'exam', '2019-12-30', '11:00:00', '13:00:00', 'Z2102'),
(34, 'exam', '2020-01-03', '09:00:00', '11:00:00', 'H2102'),
(35, 'exam', '2019-12-31', '15:00:00', '17:00:00', 'H3101'),
(37, 'paper', NULL, NULL, NULL, NULL),
(40, 'exam', '2019-12-29', '11:00:00', '13:00:00', 'Z2102');

-- --------------------------------------------------------

--
-- Table structure for table `classroom`
--

CREATE TABLE `classroom` (
  `classroom_code` varchar(16) NOT NULL,
  `capacity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `classroom`
--

INSERT INTO `classroom` (`classroom_code`, `capacity`) VALUES
('H2101', 40),
('H2102', 100),
('H3101', 50),
('H3108', 180),
('H6206', 4),
('HGX301', 60),
('Z2101', 50),
('Z2102', 60),
('Z2333', 1200);

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `course_id` varchar(32) NOT NULL,
  `course_name` varchar(32) NOT NULL,
  `course_credit` float(2,1) NOT NULL,
  `department` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`course_id`, `course_name`, `course_credit`, `department`) VALUES
('CS1001', '计算机系统工程', 4.0, '计算机学院'),
('CS1002', '程序设计', 5.0, '计算机学院'),
('CS1009', '数据库设计', 3.0, '计算机学院'),
('ECO1001', '微观经济学', 4.0, '经济学院'),
('LAW1101', '法理学导论', 3.0, '法学院'),
('PH1001', '大学物理', 4.0, '物理学院'),
('PH1002', '基础物理实验', 1.5, '物理学院'),
('PH5002', '量子力学', 4.0, '物理学院');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `depart_name` varchar(32) NOT NULL,
  `depart_code` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`depart_name`, `depart_code`) VALUES
('药学院', 'CHE'),
('计算机学院', 'CS'),
('经济学院', 'ECO'),
('法学院', 'LAW'),
('物理学院', 'PH');

-- --------------------------------------------------------

--
-- Table structure for table `major`
--

CREATE TABLE `major` (
  `major_name` varchar(32) NOT NULL,
  `department` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `major`
--

INSERT INTO `major` (`major_name`, `department`) VALUES
('法学', '法学院'),
('理论物理', '物理学院'),
('国际金融', '经济学院'),
('经济学类', '经济学院'),
('信息安全', '计算机学院'),
('计算机科学与技术', '计算机学院'),
('软件工程', '计算机学院');

-- --------------------------------------------------------

--
-- Table structure for table `quit`
--

CREATE TABLE `quit` (
  `quit_id` int(11) NOT NULL,
  `student_id` varchar(32) NOT NULL,
  `course_id` varchar(32) NOT NULL,
  `section_id` int(3) NOT NULL,
  `year` year(4) NOT NULL,
  `semester` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `quit`
--

INSERT INTO `quit` (`quit_id`, `student_id`, `course_id`, `section_id`, `year`, `semester`) VALUES
(1, 'S15222', 'CS1001', 1, 2019, '第一学期'),
(2, 'S15009', 'LAW1101', 2, 2019, '第一学期'),
(3, 'S15009', 'ECO1001', 1, 2019, '第一学期');

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `course_id` varchar(32) NOT NULL,
  `section_id` int(3) NOT NULL,
  `year` year(4) NOT NULL,
  `semester` varchar(16) NOT NULL,
  `teacher_id` varchar(32) NOT NULL,
  `classroom_code` varchar(16) NOT NULL,
  `stu_num` int(11) NOT NULL DEFAULT 0,
  `max_stu` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`course_id`, `section_id`, `year`, `semester`, `teacher_id`, `classroom_code`, `stu_num`, `max_stu`, `assessment_id`) VALUES
('CS1001', 1, 2019, '第一学期', 'T2', 'H2101', 3, 50, 1),
('CS1002', 1, 2019, '第一学期', 'T001', 'H2101', 1, 30, 25),
('CS1002', 2, 2019, '第一学期', 'T009', 'Z2101', 2, 2, 32),
('CS1009', 1, 2019, '第一学期', 'T002', 'H3108', 2, 100, 26),
('ECO1001', 1, 2019, '第一学期', 'T027', 'H6206', 3, 3, 27),
('LAW1101', 1, 2019, '第一学期', 'T020', 'H6206', 4, 3, 28),
('LAW1101', 2, 2019, '第一学期', 'T022', 'H2102', 3, 80, 34),
('PH1001', 1, 2019, '第一学期', 'T017', 'H2101', 2, 30, 29),
('PH1001', 2, 2019, '第一学期', 'T022', 'Z2102', 0, 50, 40),
('PH1002', 1, 2019, '第一学期', 'T015', 'HGX301', 1, 50, 30),
('PH1002', 2, 2019, '第一学期', 'T033', 'HGX301', 2, 50, 33),
('PH5002', 1, 2019, '第一学期', 'T019', 'H3101', 2, 30, 31),
('PH5002', 2, 2019, '第一学期', 'T018', 'H3101', 1, 30, 35),
('PH5002', 2, 2019, '第二学期', 'T018', 'H3101', 0, 30, 37);

-- --------------------------------------------------------

--
-- Table structure for table `sec_time`
--

CREATE TABLE `sec_time` (
  `course_id` varchar(32) NOT NULL,
  `section_id` int(3) NOT NULL,
  `year` year(4) NOT NULL,
  `semester` varchar(16) NOT NULL,
  `day_of_week` varchar(8) NOT NULL,
  `lesson_seq` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sec_time`
--

INSERT INTO `sec_time` (`course_id`, `section_id`, `year`, `semester`, `day_of_week`, `lesson_seq`) VALUES
('CS1001', 1, 2019, '第一学期', '周一', 5),
('CS1001', 1, 2019, '第一学期', '周一', 6),
('CS1002', 1, 2019, '第一学期', '周一', 5),
('CS1002', 1, 2019, '第一学期', '周一', 6),
('CS1002', 1, 2019, '第一学期', '周二', 7),
('CS1002', 1, 2019, '第一学期', '周二', 8),
('CS1002', 2, 2019, '第一学期', '周四', 8),
('CS1002', 2, 2019, '第一学期', '周四', 9),
('CS1009', 1, 2019, '第一学期', '周二', 1),
('CS1009', 1, 2019, '第一学期', '周二', 2),
('CS1009', 1, 2019, '第一学期', '周四', 10),
('CS1009', 1, 2019, '第一学期', '周四', 11),
('ECO1001', 1, 2019, '第一学期', '周三', 3),
('ECO1001', 1, 2019, '第一学期', '周三', 4),
('ECO1001', 1, 2019, '第一学期', '周五', 3),
('ECO1001', 1, 2019, '第一学期', '周五', 4),
('LAW1101', 1, 2019, '第一学期', '周二', 1),
('LAW1101', 1, 2019, '第一学期', '周二', 2),
('LAW1101', 1, 2019, '第一学期', '周五', 5),
('LAW1101', 1, 2019, '第一学期', '周五', 6),
('LAW1101', 2, 2019, '第一学期', '周三', 3),
('LAW1101', 2, 2019, '第一学期', '周三', 4),
('LAW1101', 2, 2019, '第一学期', '周二', 6),
('LAW1101', 2, 2019, '第一学期', '周二', 7),
('PH1001', 1, 2019, '第一学期', '周一', 8),
('PH1001', 1, 2019, '第一学期', '周一', 9),
('PH1001', 1, 2019, '第一学期', '周四', 7),
('PH1001', 1, 2019, '第一学期', '周四', 8),
('PH1001', 2, 2019, '第一学期', '周五', 5),
('PH1001', 2, 2019, '第一学期', '周五', 6),
('PH1001', 2, 2019, '第一学期', '周五', 7),
('PH1001', 2, 2019, '第一学期', '周五', 8),
('PH1002', 1, 2019, '第一学期', '周一', 5),
('PH1002', 1, 2019, '第一学期', '周一', 6),
('PH1002', 1, 2019, '第一学期', '周一', 7),
('PH1002', 1, 2019, '第一学期', '周一', 8),
('PH1002', 2, 2019, '第一学期', '周五', 11),
('PH1002', 2, 2019, '第一学期', '周五', 12),
('PH5002', 1, 2019, '第一学期', '周三', 6),
('PH5002', 1, 2019, '第一学期', '周三', 7),
('PH5002', 2, 2019, '第一学期', '周四', 8),
('PH5002', 2, 2019, '第一学期', '周四', 9),
('PH5002', 2, 2019, '第二学期', '周四', 8),
('PH5002', 2, 2019, '第二学期', '周四', 9);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_id` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL,
  `enrollment` year(4) NOT NULL,
  `major` varchar(32) NOT NULL,
  `credit` float(4,1) DEFAULT NULL,
  `gpa` float(3,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `name`, `enrollment`, `major`, `credit`, `gpa`) VALUES
('S14003', '柏彪博', 2014, '软件工程', 4.0, 2.00),
('S14099', '哈哈哈', 2014, '软件工程', 0.0, 0.00),
('S15001', '葛竹霭', 2015, '国际金融', 3.0, 3.30),
('S15002', '苗真环', 2015, '法学', 98.5, 3.09),
('S15009', '安康星', 2015, '信息安全', 4.0, 1.20),
('S15222', '吴新铭', 2017, '软件工程', 7.0, 3.57),
('S16006', '王浩', 2016, '理论物理', 3.0, 3.70),
('S16008', '柳华', 2016, '信息安全', 65.0, 3.89),
('S17053', '汤林有', 2017, '计算机科学与技术', 3.0, 4.00),
('S17302', '鲁姨翻', 2017, '软件工程', 0.0, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `stu_take_sec`
--

CREATE TABLE `stu_take_sec` (
  `student_id` varchar(32) NOT NULL,
  `course_id` varchar(32) NOT NULL,
  `section_id` int(3) NOT NULL,
  `year` year(4) NOT NULL,
  `semester` varchar(16) NOT NULL,
  `grade` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `stu_take_sec`
--

INSERT INTO `stu_take_sec` (`student_id`, `course_id`, `section_id`, `year`, `semester`, `grade`) VALUES
('S14003', 'CS1001', 1, 2019, '第一学期', 'C'),
('S14003', 'CS1009', 1, 2019, '第一学期', NULL),
('S14003', 'PH1002', 2, 2019, '第一学期', NULL),
('S14003', 'PH5002', 2, 2019, '第一学期', NULL),
('S15001', 'ECO1001', 1, 2019, '第一学期', NULL),
('S15001', 'LAW1101', 1, 2019, '第一学期', 'B+'),
('S15001', 'PH5002', 1, 2019, '第一学期', NULL),
('S15009', 'CS1001', 1, 2019, '第一学期', 'D+'),
('S15009', 'PH1001', 1, 2019, '第一学期', NULL),
('S15222', 'CS1001', 1, 2019, '第一学期', 'A'),
('S15222', 'CS1002', 2, 2019, '第一学期', NULL),
('S15222', 'LAW1101', 1, 2019, '第一学期', 'B'),
('S16006', 'CS1002', 1, 2019, '第一学期', NULL),
('S16006', 'ECO1001', 1, 2019, '第一学期', NULL),
('S16006', 'LAW1101', 1, 2019, '第一学期', 'A-'),
('S16006', 'PH1001', 1, 2019, '第一学期', NULL),
('S16008', 'CS1002', 2, 2019, '第一学期', NULL),
('S16008', 'CS1009', 1, 2019, '第一学期', NULL),
('S16008', 'LAW1101', 2, 2019, '第一学期', NULL),
('S16008', 'PH1002', 2, 2019, '第一学期', NULL),
('S17053', 'ECO1001', 1, 2019, '第一学期', NULL),
('S17053', 'LAW1101', 1, 2019, '第一学期', 'A'),
('S17053', 'PH1002', 1, 2019, '第一学期', NULL),
('S17053', 'PH5002', 1, 2019, '第一学期', NULL),
('S17302', 'LAW1101', 2, 2019, '第一学期', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `teacher_id` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL,
  `title` varchar(16) DEFAULT NULL,
  `department` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`teacher_id`, `name`, `title`, `department`) VALUES
('T001', '董芝玉', '教授', '计算机学院'),
('T002', '谈全', '教授', '计算机学院'),
('T003', '鲍丹', '教授', '计算机学院'),
('T004', '韦宁欣', '副教授', '计算机学院'),
('T005', '王安', '副教授', '计算机学院'),
('T006', '冯琴', '副教授', '计算机学院'),
('T007', '闵璧璐', '副教授', '计算机学院'),
('T008', '滕钧冠', '高级讲师', '计算机学院'),
('T009', '卜菊', '高级讲师', '计算机学院'),
('T010', '项馨', '高级讲师', '法学院'),
('T011', '祝梅', '高级讲师', '法学院'),
('T012', '喻岩中', '高级讲师', '法学院'),
('T013', '邹栋', '教授', '法学院'),
('T014', '于信子', '教授', '法学院'),
('T015', '于先', '教授', '物理学院'),
('T016', '宋露瑶', '教授', '物理学院'),
('T017', '昌磊民', '副教授', '物理学院'),
('T018', '王有', '副教授', '物理学院'),
('T019', '舒蓓', '副教授', '物理学院'),
('T020', '汤志', '副教授', '法学院'),
('T021', '戴泽', '副教授', '法学院'),
('T022', '史咏卿', '副教授', '法学院'),
('T023', '米霞香', '高级讲师', '法学院'),
('T024', '沈婷姣', '高级讲师', '法学院'),
('T025', '岑林有', '高级讲师', '法学院'),
('T026', '孙达安', '教授', '经济学院'),
('T027', '郑震', '教授', '经济学院'),
('T028', '邵妍', '教授', '经济学院'),
('T029', '尤璧璐', '教授', '经济学院'),
('T030', '秦震', '教授', '物理学院'),
('T031', '姜春菊', '教授', '物理学院'),
('T032', '毕超', '副教授', '物理学院'),
('T033', '伏晨辰', '副教授', '物理学院'),
('T034', '禹秋', '副教授', '经济学院'),
('T035', '钱娅琦', '副教授', '经济学院'),
('T2', '吴克强', '教授', '计算机学院'),
('T996', '我死了', '校长', '物理学院');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`uid`) USING BTREE;

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`) USING BTREE;

--
-- Indexes for table `application_transaction`
--
ALTER TABLE `application_transaction`
  ADD PRIMARY KEY (`apply_id`),
  ADD KEY `application_transaction_ibfk_1` (`student_id`),
  ADD KEY `application_transaction_ibfk_2` (`course_id`,`section_id`,`year`,`semester`);

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
  ADD PRIMARY KEY (`course_id`),
  ADD KEY `department` (`department`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`depart_name`),
  ADD UNIQUE KEY `depart_code` (`depart_code`);

--
-- Indexes for table `major`
--
ALTER TABLE `major`
  ADD PRIMARY KEY (`major_name`),
  ADD KEY `fk_tb_dept` (`department`);

--
-- Indexes for table `quit`
--
ALTER TABLE `quit`
  ADD PRIMARY KEY (`quit_id`),
  ADD KEY `quit_ibfk_1` (`student_id`),
  ADD KEY `quit_ibfk_2` (`course_id`,`section_id`,`year`,`semester`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`course_id`,`section_id`,`year`,`semester`),
  ADD KEY `fk_se_as` (`assessment_id`),
  ADD KEY `fk_se_cl` (`classroom_code`),
  ADD KEY `fk_se_te` (`teacher_id`);

--
-- Indexes for table `sec_time`
--
ALTER TABLE `sec_time`
  ADD UNIQUE KEY `course_id` (`course_id`,`section_id`,`year`,`semester`,`day_of_week`,`lesson_seq`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `student_ibfk_2` (`major`);

--
-- Indexes for table `stu_take_sec`
--
ALTER TABLE `stu_take_sec`
  ADD UNIQUE KEY `student_id` (`student_id`,`course_id`,`section_id`,`year`,`semester`),
  ADD KEY `fk_ta_co` (`course_id`,`section_id`,`year`,`semester`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`teacher_id`),
  ADD KEY `teacher_ibfk_2` (`department`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `application_transaction`
--
ALTER TABLE `application_transaction`
  MODIFY `apply_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `assessment`
--
ALTER TABLE `assessment`
  MODIFY `assessment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `quit`
--
ALTER TABLE `quit`
  MODIFY `quit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `account` (`uid`);

--
-- Constraints for table `application_transaction`
--
ALTER TABLE `application_transaction`
  ADD CONSTRAINT `application_transaction_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`),
  ADD CONSTRAINT `application_transaction_ibfk_2` FOREIGN KEY (`course_id`,`section_id`,`year`,`semester`) REFERENCES `section` (`course_id`, `section_id`, `year`, `semester`) ON DELETE CASCADE;

--
-- Constraints for table `assessment`
--
ALTER TABLE `assessment`
  ADD CONSTRAINT `fk_as_cl` FOREIGN KEY (`location`) REFERENCES `classroom` (`classroom_code`);

--
-- Constraints for table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `course_ibfk_1` FOREIGN KEY (`department`) REFERENCES `department` (`depart_name`);

--
-- Constraints for table `major`
--
ALTER TABLE `major`
  ADD CONSTRAINT `fk_tb_dept` FOREIGN KEY (`department`) REFERENCES `department` (`depart_name`) ON UPDATE CASCADE;

--
-- Constraints for table `quit`
--
ALTER TABLE `quit`
  ADD CONSTRAINT `quit_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`),
  ADD CONSTRAINT `quit_ibfk_2` FOREIGN KEY (`course_id`,`section_id`,`year`,`semester`) REFERENCES `section` (`course_id`, `section_id`, `year`, `semester`) ON DELETE CASCADE;

--
-- Constraints for table `section`
--
ALTER TABLE `section`
  ADD CONSTRAINT `fk_se_as` FOREIGN KEY (`assessment_id`) REFERENCES `assessment` (`assessment_id`),
  ADD CONSTRAINT `fk_se_cl` FOREIGN KEY (`classroom_code`) REFERENCES `classroom` (`classroom_code`),
  ADD CONSTRAINT `fk_se_te` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`),
  ADD CONSTRAINT `section_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`);

--
-- Constraints for table `sec_time`
--
ALTER TABLE `sec_time`
  ADD CONSTRAINT `sec_time_ibfk_1` FOREIGN KEY (`course_id`,`section_id`,`year`,`semester`) REFERENCES `section` (`course_id`, `section_id`, `year`, `semester`) ON DELETE CASCADE;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `account` (`uid`),
  ADD CONSTRAINT `student_ibfk_2` FOREIGN KEY (`major`) REFERENCES `major` (`major_name`);

--
-- Constraints for table `stu_take_sec`
--
ALTER TABLE `stu_take_sec`
  ADD CONSTRAINT `fk_ta_co` FOREIGN KEY (`course_id`,`section_id`,`year`,`semester`) REFERENCES `section` (`course_id`, `section_id`, `year`, `semester`),
  ADD CONSTRAINT `fk_ta_st` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`);

--
-- Constraints for table `teacher`
--
ALTER TABLE `teacher`
  ADD CONSTRAINT `teacher_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `account` (`uid`),
  ADD CONSTRAINT `teacher_ibfk_2` FOREIGN KEY (`department`) REFERENCES `department` (`depart_name`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
