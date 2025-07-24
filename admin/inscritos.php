<?php
require '../includes/auth.php';
require '../includes/db.php';
requiereLogin();

if (!esAdmin()) {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['curso'])) {
    header("Location: cursos.php");
    exit;
}

$curso_id = (int)$_GET['curso'];

// Obtener datos del curso
$stmt = $pdo->prepare("SELECT * FROM cursos WHERE id = ?");
$stmt->execute([$curso_id]);
$curso = $stmt->fetch();

if (!$curso) {
    echo "Curso no encontrado.";
    exit;
}

// Obtener inscritos
$stmt = $pdo->prepare("
    SELECT u.id, u.nombre, u.correo, i.fecha_inscripcion
    FROM inscripciones i
    JOIN usuarios u ON i.usuario_id = u.id
    WHERE i.curso_id = ?
    ORDER BY i.fecha_inscripcion DESC
");
$stmt->execute([$curso_id]);
$usuarios = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios inscritos - <?= htmlspecialchars($curso['titulo']) ?></title>
    <link rel="stylesheet" href="../css/admin_inscritos.css">
</head>
<body>
    <h1>Inscritos en: <?= htmlspecialchars($curso['titulo']) ?></h1>
    <p class="volver"><a href="cursos.php">← Volver a cursos</a></p>

    <?php if (empty($usuarios)): ?>
        <p class="mensaje">No hay usuarios inscritos aún.</p>
    <?php else: ?>
        <div class="tabla-contenedor">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Fecha de inscripción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td><?= $u['id'] ?></td>
                            <td><?= htmlspecialchars($u['nombre']) ?></td>
                            <td><?= htmlspecialchars($u['correo']) ?></td>
                            <td><?= $u['fecha_inscripcion'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</body>
</html>
