<?php

header("Content-Type: application/json");

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../models/Course.php';

$database = new Database();
$db = $database->connect();

$course = new Course($db);

$method = $_SERVER['REQUEST_METHOD'];

switch($method){

/* =====================
   READ
===================== */

case "GET":

    if(isset($_GET['course_id'])){

        $data = $course->getById($_GET['course_id']);

        echo json_encode($data);

    } else {

        $data = $course->getAll();

        echo json_encode($data);

    }

break;


/* =====================
   CREATE
===================== */

case "POST":

    $input = json_decode(file_get_contents("php://input"),true);

    $course->create($input);

    echo json_encode([
        "message"=>"Course created"
    ]);

break;


/* =====================
   UPDATE
===================== */

case "PUT":

    parse_str($_SERVER['QUERY_STRING'],$query);

    $input = json_decode(file_get_contents("php://input"),true);

    $course->update(
        $query['course_id'],
        $input
    );

    echo json_encode([
        "message"=>"Course updated"
    ]);

break;


/* =====================
   DELETE
===================== */

case "DELETE":

    parse_str($_SERVER['QUERY_STRING'],$query);

    $course->delete($query['course_id']);

    echo json_encode([
        "message"=>"Course deleted"
    ]);

break;

}
