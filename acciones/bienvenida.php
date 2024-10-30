<?php
session_start(); 
include_once "../conexion/conexion.php";

if (!isset($_SESSION['usuario'])) {
    echo "<h6>Por favor, inicie sesión.</h6>";
    exit;
}
$nombre_usuario = $_SESSION['usuario']; 
$id_usuario = "";
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
}?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/stylesAcciones.css">
    <title>Document</title>
    <style>
        .img-account {
            margin-left: -20px; 
        }
        .navbar-text {
            margin-left: 15px; 
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-black fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="bienvenida.php">
            <img src="../img/img_navbar.png" alt="Logo">
        </a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="buscarUsuarios.php">Añadir Amigos <span class="sr-only"></span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="solicitudesAmistad.php">Solicitudes de Amistad</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="mostrarAmigos.php">Amigos</a>
                </li>
            </ul>
        </div>
        <!-- Imagen a la derecha -->
        <div class="dropdown">
            <button class="btn dropdown-toggle img-account" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="../img/img_navbar.png" style="width: 40px; max-height: 40px; border-radius: 50%; object-fit: cover;">
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="../proc/procCerrarSesion.php">Cerrar Sesión</a></li>
                <li><a class="dropdown-item" href="mostrarAmigos.php">Amigos</a></li>
            </ul>
        </div>
        <!-- Nombre de usuario a la derecha -->
        <span class="navbar-text text-light">
            <?php if ($nombre_usuario): ?>
                <?php echo htmlspecialchars($nombre_usuario); ?>
            <?php endif; ?>
        </span>
    </div>
</nav>
    <div class="container-chats">
        <div class="barra-izquierda">
            <?php

            $sql = "SELECT tbl_usuarios.nombre_usuario
            FROM tbl_amigos 
            JOIN tbl_usuarios ON (tbl_usuarios.id_usuario = tbl_amigos.id_usuario_Uno OR tbl_usuarios.id_usuario = tbl_amigos.id_usuario_Dos)
            WHERE (tbl_amigos.id_usuario_Uno = ? OR tbl_amigos.id_usuario_Dos = ?)
            AND tbl_usuarios.id_usuario != ?";

            $stmt = mysqli_stmt_init($conexion);

            if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "iii", $id_usuario, $id_usuario, $id_usuario);
            mysqli_stmt_execute($stmt);
            $resultados = mysqli_stmt_get_result($stmt);
            }

            echo "<ul class='list-group'>";
            foreach ($resultados as $fila) {
                echo "<li class='list-group-item d-flex justify-content-between align-items-center'>" . htmlspecialchars($fila['nombre_usuario']) . 
                "<div class='d-flex align-items-center'>
                    <form action='chatear.php' method='POST' style='display:inline; margin-right: 10px;'>
                        <button type='submit' class='btn-icon'>
                            <img src='../img/img_mensaje.png' class='icono icono-bajo'>
                        </button>
                    </form>
                </div>
                </li>";
            }
            echo "</ul>";
            mysqli_stmt_close($stmt);
            mysqli_close($conexion);

            ?>
        </div>

        <div class="barra-derecha">
                <div class="logo-bienvenida">
                    <img class="chat-logo" src="../img/img_navbar.png">
                    <p class="mensaje-bienvenida"> ¡Bienvenido a la red de mensajes del momento¡ </p>
                </div>

        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>


 
