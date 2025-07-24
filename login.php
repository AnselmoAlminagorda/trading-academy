<?php
require 'includes/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = ?");
    $stmt->execute([$correo]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($contraseña, $usuario['contraseña'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol'] = $usuario['rol'];

        if ($usuario['rol'] === 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: user/dashboard.php");
        }
        exit;
    } else {
        $error = "⚠️ Credenciales incorrectas";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="css/login.css">
    <script src="js/animaciones.js"></script>
</head>
<body>
    <div class="login-container">
    <h2>Iniciar Sesión</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post">
        <input type="email" name="correo" placeholder="Correo electronico" required><br>
        <input type="password" name="contraseña" placeholder="Contraseña" required><br>
        <button type="submit">Ingresar</button>
    </form>
    </div>
</body>
</html>
