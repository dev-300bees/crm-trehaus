<?php
require_once 'includes/auth.php';
check_authentication();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Gestión de Galerías</title>
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
            <h2 class="title_page">Gestión de Galerías</h2>
            <a href="crear-galeria.php" class="btn btn-primary mb-3">Crear Galería</a>
            <table id="responsiveDataTable" class="table table-bordered text-nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Fecha</th>
                        <th>Imagen Principal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require_once 'includes/db.php';
                    $result = $conn->query("SELECT id, nombre, fecha, cover FROM galerias");
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['nombre']}</td>
                                <td>{$row['fecha']}</td>
                                <td><img src='{$row['cover']}' alt='{$row['nombre']}' style='width: 100px; height: auto;'></td>
                                <td>
                                <a href='ver-galeria.php?id={$row['id']}' class='btn btn-info btn-sm'>Ver</a>
                                    <a href='editar-galeria.php?id={$row['id']}' class='btn btn-warning btn-sm'>Editar</a>
                                    <button class='btn btn-danger btn-sm delete-galeria' data-id='{$row['id']}'>Eliminar</button>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
<?php include 'partials/script.php'; ?>
</html>
