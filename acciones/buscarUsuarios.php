<?php
session_start(); 
$nombre_usuario = isset($_SESSION['nombre_usuario']) ? $_SESSION['nombre_usuario'] : '';
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<link rel="stylesheet" href="../css/stylesAcciones.css">

<?php
include_once "../conexion/conexion.php";
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-black">
    <div class="container-fluid">
        <a class="navbar-brand" href="bienvenida.php" style="margin-left: auto;">
            <img src="../img/img_navbar.png" alt="Logo">
        </a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="buscarUsuarios.php">Buscar Usuarios <span class="sr-only"></span></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> Solicitudes de Amistad</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Pendiente</a></li>
                        <li><a class="dropdown-item" href="#">Amigos</a></li>
                    </ul>
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

  <!-- Barra para buscar usuarios -->
   <form action="" method="POST">
    <div class="search-bar">
      <input type="text" name="search" placeholder="Buscar usuario..." class="input-search">
      <button class="add-button">Buscar</button>
    </div>
   </form>


<?php
try {

  if ($_SERVER['REQUEST_METHOD'] === 'POST') { //compruebo si recibo algo por post (boton de la barra de busqueda)
        $textoBusqueda = trim($_POST['search']);  // con el trim quito los espacios de principio y final del texto  // y si el texto se envio lo captura en $textoBusqueda

        $sql = "SELECT id_usuario, nombre_usuario FROM tbl_usuarios WHERE nombre_usuario LIKE ?";  //hago la consulta
        $stmt = mysqli_prepare($conexion, $sql);
        $param = $textoBusqueda . '%';  // % filtro para buscar aquellas palarbas que comienzen por la letra que seintroduzca
        
        mysqli_stmt_bind_param($stmt, 's', $param);
        mysqli_stmt_execute($stmt);
        $resultados = mysqli_stmt_get_result($stmt);
    } else {
      $sql = "SELECT id_usuario, nombre_usuario FROM tbl_usuarios";  //y en caso de que no se haga click en el boton del formulario para filtrar nada, se mostrara la lista de usuarios
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_execute($stmt);
        $resultados = mysqli_stmt_get_result($stmt);
    }


    echo "<ul class='list-group'>";
    foreach ($resultados as $fila) {
        echo "<li class='list-group-item d-flex justify-content-between align-items-center'>" . htmlspecialchars($fila['nombre_usuario']) . 
        "<div class='d-flex align-items-center'>
            <form action='enviarSolicitud.php' method='POST' style='display:inline; margin-right: 10px;'>
                <input type='hidden' name='id_usuario_receptor' value='" . $fila['id_usuario'] . "'>
                <button type='submit' class='btn-icon'>
                    <img src='../img/img_solicitud.png' class='icono icono-bajo'>
                </button>
            </form>
        </div>
        </li>";
    }
    echo "</ul>";

    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
} catch (Exception $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>







