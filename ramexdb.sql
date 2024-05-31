-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 31, 2024 at 10:56 PM
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
  `clo_number` varchar(255) NOT NULL,
  `clo_details` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_outcomes`
--

INSERT INTO `course_outcomes` (`clo_id`, `clo_number`, `clo_details`) VALUES
(1, 'CLO3g', ''),
(2, 'CLO1fj', 'Basic knowledge computed mathematical problems'),
(3, 'CLO2f', 'Understands fundamentals or rules'),
(4, 'CLO3wqnq', 'Defines problems and ensure completeness'),
(5, 'CLO4qwzd', 'Identify problems in the real world'),
(6, 'CLO5wqbv', 'Understand concepts and ideas in creating solutions'),
(7, 'CLO1umr', 'Understand tools in chemistry'),
(8, 'CLO2wzwa', 'fundamentals of engineering chemistry'),
(9, 'CLO3bwqz', 'Focus real problems using chemistry'),
(10, 'CLO4emte', 'Understanding Engineering Chemistry'),
(11, 'CLO5avqe', 'Ensure problems and ideas to concepts of chemistry'),
(12, 'CLO1mynw', 'Real life test in chemistry'),
(13, 'CLO2mert', 'Ensure effectiveness and protection to chemicals'),
(14, 'CLO3emwe', 'Integrate technology to chemistry'),
(15, 'CLO4wqn', 'Concepts and lessons to chemistry uses'),
(16, 'CLO5emtk', 'Integrity to tools and protection'),
(17, 'CLO1wqz', 'Discipline as a computer engineering'),
(18, 'CLO2wnr', 'the job of a computer engineer'),
(19, 'CLO3qwx', 'relationships and boundaries'),
(20, 'CLO4ejnr', 'A part of a society as an engineer'),
(21, 'CLO5asb', 'Basic principles in the real world'),
(22, 'CLO1awrx', 'basic programming techniques'),
(23, 'CLO2warq', 'learning concepts and history of programming'),
(24, 'CLO3vbc', 'creating solutions and logic to programming problems'),
(25, 'CLO4mge', 'Create programming projects in the real world'),
(26, 'CLO5egq', 'use programming in concepts and designs'),
(27, 'CLO1dgsxz', 'Basic information technology concepts and ideas'),
(28, 'CLO2gmw', 'integrate technology to products'),
(29, 'CLO3fhne', 'paper works as an informative student'),
(30, 'CLO4hds', 'Practicality and function as an IT'),
(31, 'CLO5xtj', 'IT Concepts'),
(32, 'CLO1fyj', 'Philippine History'),
(33, 'CLO2lig', 'Life of Rizal and its missions'),
(34, 'CLO3znf', 'understand the lessons of Filipino heroes'),
(35, 'CLO4mgr', 'before and todays concept of heroism'),
(36, 'CLO5jer', 'Life of rizal to us'),
(37, 'CLO1tnsx', 'General Ethics as a person'),
(38, 'CLO2hrwq', 'How ethics is important to students'),
(39, 'CLO3rhx', 'ensure consistency and importance of ethics'),
(40, 'CLO4jrt', 'Consistency and Integrity as a person'),
(41, 'CLO5', 'Importance of life and its problems'),
(42, 'CLO1e', 'Knowledge of Calculus topics to real world'),
(43, 'CLO2zqv', 'learning students form logical answers to problems'),
(44, 'CLO3s', 'fundamentals of mathematical methods'),
(45, 'CLO4sb', 'real problems from real solutions using math'),
(46, 'CLO5z', 'identify problems to solutions'),
(47, 'CLO1s', ''),
(48, 'CLO2s', ''),
(49, 'CLO4dsa', ''),
(50, 'CLO5sdq', '');

-- --------------------------------------------------------

--
-- Table structure for table `course_syllabus`
--

CREATE TABLE `course_syllabus` (
  `course_syllabus_id` int(100) NOT NULL,
  `course_code` varchar(100) NOT NULL,
  `course_name` varchar(100) DEFAULT NULL,
  `clo_id` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='this table is for the drop down list for courses in course F';

--
-- Dumping data for table `course_syllabus`
--

INSERT INTO `course_syllabus` (`course_syllabus_id`, `course_code`, `course_name`, `clo_id`) VALUES
(36, 'APPROJ1', 'Applied Project 1', ''),
(55, 'ARCORLB', 'ARCOR I FORGOT', ''),
(28, 'ARTAPRE', 'Art Appreciation', ''),
(1, 'CALCONE', 'Calculus One', ''),
(9, 'CALCTWO', 'Calculus Two', ''),
(54, 'COMAROR', 'Computer ROR SOMETHING', ''),
(65, 'COMNETS', 'Computer Networking', ''),
(46, 'CONWORL', 'Contemporary World', ''),
(50, 'CPECGS1', 'Computer Engineering Website 1', ''),
(66, 'CPECGS3', 'Computer Website 3', ''),
(4, 'CPEDISC', 'Computer Engineering Discipline', ''),
(41, 'CPEDRAF', 'Computer Engineering Drafting', ''),
(67, 'CPELAWS', 'Computer Engineering Laws', ''),
(18, 'CRKTLAB', 'Circuit Laboratory', ''),
(35, 'DATALAB', 'Data Laboratory', ''),
(34, 'DATAMGT', 'Data Management', ''),
(53, 'DATCOMS', 'Data Communications', ''),
(20, 'DATSTRC', 'Data Structures', ''),
(24, 'DIEQUAT', 'Differential Equation', ''),
(51, 'DIGSLAB', 'Digital Laboratory', ''),
(52, 'DIGSPRO', 'Digital Processor', ''),
(25, 'DISCMAT', 'Discrete Mathematics', ''),
(21, 'ECONOMC', 'Engineering Economics', ''),
(17, 'ELECIRK', 'Electrical Circuits', ''),
(32, 'ELEXCKT', 'Electronics Laboratory', ''),
(33, 'ELEXLAB', 'Electronics Laboratory', ''),
(56, 'EMBEDDS', 'Embedded Digital', ''),
(57, 'EMBEDLB', 'Embedded Laboratory', ''),
(69, 'EMERTEC', 'Emersion Technology', ''),
(49, 'EMICROS', 'Engineering Micro Processors', ''),
(19, 'ENGCADD', 'Engineering CAD Design', ''),
(2, 'ENGCHEM', 'Engineering Chemistry', ''),
(3, 'ENGCHLB', 'Engineering Chemical Laboratory', ''),
(16, 'ENGDATA', 'Engineering Data', ''),
(37, 'EXCOMP1', 'IDK', ''),
(58, 'EXCOMP2', 'Communications 2', ''),
(40, 'FDCONTS', 'Fundamentals Constructions', ''),
(8, 'GETHICS', 'General Ethics', ''),
(26, 'GRAPHYS', 'Graphical IDK', ''),
(63, 'HEALTHS', 'Health and Protection', ''),
(47, 'INTOHDL', 'Into Something', ''),
(6, 'ITCONCE', 'Information Concepts', ''),
(43, 'LOGCDES', 'Logic Descriptive', ''),
(44, 'LOGICLB', 'Logical Laboratory', ''),
(22, 'MATWORL', 'Mathematics In the Real World', ''),
(48, 'MCROLAB', 'Micro Laboratory', ''),
(42, 'MIXSIGS', 'Mixed Signals', ''),
(38, 'MOBCAPP', 'Mobile Application', ''),
(60, 'NATSER1', 'National Service 1', ''),
(61, 'NATSER2', 'National Service 2', ''),
(64, 'NETSLAB', 'Networking Laboratory', ''),
(31, 'NUMERCL', 'Numerical Calculus', ''),
(12, 'OBJPROG', 'Object Programming', ''),
(27, 'OPRSYST', 'Operating Systems', ''),
(39, 'PEDUFOR', 'Physical Education 4', ''),
(15, 'PEDUONE', 'Physical Education One', ''),
(30, 'PEDUTRI', 'Physical Education Three', ''),
(23, 'PEDUTWO', 'Physical Education Two', ''),
(7, 'PHILHIS', 'Philippine History', ''),
(11, 'PHYENLB', 'Physics Engineering Laboratory', ''),
(10, 'PHYENLC', 'Physics Engineering Lecture', ''),
(68, 'PROFETH', 'Professional Ethics', ''),
(5, 'PROGLOD', 'Programming Logic', ''),
(14, 'PURPCOM', 'Purposive Communication', ''),
(29, 'RIZLIFE', 'Rizal Life', ''),
(45, 'ROBPROA', 'Robotics Process Automation', ''),
(62, 'SCITECS', 'Science and Technology', ''),
(59, 'TECH101', 'Tech 101 something', ''),
(13, 'UNDSELF', 'Understanding the Self', '');

-- --------------------------------------------------------

--
-- Table structure for table `exam`
--

CREATE TABLE `exam` (
  `exam_id` int(11) NOT NULL,
  `exam_name` varchar(255) NOT NULL,
  `course_topic_id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exam`
--

INSERT INTO `exam` (`exam_id`, `exam_name`, `course_topic_id`, `date_created`) VALUES
(54, 'Exam for Course Topic 1', 96, '2024-05-25 08:54:09'),
(55, 'Exam for Test Course Topic 1', 97, '2024-05-25 08:56:29'),
(56, 'Exam for Test Exam', 98, '2024-05-26 04:01:06'),
(57, 'Exam for Test Course Topic 2', 99, '2024-05-27 20:12:15'),
(58, 'Exam for asdfasdf', 100, '2024-05-27 20:12:43');

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
(114, 17, 'MCROLAB', 'Computer Engineering');

-- --------------------------------------------------------

--
-- Table structure for table `prof_course_topic`
--

CREATE TABLE `prof_course_topic` (
  `course_topic_id` int(10) NOT NULL,
  `course_subject_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `course_topics` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `difficulty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prof_course_topic`
--

INSERT INTO `prof_course_topic` (`course_topic_id`, `course_subject_id`, `account_id`, `course_topics`, `date_created`, `difficulty`) VALUES
(97, 104, 17, 'Test Course Topic 1', '2024-05-25 16:56:29', 2),
(98, 105, 17, 'Test Exam', '2024-05-26 12:01:06', 2);

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
  `answer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`question_id`, `exam_id`, `question_text`, `question_image`, `clo_id`, `difficulty`, `question_points`, `date_created`, `answer_id`) VALUES
(771, 55, 'Which famous play features a character named Romeo?', NULL, '2', 'E', 10, '2024-05-31 11:26:00', 1),
(773, 55, 'What is the main ingredient in guacamole?', NULL, '1', 'E', 0, '2024-05-31 18:05:20', 2),
(775, 55, '', NULL, '1', 'E', 0, '2024-05-31 18:08:20', 3),
(776, 55, '', NULL, '1', 'E', 0, '2024-05-31 18:22:00', 4),
(777, 55, '', NULL, '1', 'E', 0, '2024-05-31 18:22:06', 5),
(778, 55, '', NULL, '1', 'E', 0, '2024-05-31 18:22:14', 6),
(779, 55, '', NULL, '1', 'E', 0, '2024-05-31 18:22:19', 7),
(780, 55, '', NULL, '1', 'E', 0, '2024-05-31 18:22:25', 8),
(781, 55, '', NULL, '1', 'E', 0, '2024-05-31 18:22:31', 9),
(782, 55, '', NULL, '1', 'E', 0, '2024-05-31 18:22:36', 10),
(783, 55, '', NULL, '1', 'E', 0, '2024-05-31 18:22:47', 11),
(784, 55, '', NULL, '1', 'E', 0, '2024-05-31 18:23:13', 12),
(785, 55, '', NULL, '1', 'E', 0, '2024-05-31 18:23:20', 13),
(786, 55, '', NULL, '1', 'E', 0, '2024-05-31 18:23:31', 14),
(787, 55, '', NULL, '1', 'E', 0, '2024-05-31 18:23:46', 15),
(788, 55, '', NULL, '1', 'E', 0, '2024-05-31 18:23:53', 16),
(789, 55, 'Question 17', NULL, '1', 'E', 0, '2024-05-31 19:32:07', 17),
(790, 55, 'Question 18', NULL, '1', 'E', 0, '2024-05-31 19:32:21', 18),
(791, 55, 'Question 19', NULL, '1', 'E', 0, '2024-05-31 19:32:34', 19),
(792, 55, 'Question 20', NULL, '1', 'E', 0, '2024-05-31 19:32:46', 20),
(793, 55, 'Question 21', NULL, '1', 'E', 0, '2024-05-31 19:32:56', 21),
(794, 55, 'Question 22', NULL, '1', 'E', 0, '2024-05-31 19:33:05', 22),
(795, 55, 'Question 23', NULL, '1', 'E', 0, '2024-05-31 19:33:13', 23),
(796, 55, 'Question 24', NULL, '1', 'E', 0, '2024-05-31 19:33:26', 24),
(797, 55, 'Question 25', NULL, '1', 'E', 0, '2024-05-31 19:33:41', 25),
(798, 55, 'Question 26', NULL, '1', 'E', 0, '2024-05-31 19:33:51', 26),
(799, 55, 'Question 27', NULL, '1', 'E', 0, '2024-05-31 19:33:59', 27),
(800, 55, 'Question 28', NULL, '1', 'E', 0, '2024-05-31 19:34:13', 28),
(801, 55, 'Question 29', NULL, '1', 'E', 0, '2024-05-31 19:34:42', 29),
(802, 55, 'Question 30', NULL, '1', 'E', 0, '2024-05-31 19:34:54', 30);

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
(1461, 'Romeo and Juliet', NULL, 1, 'A', 1),
(1462, 'William', NULL, 0, 'B', 1),
(1465, 'Avocado', NULL, 1, 'A', 2),
(1466, 'B', NULL, 0, 'B', 2),
(1467, 'C', NULL, 0, 'C', 2),
(1468, 'D', NULL, 0, 'D', 2),
(1471, 'A', NULL, 1, 'A', 3),
(1472, 'B', NULL, 0, 'B', 3),
(1473, 'C', NULL, 0, 'C', 3),
(1474, 'A', NULL, 1, 'A', 4),
(1475, 'B', NULL, 0, 'B', 4),
(1476, 'A', NULL, 1, 'A', 5),
(1477, 'B', NULL, 0, 'B', 5),
(1478, 'A', NULL, 1, 'A', 6),
(1479, 'B', NULL, 0, 'B', 6),
(1480, 'A', NULL, 1, 'A', 7),
(1481, 'B', NULL, 0, 'B', 7),
(1482, 'A', NULL, 1, 'A', 8),
(1483, 'B', NULL, 0, 'B', 8),
(1484, 'A', NULL, 1, 'A', 9),
(1485, 'B', NULL, 0, 'B', 9),
(1486, 'A', NULL, 1, 'A', 10),
(1487, 'B', NULL, 0, 'B', 10),
(1488, 'A', NULL, 0, 'A', 11),
(1489, 'B', NULL, 0, 'B', 11),
(1490, 'C', NULL, 1, 'C', 11),
(1491, 'A', NULL, 1, 'A', 12),
(1492, 'B', NULL, 0, 'B', 12),
(1493, 'A', NULL, 1, 'A', 13),
(1494, 'B', NULL, 0, 'B', 13),
(1495, 'A', NULL, 1, 'A', 14),
(1496, 'B', NULL, 1, 'B', 14),
(1497, 'C', NULL, 1, 'C', 14),
(1498, 'D', NULL, 0, 'D', 14),
(1499, 'E', NULL, 0, 'E', 14),
(1500, 'A', NULL, 1, 'A', 15),
(1501, 'B', NULL, 0, 'B', 15),
(1502, 'C', NULL, 0, 'C', 15),
(1503, 'D', NULL, 0, 'D', 15),
(1504, 'A', NULL, 1, 'A', 16),
(1505, 'B', NULL, 0, 'B', 16),
(1506, 'A', NULL, 1, 'A', 17),
(1507, 'B', NULL, 0, 'B', 17),
(1508, 'A', NULL, 1, 'A', 18),
(1509, 'B', NULL, 0, 'B', 18),
(1510, 'A', NULL, 1, 'A', 19),
(1511, 'B', NULL, 0, 'B', 19),
(1512, 'A', NULL, 1, 'A', 20),
(1513, 'B', NULL, 0, 'B', 20),
(1514, 'A', NULL, 1, 'A', 21),
(1515, 'B', NULL, 0, 'B', 21),
(1516, 'A', NULL, 1, 'A', 22),
(1517, 'B', NULL, 0, 'B', 22),
(1518, 'A', NULL, 1, 'A', 23),
(1519, 'B', NULL, 0, 'B', 23),
(1520, 'A', NULL, 1, 'A', 24),
(1521, 'B', NULL, 0, 'B', 24),
(1522, 'A', NULL, 1, 'A', 25),
(1523, 'B', NULL, 0, 'B', 25),
(1524, 'A', NULL, 1, 'A', 26),
(1525, 'B', NULL, 0, 'B', 26),
(1526, 'A', NULL, 1, 'A', 27),
(1527, 'B', NULL, 0, 'B', 27),
(1528, 'A', NULL, 1, 'A', 28),
(1529, 'B', NULL, 0, 'B', 28),
(1530, 'A', NULL, 1, 'A', 29),
(1531, 'B', NULL, 0, 'B', 29),
(1532, 'A', NULL, 1, 'A', 30),
(1533, 'B', NULL, 0, 'B', 30);

-- --------------------------------------------------------

--
-- Table structure for table `question_library`
--

CREATE TABLE `question_library` (
  `answer_id` int(11) DEFAULT NULL,
  `question_text` varchar(255) DEFAULT NULL,
  `question_image` longblob DEFAULT NULL,
  `clo_id` varchar(255) DEFAULT NULL,
  `difficulty` char(1) DEFAULT NULL,
  `question_points` int(11) DEFAULT NULL,
  `course_subject_id` int(10) NOT NULL,
  `question_library_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  ADD UNIQUE KEY `clo_number` (`clo_number`);

--
-- Indexes for table `course_syllabus`
--
ALTER TABLE `course_syllabus`
  ADD PRIMARY KEY (`course_code`),
  ADD KEY `clo_id` (`clo_id`),
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
-- Indexes for table `question_library`
--
ALTER TABLE `question_library`
  ADD PRIMARY KEY (`question_library_id`);

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
  MODIFY `exam_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notification_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prof_course_subject`
--
ALTER TABLE `prof_course_subject`
  MODIFY `course_subject_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `prof_course_topic`
--
ALTER TABLE `prof_course_topic`
  MODIFY `course_topic_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `program_name`
--
ALTER TABLE `program_name`
  MODIFY `program_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=804;

--
-- AUTO_INCREMENT for table `question_choices`
--
ALTER TABLE `question_choices`
  MODIFY `question_choices_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1536;

--
-- AUTO_INCREMENT for table `question_library`
--
ALTER TABLE `question_library`
  MODIFY `question_library_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

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
