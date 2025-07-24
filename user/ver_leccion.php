<?php
require '../includes/auth.php';
require '../includes/db.php';
requiereLogin();

if ($_SESSION['rol'] !== 'usuario') {
    header("Location: ../login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$leccion_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$curso_id = isset($_GET['curso']) ? (int)$_GET['curso'] : 0;

// Obtener la lección
$stmt = $pdo->prepare("SELECT * FROM lecciones WHERE id = ? AND curso_id = ?");
$stmt->execute([$leccion_id, $curso_id]);
$leccion = $stmt->fetch();

if (!$leccion) {
    echo "Lección no encontrada.";
    exit;
}

// Marcar como completado (si se envió el botón)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = $pdo->prepare("INSERT INTO progreso (usuario_id, leccion_id, completado)
        VALUES (?, ?, 1)
        ON DUPLICATE KEY UPDATE completado = 1");
    $stmt->execute([$usuario_id, $leccion_id]);
    $mensaje = "✅ Lección marcada como completada.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($leccion['titulo']) ?></title>
    <link rel="stylesheet" href="../css/usuario_leccion.css">
</head>
<body>
    <h1><?= htmlspecialchars($leccion['titulo']) ?></h1>
    <p><a href="progreso.php?curso=<?= $curso_id ?>">← Volver al curso</a></p>

    <p><?= nl2br(htmlspecialchars($leccion['contenido'])) ?></p>

    <form method="post">
        <button type="submit">Marcar como completada</button>
    </form>

    <?php if (isset($mensaje)) echo "<p style='color:green;'>$mensaje</p>"; ?>
</body>
</html>
<?php if (!empty($leccion['archivo'])): ?>
    <p>
        📎 Archivo adjunto: 
        <a href="../uploads/<?= urlencode($leccion['archivo']) ?>" target="_blank">
            <?= htmlspecialchars($leccion['archivo']) ?>
        </a>
    </p>
<?php endif; ?>