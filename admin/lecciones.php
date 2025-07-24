<?php
require '../includes/auth.php';
require '../includes/db.php';
requiereLogin();

if (!esAdmin()) {
    header("Location: ../login.php");
    exit;
}

$curso_id = isset($_GET['curso']) ? (int)$_GET['curso'] : 0;

// Verificar que el curso existe
$stmt = $pdo->prepare("SELECT * FROM cursos WHERE id = ?");
$stmt->execute([$curso_id]);
$curso = $stmt->fetch();

if (!$curso) {
    echo "Curso no encontrado.";
    exit;
}

// Agregar lección
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['titulo'], $_POST['contenido'])) {
    $titulo = $_POST['titulo'];
    $contenido = $_POST['contenido'];

    $stmt = $pdo->prepare("INSERT INTO lecciones (curso_id, titulo, contenido) VALUES (?, ?, ?)");
    $stmt->execute([$curso_id, $titulo, $contenido]);
    $mensaje = "✅ Lección agregada.";
}

// Eliminar lección (opcional)
if (isset($_GET['eliminar'])) {
    $leccion_id = (int)$_GET['eliminar'];
    $stmt = $pdo->prepare("DELETE FROM lecciones WHERE id = ? AND curso_id = ?");
    $stmt->execute([$leccion_id, $curso_id]);
    $mensaje = "🗑️ Lección eliminada.";
}

// Obtener todas las lecciones del curso
$stmt = $pdo->prepare("SELECT * FROM lecciones WHERE curso_id = ? ORDER BY id ASC");
$stmt->execute([$curso_id]);
$lecciones = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lecciones de <?= htmlspecialchars($curso['titulo']) ?></title>
    <link rel="stylesheet" href="../css/admin_lecciones.css">
</head>
<body>
    <h1>Lecciones del curso: <?= htmlspecialchars($curso['titulo']) ?></h1>
    <p class="volver"><a href="cursos.php">← Volver a cursos</a></p>

    <?php if (isset($mensaje)) echo "<p class='mensaje'>$mensaje</p>"; ?>

    <div class="formulario">
        <h2>Agregar nueva lección</h2>
        <form method="post">
            <input type="text" name="titulo" placeholder="Título de la lección" required>
            <textarea name="contenido" placeholder="Contenido" rows="4" required></textarea>
            <button type="submit">Agregar lección</button>
        </form>
    </div>

    <div class="lecciones">
        <h2>Lecciones existentes</h2>
        <?php if (empty($lecciones)): ?>
            <p class="mensaje">No hay lecciones aún.</p>
        <?php else: ?>
            <ul>
            <?php foreach ($lecciones as $leccion): ?>
                <li>
                    <strong><?= htmlspecialchars($leccion['titulo']) ?></strong><br>
                    <small><?= nl2br(htmlspecialchars($leccion['contenido'])) ?></small><br>
                    <a class="enlace" href="editar_leccion.php?id=<?= $leccion['id'] ?>&curso=<?= $curso_id ?>">Editar</a> |
                    <a class="enlace" href="?curso=<?= $curso_id ?>&eliminar=<?= $leccion['id'] ?>" onclick="return confirm('¿Eliminar esta lección?')">Eliminar</a>   
                </li>
            <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</body>
</html>
