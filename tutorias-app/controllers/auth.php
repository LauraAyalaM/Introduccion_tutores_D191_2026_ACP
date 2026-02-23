<?php
session_start();
include "../config/db.php";

if($_POST){

    $correo = trim($_POST['correo']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM tb_usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s",$correo);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if($user){

        if($password === $user['password']){

            $_SESSION['usuario'] = $user;

            header("Location: ../views/dashboard.php");
            exit;

        }else{
            echo "Credenciales incorrectas";
        }

    }else{
        echo "Credenciales incorrectas";
    }
}
?>