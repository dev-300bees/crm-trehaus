<?php
require_once 'includes/auth.php';
check_authentication();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Crear Video</title>
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
            <h2 class="title_page">Crear Video</h2>
            <form id="form-video" method="post">
                <input type="hidden" name="action" value="crear">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Video</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="mb-3">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" required>
                </div>
                <div class="mb-3">
                    <label for="destacado" class="form-label">URL del Video Destacado</label>
                    <input type="url" class="form-control" id="destacado" name="destacado" required>
                </div>
                <div class="mb-3" id="videos-container">
                    <label for="video-url-0" class="form-label">URL de Video Adicional</label>
                    <div class="input-group mb-3">
                        <input type="url" class="form-control video-url" id="video-url-0" name="video_urls[]" required>
                        <button type="button" class="btn btn-success btn-add-url">+</button>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Crear Video</button>
            </form>
        </div>
    </div>
</div>
</body>
<?php include 'partials/script.php'; ?>
<script src="assets/ajax/videos.js"></script>
</html>
