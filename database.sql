-- ============================================================
-- Student Enrollment System - Database Schema
-- City College of Calamba | Group 1 | Midterm Output
-- ============================================================
DROP TABLE IF EXISTS enrollments;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS students;

-- TABLE: students

CREATE TABLE students (
    student_id   SERIAL PRIMARY KEY,
    first_name   VARCHAR(100) NOT NULL,
    last_name    VARCHAR(100) NOT NULL,
    email        VARCHAR(150) UNIQUE NOT NULL,
    birthdate    DATE,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- TABLE: courses

CREATE TABLE courses (
    course_id    SERIAL PRIMARY KEY,
    course_code  VARCHAR(20) UNIQUE NOT NULL,
    course_name  VARCHAR(200) NOT NULL,
    units        INT NOT NULL DEFAULT 3,
    description  TEXT,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- TABLE: enrollments

CREATE TABLE enrollments (
    enrollment_id  SERIAL PRIMARY KEY,
    student_id     INT NOT NULL REFERENCES students(student_id) ON DELETE CASCADE,
    course_id      INT NOT NULL REFERENCES courses(course_id) ON DELETE CASCADE,
    semester       VARCHAR(20) NOT NULL,  -- e.g. '1st', '2nd', 'Summer'
    school_year    VARCHAR(10) NOT NULL,  -- e.g. '2025-2026'
    grade          DECIMAL(4,2),
    enrolled_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(student_id, course_id, semester, school_year)
);


-- SAMPLE DATA
-- Students

INSERT INTO students (first_name, last_name, email, birthdate) VALUES
('Lebron',    'Catubao',  'lgcatubao@ccc.edu.ph',   '2006-05-08'),
('Christian',   'Ricamara',     'ccricamara@ccc.edu.ph',    '2005-05-10'),
('Barcelona',   'Jhomel',      'jabarcelona@ccc.edu.ph',     '2005-7-7'),
('Jericho',     'Monter',     'jcmonter@ccc.edu.ph',      '2003-01-30'),
('Aaron',   'Maligaya',    'mmmaligaya@ccc.edu.ph',   '2005-04-11'),
('Liza',    'Torres',     'ltorres@ccc.edu.ph',     '2002-06-18'),
('Jose',    'Villanueva', 'jvillanueva@ccc.edu.ph', '2003-09-27'),
('Rosa',    'Fernandez',  'rfernandez@ccc.edu.ph',  '2001-12-03');

-- Courses

INSERT INTO courses (course_code, course_name, units, description) VALUES
('CC101', 'Introduction to Computing',         3, 'Fundamentals of computers and information technology.'),
('CC102', 'Computer Programming 1',            3, 'Basic programming concepts using Python.'),
('CC103', 'Computer Programming 2',            3, 'Object-oriented programming using Java.'),
('CC201', 'Data Structures and Algorithms',    3, 'Linear and non-linear data structures.'),
('CC202', 'Database Management Systems',       3, 'Relational databases and SQL.'),
('CC203', 'Web Development',                   3, 'HTML, CSS, JavaScript, and PHP.'),
('MATH01', 'Mathematics in the Modern World',  3, 'Applied mathematics for computing students.'),
('GEC01',  'Understanding the Self',           3, 'Personal development and identity.');

-- Enrollments

INSERT INTO enrollments (student_id, course_id, semester, school_year, grade) VALUES
-- 1st Semester 2025-2026
(1, 1, '1st', '2025-2026', 1.00),
(1, 2, '1st', '2025-2026', 1.75),
(1, 7, '1st', '2025-2026', 2.00),
(2, 1, '1st', '2025-2026', 1.25),
(2, 2, '1st', '2025-2026', 1.50),
(2, 8, '1st', '2025-2026', 1.00),
(3, 1, '1st', '2025-2026', 2.25),
(3, 7, '1st', '2025-2026', 2.50),
(4, 1, '1st', '2025-2026', 1.75),
(4, 2, '1st', '2025-2026', 2.00),
(5, 1, '1st', '2025-2026', 1.50),
(5, 8, '1st', '2025-2026', 1.25),
-- 2nd Semester 2025-2026
(1, 3, '2nd', '2025-2026', NULL),
(1, 4, '2nd', '2025-2026', NULL),
(2, 3, '2nd', '2025-2026', NULL),
(2, 5, '2nd', '2025-2026', NULL),
(3, 3, '2nd', '2025-2026', NULL),
(4, 3, '2nd', '2025-2026', NULL),
(6, 1, '2nd', '2025-2026', NULL),
(6, 2, '2nd', '2025-2026', NULL),
(7, 4, '2nd', '2025-2026', NULL),
(7, 5, '2nd', '2025-2026', NULL),
(8, 6, '2nd', '2025-2026', NULL),
(8, 8, '2nd', '2025-2026', NULL),
-- Summer 2025-2026
(3, 8, 'Summer', '2025-2026', 1.75),
(5, 7, 'Summer', '2025-2026', 2.25);
