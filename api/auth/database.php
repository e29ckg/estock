<?php
$key = "8cea895549b19eb150b451a6ad6061a5";

// used to get mysql database connection
class DatabaseService{

    private $db_host = "localhost";
    private $db_name = "estock";
    private $db_user = "root";
    private $db_password = "";
    private $connection;

    public function getConnection(){

        $this->connection = null;

        try{
            $this->connection = new PDO("mysql:host=" . $this->db_host . ";dbname=" . $this->db_name, $this->db_user, $this->db_password);
            $this->connection->exec("set names utf8");
        }catch(PDOException $exception){
            http_response_code(401);

            echo json_encode(array(
                "status"=>"error",
                "message" => $exception->getMessage()
            ));
            die();
        }

        return $this->connection;
    }
}
?>