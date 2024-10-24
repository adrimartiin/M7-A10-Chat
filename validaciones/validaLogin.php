<?php
// si no esta el mismo usuario en la base de datos o la contraseña no es la misma q cuando se ha hecho el register: ERROR
// si existe el usuario en la base de datos y la contraseña es la misma q cuando se ha hecho el register: header location bienvenida

// Conexión a la base de datos
include_once '../conexion/conexion.php';

// Recibir los datos del formulario
$usuario = mysqli_real_escape_string($conexion, $_POST['username']);
$password = mysqli_real_escape_string($conexion, $_POST['password']);

// Consultar si el usuario existe en la base de datos
try{
    $sql = "SELECT nombre_usuario, psswd_usuario FROM tbl_usuarios WHERE nombre_usuario = (?) AND psswd_usuario = (?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $usuario, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    // Si el usuario existe y la contraseña es correcta
    if($row){
        session_start();
        $_SESSION['nombre_usuario'] = $row['nombre_usuario'];
        header('Location: ../acciones/bienvenida.php');
    } else {
        header('Location: ../entradas/login.php?error=1');
    }
    

    
}  catch(Exception $e){
    echo "Error: ". $e->getMessage();
}






    