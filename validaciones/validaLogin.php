<?php
session_start();
include_once '../conexion/conexion.php';

// Recibir los datos del formulario
$usuario = mysqli_real_escape_string($conexion, $_POST['username']);
$password = mysqli_real_escape_string($conexion, $_POST['password']);
$_SESSION['usuario'] = $usuario;

try {
    $sql = "SELECT nombre_usuario, psswd_usuario FROM tbl_usuarios WHERE nombre_usuario = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "s", $usuario);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    // Si el usuario existe, valido la contraseña
    if ($row && password_verify($password, $row['psswd_usuario'])) {
        $_SESSION['nombre_usuario'] = $row['nombre_usuario'];
        header('Location: ../acciones/bienvenida.php');
        exit();
    } else {
        header('Location: ../entradas/login.php?error=1');
        exit();
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
