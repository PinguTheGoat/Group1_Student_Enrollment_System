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
require_once __DIR__ . '/../models/Course.php';

$database = new Database();
$db = $database->connect();
$course = new Course($db);
$method = $_SERVER['REQUEST_METHOD'];

$course_id = isset($_GET['course_id']) ? (int) $_GET['course_id'] : null;
$action = $_GET['action'] ?? '';
$body = json_decode(file_get_contents('php://input'), true) ?? [];

function respond($data, $code = 200){
    http_response_code($code);
    echo json_encode($data);
    exit;
}

switch($method){

case "GET":

    if($course_id && $action === 'students'){
        $record = $course->getById($course_id);
        if(!$record){ respond(["error" => "Course not found"], 404); }

        respond([
            "course" => $record,
            "students" => $course->getStudents($course_id)
        ]);
    }

    if($course_id){
        $data = $course->getById($course_id);
        $data ? respond($data) : respond(["error" => "Course not found"], 404);
    }

    respond($course->getAll());

break;

case "POST":

    $required = ['course_code', 'course_name'];
    foreach($required as $field){
        if(empty($body[$field])) respond(["error" => "Field '$field' is required"], 422);
    }

    respond($course->create($body), 201);

break;

case "PUT":

    if(!$course_id){ respond(["error" => "course_id is required"], 400); }

    $required = ['course_code', 'course_name'];
    foreach($required as $field){
        if(empty($body[$field])) respond(["error" => "Field '$field' is required"], 422);
    }

    $record = $course->update($course_id, $body);
    $record ? respond($record) : respond(["error" => "Course not found"], 404);

break;

case "DELETE":

    if(!$course_id){ respond(["error" => "course_id is required"], 400); }

    $deleted = $course->delete($course_id);
    $deleted ? respond(["message" => "Course deleted"]) : respond(["error" => "Course not found"], 404);

break;

default:
    respond(["error" => "Method not allowed"], 405);

}
