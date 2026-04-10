<?php

header("Content-Type: application/json");

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../models/Student.php';

$database = new Database();
$db = $database->connect();

$student = new Student($db);

$method = $_SERVER['REQUEST_METHOD'];

switch($method){

/* =====================
   READ
===================== */

case "GET":

    if(isset($_GET['student_id'])){

        $data = $student->getById($_GET['student_id']);

        echo json_encode($data);

    } else {

        $data = $student->getAll();

        echo json_encode($data);

    }

break;


/* =====================
   CREATE
===================== */

case "POST":

    $input = json_decode(file_get_contents("php://input"),true);

    $student->create($input);

    echo json_encode([
        "message"=>"Student created"
    ]);

break;


/* =====================
   UPDATE
===================== */

case "PUT":

    parse_str($_SERVER['QUERY_STRING'],$query);

    $input = json_decode(file_get_contents("php://input"),true);

    $student->update(
        $query['student_id'],
        $input
    );

    echo json_encode([
        "message"=>"Student updated"
    ]);

break;


/* =====================
   DELETE
===================== */

case "DELETE":

    parse_str($_SERVER['QUERY_STRING'],$query);

    $student->delete($query['student_id']);

    echo json_encode([
        "message"=>"Student deleted"
    ]);

break;

}
