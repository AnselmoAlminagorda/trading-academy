<?php
require '../includes/auth.php';
require '../includes/db.php';
requiereLogin();

if ($_SESSION['rol'] !== 'usuario') {
    header("Location: ../login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Cancelar inscripción si se solicita
if (isset($_GET['cancelar'])) {
    $curso_id = (int)$_GET['cancelar'];
    $stmt = $pdo->prepare("DELETE FROM inscripciones WHERE usuario_id = ? AND curso_id = ?");
    $stmt->execute([$usuario_id, $curso_id]);
    $mensaje = "❌ Inscripción cancelada.";
}

// Obtener cursos donde el usuario está inscrito
$stmt = $pdo->prepare("
    SELECT c.*
    FROM cursos c
    JOIN inscripciones i ON c.id = i.curso_id
    WHERE i.usuario_id = ?
    ORDER BY c.nivel
");
$stmt->execute([$usuario_id]);
$cursos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Cursos</title>
</head>
<body>
    <h1>Mis Cursos</h1>
    <p><a href="dashboard.php">← Volver al panel</a></p>

    <?php if (isset($mensaje)) echo "<p style='color:red;'>$mensaje</p>"; ?>

    <?php if (empty($cursos)): ?>
        <p>Aún no estás inscrito en ningún curso.</p>
    <?php else: ?>
        <ul>
        <?php foreach ($cursos as $curso): ?>
            <li>
                <h3><?= htmlspecialchars($curso['titulo']) ?> (<?= $curso['nivel'] ?>)</h3>
                <p><?= nl2br(htmlspecialchars($curso['descripcion'])) ?></p>

                <a href="?cancelar=<?= $curso['id'] ?>" onclick="return confirm('¿Seguro que quieres cancelar tu inscripción a este curso?')">Cancelar inscripción</a> |
                <a href="progreso.php?curso=<?= $curso['id'] ?>">Ver progreso</a>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>
