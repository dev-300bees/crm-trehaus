<?php
require_once 'includes/auth.php';
check_authentication();
require_once 'includes/db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: videos.php");
    exit;
}

$videoId = intval($_GET['id']);

// Obtener los datos principales del video
$stmt = $conn->prepare("SELECT nombre, fecha, destacado FROM videos WHERE id = ?");
$stmt->bind_param("i", $videoId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: videos.php");
    exit;
}

$video = $result->fetch_assoc();

// Obtener las URLs asociadas al video
$stmt = $conn->prepare("SELECT id, url FROM video_urls WHERE video_id = ?");
$stmt->bind_param("i", $videoId);
$stmt->execute();
$urlsResult = $stmt->get_result();

$urls = [];
while ($row = $urlsResult->fetch_assoc()) {
    $urls[] = $row;
}

$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Editar Video</title>
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
            <h2 class="title_page">Editar Video</h2>
            <form id="form-video" method="post">
                <input type="hidden" name="action" value="editar">
                <input type="hidden" name="id" value="<?php echo $videoId; ?>">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Video</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($video['nombre']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo htmlspecialchars($video['fecha']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="destacado" class="form-label">URL del Video Destacado</label>
                    <input type="url" class="form-control" id="destacado" name="destacado" value="<?php echo htmlspecialchars($video['destacado']); ?>" required>
                </div>
                <div class="mb-3" id="videos-container">
                    <label class="form-label">URLs de Videos Adicionales</label>
                    <?php foreach ($urls as $url): ?>
                        <div class="input-group mb-3" id="video-group-<?php echo $url['id']; ?>">
                            <input type="url" class="form-control video-url" name="video_urls[<?php echo $url['id']; ?>]" value="<?php echo htmlspecialchars($url['url']); ?>" required>
                            <button type="button" class="btn btn-danger btn-remove-url" data-id="<?php echo $url['id']; ?>">-</button>
                        </div>
                    <?php endforeach; ?>
                    <button type="button" class="btn btn-success btn-add-url">Agregar Video</button>
                </div>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </form>
        </div>
    </div>
</div>
</body>
<?php include 'partials/script.php'; ?>
<script src="assets/ajax/videos.js"></script>
</html>
