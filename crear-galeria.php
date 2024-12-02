<?php
require_once 'includes/auth.php';
check_authentication();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Crear Galería</title>
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
            <h2 class="title_page">Crear Galería</h2>
            <form id="form-galeria" method="post">
                <input type="hidden" name="action" value="crear">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre de la Galería</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="mb-3">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" required>
                </div>
                <div class="mb-3">
                    <label for="dropzone-cover" class="form-label">Imagen Destacada (Cover)</label>
                    <div id="dropzone-cover" class="dropzone"></div>
                </div>
                <div class="mb-3">
                    <label for="dropzone-imagenes" class="form-label">Imágenes de la Galería</label>
                    <div id="dropzone-imagenes" class="dropzone"></div>
                </div>
                <button type="submit" class="btn btn-primary">Crear Galería</button>
            </form>
        </div>
    </div>
</div>
</body>
<?php include 'partials/script.php'; ?>
<script src="assets/ajax/galerias.js"></script>
</html>
