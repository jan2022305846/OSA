<?php
require_once '../../classes/queries/User.php';
require_once '../../classes/queries/Admin.php';

class Auth
{
    private $userQueries;
    private $adminQueries;

    public function __construct($dbConnection)
    {
        $this->userQueries = new UserQueries($dbConnection);
        $this->adminQueries = new AdminQueries($dbConnection);
    }

    public function login($student_id, $password)
    {
        $user = $this->userQueries->getUserByStudentId($student_id);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['student_id'] = $user['student_id'];
            $_SESSION['first_name'] = $user['first_name'];
            return true;
        }
        return false;
    }
    
    
    public function adminLogin($admin_id, $password)
    {
        $admin = $this->adminQueries->getAdminById($admin_id);
    
        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['admin_id'];
            return true;
        }
    
        return false;
    }
    
    
    public function register($student_id, $first_name, $last_name, $mobile_num, $email, $password, $program, $section)
    {
        // Check if user already exists
        if ($this->userQueries->getUserByStudentId($student_id)) {
            return false; // Student ID already exists
        }
    
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
        // Create the user
        return $this->userQueries->createUser($student_id, $first_name, $last_name, $mobile_num, $email, $hashed_password, $program, $section);
    }
    
}
?>
