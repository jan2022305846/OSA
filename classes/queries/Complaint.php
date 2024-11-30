<?php
class Complaint
{
    private $db;

    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }

    public function getComplaintStats($studentId)
    {
        $query = "
            SELECT 
                cs.status_name AS status, 
                COUNT(c.complaint_id) AS count 
            FROM 
                Complaints c
            JOIN 
                ComplaintStatus cs ON c.status_id = cs.status_id
            WHERE 
                c.student_id = ?
            GROUP BY 
                cs.status_name";

        $stmt = $this->db->prepare($query);
        if ($stmt) {
            $stmt->bind_param('i', $studentId);
            $stmt->execute();
            $result = $stmt->get_result();

            $stats = [];
            while ($row = $result->fetch_assoc()) {
                $stats[$row['status']] = $row['count'];
            }
            $stmt->close();

            return $stats;
        } else {
            die("Error preparing statement: " . $this->db->error);
        }
    }

    public function submitComplaint($studentId, $type, $description)
    {
        // Sanitize inputs
        $type = htmlspecialchars(strip_tags($type));
        $description = htmlspecialchars(strip_tags($description));

        // Fetch the default status_id for 'Pending'
        $queryStatus = "SELECT status_id FROM ComplaintStatus WHERE status_name = 'Pending' LIMIT 1";
        $result = $this->db->query($queryStatus);

        if ($result && $row = $result->fetch_assoc()) {
            $statusId = $row['status_id'];
        } else {
            throw new Exception("Default status 'Pending' not found in ComplaintStatus table.");
        }

        // Fetch the department ID for the student
        $queryDept = "SELECT dept_id FROM Students WHERE student_id = ? LIMIT 1";
        $stmtDept = $this->db->prepare($queryDept);
        if ($stmtDept) {
            $stmtDept->bind_param('i', $studentId);
            $stmtDept->execute();
            $resultDept = $stmtDept->get_result();

            if ($resultDept && $row = $resultDept->fetch_assoc()) {
                $deptId = $row['dept_id'];
            } else {
                $stmtDept->close();
                throw new Exception("Student with ID $studentId not found or does not have an associated department.");
            }
            $stmtDept->close();
        } else {
            throw new Exception("Error preparing department query: " . $this->db->error);
        }

        // Insert the complaint
        $queryInsert = "
            INSERT INTO Complaints (student_id, dept_id, type, description, status_id)
            VALUES (?, ?, ?, ?, ?)";

        $stmtInsert = $this->db->prepare($queryInsert);
        if ($stmtInsert) {
            $stmtInsert->bind_param('iissi', $studentId, $deptId, $type, $description, $statusId);

            // Debug values before executing
            var_dump($studentId, $deptId, $type, $description, $statusId);

            if ($stmtInsert->execute()) {
                echo "Insert successful!";
                $stmtInsert->close();
                return true;// Complaint submitted successfully
            } else {
                echo "Insert failed: " . $stmtInsert->error;
                $stmtInsert->close();
                return false;
            }
        } else {
            throw new Exception("Error preparing complaint insertion query: " . $this->db->error);
        }
    }

    public function getComplaintHistory($studentId)
    {
        $query = "
            SELECT 
                c.complaint_id AS id,
                c.type,
                c.description,
                cs.status_name AS status,
                c.date AS complaint_date,
                d.name AS department
            FROM 
                Complaints c
            JOIN 
                ComplaintStatus cs ON c.status_id = cs.status_id
            JOIN 
                Department d ON c.dept_id = d.dept_id
            WHERE 
                c.student_id = ?
            ORDER BY 
                c.date DESC";

        $stmt = $this->db->prepare($query);
        if ($stmt) {
            $stmt->bind_param('i', $studentId);
            $stmt->execute();
            $result = $stmt->get_result();

            $complaints = [];
            while ($row = $result->fetch_assoc()) {
                $complaints[] = $row;
            }
            $stmt->close();

            return $complaints;
        } else {
            throw new Exception("Error preparing statement: " . $this->db->error);
        }
    }

    public function deleteComplaint($complaintId) {
        // Validate the complaint ID
        if (empty($complaintId) || !is_numeric($complaintId)) {
            throw new InvalidArgumentException("Invalid complaint ID.");
        }

        // SQL query to delete the complaint
        $query = "DELETE FROM Complaints WHERE complaint_id = ?";

        // Prepare the statement
        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            throw new Exception("Failed to prepare the statement: " . $this->db->error);
        }

        // Bind the parameter to the statement
        $stmt->bind_param("i", $complaintId);

        // Execute the query
        if ($stmt->execute()) {
            // Check if a row was affected
            if ($stmt->affected_rows > 0) {
                $stmt->close();
                return true; // Deletion was successful
            } else {
                $stmt->close();
                return false; // No rows affected (e.g., complaint does not exist)
            }
        } else {
            // Handle execution failure
            $error = $stmt->error;
            $stmt->close();
            throw new Exception("Failed to execute statement: " . $error);
        }
    }

    public function updateComplaint($complaintId, $studentId, $type, $description)
    {
        // Sanitize inputs
        $type = htmlspecialchars(strip_tags($type));
        $description = htmlspecialchars(strip_tags($description));

        // Fetch the default status_id for 'Pending' (if necessary)
        $queryStatus = "SELECT status_id FROM ComplaintStatus WHERE status_name = 'Pending' LIMIT 1";
        $result = $this->db->query($queryStatus);

        if ($result && $row = $result->fetch_assoc()) {
            $statusId = $row['status_id'];
        } else {
            throw new Exception("Default status 'Pending' not found in ComplaintStatus table.");
        }

        // Update the complaint
        $queryUpdate = "
            UPDATE Complaints
            SET type = ?, description = ?, status_id = ?
            WHERE complaint_id = ? AND student_id = ?";

        $stmtUpdate = $this->db->prepare($queryUpdate);
        if ($stmtUpdate) {
            $stmtUpdate->bind_param('ssiii', $type, $description, $statusId, $complaintId, $studentId);

            // Debug values before executing
            var_dump($type, $description, $statusId, $complaintId, $studentId);

            if ($stmtUpdate->execute()) {
                $stmtUpdate->close();
                return true;  // Complaint updated successfully
            } else {
                $stmtUpdate->close();
                return false;  // Failed to update
            }
        } else {
            throw new Exception("Error preparing complaint update query: " . $this->db->error);
        }
    }

    public function getComplaintById($complaintId)
    {
        $query = "SELECT * FROM Complaints WHERE complaint_id = ? LIMIT 1";
        $stmt = $this->db->prepare($query);
        if ($stmt) {
            $stmt->bind_param('i', $complaintId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result && $row = $result->fetch_assoc()) {
                return $row;  // Return the complaint details
            } else {
                return null;  // Complaint not found
            }
        } else {
            throw new Exception("Error preparing complaint fetch query: " . $this->db->error);
        }
    }

    public function getComplaintNote($complaintId)
    {
        $query = "SELECT note FROM ComplaintNotes WHERE complaint_id = ? LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $complaintId);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc(); // Fetch the note
            }
        }
        return null; // Return null if no note is found
    }

    public function getAllComplaints() {
        $query = "
            SELECT 
                d.name AS department,
                SUM(CASE WHEN c.status_id = 1 THEN 1 ELSE 0 END) AS pending_complaints,
                SUM(CASE WHEN c.status_id = 2 THEN 1 ELSE 0 END) AS inprogress_complaints,
                SUM(CASE WHEN c.status_id = 3 THEN 1 ELSE 0 END) AS resolved_complaints
            FROM Department d
            LEFT JOIN Complaints c ON d.dept_id = c.dept_id
            GROUP BY d.name
        ";
    
        $result = $this->db->query($query);
    
        if ($result === false) {
            // Handle query failure
            die("Query Error: " . $this->db->error);
        }
    
        $complaintStats = [];
        while ($row = $result->fetch_assoc()) {
            $complaintStats[] = $row;
        }
    
        return $complaintStats;
    }       

    public function getDepartments() {
        $query = "SELECT dept_id, name FROM Department";
        $result = $this->db->query($query);
        $departments = [];
        while ($row = $result->fetch_assoc()) {
            $departments[] = $row;
        }
        return $departments;
    }

    public function getStatuses() {
        $query = "SELECT * FROM ComplaintStatus";
        $result = $this->db->query($query);
        $statuses = [];
        while ($row = $result->fetch_assoc()) {
            $statuses[] = $row;
        }
        return $statuses;
    }

    public function getFilteredComplaints($dept, $status) {
        // Base query
        $query = "
            SELECT c.complaint_id, c.type, c.description, d.name AS department, s.status_name
            FROM Complaints c
            JOIN Department d ON c.dept_id = d.dept_id
            JOIN ComplaintStatus s ON c.status_id = s.status_id
            WHERE 1=1";
    
        // Add filters to query
        $params = [];
        $types = '';
    
        if ($dept !== 'all') {
            $query .= " AND c.dept_id = ?";
            $params[] = $dept;
            $types .= 'i'; // 'i' for integer
        }
    
        if ($status !== 'all') {
            $query .= " AND s.status_name = ?";
            $params[] = $status;
            $types .= 's'; // 's' for string
        }
    
        // Prepare and execute the query
        $stmt = $this->db->prepare($query);
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
    
        // Fetch results
        $result = $stmt->get_result(); // Use get_result() to retrieve the result set
        $complaints = [];
        while ($row = $result->fetch_assoc()) { // Fetch rows one by one
            $complaints[] = $row;
        }
    
        // Close statement
        $stmt->close();
    
        return $complaints;
    }

    public function processComplaint($complaintId, $note, $statusId, $adminId) {
        // Update complaint status
        $updateStatusQuery = "UPDATE Complaints SET status_id = $statusId WHERE complaint_id = $complaintId";
        if (!$this->db->query($updateStatusQuery)) {
            return false; // Return false if the status update fails
        }
    
        // Check if a note already exists
        $checkNoteQuery = "SELECT note_id FROM ComplaintNotes WHERE complaint_id = $complaintId LIMIT 1";
        $result = $this->db->query($checkNoteQuery);
    
        if ($result && $result->num_rows > 0) {
            // Update existing note
            $updateNoteQuery = "UPDATE ComplaintNotes SET note = '$note' WHERE complaint_id = $complaintId";
            if (!$this->db->query($updateNoteQuery)) {
                return false; // Return false if the note update fails
            }
        } else {
            // Insert new note
            $insertNoteQuery = "INSERT INTO ComplaintNotes (complaint_id, admin_id, note) VALUES ($complaintId, $adminId, '$note')";
            if (!$this->db->query($insertNoteQuery)) {
                return false; // Return false if the note insertion fails
            }
        }
    
        return true; // Return true if all operations succeed
    }
    
}
