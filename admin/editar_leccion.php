<?php
require '../includes/auth.php';
require '../includes/db.php';
requiereLogin();

if (!esAdmin()) {
    header("Location: ../login.php");
    exit;
}

$leccion_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$curso_id = isset($_GET['curso']) ? (int)$_GET['curso'] : 0;

// Obtener la lección actual
$stmt = $pdo->prepare("SELECT * FROM lecciones WHERE id = ? AND curso_id = ?");
$stmt->execute([$leccion_id, $curso_id]);
$leccion = $stmt->fetch();

if (!$leccion) {
    echo "Lección no encontrada.";
    exit;
}

// 🔧 Agrega estas líneas:
$titulo = $leccion['titulo'];
$contenido = $leccion['contenido'];

if (!$leccion) {
    echo "Lección no encontrada.";
    exit;
}

// Guardar cambios si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo = $_POST['titulo'];
    $contenido = $_POST['contenido'];
    $archivo_nombre = $leccion['archivo']; // valor por defecto

    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === 0) {
        $nombre_temp = $_FILES['archivo']['tmp_name'];
        $nombre_archivo = basename($_FILES['archivo']['name']);
        $ruta_destino = "../uploads/" . $nombre_archivo;

        if (move_uploaded_file($nombre_temp, $ruta_destino)) {
            $archivo_nombre = $nombre_archivo;
        }
    }

    $stmt = $pdo->prepare("UPDATE lecciones SET titulo = ?, contenido = ?, archivo = ? WHERE id = ? AND curso_id = ?");
    $stmt->execute([$titulo, $contenido, $archivo_nombre, $leccion_id, $curso_id]);

    header("Location: lecciones.php?curso=$curso_id");
    exit;
}

?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Lección</title>
    <link rel="stylesheet" href="../css/admin_editarLecciones.css">
</head>
<body>
    <h1>Editar Lección</h1>
    <p><a href="lecciones.php?curso=<?= $curso_id ?>">← Volver a las lecciones</a></p>

    <form method="post" enctype="multipart/form-data">
        <label>Título:</label><br>
        <input type="text" name="titulo" value="<?= htmlspecialchars($leccion['titulo']) ?>" required><br><br>

        <label>Contenido:</label><br>
        <textarea name="contenido" rows="5" cols="60" required><?= htmlspecialchars($leccion['contenido']) ?></textarea><br><br>

        <label>Archivo adjunto (opcional):</label><br>
        <input type="file" name="archivo"><br><br>

        <button type="submit">Guardar cambios</button>
    </form>
</body>
</html>