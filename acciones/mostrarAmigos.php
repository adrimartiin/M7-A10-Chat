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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMW9qY85/Z0d+z5j6PRA4qOdjB2h6Y9a8qP+e" crossorigin="anonymous">
    <title>Amigos</title> 
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
                    <a class="nav-link" href="buscarUsuarios.php">Buscar Usuarios <span class="sr-only"></span></a>
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

<div class="container" style="margin-top: 100px;">
    <?php
    include_once "../conexion/conexion.php"; // Asegúrate de que esta conexión se inicializa en una variable, por ejemplo, $conexion

    try {
        // Define la consulta para obtener los detalles de amistad
        $query = "SELECT u.nombre_usuario, a.fecha_amistad
                  FROM tbl_amigos AS a
                  INNER JOIN tbl_usuarios AS u ON a.id_usuario_Uno = u.id_usuario";
        
        // Preparar la declaración
        $stmt = mysqli_prepare($conexion, $query);

        // Ejecutar la declaración
        mysqli_stmt_execute($stmt);

        // Obtener el resultado de la consulta
        $result = mysqli_stmt_get_result($stmt);

        // Mostrar los resultados
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='friend-item'>";
                echo "<span>Usuario: " . htmlspecialchars($row["nombre_usuario"]) . " - Amigos desde: " . htmlspecialchars($row["fecha_amistad"]) . "</span>";
                echo "<form action='bienvenida.php' method='post' style='display:inline;'>";
                echo "<input type='hidden' name='usuario' value='" . htmlspecialchars($row["nombre_usuario"]) . "'>";
                echo "<button type='submit' class='chat-button'>
                    <img src='../img/img_mensaje.png' class='icono icono-bajo'>
                </button>";
                echo "</form></div>";
            }
        } else {
            echo "No se encontraron amistades.";
        }

        // Cerrar el statement después de usarlo
        mysqli_stmt_close($stmt);

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gyb1Rf4G4AjpX1yOeZjzzgFhC1BbxE/2dmY2+4z8ZT9R6p7+5" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-wy5r0tbtQQiMS4UIHP7lCwWj1FL1YtHkGNdP9IjN9mbYyNrMj8fbsMz3oWnka6UO" crossorigin="anonymous"></script>
</body>
</html>






