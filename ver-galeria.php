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
$stmt = $conn->prepare("SELECT imagen FROM galeria_imagenes WHERE galeria_id = ?");
$stmt->bind_param("i", $galeriaId);
$stmt->execute();
$imagenesResult = $stmt->get_result();

$imagenes = [];
while ($row = $imagenesResult->fetch_assoc()) {
    $imagenes[] = $row['imagen'];
}

$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Ver Galería</title>
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
            <h2 class="title_page">Detalles de la Galería</h2>
            <div class="card mb-4">
                <div class="card-body">
                    <h4>Nombre: <?php echo htmlspecialchars($galeria['nombre']); ?></h4>
                    <p><strong>Fecha:</strong> <?php echo htmlspecialchars($galeria['fecha']); ?></p>
                    <p><strong>Imagen Destacada:</strong></p>
                    <img src="<?php echo htmlspecialchars($galeria['cover']); ?>" alt="Imagen destacada" class="img-fluid mb-3" style="max-width: 300px;">
                </div>
            </div>
            <h3 class="mb-3">Imágenes de la Galería</h3>
            <div class="row">
                <?php if (!empty($imagenes)): ?>
                    <?php foreach ($imagenes as $imagen): ?>
                        <div class="col-md-3 mb-4">
                            <div class="card">
                                <img src="<?php echo htmlspecialchars($imagen); ?>" alt="Imagen de galería" class="card-img-top img-fluid">
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">No hay imágenes relacionadas con esta galería.</p>
                <?php endif; ?>
            </div>
            <a href="galerias.php" class="btn btn-secondary">Volver a la lista</a>
        </div>
    </div>
</div>
</body>
<?php include 'partials/script.php'; ?>
</html>
