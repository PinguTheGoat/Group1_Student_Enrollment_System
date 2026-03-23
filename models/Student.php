<?php

class Student {

    private $conn;
    private $table = "students";

    public function __construct($db){
        $this->conn = $db;
    }

    private function syncIdSequence(){

        $this->conn->exec(
            "SELECT setval(
                pg_get_serial_sequence('students', 'student_id'),
                COALESCE((SELECT MAX(student_id) FROM students), 0) + 1,
                false
            )"
        );
    }

    /* =====================
       READ ALL
    ===================== */

    public function getAll(){

        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table} ORDER BY student_id"
        );

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =====================
       READ SINGLE
    ===================== */

    public function getById($id){

        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table} WHERE student_id = ?"
        );

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =====================
       CREATE
    ===================== */

    public function create($data){

        $this->syncIdSequence();

        $stmt = $this->conn->prepare(
            "INSERT INTO {$this->table} (first_name, last_name, email, birthdate)
             VALUES (?, ?, ?, ?)
             RETURNING *"
        );

        $stmt->execute([
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['birthdate'] ?? null
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =====================
       UPDATE
    ===================== */

    public function update($id, $data){

        $stmt = $this->conn->prepare(
            "UPDATE {$this->table}
             SET first_name = ?, last_name = ?, email = ?, birthdate = ?
             WHERE student_id = ?
             RETURNING *"
        );

        $stmt->execute([
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['birthdate'] ?? null,
            $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =====================
       DELETE
    ===================== */

    public function delete($id){

        $stmt = $this->conn->prepare(
            "DELETE FROM {$this->table} WHERE student_id = ?"
        );

        $stmt->execute([$id]);

        return $stmt->rowCount() > 0;
    }

    /* =====================
       GET COURSES (relationship)
    ===================== */

    public function getCourses($id){

        $stmt = $this->conn->prepare(
            "SELECT c.course_id, c.course_code, c.course_name, c.units,
                    e.semester, e.school_year, e.grade, e.enrolled_at
             FROM enrollments e
             JOIN courses c ON c.course_id = e.course_id
             WHERE e.student_id = ?
             ORDER BY e.school_year, e.semester"
        );

        $stmt->execute([$id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =====================
       TOTAL COUNT (analytics)
    ===================== */

    public function getTotalCount(){
        return (int) $this->conn->query("SELECT COUNT(*) FROM {$this->table}")->fetchColumn();
    }

}
