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
    <link rel="stylesheet" href="../css/sidebar.css">
    <script src="../js/sidebar.js"></script>
</head>
<body>
    <section>
        <nav class="sidebar">
            <div class="logo">Trading Academy</div>
            <ul>
                <li><a href="../user/cursos.php">Ver cursos disponibles</a></li>
                <li><a href="../user/mis_cursos.php">Mis cursos</a></li>
                <!-- <li><a href="../user/progreso.php">Mi progreso</a></li>
                <li><a href="../user/recompensas.php">Mis recompensas</a></li> -->
                <li><a href="../logout.php">Cerrar sesión</a></li>
            </ul>
        </nav>
        <main class="main-content">
            <h1>Bienvenido, <?= $_SESSION['nombre'] ?> (Usuario)</h1>
            <p>En este panel puedes ver los cursos disponibles, tus cursos inscritos y tu progreso
        </main>
    </section>
    <script>
        // Evitar volver con flecha atrás tras cerrar sesión
        if (performance.navigation.type === 2) {
            window.location.reload(true);
        }
    </script>
</body>
</html>
