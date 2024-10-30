<?php
// si se acepta la solicitud de amistad se hace un insert en la tabla tbl_amigos
// cuando la acepta cambia el estado de la solicitud a aceptada 

session_start();
include_once '../conexion/conexion.php';

if(isset($_POST['id_solicitud'])){
    $id_solicitud = $_POST['id_solicitud'];
    try{
    // Iniciar el autocommit a false
    mysqli_autocommit($conexion, false);
    // Iniciar la transacción 
    mysqli_begin_transaction($conexion, MYSQLI_TRANS_START_READ_WRITE);

    // query 1 para el update de la solicitud a aceptada
    $query1 = "UPDATE tbl_solicitudes SET estado_solicitud = 'aceptada' WHERE id_solicitud = ?";
    $stmt1 = mysqli_prepare($conexion, $query1);
    mysqli_stmt_bind_param($stmt1, 'i', $id_solicitud);
    mysqli_stmt_execute($stmt1);

    // query 2 para el insert en la tabla tbl_amigos
    $query2 = "
    INSERT INTO tbl_amigos (id_usuario_Uno, id_usuario_Dos)
    SELECT id_usuario_solicitante, id_usuario_receptor 
    FROM tbl_solicitudes 
    WHERE id_solicitud = ?
    ";
    $stmt2 = mysqli_prepare($conexion, $query2);
    mysqli_stmt_bind_param($stmt2, 'i', $id_solicitud);
    mysqli_stmt_execute($stmt2);

    // Se hace el commit y por lo tanto se confirman las dos consultas
    mysqli_commit($conexion);
    // Se cierra la conexión
    mysqli_stmt_close($stmt1);
    mysqli_stmt_close($stmt2);

    // header ('Location a mostrarAmigos?') o seguir en pagina de solicitudes por si hay mas
    header ('Location: solicitudesAmistad.php');

    } catch (Exception $e) {
        // Deshacer la transacción en caso de error
        mysqli_rollback($conexion);
        echo "Error al procesar la solicitud: ", $e->getMessage();
    }
    


} else {
    echo "Error... No se encuentra el id de la solicitud";
}








