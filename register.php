<?php
require 'includes/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);

    // Verificar si el correo ya está registrado
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = ?");
    $stmt->execute([$correo]);

    if ($stmt->fetch()) {
        $error = "⚠️ El correo ya está registrado.";
    } else {
        // Insertar nuevo usuario con rol por defecto "usuario"
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo, contraseña) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $correo, $contraseña]);

        $success = "✅ Usuario registrado correctamente. Ahora puedes iniciar sesión.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">SS
<head>
    <meta charset="UTF-8">
    <title>Registrarse</title>
    <link rel="stylesheet" href="css/registro.css">
</head>
<body>
    <h2>Registro de Usuario</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
    <form method="post">
        <input type="text" name="nombre" placeholder="Nombre completo" required><br>
        <input type="email" name="correo" placeholder="Correo" required><br>
        <input type="password" name="contraseña" placeholder="Contraseña" required><br>
        <button type="submit">Registrarse</button>
    </form>
    <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
</body>
</html>
