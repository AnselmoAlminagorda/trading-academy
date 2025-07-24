<?php
require '../includes/auth.php';
require '../includes/db.php';
requiereLogin();

if (!esAdmin()) {
    header("Location: ../login.php");
    exit;
}

// Agregar curso
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['titulo'])) {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $nivel = $_POST['nivel'];

    $stmt = $pdo->prepare("INSERT INTO cursos (titulo, descripcion, nivel) VALUES (?, ?, ?)");
    $stmt->execute([$titulo, $descripcion, $nivel]);
    $mensaje = "✅ Curso agregado con éxito.";
}

// Eliminar curso
if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    $stmt = $pdo->prepare("DELETE FROM cursos WHERE id = ?");
    $stmt->execute([$id]);
    $mensaje = "🗑️ Curso eliminado.";
}

// Obtener todos los cursos
$cursos = $pdo->query("SELECT * FROM cursos ORDER BY nivel")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Cursos</title>
    <link rel="stylesheet" href="../css/admin_curso.css">
</head>
<body>
    <h1>Gestión de Cursos</h1>
    <p class="volver"><a href="dashboard.php">← Volver al panel</a></p>

    <?php if (isset($mensaje)) echo "<p style='color:green;'>$mensaje</p>"; ?>

    <h2>Agregar Curso</h2>
    <form method="post" class="form-curso">
        <input type="text" name="titulo" placeholder="Título del curso" required>
        <textarea name="descripcion" placeholder="Descripción del curso" required></textarea>
        <select name="nivel" required>
            <option value="básico">Básico</option>
            <option value="intermedio">Intermedio</option>
            <option value="avanzado">Avanzado</option>
        </select>
        <button type="submit">Agregar curso</button>
    </form>

    <h2>Cursos existentes</h2>
    <table border="1" cellpadding="6">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Nivel</th>
                <th>Descripción</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($cursos as $curso): ?>
            <tr>
                <td><?= $curso['id'] ?></td>
                <td><?= htmlspecialchars($curso['titulo']) ?></td>
                <td><?= $curso['nivel'] ?></td>
                <td><?= nl2br(htmlspecialchars($curso['descripcion'])) ?></td>
                <td>
                    <a href="editar_curso.php?id=<?= $curso['id'] ?>">Editar</a> |
                    <a href="?eliminar=<?= $curso['id'] ?>" onclick="return confirm('¿Eliminar este curso?');">Eliminar</a> |
                    <a href="inscritos.php?curso=<?= $curso['id'] ?>">Ver inscritos</a>
                    <a href="lecciones.php?curso=<?= $curso['id'] ?>">Gestionar lecciones</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
