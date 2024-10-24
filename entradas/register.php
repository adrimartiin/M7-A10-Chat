<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="login-container">
        <h1>Resgístrate</h1>
        <form action="../validaciones/validaRegister.php" method="POST">
            <input type="text" name="nombre_usuario" placeholder="Nombre de usuario">
            <input type="text" name="nombreReal" placeholder="Nombre">
            <input type="tel" name="numTelefono" placeholder="Número de teléfono">
            <input type="password" name="password" placeholder="Contraseña">
            <input type="password" name="repetirPassword" placeholder="Repite la contraseña">
            <button type="submit" class="login-btn">Entrar</button>
            <?php
                if(isset($_GET['error']) == '1'){
                    echo "<p style='color: red'>El nombre de usuario ya existe</p>";
                }
            ?>
        </form>
    </div>
</body>
</html>

