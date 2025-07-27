<?php
require '../includes/auth.php';
require '../includes/db.php';
requiereLogin();

if ($_SESSION['rol'] !== 'usuario') {
    header("Location: ../login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$curso_id = isset($_GET['curso']) ? (int)$_GET['curso'] : 0;

// Obtener curso
$stmt = $pdo->prepare("SELECT * FROM cursos WHERE id = ?");
$stmt->execute([$curso_id]);
$curso = $stmt->fetch();

if (!$curso) {
    echo "Curso no encontrado.";
    exit;
}

// Obtener lecciones del curso
$stmt = $pdo->prepare("SELECT * FROM lecciones WHERE curso_id = ?");
$stmt->execute([$curso_id]);
$lecciones = $stmt->fetchAll();

// Marcar como completado si se envió
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['leccion_id'])) {
    $leccion_id = (int)$_POST['leccion_id'];
    $stmt = $pdo->prepare("INSERT INTO progreso (usuario_id, leccion_id, completado) VALUES (?, ?, 1)
        ON DUPLICATE KEY UPDATE completado = 1");
    $stmt->execute([$usuario_id, $leccion_id]);
    header("Location: progreso.php?curso=$curso_id");
    exit;
}

// Obtener lecciones completadas
$stmt = $pdo->prepare("SELECT leccion_id FROM progreso WHERE usuario_id = ? AND completado = 1");
$stmt->execute([$usuario_id]);
$completadas = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Progreso: <?= htmlspecialchars($curso['titulo']) ?></title>
    <link rel="stylesheet" href="../css/progreso_user.css">
</head>
<body>
    <h1>Progreso en: <?= htmlspecialchars($curso['titulo']) ?></h1>
    <p class="volver"><a class="volver" href="mis_cursos.php">← Volver a mis cursos</a></p>

    <ul>
    <?php foreach ($lecciones as $leccion): ?>
        <li>
            <strong><?= htmlspecialchars($leccion['titulo']) ?></strong><br>
            <a href="ver_leccion.php?id=<?= $leccion['id'] ?>&curso=<?= $curso_id ?>">
            <?= in_array($leccion['id'], $completadas) ? '✅ ' : '' ?>Ver contenido
            </a>
        </li>
    <?php endforeach; ?>
    </ul>

    <p>
        Avance: <?= count($completadas) ?>/<?= count($lecciones) ?> lecciones completadas
    </p>
</body>
</html>
