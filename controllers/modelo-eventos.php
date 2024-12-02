<?php
require_once '../includes/db.php';

$action = $_GET['action'] ?? '';

if ($action === 'create') {
    $titulo = $_POST['titulo'];
    $fecha = $_POST['fecha'];
    $url_reserva = $_POST['url_reserva'] ?? null;
    $url_compra = $_POST['url_compra'] ?? null;

    // Subir imagen
    $cover = $_FILES['cover'];
    $coverName = time() . "_" . basename($cover['name']);
    $targetDir = "../assets/uploads/";
    $targetFile = $targetDir . $coverName;

    if (move_uploaded_file($cover['tmp_name'], $targetFile)) {
        $stmt = $conn->prepare("INSERT INTO eventos (titulo, fecha, cover, url_reserva, url_compra) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $titulo, $fecha, $coverName, $url_reserva, $url_compra);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Evento creado exitosamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al crear el evento.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al subir el archivo.']);
    }
    exit;
}

if ($action === 'edit') {
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $fecha = $_POST['fecha'];
    $url_reserva = $_POST['url_reserva'] ?? null;
    $url_compra = $_POST['url_compra'] ?? null;

    // Verificar si se subió un nuevo cover
    if (!empty($_FILES['cover']['name'])) {
        $cover = $_FILES['cover'];
        $coverName = time() . "_" . basename($cover['name']);
        $targetDir = "../assets/uploads/";
        $targetFile = $targetDir . $coverName;

        if (move_uploaded_file($cover['tmp_name'], $targetFile)) {
            // Actualizar con nueva imagen
            $stmt = $conn->prepare("UPDATE eventos SET titulo = ?, fecha = ?, cover = ?, url_reserva = ?, url_compra = ? WHERE id = ?");
            $stmt->bind_param("sssssi", $titulo, $fecha, $coverName, $url_reserva, $url_compra, $id);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al subir el archivo.']);
            exit;
        }
    } else {
        // Actualizar sin cambiar la imagen
        $stmt = $conn->prepare("UPDATE eventos SET titulo = ?, fecha = ?, url_reserva = ?, url_compra = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $titulo, $fecha, $url_reserva, $url_compra, $id);
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Evento actualizado correctamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el evento.']);
    }
    exit;
}

if ($action === 'delete') {
    $id = $_GET['id'];

    // Eliminar registro
    $stmt = $conn->prepare("DELETE FROM eventos WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Evento eliminado correctamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el evento.']);
    }
    exit;
}
