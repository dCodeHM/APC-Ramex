-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 05, 2024 at 01:41 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ramexdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `account_id` int(11) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL DEFAULT '',
  `role` varchar(60) NOT NULL DEFAULT 'Unassigned',
  `program_name` varchar(100) NOT NULL DEFAULT 'Unassigned'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`account_id`, `user_email`, `pwd`, `date_created`, `first_name`, `last_name`, `role`, `program_name`) VALUES
(1, 'pile-siwodo60@apc.edu.ph', 'hrhxf', '2024-04-17 23:50:58', 'wow', 'Siwodo', 'Program Director', 'Information Technology'),
(2, 'nuxag_uhava37@apc.edu.ph', 'qye', '2024-04-17 23:50:58', 'Nuxag', 'Uhava', 'Unassigned', 'Computer Engineering'),
(3, 'duw-uxeyoxu99@apc.edu.ph', 'qeeye', '2024-04-17 23:50:58', 'Duw', 'Uxeyoxu', 'Unassigned', 'Unassigned'),
(4, 'dacup-ezuju22@apc.edu.ph', 'adhe', '2024-04-17 23:50:58', 'Dacup', 'Ezuju', 'Unassigned', 'Physics'),
(5, 'fetagip_exa51@apc.edu.ph', 'Fetagip', '2024-04-17 23:50:58', 'Hetagip', 'Exa', 'Unassigned', 'Unassigned'),
(6, 'hen-agecihe6@apc.edu.ph', 'haaddg', '2024-04-17 23:50:58', 'Hen', 'Agecihe', 'Unassigned', 'Unassigned'),
(7, 'mos-ivezuro75@apc.edu.ph', 'gadhe', '2024-04-17 23:50:58', 'Mos', 'Ivezuro', 'Unassigned', 'Unassigned'),
(8, 'cala_yujiki4@apc.edu.ph', 'hsshf', '2024-04-17 23:50:58', 'Cala', 'Yukizi', 'Unassigned', 'Unassigned'),
(9, 'luxupu-noso90@apc.edu.ph', 'sgd', '2024-04-17 23:50:58', 'Luxupu', 'Noso', 'Unassigned', 'Unassigned'),
(10, 'voki_yuvaxu1@apc.edu.ph', 'asd', '2024-04-17 23:50:58', 'Voki', 'Yuvaxu', 'Professor', 'Unassigned'),
(11, 'fesij_arele63@apc.edu.ph', 'qwe', '2024-04-17 23:50:58', 'Fesij', 'Arele', 'Unassigned', 'Computer Engineering'),
(12, 'kuhevur-ufu46@apc.edu.ph', 'asd', '2024-04-17 23:50:58', 'Kehevur', 'Ufu', 'Unassigned', 'Unassigned'),
(13, 'ruheja_recu94@apc.edu.ph', 'www', '2024-04-17 23:50:58', 'Ruheja', 'Recu', 'Executive Director', 'Computer Engineering'),
(14, 'vefadik-ere33@apc.edu.ph', 'qqqw', '2024-04-17 23:50:58', 'Vefadik', 'Ere', 'Unassigned', 'Computer Engineering'),
(15, 'nedix-afidi79@apc.edu.ph', '123', '2024-04-17 23:50:58', 'Nedix', 'Afidi', 'Unassigned', 'Computer Engineering'),
(16, 'muveb_isili25@apc.edu.ph', '6969', '2024-04-17 23:50:58', 'Muveb', 'Isili', 'Professor', 'Computer Science'),
(17, 'hmanes@student.apc.edu.ph', '123', '2024-05-03 10:05:12', 'Honniel', 'Manes', 'Executive Director', 'Unassigned'),
(18, 'cagonzales@student.apc.edu.ph', '123', '2024-05-06 07:07:29', 'Charmie', 'Gonzales', 'Professor', 'Unassigned');

-- --------------------------------------------------------

--
-- Table structure for table `course_outcomes`
--

CREATE TABLE `course_outcomes` (
  `clo_id` int(100) NOT NULL,
  `clo_number` varchar(255) DEFAULT NULL,
  `clo_details` varchar(255) NOT NULL,
  `course_syllabus_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_outcomes`
--

INSERT INTO `course_outcomes` (`clo_id`, `clo_number`, `clo_details`, `course_syllabus_id`) VALUES
(1, 'CLO3', 'DSDFSADF', 1),
(2, 'CLO1', 'Basic knowledge computed mathematical problems', 1),
(3, 'CLO2', 'Understands fundamentals or rules', 1),
(4, 'CLO3', 'Defines problems and ensure completeness', 1),
(5, 'CLO4', 'Identify problems in the real world', 1),
(6, 'CLO5', 'Understand concepts and ideas in creating solutions', 1),
(7, 'CLO1', 'Understand tools in chemistry', 2),
(8, 'CLO2', 'fundamentals of engineering chemistry', 2),
(9, 'CLO3', 'Focus real problems using chemistry', 2),
(10, 'CLO4', 'Understanding Engineering Chemistry', 2),
(11, 'CLO5', 'Ensure problems and ideas to concepts of chemistry', 2),
(12, 'CLO1', 'Real life test in chemistry', 3),
(13, 'CLO2', 'Ensure effectiveness and protection to chemicals', 3),
(14, 'CLO3', 'Integrate technology to chemistry', 3),
(15, 'CLO4', 'Concepts and lessons to chemistry uses', 3),
(16, 'CLO5', 'Integrity to tools and protection', 3),
(17, 'CLO1', 'Discipline as a computer engineering', 4),
(18, 'CLO2', 'the job of a computer engineer', 4),
(19, 'CLO3', 'relationships and boundaries', 4),
(20, 'CLO4', 'A part of a society as an engineer', 4),
(21, 'CLO5', 'Basic principles in the real world', 4),
(22, 'CLO1', 'basic programming techniques', 5),
(23, 'CLO2', 'learning concepts and history of programming', 5),
(24, 'CLO3', 'creating solutions and logic to programming problems', 5),
(25, 'CLO4', 'Create programming projects in the real world', 5),
(26, 'CLO5', 'use programming in concepts and designs', 5),
(27, 'CLO1', 'Basic information technology concepts and ideas', 6),
(28, 'CLO2', 'integrate technology to products', 6),
(29, 'CLO3', 'paper works as an informative student', 6),
(30, 'CLO4', 'Practicality and function as an IT', 6),
(31, 'CLO5', 'IT Concepts', 6),
(32, 'CLO1', 'Philippine History', 7),
(33, 'CLO2', 'Life of Rizal and its missions', 7),
(34, 'CLO3', 'understand the lessons of Filipino heroes', 7),
(35, 'CLO4', 'before and todays concept of heroism', 7),
(36, 'CLO5', 'Life of rizal to us', 7),
(37, 'CLO1', 'General Ethics as a person', 8),
(38, 'CLO2', 'How ethics is important to students', 8),
(39, 'CLO3', 'ensure consistency and importance of ethics', 8),
(40, 'CLO4', 'Consistency and Integrity as a person', 8),
(41, 'CLO5', 'Importance of life and its problems', 8),
(42, 'CLO1', 'Knowledge of Calculus topics to real world', 9),
(43, 'CLO2', 'learning students form logical answers to problems', 9),
(44, 'CLO3', 'fundamentals of mathematical methods', 9),
(45, 'CLO4', 'real problems from real solutions using math', 9),
(46, 'CLO5', 'identify problems to solutions', 9),
(47, 'CLO1', 'awdawdawdawdawdawd', 10),
(48, 'CLO2', 'awdawdsrydsrts', 10),
(49, 'CLO4', 'dr6tudrtfududu', 10),
(50, 'CLO5', 'fghjf7yjt667j6', 10);

-- --------------------------------------------------------

--
-- Table structure for table `course_syllabus`
--

CREATE TABLE `course_syllabus` (
  `course_syllabus_id` int(100) NOT NULL,
  `course_code` varchar(100) NOT NULL,
  `course_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='this table is for the drop down list for courses in course F';

--
-- Dumping data for table `course_syllabus`
--

INSERT INTO `course_syllabus` (`course_syllabus_id`, `course_code`, `course_name`) VALUES
(36, 'APPROJ1', 'Applied Project 1'),
(55, 'ARCORLB', 'ARCOR I FORGOT'),
(28, 'ARTAPRE', 'Art Appreciation'),
(1, 'CALCONE', 'Calculus One'),
(9, 'CALCTWO', 'Calculus Two'),
(54, 'COMAROR', 'Computer ROR SOMETHING'),
(65, 'COMNETS', 'Computer Networking'),
(46, 'CONWORL', 'Contemporary World'),
(50, 'CPECGS1', 'Computer Engineering Website 1'),
(66, 'CPECGS3', 'Computer Website 3'),
(4, 'CPEDISC', 'Computer Engineering Discipline'),
(41, 'CPEDRAF', 'Computer Engineering Drafting'),
(67, 'CPELAWS', 'Computer Engineering Laws'),
(18, 'CRKTLAB', 'Circuit Laboratory'),
(35, 'DATALAB', 'Data Laboratory'),
(34, 'DATAMGT', 'Data Management'),
(53, 'DATCOMS', 'Data Communications'),
(20, 'DATSTRC', 'Data Structures'),
(24, 'DIEQUAT', 'Differential Equation'),
(51, 'DIGSLAB', 'Digital Laboratory'),
(52, 'DIGSPRO', 'Digital Processor'),
(25, 'DISCMAT', 'Discrete Mathematics'),
(21, 'ECONOMC', 'Engineering Economics'),
(17, 'ELECIRK', 'Electrical Circuits'),
(32, 'ELEXCKT', 'Electronics Laboratory'),
(33, 'ELEXLAB', 'Electronics Laboratory'),
(56, 'EMBEDDS', 'Embedded Digital'),
(57, 'EMBEDLB', 'Embedded Laboratory'),
(69, 'EMERTEC', 'Emersion Technology'),
(49, 'EMICROS', 'Engineering Micro Processors'),
(19, 'ENGCADD', 'Engineering CAD Design'),
(2, 'ENGCHEM', 'Engineering Chemistry'),
(3, 'ENGCHLB', 'Engineering Chemical Laboratory'),
(16, 'ENGDATA', 'Engineering Data'),
(37, 'EXCOMP1', 'IDK'),
(58, 'EXCOMP2', 'Communications 2'),
(40, 'FDCONTS', 'Fundamentals Constructions'),
(8, 'GETHICS', 'General Ethics'),
(26, 'GRAPHYS', 'Graphical IDK'),
(63, 'HEALTHS', 'Health and Protection'),
(47, 'INTOHDL', 'Into Something'),
(6, 'ITCONCE', 'Information Concepts'),
(43, 'LOGCDES', 'Logic Descriptive'),
(44, 'LOGICLB', 'Logical Laboratory'),
(22, 'MATWORL', 'Mathematics In the Real World'),
(48, 'MCROLAB', 'Micro Laboratory'),
(42, 'MIXSIGS', 'Mixed Signals'),
(38, 'MOBCAPP', 'Mobile Application'),
(60, 'NATSER1', 'National Service 1'),
(61, 'NATSER2', 'National Service 2'),
(64, 'NETSLAB', 'Networking Laboratory'),
(31, 'NUMERCL', 'Numerical Calculus'),
(12, 'OBJPROG', 'Object Programming'),
(27, 'OPRSYST', 'Operating Systems'),
(39, 'PEDUFOR', 'Physical Education 4'),
(15, 'PEDUONE', 'Physical Education One'),
(30, 'PEDUTRI', 'Physical Education Three'),
(23, 'PEDUTWO', 'Physical Education Two'),
(7, 'PHILHIS', 'Philippine History'),
(11, 'PHYENLB', 'Physics Engineering Laboratory'),
(10, 'PHYENLC', 'Physics Engineering Lecture'),
(68, 'PROFETH', 'Professional Ethics'),
(5, 'PROGLOD', 'Programming Logic'),
(14, 'PURPCOM', 'Purposive Communication'),
(29, 'RIZLIFE', 'Rizal Life'),
(45, 'ROBPROA', 'Robotics Process Automation'),
(62, 'SCITECS', 'Science and Technology'),
(59, 'TECH101', 'Tech 101 something'),
(13, 'UNDSELF', 'Understanding the Self');

-- --------------------------------------------------------

--
-- Table structure for table `exam`
--

CREATE TABLE `exam` (
  `exam_id` int(11) NOT NULL,
  `exam_name` varchar(255) NOT NULL,
  `course_topic_id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `easy` int(11) DEFAULT NULL,
  `normal` int(11) DEFAULT NULL,
  `hard` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exam`
--

INSERT INTO `exam` (`exam_id`, `exam_name`, `course_topic_id`, `date_created`, `easy`, `normal`, `hard`) VALUES
(95, 'Exam for Test Topic 1', 137, '2024-06-05 08:03:39', 2, 1, 1),
(96, 'Exam for Test Topic 2', 138, '2024-06-05 08:08:56', 1, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `exam_upload`
--

CREATE TABLE `exam_upload` (
  `upload_id` int(10) NOT NULL,
  `filename` blob NOT NULL,
  `exam_id` int(10) NOT NULL,
  `folder_id` int(10) NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `notification_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `message` varchar(255) NOT NULL,
  `dateT` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prof_course_subject`
--

CREATE TABLE `prof_course_subject` (
  `course_subject_id` int(10) NOT NULL,
  `account_id` int(11) NOT NULL,
  `course_code` varchar(100) DEFAULT NULL,
  `program_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prof_course_subject`
--

INSERT INTO `prof_course_subject` (`course_subject_id`, `account_id`, `course_code`, `program_name`) VALUES
(104, 17, 'ENGCHEM', 'Computer Engineering'),
(105, 17, 'ELECIRK', 'Computer Engineering'),
(108, 17, 'PEDUONE', 'Master of Science in Computer Science'),
(109, 17, 'PHILHIS', 'Accountancy'),
(110, 17, 'ENGDATA', 'Architecture'),
(111, 17, 'ENGCHLB', 'Civil Engineering'),
(112, 18, 'COMAROR', 'Architecture'),
(113, 17, 'ENGCHLB', 'Electronics Engineering'),
(114, 17, 'MCROLAB', 'Computer Engineering'),
(115, 17, 'ECONOMC', 'Master of Science in Information Systems'),
(116, 17, 'EXCOMP2', 'Civil Engineering'),
(117, 17, 'NETSLAB', 'Physics');

-- --------------------------------------------------------

--
-- Table structure for table `prof_course_topic`
--

CREATE TABLE `prof_course_topic` (
  `course_topic_id` int(10) NOT NULL,
  `course_subject_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `course_topics` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prof_course_topic`
--

INSERT INTO `prof_course_topic` (`course_topic_id`, `course_subject_id`, `account_id`, `course_topics`, `date_created`) VALUES
(137, 104, 17, 'Test Topic 1', '2024-06-05 16:03:39'),
(138, 104, 17, 'Test Topic 2', '2024-06-05 16:08:56');

-- --------------------------------------------------------

--
-- Table structure for table `program_name`
--

CREATE TABLE `program_name` (
  `program_name` varchar(100) NOT NULL,
  `program_id` int(10) NOT NULL,
  `program_desc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program_name`
--

INSERT INTO `program_name` (`program_name`, `program_id`, `program_desc`) VALUES
('Accountancy', 11, 'School of Management'),
('Architecture', 8, 'School of Architecture'),
('Business Administration', 10, 'School of Management'),
('Civil Engineering', 3, 'School of Engineering'),
('Computer Engineering', 1, 'School of Engineering'),
('Computer Science', 5, 'School of Computing and Information Technologies'),
('Computer Technology', 9, 'School of Computing and Information Technologies'),
('Electronics Engineering', 2, 'School of Engineering'),
('Finance Management', 12, 'School of Management'),
('Information Technology', 4, 'School of Computing and Information Technologies'),
('Management Accounting', 14, 'School of Management'),
('Master in Game Design', 21, 'Graduate School'),
('Master in Management', 22, 'Graduate School'),
('Master of Engineering Major in Computer Engineering', 20, 'Graduate School'),
('Master of Science in Computer Science', 17, 'Graduate School'),
('Master of Science in Information Systems', 18, 'Graduate School'),
('Master of Science in Information Technology', 19, 'Graduate School'),
('Multimedia Arts', 15, 'School of Multimedia and Arts'),
('Physics', 6, 'School of Research'),
('Psychology', 16, 'School of Multimedia and Arts'),
('Tourism Management', 13, 'School of Management'),
('Unassigned', 7, 'No School Unavailable');

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `question_id` int(11) NOT NULL,
  `exam_id` int(11) DEFAULT NULL,
  `question_text` varchar(255) DEFAULT NULL,
  `question_image` longblob DEFAULT NULL,
  `clo_id` varchar(50) DEFAULT NULL,
  `difficulty` char(1) DEFAULT NULL,
  `question_points` int(11) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `answer_id` int(11) DEFAULT NULL,
  `in_question_library` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`question_id`, `exam_id`, `question_text`, `question_image`, `clo_id`, `difficulty`, `question_points`, `date_created`, `answer_id`, `in_question_library`) VALUES
(895, 95, 'Question 1', NULL, '7', 'E', 0, '2024-06-05 08:04:13', 1, 1),
(896, 95, 'Question 2', NULL, '7', 'E', 0, '2024-06-05 08:04:20', 2, 1),
(897, 95, 'Question 3', NULL, '7', 'E', 20, '2024-06-05 08:07:29', 3, 1),
(900, 96, 'Question 1', NULL, '7', 'E', 0, '2024-06-05 10:27:02', 4, 1),
(901, 96, 'Question 2', NULL, '7', 'N', 0, '2024-06-05 11:36:41', 5, 1),
(902, 96, 'Question 3', NULL, '7', 'N', 20, '2024-06-05 11:36:55', 6, 1),
(903, 96, 'Question 4', NULL, '11', 'E', 10, '2024-06-05 11:37:08', 7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `question_choices`
--

CREATE TABLE `question_choices` (
  `question_choices_id` int(11) NOT NULL,
  `answer_text` varchar(255) DEFAULT NULL,
  `answer_image` longblob DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT 0,
  `letter` text DEFAULT NULL,
  `answer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question_choices`
--

INSERT INTO `question_choices` (`question_choices_id`, `answer_text`, `answer_image`, `is_correct`, `letter`, `answer_id`) VALUES
(1704, 'A', NULL, 1, 'A', 1),
(1705, 'B', NULL, 0, 'B', 1),
(1706, 'A', NULL, 1, 'A', 2),
(1707, 'B', NULL, 0, 'B', 2),
(1708, 'A', NULL, 1, 'A', 3),
(1709, 'B', NULL, 0, 'B', 3),
(1714, 'A', NULL, 1, 'A', 4),
(1715, 'B', NULL, 0, 'B', 4),
(1716, 'A', NULL, 1, 'A', 5),
(1717, 'B', NULL, 0, 'B', 5),
(1718, 'A', NULL, 1, 'A', 6),
(1719, 'B', NULL, 0, 'B', 6),
(1720, 'A', NULL, 1, 'A', 7),
(1721, 'B', NULL, 0, 'B', 7);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `role` varchar(50) NOT NULL,
  `role_description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`role`, `role_description`) VALUES
('Executive Director', 'Has access to the Student Assessment, Course Assessment, and Exam Maker.'),
('Professor', 'Has access to the Student Assessment and Exam Maker'),
('Program Director', 'Has access to the Student Assessment, Course Assessment, and Exam Maker.'),
('Unassigned', 'Has no access');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`account_id`),
  ADD UNIQUE KEY `user_email` (`user_email`),
  ADD KEY `role` (`role`),
  ADD KEY `program_name` (`program_name`);

--
-- Indexes for table `course_outcomes`
--
ALTER TABLE `course_outcomes`
  ADD PRIMARY KEY (`clo_id`),
  ADD KEY `course_syllabus_id` (`course_syllabus_id`);

--
-- Indexes for table `course_syllabus`
--
ALTER TABLE `course_syllabus`
  ADD PRIMARY KEY (`course_code`),
  ADD KEY `course_syllabus_id` (`course_syllabus_id`);

--
-- Indexes for table `exam`
--
ALTER TABLE `exam`
  ADD PRIMARY KEY (`exam_id`);

--
-- Indexes for table `exam_upload`
--
ALTER TABLE `exam_upload`
  ADD PRIMARY KEY (`upload_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `connection for user` (`user_id`);

--
-- Indexes for table `prof_course_subject`
--
ALTER TABLE `prof_course_subject`
  ADD PRIMARY KEY (`course_subject_id`),
  ADD KEY `course_folder` (`account_id`),
  ADD KEY `program_name` (`program_name`),
  ADD KEY `course_code` (`course_code`);

--
-- Indexes for table `prof_course_topic`
--
ALTER TABLE `prof_course_topic`
  ADD PRIMARY KEY (`course_topic_id`),
  ADD KEY `course_subject_id` (`course_subject_id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `program_name`
--
ALTER TABLE `program_name`
  ADD PRIMARY KEY (`program_name`),
  ADD UNIQUE KEY `program_id` (`program_id`) USING BTREE;

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`question_id`);

--
-- Indexes for table `question_choices`
--
ALTER TABLE `question_choices`
  ADD PRIMARY KEY (`question_choices_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `course_outcomes`
--
ALTER TABLE `course_outcomes`
  MODIFY `clo_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `course_syllabus`
--
ALTER TABLE `course_syllabus`
  MODIFY `course_syllabus_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `exam`
--
ALTER TABLE `exam`
  MODIFY `exam_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notification_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prof_course_subject`
--
ALTER TABLE `prof_course_subject`
  MODIFY `course_subject_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT for table `prof_course_topic`
--
ALTER TABLE `prof_course_topic`
  MODIFY `course_topic_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `program_name`
--
ALTER TABLE `program_name`
  MODIFY `program_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=904;

--
-- AUTO_INCREMENT for table `question_choices`
--
ALTER TABLE `question_choices`
  MODIFY `question_choices_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1722;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `program lists` FOREIGN KEY (`program_name`) REFERENCES `program_name` (`program_name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `roles` FOREIGN KEY (`role`) REFERENCES `role` (`role`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `course_outcomes`
--
ALTER TABLE `course_outcomes`
  ADD CONSTRAINT `COURSE SYLLABUS` FOREIGN KEY (`course_syllabus_id`) REFERENCES `course_syllabus` (`course_syllabus_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prof_course_subject`
--
ALTER TABLE `prof_course_subject`
  ADD CONSTRAINT `account_id` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `course_syllabus` FOREIGN KEY (`course_code`) REFERENCES `course_syllabus` (`course_code`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `program_name` FOREIGN KEY (`program_name`) REFERENCES `program_name` (`program_name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prof_course_topic`
--
ALTER TABLE `prof_course_topic`
  ADD CONSTRAINT `account` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `course_subject_id` FOREIGN KEY (`course_subject_id`) REFERENCES `prof_course_subject` (`course_subject_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
