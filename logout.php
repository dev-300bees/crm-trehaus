<?php
session_start();

// Destruir todas las sesiones
session_unset();
session_destroy();

// Redirigir al usuario al login
header("Location: login.php");
exit;
?>
