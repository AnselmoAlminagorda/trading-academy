<?php
require '../includes/auth.php';
requiereLogin();

if (!esAdmin()) {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Administrador</title>
</head>
<body>
    <h1>Bienvenido al Panel de Administración</h1>
    <p>Hola, <?= $_SESSION['nombre'] ?> (Admin)</p>

    <ul>
        <li><a href="cursos.php">Gestionar Cursos</a></li>
        <li><a href="usuarios.php">Gestionar Usuarios</a></li>
        <li><a href="../logout.php">Cerrar sesión</a></li>
    </ul>

    <script>
        if (performance.navigation.type === 2) {
            window.location.reload(true);
        }
    </script>
</body>
</html>
