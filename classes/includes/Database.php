<?php
class Database
{
    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
    private $dbname = 'jerico';
    private $conn;

    public function getConnection()
    {
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->dbname);
    
        if ($this->conn->connect_error) {
            throw new Exception('Database connection failed: ' . $this->conn->connect_error);
        }
    
        return $this->conn;
    }
    
}
?>
