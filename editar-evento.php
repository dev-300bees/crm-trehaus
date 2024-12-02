<?php
require_once 'includes/auth.php';
check_authentication();

require_once 'includes/db.php';
$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: eventos.php');
    exit;
}

// Obtener datos del evento
$stmt = $conn->prepare("SELECT * FROM eventos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: eventos.php');
    exit;
}

$evento = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Editar Evento</title>
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
                <h2 class="title_page">Editar Evento</h2>
                <form id="editEventForm" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo $evento['titulo']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo $evento['fecha']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="cover" class="form-label">Cover (dejar vacío para no cambiar)</label>
                        <input type="file" class="form-control" id="cover" name="cover" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="url_reserva" class="form-label">URL de Reserva</label>
                        <input type="url" class="form-control" id="url_reserva" name="url_reserva" value="<?php echo $evento['url_reserva']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="url_compra" class="form-label">URL de Compra</label>
                        <input type="url" class="form-control" id="url_compra" name="url_compra" value="<?php echo $evento['url_compra']; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</body>
<?php include 'partials/script.php'; ?>
<script src="assets/ajax/eventos.js"></script>
</html>
