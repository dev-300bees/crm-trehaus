<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password, rol FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_rol'] = $user['rol'];
            $_SESSION['username'] = $username;
            echo json_encode(['status' => 'success', 'message' => 'Login exitoso']);
            exit;
        }
    }
    echo json_encode(['status' => 'error', 'message' => 'Credenciales inválidas']);
    exit;
}
