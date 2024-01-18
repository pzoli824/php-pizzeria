<?php 

class Database {

    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $connection = null;

    public function _construct() {}

    protected function getConnection()
    {
        try {
            $this->connection = new PDO("mysql:host=$this->servername;dbname=pizzeria", $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->connection;
          } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
          }
    }

    protected function closeConnection() {
        $this->connection = null;
    }
}

interface CrudOperations {
    public function getAll();
    public function get($id);
    public function update($dto);
    public function delete($id);
    public function create($dto);
  }

?>