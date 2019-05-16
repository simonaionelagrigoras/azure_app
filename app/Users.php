<?php
/**
 * Created by PhpStorm.
 * User: Simona
 * Date: 28/02/2019
 * Time: 16:24
 */

class Users{

    protected function connect(){
        $host     = 'cc-azure-webapp-mysql.mysql.database.azure.com';
        $username = 'app_user@cc-azure-webapp-mysql';
        $password = 'app_us3r_pass';
        $db_name  = 'app';
        try {
            //Establishes the connection
            $conn = mysqli_init();
            mysqli_ssl_set($conn,null,null,"BaltimoreCyberTrustRoot.crt.pem",NULL,NULL);
            mysqli_real_connect($conn, $host, $username, $password, $db_name, 3306);
            if (mysqli_connect_errno($conn)) {
                die('Failed to connect to MySQL: '.mysqli_connect_error());
            }

            //$pdo = new PDO("mysql:dbname={$reImportDB};host=$host", $user, $pass);
            //$pdo = new PDO("mysql:dbname=app;host=localhost", "app_user", "app_us3r_pass");
            //$pdo = new PDO("sqlsrv:Server=localhost;Database=app", "app_user", "app_us3r_pass");
            //$pdo = new PDO("mysql:host=$host;port=3306;dbname=$reImportDB", $user, $pass);
            //$pdo = new PDO("sqlsrv:Server=cc-azure-webapp-mysql.mysql.database.azure.com;Database=app", "app_user@cc-azure-webapp-mysql", "app_us3r_pass");

        }catch (PDOException $e) {
            throw new Exception("Error!: " . $e->getMessage());
        }
        if (!$conn) {
            throw new Exception("Error in mysql connection");

        }
        return $conn;
    }

    public function getUsersList(){
        try{
            $conn = $this->connect();
            $result = [];
            $sql = "SELECT * FROM users ORDER BY id DESC";
            $query = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_assoc($query)){
                $result[] = $row;
            }
            return $result;
        }catch (Exception $e){
            return ['error' => "Could get users: " . $e->getMessage()];
        }
    }

    public function createUser($name, $email, $age){
        if(strlen($name) <3){
            return ['error' => 'Name must have at least 3 characters'];
        }

        if(strlen($email) <7){
            return ['error' => 'Email must have at least 7 characters'];
        }

        if(!is_numeric($age)){
            return ['error' => 'Age must be a number'];
        }

        try{
            $conn = $this->connect();

            $sql = "INSERT INTO `users` (name, email, age) VALUES ('" . $name . "','" . $email . "','" . $age . "')";
            $conn->query($sql);
            if(count($conn->error_list)){
                return ['error' => $conn->error_list];
            }else{
                return ['success' => "User created"];
            }
        }catch (Exception $e){
            return ['error' => "Could not create user: " . $e->getMessage()];
        }
    }

    public function deleteUser($userId){
        if(!strlen($userId)){
            return ['error' => 'User id is required'];
        }

        try{
            $conn = $this->connect();

            $sql = "DELETE FROM `users`WHERE id='$userId'";
            $conn->query($sql);
            if(count($conn->error_list)){
                return ['error' => $conn->error_list];
            }else{
                return ['success' => "User Deleted"];
            }

        }catch (Exception $e){
            return ['error' => "Could not delete user" . $e->getMessage()];
        }

    }
}