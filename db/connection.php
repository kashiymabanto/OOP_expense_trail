<?php
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "expense";
    private $con;

    public function __construct() {
        $this->con = $this->connect();
    }

    private function connect() {
        $con = new mysqli($this->host, $this->username, $this->password, $this->dbname);
        if ($con->connect_error) {
            die("Connection failed: " . $con->connect_error);
        }
        return $con;
    }

    public function getConnection() {
        return $this->con;
    }
}
?>
