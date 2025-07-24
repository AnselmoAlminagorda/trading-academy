<?php
require '../includes/auth.php';
require '../includes/db.php';
requiereLogin();

if ($_SESSION['rol'] !== 'usuario') {
    header("Location: ../login.php");
    exit;
}

// Procesar inscripción
if (isset($_POST['curso_id'])) {
    $curso_id = (int)$_POST['curso_id'];
    $usuario_id = $_SESSION['usuario_id'];

    // Verifica si ya está inscrito
    $stmt = $pdo->prepare("SELECT * FROM inscripciones WHERE usuario_id = ? AND curso_id = ?");
    $stmt->execute([$usuario_id, $curso_id]);

    if ($stmt->rowCount() === 0) {
        $stmt = $pdo->prepare("INSERT INTO inscripciones (usuario_id, curso_id) VALUES (?, ?)");
        $stmt->execute([$usuario_id, $curso_id]);
        $mensaje = "✅ Te has inscrito al curso.";
    } else {
        $mensaje = "⚠️ Ya estás inscrito en ese curso.";
    }
}

// Obtener cursos
$cursos = $pdo->query("SELECT * FROM cursos ORDER BY nivel")->fetchAll();

// Obtener cursos en los que ya está inscrito
$stmt = $pdo->prepare("SELECT curso_id FROM inscripciones WHERE usuario_id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$inscritos = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cursos Disponibles</title>
    <link rel="stylesheet" href="../css/cursos_user.css">
    <script src="../js/cursos_user.js"></script>
</head>
<body>
    <div class="container">
        <h1>Cursos Disponibles</h1>
        <p><a href="dashboard.php">← Volver al panel</a></p>

        <?php if (isset($mensaje)) echo "<p style='color:green;'>$mensaje</p>"; ?>

        <ul class="cursos-grid">
            <?php foreach ($cursos as $curso): ?>
                <li class="curso-box">
                    <h3><?= htmlspecialchars($curso['titulo']) ?> (<?= $curso['nivel'] ?>)</h3>
                    <p><?= nl2br(htmlspecialchars($curso['descripcion'])) ?></p>

                    <div class="curso-accion">
                        <?php if (in_array($curso['id'], $inscritos)): ?>
                            <strong class="ya-inscrito">✅ Ya inscrito</strong>
                        <?php else: ?>
                            <form method="post">
                                <input type="hidden" name="curso_id" value="<?= $curso['id'] ?>">
                                <button type="submit">Inscribirme</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>

