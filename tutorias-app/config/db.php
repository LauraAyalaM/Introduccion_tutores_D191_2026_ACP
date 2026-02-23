<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "sistema_tutorias";

$conn = new mysqli($host,$user,$pass,$dbname);

if($conn->connect_error){
    die("Error de conexión");
}
?>