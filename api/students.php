<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if($_SERVER['REQUEST_METHOD'] === 'OPTIONS'){
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Student.php';

$database = new Database();
$db = $database->connect();
$student = new Student($db);
$method = $_SERVER['REQUEST_METHOD'];

$student_id = isset($_GET['student_id']) ? (int) $_GET['student_id'] : null;
$action = $_GET['action'] ?? '';
$body = json_decode(file_get_contents('php://input'), true) ?? [];

function respond($data, $code = 200){
    http_response_code($code);
    echo json_encode($data);
    exit;
}

switch($method){

case "GET":

    if($student_id && $action === 'courses'){
        $record = $student->getById($student_id);
        if(!$record){ respond(["error" => "Student not found"], 404); }

        respond([
            "student" => $record,
            "courses" => $student->getCourses($student_id)
        ]);
    }

    if($student_id){
        $data = $student->getById($student_id);
        $data ? respond($data) : respond(["error" => "Student not found"], 404);
    }

    respond($student->getAll());

break;

case "POST":

    $required = ['first_name', 'last_name', 'email'];
    foreach($required as $field){
        if(empty($body[$field])) respond(["error" => "Field '$field' is required"], 422);
    }

    respond($student->create($body), 201);

break;

case "PUT":

    if(!$student_id){ respond(["error" => "student_id is required"], 400); }

    $required = ['first_name', 'last_name', 'email'];
    foreach($required as $field){
        if(empty($body[$field])) respond(["error" => "Field '$field' is required"], 422);
    }

    $record = $student->update($student_id, $body);
    $record ? respond($record) : respond(["error" => "Student not found"], 404);

break;

case "DELETE":

    if(!$student_id){ respond(["error" => "student_id is required"], 400); }

    $deleted = $student->delete($student_id);
    $deleted ? respond(["message" => "Student deleted"]) : respond(["error" => "Student not found"], 404);

break;

default:
    respond(["error" => "Method not allowed"], 405);

}
