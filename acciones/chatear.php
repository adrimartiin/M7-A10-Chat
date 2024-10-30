<?php
session_start(); 
include_once "../conexion/conexion.php";

if (!isset($_SESSION['usuario'])) {
    echo "<h6>Por favor, inicie sesión.</h6>";
    exit;
}

$nombre_usuario = $_SESSION['usuario']; 
$id_usuario = "";
$amigo_id = isset($_POST['amigo_id']) ? intval($_POST['amigo_id']) : null;
$mensaje = isset($_POST['mensaje']) ? trim($_POST['mensaje']) : null;

try {
    // Obtener ID del usuario
    $sql = "SELECT id_usuario FROM tbl_usuarios WHERE nombre_usuario = ?";
    $stmt = mysqli_stmt_init($conexion);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $nombre_usuario); 
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $usuario = mysqli_fetch_assoc($result);
        $id_usuario = $usuario['id_usuario'];
    }

    // Manejar el envío del mensaje
    if ($mensaje && $amigo_id) {
        $sql = "INSERT INTO tbl_mensajes (id_usuario_emisor, id_usuario_receptor, mensaje) VALUES (?, ?, ?)";
        if ($stmt = mysqli_prepare($conexion, $sql)) {
            mysqli_stmt_bind_param($stmt, "iis", $id_usuario, $amigo_id, $mensaje);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
} catch (Exception $e) {
    echo "<br><h6>" . htmlspecialchars($e->getMessage()) . "</h6>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/stylesAcciones.css">
    <title>Chat</title>
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
<div class="container-chats">
    <div class="barra-izquierda">
        <?php
        // Obtener lista de amigos
        $sql = "SELECT tbl_usuarios.id_usuario, tbl_usuarios.nombre_usuario
                FROM tbl_amigos 
                JOIN tbl_usuarios ON (tbl_usuarios.id_usuario = tbl_amigos.id_usuario_Uno OR tbl_usuarios.id_usuario = tbl_amigos.id_usuario_Dos)
                WHERE (tbl_amigos.id_usuario_Uno = ? OR tbl_amigos.id_usuario_Dos = ?)
                AND tbl_usuarios.id_usuario != ?";

        $stmt = mysqli_stmt_init($conexion);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "iii", $id_usuario, $id_usuario, $id_usuario);
            mysqli_stmt_execute($stmt);
            $resultados = mysqli_stmt_get_result($stmt);

            echo "<ul class='list-group'>";
            while ($fila = mysqli_fetch_assoc($resultados)) {
                echo "<li class='list-group-item d-flex justify-content-between align-items-center'>" . htmlspecialchars($fila['nombre_usuario'] ?? '') . 
                "<form action='chatear.php' method='POST' style='display:inline;'>
                    <input type='hidden' name='amigo_id' value='" . htmlspecialchars($fila['id_usuario'] ?? '') . "'>
                    <button type='submit' class='btn btn-link'>
                        <img src='../img/img_mensaje.png' class='icono icono-bajo'>
                    </button>
                </form>
                </li>";
            }
            echo "</ul>";
            mysqli_stmt_close($stmt);
        } else {
            echo "<h6>Error al obtener la lista de amigos.</h6>";
        }
        ?>
    </div>
    <div class="barra-derecha">
        <div id="chat" class="messages" style="flex-grow: 1; overflow-y: auto;">
            <?php
            if ($amigo_id) {
                // Obtener mensajes entre usuarios
                $sql = "SELECT tbl_mensajes.id_mensaje, tbl_mensajes.id_usuario_emisor, tbl_mensajes.mensaje, tbl_mensajes.fecha_mensaje, tbl_usuarios.nombre_usuario 
                        FROM tbl_mensajes 
                        JOIN tbl_usuarios ON tbl_usuarios.id_usuario = tbl_mensajes.id_usuario_emisor
                        WHERE (tbl_mensajes.id_usuario_emisor = ? AND tbl_mensajes.id_usuario_receptor = ?) OR (tbl_mensajes.id_usuario_emisor = ? AND tbl_mensajes.id_usuario_receptor = ?)
                        ORDER BY tbl_mensajes.fecha_mensaje";

                if ($stmt = mysqli_prepare($conexion, $sql)) {
                    mysqli_stmt_bind_param($stmt, "iiii", $id_usuario, $amigo_id, $amigo_id, $id_usuario);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $id_mensaje, $id_usuario_emisor, $mensaje, $fecha_mensaje, $nombre_usuario);
                    mysqli_stmt_store_result($stmt);

                    while (mysqli_stmt_fetch($stmt)) {
                        $class = ($id_usuario_emisor == $id_usuario) ? 'sent' : 'received'; // Determina la clase
                        echo "<div class='message $class'><strong>" . htmlspecialchars($nombre_usuario ?? '') . ":</strong> " . htmlspecialchars($mensaje ?? '') . " <em>(" . htmlspecialchars($fecha_mensaje ?? '') . ")</em></div>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    echo "<h6>Error al obtener los mensajes.</h6>";
                }
            }
            ?>
        </div>

        <!-- Formulario de entrada al final -->
        <form action="chatear.php" method="POST" class="chat-input d-flex" style="margin-top: 10px;">
            <input type="hidden" name="amigo_id" value="<?php echo htmlspecialchars($amigo_id ?? ''); ?>">
            <input type="text" name="mensaje" class="input-message form-control me-2" placeholder="Escribe un mensaje..." aria-label="Escribe un mensaje..." required>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
