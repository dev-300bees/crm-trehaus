<?php
require_once '../includes/db.php';

$action = $_GET['action'] ?? '';

if ($action === 'create') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];

    $stmt = $conn->prepare("INSERT INTO usuarios (username, password, rol) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $rol);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Usuario creado exitosamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al crear el usuario.']);
    }
    exit;
}

if ($action === 'delete') {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Usuario eliminado exitosamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el usuario.']);
    }
    exit;
}

if ($action === 'edit') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $rol = $_POST['rol'];

    // Verificar si se cambia la contraseña
    if (!empty($password)) {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuarios SET username = ?, password = ?, rol = ? WHERE id = ?");
        $stmt->bind_param("sssi", $username, $password_hashed, $rol, $id);
    } else {
        $stmt = $conn->prepare("UPDATE usuarios SET username = ?, rol = ? WHERE id = ?");
        $stmt->bind_param("ssi", $username, $rol, $id);
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Usuario actualizado correctamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el usuario.']);
    }
    exit;
}

