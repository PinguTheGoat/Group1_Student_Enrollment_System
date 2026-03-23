# Group 1 — Student Enrollment System API

**City College of Calamba | Department of Computing and Informatics | Midterm Output**

A RESTful API built with **PHP (OOP)** and **PostgreSQL** that manages students, courses, and their enrollments.

---

## Member Roles

| Member | Role |
|--------|------|
| Lebron Catubao      | Database Designer |
| Lebron Catubao      | Model Developer (PHP OOP) |
| Jericho Monter      | CRUD API Developer |
| Jhomel Barcelon     | Relationship API Developer |
| Christian Ricamara  | Data Analytics API Developer |
| Aaron Maligaya      | Documentation and Testing |

---
## Project Structure

- index.php (API info only)
- api/students.php
- api/courses.php
- api/enrollments.php
- api/analytics.php
- config/database.php
- models/Student.php
- models/Course.php
- models/Enrollment.php
- database.sql

## Setup

1. Create database:

```sql
CREATE DATABASE enrollment_db;
\c enrollment_db
\i database.sql
```

2. Update DB credentials in config/database.php.

3. Run with local server (example):

```bash
php -S localhost:8000
```

## Base URL

http://localhost:8000/Group1_Student_Enrollment_System

## Endpoints (No Routing)

### Students

- GET api/students.php
- GET api/students.php?student_id=1
- GET api/students.php?student_id=1&action=courses
- POST api/students.php
- PUT api/students.php?student_id=1
- DELETE api/students.php?student_id=1

POST/PUT body:

```json
{
  "first_name": "Juan",
  "last_name": "Dela Cruz",
  "email": "juan@example.com",
  "birthdate": "2003-04-15"
}
```

### Courses

- GET api/courses.php
- GET api/courses.php?course_id=1
- GET api/courses.php?course_id=1&action=students
- POST api/courses.php
- PUT api/courses.php?course_id=1
- DELETE api/courses.php?course_id=1

POST/PUT body:

```json
{
  "course_code": "CC301",
  "course_name": "Software Engineering",
  "units": 3,
  "description": "Software development lifecycle"
}
```

### Enrollments

- GET api/enrollments.php
- GET api/enrollments.php?enrollment_id=1
- POST api/enrollments.php
- PUT api/enrollments.php?enrollment_id=1
- DELETE api/enrollments.php?enrollment_id=1

POST/PUT body:

```json
{
  "student_id": 1,
  "course_id": 2,
  "semester": "1st",
  "school_year": "2024-2025",
  "grade": null
}
```

### Analytics

- GET api/analytics.php?metric=total-students
- GET api/analytics.php?metric=students-per-course
- GET api/analytics.php?metric=enrollments-per-semester
