<?php
class AdminQueries
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function getAdminById($admin_id)
    {
        $query = '
            SELECT a.admin_id, a.first_name, a.last_name, a.email, ac.password
            FROM Admin a
            JOIN AdminCredentials ac ON a.admin_id = ac.admin_id
            WHERE a.admin_id = ? LIMIT 1';
        $stmt = $this->conn->prepare($query);
    
        if ($stmt) {
            $stmt->bind_param('s', $admin_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } else {
            throw new Exception('Query preparation failed: ' . $this->conn->error);
        }
    }
    
    public function updateSettings($admin_id, $first_name, $last_name, $email, $password = '') {
        $this->conn->begin_transaction(); // Start a transaction for consistency
    
        try {
            // Update student information
            $query1 = 'UPDATE Admin SET first_name = ?, last_name = ?, email = ?, section = ?, WHERE student_id = ?';
            $stmt1 = $this->conn->prepare($query1);
            $stmt1->bind_param('sssssss', $first_name, $last_name, $email, $admin_id);
            $stmt1->execute();
    
            // Update the password if provided
            if (!empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $query2 = 'UPDATE AdminCredentials SET password = ? WHERE admin_id = ?';
                $stmt2 = $this->conn->prepare($query2);
                $stmt2->bind_param('ss', $hashed_password, $admin_id);
                $stmt2->execute();
            }
    
            $this->conn->commit(); // Commit the transaction
            return true;
        } catch (Exception $e) {
            $this->conn->rollback(); // Rollback the transaction on error
            throw $e;
        }
    }
}
?>
