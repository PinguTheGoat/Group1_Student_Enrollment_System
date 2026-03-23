<?php

class Database {

    private $host = "localhost";
    private $port = "5432";
    private $db_name = "enrollment_db";
    private $username = "postgres";
    private $password = "050806";
    public $conn;

    public function connect(){

        $this->conn = null;

        try{

            $this->conn = new PDO(
                "pgsql:host=". $this->host. ";port=". $this->port .";dbname=". $this->db_name,
                $this->username,
                $this->password
            );

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $e){

            echo "Connection Error: " . $e->getMessage();

        }

        return $this->conn;
    }

}
