<?php
session_start();
require_once "../config/database.php";

if(isset($_POST['login'])){

    $correo = $_POST['correo'];
    $password = $_POST['password'];

    $db = new Database();
    $conn = $db->conectar();

    $sql = "SELECT * FROM tb_usuarios 
            WHERE correo = ? 
            AND password = ?
            AND activo = 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $correo, $password);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if($resultado->num_rows == 1){

        $usuario = $resultado->fetch_assoc();

        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol'] = obtenerRol($usuario['id_rol'], $conn);

        header("Location: ../views/dashboard.php");
        exit();

    }else{
        echo "<script>
                alert('Credenciales incorrectas');
                window.location.href='../views/login.php';
              </script>";
    }
}

function obtenerRol($id_rol,$conn){
    $sql = "SELECT nombre FROM tb_rol WHERE id_rol=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i",$id_rol);
    $stmt->execute();

    $res = $stmt->get_result()->fetch_assoc();

    return $res['nombre'];
}
?>