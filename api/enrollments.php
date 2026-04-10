<?php

header("Content-Type: application/json");

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../models/Enrollment.php';

$database = new Database();
$db = $database->connect();

$enrollment = new Enrollment($db);

$method = $_SERVER['REQUEST_METHOD'];

switch($method){

/* =====================
   READ
===================== */

case "GET":

    if(isset($_GET['enrollment_id'])){

        $data = $enrollment->getById($_GET['enrollment_id']);

        echo json_encode($data);

    } else {

        $data = $enrollment->getAll();

        echo json_encode($data);

    }

break;


/* =====================
   CREATE
===================== */

case "POST":

    $input = json_decode(file_get_contents("php://input"),true);

    $enrollment->create($input);

    echo json_encode([
        "message"=>"Enrollment created"
    ]);

break;


/* =====================
   UPDATE
===================== */

case "PUT":

    parse_str($_SERVER['QUERY_STRING'],$query);

    $input = json_decode(file_get_contents("php://input"),true);

    $enrollment->update(
        $query['enrollment_id'],
        $input
    );

    echo json_encode([
        "message"=>"Enrollment updated"
    ]);

break;


/* =====================
   DELETE
===================== */

case "DELETE":

    parse_str($_SERVER['QUERY_STRING'],$query);

    $enrollment->delete($query['enrollment_id']);

    echo json_encode([
        "message"=>"Enrollment deleted"
    ]);

break;

}
