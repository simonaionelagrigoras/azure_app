<?php
/**
 * Created by PhpStorm.
 * User: Simona
 * Date: 28/02/2019
 * Time: 16:24
 */

class Users{

    protected function connect(){
        $reImportDB = 'app';
        $host = 'cc-azure-webapp-mysql.mysql.database.azure.com';
        $user = 'app_user';
        $pass = 'app_us3r_pass';
        try {
            //$pdo = new PDO("mysql:dbname={$reImportDB};host=$host", $user, $pass);
            //$pdo = new PDO("mysql:dbname=app;host=localhost", "app_user", "app_us3r_pass");
            //$pdo = new PDO("sqlsrv:Server=localhost;Database=app", "app_user", "app_us3r_pass");
            //$pdo = new PDO("mysql:host=$host;port=3306;dbname=$reImportDB", $user, $pass);
            //$pdo = new PDO("sqlsrv:Server=cc-azure-webapp-mysql.mysql.database.azure.com;Database=app", "app_user@cc-azure-webapp-mysql", "app_us3r_pass");
            $serverName = "cc-azure-webapp-mysql.mysql.database.azure.com"; // update me
            $connectionOptions = array(
                "Database" => "app", // update me
                "Uid" => "app_user", // update me
                "PWD" => "app_us3r_pass" // update me
            );
            //Establishes the connection
            $conn = sqlsrv_connect($serverName, $connectionOptions);
        }catch (PDOException $e) {
            throw new Exception("Error!: " . $e->getMessage());
            //die();
        }
        if (!$pdo) {
            throw new Exception("Error in mysql connection");
            //print "Eroare: Nu a fost posibilă conectarea la MySQL." . PHP_EOL;
            //print "Valoarea errno: " . mysqli_connect_errno() . PHP_EOL;
            //print "Valoarea error: " . mysqli_connect_error() . PHP_EOL;
        }
       // print "Succes: Connection to db successful" . PHP_EOL;
        return $pdo;
    }

    public function getUsersList(){
        try{
            $pdo = $this->connect();

            $sql = "SELECT * FROM users ORDER BY id DESC";
            $query = $pdo->prepare($sql);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
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
            $pdo = $this->connect();

            $sql = "INSERT INTO users (`name`, `email`, `age`) VALUES (?,?,?)";
            $stmt= $pdo->prepare($sql);
            $stmt->execute([$name, $email, $age]);
            return ['success' => "User created"];
        }catch (Exception $e){
            return ['error' => "Could not create user: " . $e->getMessage()];
        }

    }


    public function deleteUser($userId){
        if(!strlen($userId)){
            return ['error' => 'User id is required'];
        }

        try{
            $pdo = $this->connect();

            $sql = "DELETE FROM `users`WHERE id=$userId";
            $query = $pdo->prepare($sql);
            $query->execute();
            return ['success' => "User Deleted"];
        }catch (Exception $e){
            return ['error' => "Could not delete user" . $e->getMessage()];
        }

    }
}