<?php

if(isset($_GET['msg'])){

    $msg = $_GET['msg'];

    switch($msg){

        case "creado":
            $texto = "Registro creado correctamente.";
            $tipo = "success";
        break;

        case "editado":
            $texto = "Registro actualizado correctamente.";
            $tipo = "info";
        break;

        case "eliminado":
            $texto = "Registro eliminado correctamente.";
            $tipo = "danger";
        break;

        case "estado":
            $texto = "Estado actualizado correctamente.";
            $tipo = "warning";
        break;

        case "error":
            $texto = "Ocurrió un error en la operación.";
            $tipo = "danger";
        break;

        default:
            $texto = "Operación realizada.";
            $tipo = "secondary";
    }

    echo "
    <div class='alert alert-$tipo alert-dismissible fade show' role='alert'>
        $texto
        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
    </div>
    ";
}

?>