<?php
class Database {
    private $host = "localhost";
    private $username = "group2";
    private $password = "group2";
    private $database = "Library_web_db";
    public $conn;

    public function __construct() {
        $this->connectDB();
    }

    public function connectDB() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function query($sql) {
        return $this->conn->query($sql);
    }

    public function prepare($sql) {
        return $this->conn->prepare($sql);
    }
}

$database = new Database();
?>
