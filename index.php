<?php

header("Content-Type: application/json");

echo json_encode([
    "message" => "Student Enrollment System API (no routing)",
    "endpoints" => [
        "api/students.php",
        "api/courses.php",
        "api/enrollments.php",
        "api/analytics.php"
    ],
    "examples" => [
        "GET api/students.php",
        "GET api/students.php?student_id=1",
        "GET api/students.php?student_id=1&action=courses",
        "GET api/courses.php?course_id=1&action=students",
        "GET api/analytics.php?metric=total-students"
    ]
]);
