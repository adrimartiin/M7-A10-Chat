<?php
session_start();
include_once '../conexion/conexion.php';

// Verifica si se recibe el ID por POST
if (isset($_POST['id_solicitud'])) {
    $id = $_POST['id_solicitud'];
    try {
        // Eliminar la solicitud usando el ID
        $sql = "DELETE FROM tbl_solicitudes WHERE id_solicitud = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $id);

        // Ejecutar la eliminación y verifica el resultado
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            mysqli_close($conexion);
            header('Location: solicitudesAmistad.php');
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "ERROR!.";
}
