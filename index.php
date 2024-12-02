<?php
require_once 'includes/auth.php';
check_authentication();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Usuarios</title>
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
                <h2 class="title_page">Bienvenido</h2>
                
            </div>
        </div>
    </div>
</body>
<?php include 'partials/script.php'; ?>
</html>
