<?php
    // Creamos una conexión a la base de datos
    include_once '../conexion/conexion.php';

    // Inicio insert para usuarios
    try{
        // Recogemos campos del formulario protegidos con mysqli_real_escape_string
        $username = mysqli_real_escape_string($conexion, $_POST['nombre_usuario']);
        $nombreReal = mysqli_real_escape_string($conexion, $_POST['nombreReal']);
        $telf = mysqli_real_escape_string($conexion, $_POST['numTelefono']);
        $password = mysqli_real_escape_string($conexion, $_POST['password']);
        $repetirPassword = mysqli_real_escape_string($conexion, $_POST['repetirPassword']);

        // Creo las variables de sesión
        $_SESSION['username'] = $username;
        $_SESSION['nombreReal'] = $nombreReal;
        $_SESSION['telf'] = $telf;
        $_SESSION['password'] = $password;

        // Encriptacion de contraseña
        $encriptedPsswd = password_hash($password, PASSWORD_BCRYPT);
        
        // Comprobar si antes de hacer el insert hay un usuario con el mismo nombre
        $sql = "SELECT nombre_usuario FROM tbl_usuarios WHERE nombre_usuario = (?)";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        $usuarioExistente = mysqli_fetch_assoc($resultado);

        
        if($usuarioExistente){
            echo "<p style='color: red'> Nombre de usuario ya existente</p>";
            header('Location:../entradas/register.php?error=1');
        } else {
            // codigo del insert
            $sql = "INSERT INTO tbl_usuarios (nombre_usuario, nombreReal_usuario, telf_usuario, psswd_usuario) VALUES (?,?,?,?)";
            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $username, $nombreReal, $telf, $password);
            mysqli_stmt_execute($stmt);
            $resultados = mysqli_stmt_get_result($stmt);
            header('Location: ../entradas/login.php');
            exit();
        }
    } catch(Exception $e){
        echo "Error: ". $e->getMessage();
    }