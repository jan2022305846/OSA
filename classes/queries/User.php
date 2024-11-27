<?php
class UserQueries
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function getUserByStudentId($student_id)
    {
        $query = '
            SELECT s.student_id, s.first_name, s.last_name, s.email, sc.password
            FROM Students s
            JOIN StudentCredentials sc ON s.student_id = sc.student_id
            WHERE s.student_id = ? LIMIT 1';
        $stmt = $this->conn->prepare($query);
    
        if ($stmt) {
            $stmt->bind_param('s', $student_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } else {
            throw new Exception('Query preparation failed: ' . $this->conn->error);
        }
    }
    
    public function createUser($student_id, $first_name, $last_name, $mobile_num, $email, $hashed_password, $program, $section)
    {
        $this->conn->begin_transaction(); // Start a transaction for consistency
    
        try {
            // Fetch the department ID based on the program name
            $query1 = 'SELECT dept_id FROM Department WHERE name = ? LIMIT 1';
            $stmt1 = $this->conn->prepare($query1);
            $stmt1->bind_param('s', $program);
            $stmt1->execute();
            $result = $stmt1->get_result();
            $dept = $result->fetch_assoc();
    
            if (!$dept) {
                throw new Exception('Invalid program. Department not found.');
            }
    
            $dept_id = $dept['dept_id'];
    
            // Insert into Students table
            $query2 = 'INSERT INTO Students (student_id, first_name, last_name, email, section, c_num, dept_id)
                        VALUES (?, ?, ?, ?, ?, ?, ?)';
            $stmt2 = $this->conn->prepare($query2);
    
            // Use the actual mobile number for the c_num column
            $stmt2->bind_param('ssssssi', $student_id, $first_name, $last_name, $email, $section, $mobile_num, $dept_id);
            $stmt2->execute();
    
            // Insert into StudentCredentials table
            $query3 = 'INSERT INTO StudentCredentials (student_id, password)
                        VALUES (?, ?)';
            $stmt3 = $this->conn->prepare($query3);
            $stmt3->bind_param('ss', $student_id, $hashed_password);
            $stmt3->execute();
    
            $this->conn->commit(); // Commit the transaction
            return true;
        } catch (Exception $e) {
            $this->conn->rollback(); // Rollback the transaction on error
            throw $e;
        }
    }
    
    public function updateSettings($student_id, $first_name, $last_name, $email, $section, $c_num, $department_id, $password = '') {
        $this->conn->begin_transaction(); // Start a transaction for consistency
    
        try {
            // Update student information
            $query1 = 'UPDATE Students SET first_name = ?, last_name = ?, email = ?, section = ?, c_num = ?, dept_id = ? WHERE student_id = ?';
            $stmt1 = $this->conn->prepare($query1);
            $stmt1->bind_param('sssssss', $first_name, $last_name, $email, $section, $c_num, $department_id, $student_id);
            $stmt1->execute();
    
            // Update the password if provided
            if (!empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $query2 = 'UPDATE StudentCredentials SET password = ? WHERE student_id = ?';
                $stmt2 = $this->conn->prepare($query2);
                $stmt2->bind_param('ss', $hashed_password, $student_id);
                $stmt2->execute();
            }
    
            $this->conn->commit(); // Commit the transaction
            return true;
        } catch (Exception $e) {
            $this->conn->rollback(); // Rollback the transaction on error
            throw $e;
        }
    }
    
    public function getDepartments() {
        $query = 'SELECT dept_id, name FROM department';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $departments = [];
        while ($row = $result->fetch_assoc()) {
            $departments[] = $row;
        }
    
        return $departments;
    }
    
}
?>