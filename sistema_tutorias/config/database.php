<?php

class Database{

    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $db = "sistema_tutorias";

    public function conectar(){

        $conn = new mysqli(
            $this->host,
            $this->user,
            $this->pass,
            $this->db
        );

        if($conn->connect_error){
            die("Error de conexión");
        }

        return $conn;
    }
}
?>