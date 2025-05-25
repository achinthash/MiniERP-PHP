<?php

$env = parse_ini_file(__DIR__ . '/../.env');

define('DB_HOST', $env['DB_HOST']);
define('DB_USERNAME', $env['DB_USERNAME']);
define('DB_PASSWORD', $env['DB_PASSWORD']);
define('DB_NAME', $env['DB_NAME']);


    class Database{
     
        private $host = DB_HOST;
        private $db_name = DB_NAME;
        private $username = DB_USERNAME;
        private $password = DB_PASSWORD;
        public $conn;
    

        public function connect(){
        
            $this->conn = null;

            try{
                $this->conn = new PDO("mysql:host=".$this->host . ";dbname=".$this->db_name, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // echo " Connection successful!";
            } catch(PDOEXCEPTION $e){   
                echo "Connection error: " . $e->getMessage();
            }
            return $this->conn;
        }
    
    }




    // $db = new Database();
    // $conn = $db->connect();
    
    // echo "<pre>";
    // var_dump($conn);
    // echo "</pre>";
    

?>