<?php
require '../includes/auth.php';
requiereLogin();

// Solo usuarios normales pueden acceder aquí
if ($_SESSION['rol'] !== 'usuario') {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Usuario</title>
</head>
<body>
    <h1>Bienvenido a Trading Academy</h1>
    <p>Hola, <?= $_SESSION['nombre'] ?> (Usuario)</p>

    <ul>
        <li><a href="../user/cursos.php">Ver cursos disponibles</a></li>
        <li><a href="../user/mis_cursos.php">Mis cursos</a></li>
        <li><a href="#">Mi progreso</a></li>
        <li><a href="#">Mis recompensas</a></li>
        <li><a href="../logout.php">Cerrar sesión</a></li>
    </ul>

    <script>
        // Evitar volver con flecha atrás tras cerrar sesión
        if (performance.navigation.type === 2) {
            window.location.reload(true);
        }
    </script>
</body>
</html>
