<?php

class Course {

    private $conn;
    private $table = "courses";

    public function __construct($db){
        $this->conn = $db;
    }

    private function syncIdSequence(){

        $this->conn->exec(
            "SELECT setval(
                pg_get_serial_sequence('courses', 'course_id'),
                COALESCE((SELECT MAX(course_id) FROM courses), 0) + 1,
                false
            )"
        );
    }

    /* =====================
       READ ALL
    ===================== */

    public function getAll(){

        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table} ORDER BY course_id"
        );

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =====================
       READ SINGLE
    ===================== */

    public function getById($id){

        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table} WHERE course_id = ?"
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
            "INSERT INTO {$this->table} (course_code, course_name, units, description)
             VALUES (?, ?, ?, ?)
             RETURNING *"
        );

        $stmt->execute([
            $data['course_code'],
            $data['course_name'],
            $data['units'] ?? 3,
            $data['description'] ?? null
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =====================
       UPDATE
    ===================== */

    public function update($id, $data){

        $stmt = $this->conn->prepare(
            "UPDATE {$this->table}
             SET course_code = ?, course_name = ?, units = ?, description = ?
             WHERE course_id = ?
             RETURNING *"
        );

        $stmt->execute([
            $data['course_code'],
            $data['course_name'],
            $data['units'] ?? 3,
            $data['description'] ?? null,
            $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =====================
       DELETE
    ===================== */

    public function delete($id){

        $stmt = $this->conn->prepare(
            "DELETE FROM {$this->table} WHERE course_id = ?"
        );

        $stmt->execute([$id]);

        return $stmt->rowCount() > 0;
    }

    /* =====================
       GET STUDENTS (relationship)
    ===================== */

    public function getStudents($id){

        $stmt = $this->conn->prepare(
            "SELECT s.student_id, s.first_name, s.last_name, s.email,
                    e.semester, e.school_year, e.grade
             FROM enrollments e
             JOIN students s ON s.student_id = e.student_id
             WHERE e.course_id = ?
             ORDER BY e.school_year, s.last_name"
        );

        $stmt->execute([$id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =====================
       STUDENTS PER COURSE (analytics)
    ===================== */

    public function getStudentsPerCourse(){

        $stmt = $this->conn->query(
            "SELECT c.course_id, c.course_code, c.course_name,
                    COUNT(e.student_id) AS student_count
             FROM courses c
             LEFT JOIN enrollments e ON e.course_id = c.course_id
             GROUP BY c.course_id, c.course_code, c.course_name
             ORDER BY student_count DESC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
