<?php

class Usuario {

    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }

    // LISTAR
    public function listar($estado = '', $rol = ''){

        $where = [];

        if($estado !== ''){
            $where[] = "u.activo = '$estado'";
        }

        if($rol !== ''){
            $where[] = "r.nombre = '$rol'";
        }

        $where_sql = '';

        if(count($where) > 0){
            $where_sql = "WHERE " . implode(" AND ", $where);
        }

        $sql = "SELECT 
                    u.id_usuario,
                    u.nombre as nombre_usuario,
                    u.correo,
                    r.nombre as rol_nombre,
                    u.activo
                FROM tb_usuarios u
                INNER JOIN tb_rol r ON u.id_rol = r.id_rol
                $where_sql
                ORDER BY u.id_usuario DESC";

        return $this->conexion->query($sql);
    }

    // OBTENER UNO
    public function obtener($id){

        $stmt = $this->conexion->prepare("SELECT * FROM tb_usuarios WHERE id_usuario=?");
        $stmt->bind_param("i",$id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // CREAR
    public function crear($nombre,$correo,$password,$id_rol){

        $stmt = $this->conexion->prepare("
            INSERT INTO tb_usuarios (nombre,correo,password,id_rol)
            VALUES (?,?,?,?)
        ");

        $stmt->bind_param("sssi",$nombre,$correo,$password,$id_rol);

        return $stmt->execute();
    }

    // VERIFICAR EMAIL
    public function existeCorreo($correo){

        $stmt = $this->conexion->prepare("
        SELECT id_usuario FROM tb_usuarios WHERE correo = ?
        ");

        $stmt->bind_param("s",$correo);
        $stmt->execute();
        $stmt->store_result();

        return $stmt->num_rows > 0;
    }

    // ACTUALIZAR
    public function actualizar($id,$nombre,$correo,$password,$id_rol){

        if(!empty($password)){

            $stmt = $this->conexion->prepare("
                UPDATE tb_usuarios 
                SET nombre=?,correo=?,password=?,id_rol=?
                WHERE id_usuario=?
            ");

            $stmt->bind_param("sssii",$nombre,$correo,$password,$id_rol,$id);

        }else{

            $stmt = $this->conexion->prepare("
                UPDATE tb_usuarios 
                SET nombre=?,correo=?,id_rol=?
                WHERE id_usuario=?
            ");

            $stmt->bind_param("ssii",$nombre,$correo,$id_rol,$id);
        }

        return $stmt->execute();
    }

    // CAMBIAR ESTADO
    public function cambiarEstado($id,$estado){

        $stmt = $this->conexion->prepare("
        UPDATE tb_usuarios SET activo=? WHERE id_usuario=?
        ");

        $stmt->bind_param("ii",$estado,$id);

        return $stmt->execute();
    }

}