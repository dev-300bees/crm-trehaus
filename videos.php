<?php
require_once 'includes/auth.php';
check_authentication();
require_once 'includes/db.php';

// Obtener todos los videos
$result = $conn->query("SELECT id, nombre, fecha, destacado FROM videos");
$videos = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Listado de Videos</title>
    <?php include 'partials/styles.php'; ?>
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <?php include 'partials/aside.php'; ?>
    <!-- Main Content -->
    <div id="main-content" class="flex-grow-1">
        <!-- Header -->
        <?php include 'partials/header.php'; ?>
        <!-- Page Content -->
        <div class="container-fluid py-4">
            <h2 class="title_page">Listado de Videos</h2>
            <a href="crear-video.php" class="btn btn-primary mb-3">Crear Video</a>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Fecha</th>
                        <th>Destacado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($videos as $video): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($video['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($video['fecha']); ?></td>
                            <td>
                                <a href="<?php echo htmlspecialchars($video['destacado']); ?>" target="_blank">Ver Video</a>
                            </td>
                            <td>
                                <a href="editar-video.php?id=<?php echo $video['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                <button class="btn btn-danger btn-sm btn-delete-video" data-id="<?php echo $video['id']; ?>">Eliminar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
<?php include 'partials/script.php'; ?>
<script src="assets/ajax/videos.js"></script>
</html>
