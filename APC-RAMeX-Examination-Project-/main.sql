--
-- File generated with SQLiteStudio v3.4.4 on Mon Mar 25 06:40:14 2024
--
-- Text encoding used: System
--
PRAGMA foreign_keys = off;
BEGIN TRANSACTION;

-- Table: account
DROP TABLE IF EXISTS account;
CREATE TABLE IF NOT EXISTS account (
account_id INTEGER PRIMARY KEY AUTOINCREMENT,
user_email VARCHAR UNIQUE,
password VARCHAR,
date_created INTEGER,
first_name TEXT,
last_name TEXT);
INSERT INTO account (account_id, user_email, password, date_created, first_name, last_name) VALUES (1, 'cjcruz@student.apc.edu.ph', 'password12345', '2024-02-23', 'Christian', 'Cruz');
INSERT INTO account (account_id, user_email, password, date_created, first_name, last_name) VALUES (2, 'amgomez2@student.apc.edu.ph', 'tite1234569', '2024-02-23', 'Aivan', 'Gomez');
INSERT INTO account (account_id, user_email, password, date_created, first_name, last_name) VALUES (3, 'hmanes@student.apc.edu.ph', 'gwaposikuya12345', '2024-02-23', 'Honniel', 'Manes');
INSERT INTO account (account_id, user_email, password, date_created, first_name, last_name) VALUES (4, 'amforneas3@student.apc.edu.ph', 'mobcappnatinanona12345', '2024-02-23', 'Aldrich', 'Forneas');
INSERT INTO account (account_id, user_email, password, date_created, first_name, last_name) VALUES (5, 'einsteiny@apc.edu.ph', 'tiny12345', '2024-02-23', 'Einstein', 'Yong');

-- Table: exam_library
DROP TABLE IF EXISTS exam_library;
CREATE TABLE IF NOT EXISTS exam_library (
exam_id INTEGER PRIMARY KEY AUTOINCREMENT,
exam_name TEXT,
question_id INTEGER,
folder_id INTEGER,
date_created INTERGER);
INSERT INTO exam_library (exam_id, exam_name, question_id, folder_id, date_created) VALUES (1, 'Clipper and Clamper', 1, 1, 2023);
INSERT INTO exam_library (exam_id, exam_name, question_id, folder_id, date_created) VALUES (2, 'Semiconductor Diode', 2, 1, 2023);
INSERT INTO exam_library (exam_id, exam_name, question_id, folder_id, date_created) VALUES (3, 'Diode Applications', 3, 1, 2023);
INSERT INTO exam_library (exam_id, exam_name, question_id, folder_id, date_created) VALUES (4, 'Special Purpose Diodes', 4, 1, 2024);
INSERT INTO exam_library (exam_id, exam_name, question_id, folder_id, date_created) VALUES (5, 'Midterm Exams', 5, 1, 2024);
INSERT INTO exam_library (exam_id, exam_name, question_id, folder_id, date_created) VALUES (6, 'BJT DC Analysis', 6, 1, 2024);
INSERT INTO exam_library (exam_id, exam_name, question_id, folder_id, date_created) VALUES (7, 'BJT Concepts and Principles', 7, 1, 2024);
INSERT INTO exam_library (exam_id, exam_name, question_id, folder_id, date_created) VALUES (8, 'FET Biasing', 8, 1, 2024);
INSERT INTO exam_library (exam_id, exam_name, question_id, folder_id, date_created) VALUES (9, 'FET Concepts', 9, 1, 2024);
INSERT INTO exam_library (exam_id, exam_name, question_id, folder_id, date_created) VALUES (10, 'Switching Circuits', 10, 1, 2024);
INSERT INTO exam_library (exam_id, exam_name, question_id, folder_id, date_created) VALUES (11, 'Final Exam', 11, 1, 2024);
INSERT INTO exam_library (exam_id, exam_name, question_id, folder_id, date_created) VALUES (12, 'Mathematical Investigation', 12, 2, 2023);
INSERT INTO exam_library (exam_id, exam_name, question_id, folder_id, date_created) VALUES (13, 'Mathematical Logic', 13, 2, 2023);
INSERT INTO exam_library (exam_id, exam_name, question_id, folder_id, date_created) VALUES (14, 'Quantifiers', 14, 2, 2023);
INSERT INTO exam_library (exam_id, exam_name, question_id, folder_id, date_created) VALUES (15, 'Data Management', 14, 2, 2023);

-- Table: exam_upload
DROP TABLE IF EXISTS exam_upload;
CREATE TABLE IF NOT EXISTS exam_upload (
upload_id INTEGER PRIMARY KEY AUTOINCREMENT,
filename TEXT,
exam_id INTEGER,
folder_id INTEGER,
upload_date INTERGER);
INSERT INTO exam_upload (upload_id, filename, exam_id, folder_id, upload_date) VALUES (1, 'Quiz 2', 2, 1, 2023);
INSERT INTO exam_upload (upload_id, filename, exam_id, folder_id, upload_date) VALUES (2, 'Quiz 4', 4, 1, 2023);
INSERT INTO exam_upload (upload_id, filename, exam_id, folder_id, upload_date) VALUES (3, 'Quiz 5', 5, 1, 2023);
INSERT INTO exam_upload (upload_id, filename, exam_id, folder_id, upload_date) VALUES (4, 'Quiz 1', 12, 2, 2023);
INSERT INTO exam_upload (upload_id, filename, exam_id, folder_id, upload_date) VALUES (5, 'Quiz 3', 13, 2, 2023);
INSERT INTO exam_upload (upload_id, filename, exam_id, folder_id, upload_date) VALUES (6, 'Quiz 4', 14, 2, 2023);

-- Table: notification
DROP TABLE IF EXISTS notification;
CREATE TABLE IF NOT EXISTS notification (
notification_id INTEGER PRIMARY KEY AUTOINCREMENT,
user_id INTEGER,
date INTEGER,
message TEXT);
INSERT INTO notification (notification_id, user_id, date, message) VALUES (1, 1, 2023, 'Professor Role has been Given');
INSERT INTO notification (notification_id, user_id, date, message) VALUES (2, 2, 2023, 'Professor Role has been Given');
INSERT INTO notification (notification_id, user_id, date, message) VALUES (3, 3, 2023, 'Professor Role has been Given');
INSERT INTO notification (notification_id, user_id, date, message) VALUES (4, 4, 2023, 'Professor Role has been Given');
INSERT INTO notification (notification_id, user_id, date, message) VALUES (5, 5, 2023, 'Professor Role has been Given');

-- Table: prof_course_folder
DROP TABLE IF EXISTS prof_course_folder;
CREATE TABLE IF NOT EXISTS prof_course_folder (
folder_id INTEGER PRIMARY KEY AUTOINCREMENT,
name TEXT,
user_id INTEGER,
course_subject_id INTEGER);
INSERT INTO prof_course_folder (folder_id, name, user_id, course_subject_id) VALUES (1, 'ELEXCKT', 1, 1);
INSERT INTO prof_course_folder (folder_id, name, user_id, course_subject_id) VALUES (2, 'MOBCAPP', 2, 2);
INSERT INTO prof_course_folder (folder_id, name, user_id, course_subject_id) VALUES (3, 'MATWORL', 3, 3);
INSERT INTO prof_course_folder (folder_id, name, user_id, course_subject_id) VALUES (4, 'MATWORL', 3, 3);
INSERT INTO prof_course_folder (folder_id, name, user_id, course_subject_id) VALUES (5, 'OPERSYST', 4, 4);
INSERT INTO prof_course_folder (folder_id, name, user_id, course_subject_id) VALUES (6, 'UNDSELF', 5, 5);

-- Table: prof_course_subject
DROP TABLE IF EXISTS prof_course_subject;
CREATE TABLE IF NOT EXISTS prof_course_subject (
course_subject_id INTEGER PRIMARY KEY AUTOINCREMENT,
course_subject TEXT,
course_syllabus_id INTEGER,
course_topic_id INTEGER);
INSERT INTO prof_course_subject (course_subject_id, course_subject, course_syllabus_id, course_topic_id) VALUES (1, 'Electronics Circuits', 1, 1);
INSERT INTO prof_course_subject (course_subject_id, course_subject, course_syllabus_id, course_topic_id) VALUES (2, 'Mobile Capstone Project', 2, 2);
INSERT INTO prof_course_subject (course_subject_id, course_subject, course_syllabus_id, course_topic_id) VALUES (3, 'Math World', 3, 3);
INSERT INTO prof_course_subject (course_subject_id, course_subject, course_syllabus_id, course_topic_id) VALUES (4, 'Operating Systems', 4, 4);
INSERT INTO prof_course_subject (course_subject_id, course_subject, course_syllabus_id, course_topic_id) VALUES (5, 'Understanding the Self', 5, 5);

-- Table: prof_course_topic
DROP TABLE IF EXISTS prof_course_topic;
CREATE TABLE IF NOT EXISTS prof_course_topic (course_topic_id INTEGER PRIMARY KEY AUTOINCREMENT, course_topics TEXT, date_created NUMERIC);
INSERT INTO prof_course_topic (course_topic_id, course_topics, date_created) VALUES (1, 'Clippers and Clampers', NULL);
INSERT INTO prof_course_topic (course_topic_id, course_topics, date_created) VALUES (2, 'Mathematical Logic', NULL);
INSERT INTO prof_course_topic (course_topic_id, course_topics, date_created) VALUES (3, NULL, 2023);

-- Table: program_list
DROP TABLE IF EXISTS program_list;
CREATE TABLE IF NOT EXISTS program_list (
program_id INTEGER PRIMARY KEY AUTOINCREMENT,
program_name TEXT,
course_id TEXT);
INSERT INTO program_list (program_id, program_name, course_id) VALUES (1, 'CPE', 'ELEXCRKT');
INSERT INTO program_list (program_id, program_name, course_id) VALUES (2, 'CPE', 'MOBCAPP');
INSERT INTO program_list (program_id, program_name, course_id) VALUES (3, 'CPE', 'ECONOMC');
INSERT INTO program_list (program_id, program_name, course_id) VALUES (4, 'CPE', 'DATAMGT');
INSERT INTO program_list (program_id, program_name, course_id) VALUES (5, 'CPE', 'CRKTLEC');

-- Table: question_choices
DROP TABLE IF EXISTS question_choices;
CREATE TABLE IF NOT EXISTS question_choices (answer_id INTEGER PRIMARY KEY AUTOINCREMENT, answer BLOB, choices BLOB);
INSERT INTO question_choices (answer_id, answer, choices) VALUES (1, '25', '10, 5, 25, 40');
INSERT INTO question_choices (answer_id, answer, choices) VALUES (2, 'AND', 'NAND, AND, NOR, XNOR');
INSERT INTO question_choices (answer_id, answer, choices) VALUES (3, 'Economics', 'Economics, Business, Capital, Market');

-- Table: question_library
DROP TABLE IF EXISTS question_library;
CREATE TABLE IF NOT EXISTS question_library (question_id INTEGER PRIMARY KEY AUTOINCREMENT, question TEXT, clo_id INTEGER, difficulty TEXT, question_points INTERGER, answer_id BLOB);
INSERT INTO question_library (question_id, question, clo_id, difficulty, question_points, answer_id) VALUES (1, 'Question 1', 1, 'Easy', 5, NULL);
INSERT INTO question_library (question_id, question, clo_id, difficulty, question_points, answer_id) VALUES (2, 'Question 2', 5, 'Medium', 10, NULL);
INSERT INTO question_library (question_id, question, clo_id, difficulty, question_points, answer_id) VALUES (3, 'Question 3', 2, 'Medium', 10, NULL);
INSERT INTO question_library (question_id, question, clo_id, difficulty, question_points, answer_id) VALUES (4, 'Question 4', 6, 'Easy', 5, NULL);
INSERT INTO question_library (question_id, question, clo_id, difficulty, question_points, answer_id) VALUES (5, 'Question 5', 3, 'Hard', 15, NULL);
INSERT INTO question_library (question_id, question, clo_id, difficulty, question_points, answer_id) VALUES (6, 'Question 6', 5, 'Hard', 15, NULL);

-- Table: user
DROP TABLE IF EXISTS user;
CREATE TABLE IF NOT EXISTS user (user_id INTEGER PRIMARY KEY AUTOINCREMENT, account_id INTEGER, role TEXT, role_request TEXT, program_id INTEGER);
INSERT INTO user (user_id, account_id, role, role_request, program_id) VALUES (1, 1, 'User', 'Professor', 5);
INSERT INTO user (user_id, account_id, role, role_request, program_id) VALUES (2, 2, 'User', 'Professor', 3);
INSERT INTO user (user_id, account_id, role, role_request, program_id) VALUES (3, 3, 'User', 'Professor', 1);
INSERT INTO user (user_id, account_id, role, role_request, program_id) VALUES (4, 4, 'User', 'Professor', 2);
INSERT INTO user (user_id, account_id, role, role_request, program_id) VALUES (5, 5, 'User', 'Professor', 4);

COMMIT TRANSACTION;
PRAGMA foreign_keys = on;
