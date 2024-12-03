<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'crear') {
    $nombre = $_POST['nombre'];
    $fecha = $_POST['fecha'];
    $destacado = $_POST['destacado'];
    $videoUrls = $_POST['video_urls'] ?? [];

    // Validar que haya al menos un video adicional
    if (empty($videoUrls)) {
        echo json_encode(['success' => false, 'message' => 'Debe haber al menos un video adicional.']);
        exit;
    }

    // Insertar el video principal en la base de datos
    $stmt = $conn->prepare("INSERT INTO videos (nombre, fecha, destacado) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre, $fecha, $destacado);

    if ($stmt->execute()) {
        $videoId = $stmt->insert_id;

        // Insertar las URLs de los videos adicionales
        foreach ($videoUrls as $url) {
            $stmt = $conn->prepare("INSERT INTO video_urls (video_id, url) VALUES (?, ?)");
            $stmt->bind_param("is", $videoId, $url);
            $stmt->execute();
        }

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al crear el video.']);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'editar') {
    $id = intval($_POST['id']);
    $nombre = $_POST['nombre'];
    $fecha = $_POST['fecha'];
    $destacado = $_POST['destacado'];
    $videoUrls = $_POST['video_urls'] ?? [];

    // Actualizar datos principales
    $stmt = $conn->prepare("UPDATE videos SET nombre = ?, fecha = ?, destacado = ? WHERE id = ?");
    $stmt->bind_param("sssi", $nombre, $fecha, $destacado, $id);

    if ($stmt->execute()) {
        // Eliminar URLs marcadas para eliminar
        $deletedUrls = array_keys(array_filter($videoUrls, fn($url) => empty($url)));
        if (!empty($deletedUrls)) {
            $placeholders = implode(',', array_fill(0, count($deletedUrls), '?'));
            $types = str_repeat('i', count($deletedUrls));
            $stmt = $conn->prepare("DELETE FROM video_urls WHERE id IN ($placeholders)");
            $stmt->bind_param($types, ...$deletedUrls);
            $stmt->execute();
        }

        // Actualizar o insertar nuevas URLs
        foreach ($videoUrls as $urlId => $url) {
            if (is_numeric($urlId)) {
                $stmt = $conn->prepare("UPDATE video_urls SET url = ? WHERE id = ?");
                $stmt->bind_param("si", $url, $urlId);
            } else {
                $stmt = $conn->prepare("INSERT INTO video_urls (video_id, url) VALUES (?, ?)");
                $stmt->bind_param("is", $id, $url);
            }
            $stmt->execute();
        }

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el video.']);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'eliminar') {
    $id = intval($_POST['id']);

    $stmt = $conn->prepare("DELETE FROM videos WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar el video.']);
    }
    exit;
}

?>
