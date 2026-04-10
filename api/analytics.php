<?php

header("Content-Type: application/json");

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../models/Student.php';
require __DIR__ . '/../models/Course.php';
require __DIR__ . '/../models/Enrollment.php';

$database = new Database();
$db = $database->connect();

$student = new Student($db);
$course = new Course($db);
$enrollment = new Enrollment($db);

$method = $_SERVER['REQUEST_METHOD'];
$metric = $_GET['metric'] ?? '';

if($method === 'GET'){

    if($metric === 'total-students'){
        echo json_encode([
            "total_students" => $student->getTotalCount()
        ]);
    } elseif($metric === 'students-per-course'){
        echo json_encode([
            "data" => $course->getStudentsPerCourse()
        ]);
    } elseif($metric === 'enrollments-per-semester'){
        echo json_encode([
            "data" => $enrollment->getPerSemester()
        ]);
    } else {
        echo json_encode([
            "error" => "Unknown analytics metric",
            "available" => [
                "total-students",
                "students-per-course",
                "enrollments-per-semester"
            ]
        ]);
    }

}
