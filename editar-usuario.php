<?php
require_once 'includes/auth.php';
check_authentication();

require_once 'includes/db.php';
$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: usuarios.php');
    exit;
}

// Obtener datos del usuario
$stmt = $conn->prepare("SELECT username, rol FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: usuarios.php');
    exit;
}

$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Editar Usuario</title>
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
                <h2 class="title_page">Editar Usuario</h2>
                <form id="editUserForm">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="mb-3">
                        <label for="username" class="form-label">Usuario</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña (dejar vacío para no cambiar)</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="mb-3">
                        <label for="rol" class="form-label">Rol</label>
                        <select id="rol" name="rol" class="form-select">
                            <option value="admin" <?php echo $user['rol'] === 'admin' ? 'selected' : ''; ?>>Administrador</option>
                            <option value="user" <?php echo $user['rol'] === 'user' ? 'selected' : ''; ?>>Usuario</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</body>
<?php include 'partials/script.php'; ?>
<script src="assets/ajax/usuarios.js"></script>
</html>
