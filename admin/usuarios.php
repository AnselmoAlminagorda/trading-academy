<?php
require '../includes/auth.php';
require '../includes/db.php';
requiereLogin();

if (!esAdmin()) {
    header("Location: ../login.php");
    exit;
}

// Eliminar usuario
if (isset($_GET['eliminar'])) {
    $idEliminar = (int) $_GET['eliminar'];

    // Evitar que un admin se elimine a sí mismo
    if ($idEliminar !== $_SESSION['usuario_id']) {
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$idEliminar]);
        $mensaje = "✅ Usuario eliminado.";
    } else {
        $mensaje = "❌ No puedes eliminarte a ti mismo.";
    }
}

// Obtener todos los usuarios
$stmt = $pdo->query("SELECT id, nombre, correo, rol FROM usuarios ORDER BY id ASC");
$usuarios = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Usuarios</title>
    <link rel="stylesheet" href="../css/admin_usuarios.css">
</head>
<body>
    <h1>Gestión de Usuarios</h1>
    <p><a href="dashboard.php">← Volver al Panel</a></p>

    <?php if (isset($mensaje)) echo "<p style='color:green;'>$mensaje</p>"; ?>

    <table border="1" cellpadding="6">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?= $usuario['id'] ?></td>
                <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                <td><?= htmlspecialchars($usuario['correo']) ?></td>
                <td><?= $usuario['rol'] ?></td>
                <td>
                    <?php if ($usuario['id'] !== $_SESSION['usuario_id']): ?>
                        <a href="?eliminar=<?= $usuario['id'] ?>" onclick="return confirm('¿Seguro que deseas eliminar este usuario?');">Eliminar</a>
                    <?php else: ?>
                        (Tú)
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
