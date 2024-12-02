<?php
// Configuración de la conexión
$host = '127.0.0.1';
$dbname = 'crm-treehaus';
$username = 'root'; // Cambia según tu configuración
$password = '';     // Cambia según tu configuración

// Conexión a la base de datos
$conn = new mysqli($host, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
