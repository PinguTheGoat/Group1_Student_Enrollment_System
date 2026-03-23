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
require_once __DIR__ . '/../models/Enrollment.php';

$database = new Database();
$db = $database->connect();
$enrollment = new Enrollment($db);
$method = $_SERVER['REQUEST_METHOD'];

$enrollment_id = isset($_GET['enrollment_id']) ? (int) $_GET['enrollment_id'] : null;
$body = json_decode(file_get_contents('php://input'), true) ?? [];

function respond($data, $code = 200){
    http_response_code($code);
    echo json_encode($data);
    exit;
}

switch($method){

case "GET":

    if($enrollment_id){
        $data = $enrollment->getById($enrollment_id);
        $data ? respond($data) : respond(["error" => "Enrollment not found"], 404);
    }

    respond($enrollment->getAll());

break;

case "POST":

    $required = ['student_id', 'course_id', 'semester', 'school_year'];
    foreach($required as $field){
        if(empty($body[$field])) respond(["error" => "Field '$field' is required"], 422);
    }

    try{
        respond($enrollment->create($body), 201);
    } catch(PDOException $e){
        if(str_contains(strtolower($e->getMessage()), 'unique')){
            respond(["error" => "Student is already enrolled in this course for the given semester."], 409);
        }
        throw $e;
    }

break;

case "PUT":

    if(!$enrollment_id){ respond(["error" => "enrollment_id is required"], 400); }

    $required = ['student_id', 'course_id', 'semester', 'school_year'];
    foreach($required as $field){
        if(empty($body[$field])) respond(["error" => "Field '$field' is required"], 422);
    }

    $record = $enrollment->update($enrollment_id, $body);
    $record ? respond($record) : respond(["error" => "Enrollment not found"], 404);

break;

case "DELETE":

    if(!$enrollment_id){ respond(["error" => "enrollment_id is required"], 400); }

    $deleted = $enrollment->delete($enrollment_id);
    $deleted ? respond(["message" => "Enrollment deleted"]) : respond(["error" => "Enrollment not found"], 404);

break;

default:
    respond(["error" => "Method not allowed"], 405);

}
