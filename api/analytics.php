<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if($_SERVER['REQUEST_METHOD'] === 'OPTIONS'){
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Enrollment.php';

$database = new Database();
$db = $database->connect();

$student = new Student($db);
$course = new Course($db);
$enrollment = new Enrollment($db);
$method = $_SERVER['REQUEST_METHOD'];
$metric = $_GET['metric'] ?? '';

function respond($data, $code = 200){
    http_response_code($code);
    echo json_encode($data);
    exit;
}

if($method !== "GET"){
    respond(["error" => "Method not allowed"], 405);
}

if($metric === 'total-students'){
    respond(["total_students" => $student->getTotalCount()]);
}

if($metric === 'students-per-course'){
    respond(["data" => $course->getStudentsPerCourse()]);
}

if($metric === 'enrollments-per-semester'){
    respond(["data" => $enrollment->getPerSemester()]);
}

respond([
    "error" => "Unknown analytics metric",
    "available" => [
        "total-students",
        "students-per-course",
        "enrollments-per-semester"
    ]
], 404);
