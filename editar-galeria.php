<?php
require_once 'includes/auth.php';
check_authentication();
require_once 'includes/db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: galerias.php");
    exit;
}

$galeriaId = intval($_GET['id']);

// Obtener los datos de la galería
$stmt = $conn->prepare("SELECT nombre, fecha, cover FROM galerias WHERE id = ?");
$stmt->bind_param("i", $galeriaId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: galerias.php");
    exit;
}

$galeria = $result->fetch_assoc();

// Obtener las imágenes relacionadas con la galería
$stmt = $conn->prepare("SELECT id, imagen FROM galeria_imagenes WHERE galeria_id = ?");
$stmt->bind_param("i", $galeriaId);
$stmt->execute();
$imagenesResult = $stmt->get_result();

$imagenes = [];
while ($row = $imagenesResult->fetch_assoc()) {
    $imagenes[] = $row;
}

$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Editar Galería</title>
    <?php include 'partials/styles.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropzone/dist/dropzone.css">
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
            <h2 class="title_page">Editar Galería</h2>
            <form id="edit-galeria-form" method="post">
                <input type="hidden" name="action" value="editar">
                <input type="hidden" name="id" value="<?php echo $galeriaId; ?>">
                <div class="mb-3">
                    <label for="edit-nombre" class="form-label">Nombre de la Galería</label>
                    <input type="text" class="form-control" id="edit-nombre" name="nombre" value="<?php echo htmlspecialchars($galeria['nombre']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="edit-fecha" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="edit-fecha" name="fecha" value="<?php echo htmlspecialchars($galeria['fecha']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="edit-dropzone-cover" class="form-label">Imagen Destacada (Cover)</label>
                    <div id="edit-dropzone-cover" class="dropzone" 
                         data-filename="<?php echo basename($galeria['cover']); ?>" 
                         data-url="<?php echo $galeria['cover']; ?>"></div>
                </div>
                <div class="mb-3">
                    <label for="edit-dropzone-imagenes" class="form-label">Imágenes de la Galería</label>
                    <div class="mb-3">
                        <label for="edit-dropzone-imagenes" class="form-label">Imágenes de la Galería</label>
                        <div id="edit-dropzone-imagenes" class="dropzone" 
                                data-existing-images='<?php echo json_encode(array_map(function ($imagen) {
                                    return [
                                        'id' => $imagen['id'],
                                        'name' => basename($imagen['imagen']), // Nombre del archivo
                                        'url' => $imagen['imagen'] // URL completa del archivo
                                    ];
                                }, $imagenes)); ?>'></div>
                    </div>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </form>
        </div>
    </div>
</div>
</body>
<?php include 'partials/script.php'; ?>
<script src="assets/ajax/edit-galeria.js"></script>
</html>
