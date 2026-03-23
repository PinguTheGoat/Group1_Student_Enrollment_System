<?php

class Enrollment {

    private $conn;
    private $table = "enrollments";

    public function __construct($db){
        $this->conn = $db;
    }

    private function syncIdSequence(){

        $this->conn->exec(
            "SELECT setval(
                pg_get_serial_sequence('enrollments', 'enrollment_id'),
                COALESCE((SELECT MAX(enrollment_id) FROM enrollments), 0) + 1,
                false
            )"
        );
    }

    /* =====================
       READ ALL
    ===================== */

    public function getAll(){

        $stmt = $this->conn->query(
            "SELECT e.*,
                    s.first_name, s.last_name,
                    c.course_code, c.course_name
             FROM enrollments e
             JOIN students   s ON s.student_id = e.student_id
             JOIN courses    c ON c.course_id   = e.course_id
             ORDER BY e.enrollment_id"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =====================
       READ SINGLE
    ===================== */

    public function getById($id){

        $stmt = $this->conn->prepare(
            "SELECT e.*,
                    s.first_name, s.last_name,
                    c.course_code, c.course_name
             FROM enrollments e
             JOIN students   s ON s.student_id = e.student_id
             JOIN courses    c ON c.course_id   = e.course_id
             WHERE e.enrollment_id = ?"
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
            "INSERT INTO {$this->table} (student_id, course_id, semester, school_year, grade)
             VALUES (?, ?, ?, ?, ?)
             RETURNING *"
        );

        $stmt->execute([
            $data['student_id'],
            $data['course_id'],
            $data['semester'],
            $data['school_year'],
            $data['grade'] ?? null
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =====================
       UPDATE
    ===================== */

    public function update($id, $data){

        $stmt = $this->conn->prepare(
            "UPDATE {$this->table}
             SET student_id = ?, course_id = ?, semester = ?, school_year = ?, grade = ?
             WHERE enrollment_id = ?
             RETURNING *"
        );

        $stmt->execute([
            $data['student_id'],
            $data['course_id'],
            $data['semester'],
            $data['school_year'],
            $data['grade'] ?? null,
            $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =====================
       DELETE
    ===================== */

    public function delete($id){

        $stmt = $this->conn->prepare(
            "DELETE FROM {$this->table} WHERE enrollment_id = ?"
        );

        $stmt->execute([$id]);

        return $stmt->rowCount() > 0;
    }

    /* =====================
       ENROLLMENTS PER SEMESTER (analytics)
    ===================== */

    public function getPerSemester(){

        $stmt = $this->conn->query(
            "SELECT school_year, semester,
                    COUNT(*) AS enrollment_count
             FROM {$this->table}
             GROUP BY school_year, semester
             ORDER BY school_year, semester"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
