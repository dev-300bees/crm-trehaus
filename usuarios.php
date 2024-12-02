<?php
require_once 'includes/auth.php';
check_authentication();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Gestión de Usuarios</title>
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
                <h2 class="title_page">Gestión de Usuarios</h2>
                <a href="crear-usuario.php" class="btn btn-primary mb-3">Crear Usuario</a>
                <table id="responsiveDataTable" class="table table-bordered text-nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Fecha Creación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once 'includes/db.php';
                        $result = $conn->query("SELECT id, username, rol, fecha_creacion FROM usuarios");
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['username']}</td>
                                    <td>{$row['rol']}</td>
                                    <td>{$row['fecha_creacion']}</td>
                                    <td>
                                        <a href='editar-usuario.php?id={$row['id']}' class='btn btn-warning btn-sm'>Editar</a>
                                        <button class='btn btn-danger btn-sm delete-user' data-id='{$row['id']}'>Eliminar</button>
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
<script src="assets/ajax/usuarios.js"></script>
</html>
