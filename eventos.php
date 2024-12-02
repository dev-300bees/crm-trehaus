<?php
require_once 'includes/auth.php';
check_authentication();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Gestión de Eventos</title>
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
                <h2 class="title_page">Gestión de Eventos</h2>
                <a href="crear-evento.php" class="btn btn-primary mb-3">Crear Evento</a>
                <table id="responsiveDataTable" class="table table-bordered text-nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Fecha</th>
                            <th>Cover</th>
                            <th>Reserva</th>
                            <th>Compra</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once 'includes/db.php';
                        $result = $conn->query("SELECT * FROM eventos");
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['Id']}</td>
                                    <td>{$row['titulo']}</td>
                                    <td>{$row['fecha']}</td>
                                    <td><img src='assets/uploads/{$row['cover']}' alt='Cover' style='width:50px;'></td>
                                    <td><a href='{$row['url_reserva']}' target='_blank'>Reserva</a></td>
                                    <td><a href='{$row['url_compra']}' target='_blank'>Compra</a></td>
                                    <td>
                                        <a href='editar-evento.php?id={$row['Id']}' class='btn btn-warning btn-sm'>Editar</a>
                                        <button class='btn btn-danger btn-sm delete-event' data-id='{$row['Id']}'>Eliminar</button>
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
<script src="assets/ajax/eventos.js"></script>
</html>
