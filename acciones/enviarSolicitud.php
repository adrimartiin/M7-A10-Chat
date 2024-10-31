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

// Verificar si ya existe una solicitud de amistad pendiente o aceptada entre los usuarios
try {
    $sqlCheck = "SELECT * FROM tbl_solicitudes 
                 WHERE id_usuario_solicitante = ? AND id_usuario_receptor = ? 
                 AND estado_solicitud IN ('pendiente', 'aceptada')";
    $stmtCheck = mysqli_stmt_init($conexion);
    if (mysqli_stmt_prepare($stmtCheck, $sqlCheck)) {
        mysqli_stmt_bind_param($stmtCheck, "ii", $id_usuario, $id_usuario_receptor);
        mysqli_stmt_execute($stmtCheck);
        $resultCheck = mysqli_stmt_get_result($stmtCheck);

        if (mysqli_num_rows($resultCheck) > 0) {
            // Mostrar mensaje de error en rojo si la solicitud ya existe
            echo "<div style='color: red;'><h3>Error: Ya has enviado una solicitud de amistad a este usuario.</h3></div>";
            echo "<a href='buscarUsuarios.php' style='color: blue;'>Volver a buscar usuarios</a>";
            exit();
        }
    }
} catch (Exception $e) {
    echo "<br><h6>" . htmlspecialchars($e->getMessage()) . "</h6>";
    exit;
}

// Insertar la solicitud de amistad si no existe
try {
    $sql = "INSERT INTO tbl_solicitudes (id_usuario_solicitante, id_usuario_receptor, estado_solicitud)
            VALUES (?, ?, 'pendiente')";
    $stmt = mysqli_stmt_init($conexion);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $id_usuario, $id_usuario_receptor);
        mysqli_stmt_execute($stmt);
        
        // Redirigir al usuario sin mostrar mensaje de éxito
        header("Location: ./solicitudesAmistad.php");
        exit();
    } else {
        echo "<h6>Error al preparar la declaración.</h6>";
    }
} catch (Exception $e) {
    echo "<br><h6>" . htmlspecialchars($e->getMessage()) . "</h6>";
    exit;
}
?>


