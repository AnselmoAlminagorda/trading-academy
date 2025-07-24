<?php
require '../includes/auth.php';
require '../includes/db.php';
requiereLogin();

if (!esAdmin()) {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: cursos.php");
    exit;
}

$id = (int) $_GET['id'];

// Obtener datos del curso actual
$stmt = $pdo->prepare("SELECT * FROM cursos WHERE id = ?");
$stmt->execute([$id]);
$curso = $stmt->fetch();

if (!$curso) {
    echo "Curso no encontrado.";
    exit;
}

// Guardar cambios si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $nivel = $_POST['nivel'];

    $stmt = $pdo->prepare("UPDATE cursos SET titulo = ?, descripcion = ?, nivel = ? WHERE id = ?");
    $stmt->execute([$titulo, $descripcion, $nivel, $id]);

    header("Location: cursos.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Curso</title>
    <link rel="stylesheet" href="../css/admin_editarCurso.css">
</head>
<body>
    <h1>Editar Curso</h1>
    <p class="volver"><a href="cursos.php">← Volver a la lista</a></p>

    <form method="post">
        <label>Título:</label><br>
        <input type="text" name="titulo" value="<?= htmlspecialchars($curso['titulo']) ?>" required><br><br>

        <label>Descripción:</label><br>
        <textarea name="descripcion" required><?= htmlspecialchars($curso['descripcion']) ?></textarea><br><br>

        <label>Nivel:</label><br>
        <select name="nivel" required>
            <option value="básico" <?= $curso['nivel'] === 'básico' ? 'selected' : '' ?>>Básico</option>
            <option value="intermedio" <?= $curso['nivel'] === 'intermedio' ? 'selected' : '' ?>>Intermedio</option>
            <option value="avanzado" <?= $curso['nivel'] === 'avanzado' ? 'selected' : '' ?>>Avanzado</option>
        </select><br><br>

        <button type="submit">Guardar cambios</button>
    </form>
</body>
</html>
