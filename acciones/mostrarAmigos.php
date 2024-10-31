<?php
session_start(); 
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
            <img src="../img/img_navbar.png" alt="Logo" class="logo-navbar">
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
        <div class="dropdown">
            <button class="btn dropdown-toggle img-account" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="../img/img_navbar.png" style="width: 40px; max-height: 40px; border-radius: 50%; object-fit: cover;">
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="../proc/procCerrarSesion.php">Cerrar Sesión</a></li>
                <li><a class="dropdown-item" href="mostrarAmigos.php">Amigos</a></li>
            </ul>
        </div>
        <span class="navbar-text text-light user-name">
            <?php if ($nombre_usuario): ?>
                <?php echo htmlspecialchars($nombre_usuario); ?>
            <?php endif; ?>
        </span>
    </div>
</nav>

<div class="container friend-request-list mt-5 pt-5">
<?php
include_once "../conexion/conexion.php";

if (!isset($_SESSION['usuario'])) {
    echo "<h6>Por favor, inicie sesión.</h6>";
    exit;
}

$nombre_usuario = $_SESSION['usuario'];
$id_usuario = "";

// Obtener el ID del usuario autenticado
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

// Obtener la lista de amigos aceptados
try {
    $sql = "
        SELECT u.nombre_usuario, u.nombreReal_usuario, u.telf_usuario
        FROM tbl_amigos a
        JOIN tbl_usuarios u ON (u.id_usuario = a.id_usuario_Uno OR u.id_usuario = a.id_usuario_Dos)
        WHERE (a.id_usuario_Uno = ? OR a.id_usuario_Dos = ?)
        AND u.id_usuario != ?";
        
    $stmt = mysqli_stmt_init($conexion);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "iii", $id_usuario, $id_usuario, $id_usuario);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        // Mostrar la lista de amigos
        if (mysqli_num_rows($result) > 0) {
            echo "<h4 class='mb-3'>Lista de amigos aceptados:</h4><ul class='list-group'>";
            while ($amigo = mysqli_fetch_assoc($result)) {
                echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                    <span>Usuario: " . htmlspecialchars($amigo['nombre_usuario']) . "</span>
                </li>";
            }
            echo "</ul>";
        } else {
            echo "<h6>No tienes amigos aceptados.</h6>";
        }
    } else {
        echo "<h6>Error al preparar la declaración.</h6>";
    }
} catch (Exception $e) {
    echo "<br><h6>" . htmlspecialchars($e->getMessage()) . "</h6>";
    exit;
}
?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-Ho7jG9z9B5QsPLg8mgSMTWVwHG79/Z1azTVY9H/m/e58K1iTVYHRt6hx4JK1K1Yf" crossorigin="anonymous"></script>
</body>
</html>

