<?php
session_start(); 
include_once "../conexion/conexion.php"; 

if (!isset($_SESSION['usuario'])) {
    echo "<h6>Por favor, inicie sesión.</h6>";
    exit;
}

$nombre_usuario = isset($_SESSION['nombre_usuario']) ? $_SESSION['nombre_usuario'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/stylesAcciones.css">
    <title>Solicitudes de Amistad</title>
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
                    <a class="nav-link" href="buscarUsuarios.php">Añadir Amigos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="solicitudesAmistad.php">Solicitudes de Amistad</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="mostrarAmigos.php">Amigos</a>
                </li>
            </ul>
        </div>
        <div class="dropdown">
            <button class="btn dropdown-toggle img-account" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="../img/img_navbar.png" style="width: 40px; max-height: 40px; border-radius: 50%; object-fit: cover;">
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="../proc/procCerrarSesion.php">Cerrar Sesión</a></li>
                <li><a class="dropdown-item" href="mostrarAmigos.php">Amigos</a></li>
            </ul>
        </div>
        <span class="navbar-text text-light">
            <?php if ($nombre_usuario): ?>
                <?php echo htmlspecialchars($nombre_usuario); ?>
            <?php endif; ?>
        </span>
    </div>
</nav>

<div class="container solicitud-card">
    <?php
    try {
        // Obtener el ID del usuario actual
        $sql = "SELECT id_usuario FROM tbl_usuarios WHERE nombre_usuario = ?";
        $stmt = mysqli_stmt_init($conexion);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $nombre_usuario);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $usuario = mysqli_fetch_assoc($result);
            $id_usuario = $usuario['id_usuario'];
        }

        // Consultar las solicitudes pendientes para el usuario actual
        $sqlSolicitudes = "SELECT tbl_solicitudes.id_solicitud, tbl_usuarios.nombre_usuario, tbl_solicitudes.fecha_solicitud
                           FROM tbl_solicitudes
                           JOIN tbl_usuarios ON tbl_solicitudes.id_usuario_solicitante = tbl_usuarios.id_usuario
                           WHERE tbl_solicitudes.id_usuario_receptor = ? AND tbl_solicitudes.estado_solicitud = 'pendiente'";
        $stmtSolicitudes = mysqli_stmt_init($conexion);
        if (mysqli_stmt_prepare($stmtSolicitudes, $sqlSolicitudes)) {
            mysqli_stmt_bind_param($stmtSolicitudes, "i", $id_usuario);
            mysqli_stmt_execute($stmtSolicitudes);
            $solicitudes = mysqli_stmt_get_result($stmtSolicitudes);

            echo "<h4 class='solicitud-header'>Solicitudes de Amistad Pendientes</h4>";
            if (mysqli_num_rows($solicitudes) > 0) {
                while ($solicitud = mysqli_fetch_assoc($solicitudes)) {
                    echo "<div class='solicitud-item'>
                            <span><strong>" . htmlspecialchars($solicitud['nombre_usuario']) . "</strong> • " . htmlspecialchars($solicitud['fecha_solicitud']) . "</span>
                            <div class='solicitud-buttons'>
                                <form action='aceptarSolicitud.php' method='POST'>
                                    <input type='hidden' name='id_solicitud' value='" . $solicitud['id_solicitud'] . "'>
                                    <button type='submit' class='btn btn-success btn-sm'>Aceptar</button>
                                </form>
                                <form action='rechazarSolicitud.php' method='POST' style='margin-left:5px;'>
                                    <input type='hidden' name='id_solicitud' value='" . $solicitud['id_solicitud'] . "'>
                                    <button type='submit' class='btn btn-danger btn-sm'>Rechazar</button>
                                </form>
                            </div>
                          </div>";
                }
            } else {
                echo "<p class='text-center'>No tienes solicitudes de amistad pendientes.</p>";
            }
        }
    } catch (Exception $e) {
        echo "<br><h6>" . htmlspecialchars($e->getMessage()) . "</h6>";
        exit;
    }
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
