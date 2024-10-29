<?php
session_start();
include_once "../conexion/conexion.php";

if (!isset($_SESSION['usuario'])) {
    echo "<h6>Por favor, inicie sesión.</h6>";
    exit;
}

// Verificar si se recibió id_usuario_receptor desde un formulario o URL
if (isset($_POST['id_usuario_receptor'])) {
    $id_usuario_receptor = $_POST['id_usuario_receptor'];
} else {
    echo "<h6>Error: No se ha especificado el usuario receptor.</h6>";
    exit;
}

$nombre_usuario = $_SESSION['usuario'];
$id_usuario = "";

// Obtener el ID del usuario solicitante a partir de la sesión
try {
    $sql = "SELECT id_usuario FROM tbl_usuarios WHERE nombre_usuario = ?";
    $stmt = mysqli_stmt_init($conexion);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $nombre_usuario);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $usuario = mysqli_fetch_assoc($result);
        $id_usuario = $usuario['id_usuario'];
    }
} catch (Exception $e) {
    echo "<br><h6>" . htmlspecialchars($e->getMessage()) . "</h6>";
    exit;
}

// Insertar la solicitud de amistad en tbl_solicitudes
try {
    $sql = "INSERT INTO tbl_solicitudes (id_usuario_solicitante, id_usuario_receptor, estado_solicitud)
            VALUES (?, ?, 'pendiente')";
    $stmt = mysqli_stmt_init($conexion);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $id_usuario, $id_usuario_receptor);
        mysqli_stmt_execute($stmt);
        echo "<h6>Solicitud de amistad enviada con éxito.</h6>";
    } else {
        echo "<h6>Error al preparar la declaración.</h6>";
    }
} catch (Exception $e) {
    echo "<br><h6>" . htmlspecialchars($e->getMessage()) . "</h6>";
    exit;
}
?>
