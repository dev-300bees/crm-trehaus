<?php
require_once 'includes/auth.php';
check_authentication();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Crear Evento</title>
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
                <h2 class="title_page">Crear Evento</h2>
                <form id="createEventForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" required>
                    </div>
                    <div class="mb-3">
                        <label for="cover" class="form-label">Cover</label>
                        <input type="file" class="form-control" id="cover" name="cover" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                        <label for="url_reserva" class="form-label">URL de Reserva</label>
                        <input type="url" class="form-control" id="url_reserva" name="url_reserva">
                    </div>
                    <div class="mb-3">
                        <label for="url_compra" class="form-label">URL de Compra</label>
                        <input type="url" class="form-control" id="url_compra" name="url_compra">
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</body>
<?php include 'partials/script.php'; ?>
<script src="assets/ajax/eventos.js"></script>
</html>
