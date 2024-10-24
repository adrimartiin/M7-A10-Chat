<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
        </div>
    </nav>