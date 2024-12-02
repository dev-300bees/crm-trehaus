<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'crear') {
    $nombre = $_POST['nombre'] ?? '';
    $fecha = $_POST['fecha'] ?? '';
    $coverPath = '';
    $imagenesPaths = [];
    $targetDir = "../assets/images/gallery/";

    // Verificar si el directorio existe, si no, crearlo
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true); // Crear el directorio con permisos 755
    }

    // Procesar la imagen destacada (cover)
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] === 0) {
        $fileExtension = pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION); // Obtener la extensión del archivo
        $coverName = uniqid() . "." . $fileExtension; // Renombrar la imagen
        $coverPath = $targetDir . $coverName;

        if (!move_uploaded_file($_FILES['cover']['tmp_name'], $coverPath)) {
            echo json_encode(['success' => false, 'message' => 'Error al subir la imagen destacada.']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'La imagen destacada es requerida.']);
        exit;
    }

    // Procesar las imágenes de la galería
    if (isset($_FILES['imagenes'])) {
        foreach ($_FILES['imagenes']['name'] as $index => $imagen) {
            if ($_FILES['imagenes']['error'][$index] === 0) {
                $fileExtension = pathinfo($imagen, PATHINFO_EXTENSION); // Obtener la extensión del archivo
                $imageName = uniqid() . "." . $fileExtension; // Renombrar la imagen
                $imagePath = $targetDir . $imageName;

                if (move_uploaded_file($_FILES['imagenes']['tmp_name'][$index], $imagePath)) {
                    $imagenesPaths[] = $imagePath; // Agregar el path al array
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al subir una de las imágenes de la galería.']);
                    exit;
                }
            }
        }
    }

    // Guardar la galería en la base de datos
    $stmt = $conn->prepare("INSERT INTO galerias (nombre, fecha, cover) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre, $fecha, $coverPath);

    if ($stmt->execute()) {
        $galeriaId = $stmt->insert_id;

        foreach ($imagenesPaths as $path) {
            $stmtImagen = $conn->prepare("INSERT INTO galeria_imagenes (galeria_id, imagen) VALUES (?, ?)");
            $stmtImagen->bind_param("is", $galeriaId, $path);
            $stmtImagen->execute();
        }

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al guardar la galería en la base de datos.']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'editar') {
    $id = intval($_POST['id']);
    $nombre = $_POST['nombre'];
    $fecha = $_POST['fecha'];
    $newCover = $_FILES['cover'] ?? null;
    $newImages = $_FILES['imagenes'] ?? [];
    $deletedImages = $_POST['deleted_images'] ?? [];

    $targetDir = "../assets/images/gallery/";

    // Actualizar nombre y fecha
    $stmt = $conn->prepare("UPDATE galerias SET nombre = ?, fecha = ? WHERE id = ?");
    $stmt->bind_param("ssi", $nombre, $fecha, $id);
    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar la galería.']);
        exit;
    }

    // Manejar la imagen destacada (cover)
    if ($newCover) {
        $coverName = uniqid() . "." . pathinfo($newCover['name'], PATHINFO_EXTENSION);
        $coverPath = $targetDir . $coverName;

        // Subir la nueva imagen destacada
        if (move_uploaded_file($newCover['tmp_name'], $coverPath)) {
            // Actualizar la imagen destacada en la base de datos
            $stmt = $conn->prepare("UPDATE galerias SET cover = ? WHERE id = ?");
            $stmt->bind_param("si", $coverPath, $id);
            $stmt->execute();
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al subir la imagen destacada.']);
            exit;
        }
    }

    // Eliminar imágenes marcadas para eliminación
    if (!empty($deletedImages)) {
        foreach ($deletedImages as $deletedName) {
            // Obtener la ruta del archivo a eliminar
            $filePath = '../assets/images/gallery/'.$deletedName;
            $stmt = $conn->prepare("SELECT id FROM galeria_imagenes WHERE imagen = ?");
            $stmt->bind_param("s", $filePath);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $deletedId = $row['id'];

                // Eliminar el archivo del servidor
                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                // Eliminar el registro de la base de datos
                $stmt = $conn->prepare("DELETE FROM galeria_imagenes WHERE id = ?");
                $stmt->bind_param("i", $deletedId);
                $stmt->execute();
            }
        }
    }

    // Subir nuevas imágenes
    if (!empty($newImages['name'])) {
        foreach ($newImages['name'] as $index => $name) {
            if ($newImages['error'][$index] === 0) {
                $fileName = uniqid() . "." . pathinfo($name, PATHINFO_EXTENSION);
                $filePath = $targetDir . $fileName;

                // Subir el archivo al servidor
                if (move_uploaded_file($newImages['tmp_name'][$index], $filePath)) {
                    // Insertar la nueva imagen en la base de datos
                    $stmt = $conn->prepare("INSERT INTO galeria_imagenes (galeria_id, imagen) VALUES (?, ?)");
                    $stmt->bind_param("is", $id, $filePath);
                    $stmt->execute();
                }
            }
        }
    }

    echo json_encode(['success' => true,'data' => $filePath, 'message' => 'Galería actualizada correctamente.']);
    exit;
}