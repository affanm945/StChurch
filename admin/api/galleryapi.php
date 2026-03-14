<?php
header('Content-Type: application/json');
require_once '../db_config.php';
$conn = OpenCon();

// GET all images
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $conn->query("SELECT * FROM gallery ORDER BY uploaded_at DESC");
    $images = [];
    while ($row = $result->fetch_assoc()) {
        $images[] = $row;
    }
    echo json_encode($images);
    exit;
}

// ADD new image (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['_method'])) {
    $title = $_POST['title'] ?? '';
    $image = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imgName = uniqid() . '_' . basename($_FILES['image']['name']);
        $target = '../../image/' . $imgName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image = $imgName;
        }
    }

    if ($image) {
        $stmt = $conn->prepare("INSERT INTO gallery (image, title) VALUES (?, ?)");
        $stmt->bind_param("ss", $image, $title);
        $stmt->execute();
        echo json_encode(['success' => true, 'id' => $conn->insert_id]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Image upload failed.']);
    }
    exit;
}

// UPDATE image (PUT via POST + _method=PUT)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
    $id = intval($_POST['id']);
    $title = $_POST['title'] ?? '';

    // Update title
    $stmt = $conn->prepare("UPDATE gallery SET title=? WHERE id=?");
    $stmt->bind_param("si", $title, $id);
    $stmt->execute();

    // If new image uploaded, replace old one
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Get old image
        $stmtImg = $conn->prepare("SELECT image FROM gallery WHERE id=?");
        $stmtImg->bind_param("i", $id);
        $stmtImg->execute();
        $resImg = $stmtImg->get_result();
        if ($rowImg = $resImg->fetch_assoc()) {
            $oldImg = $rowImg['image'];
            if ($oldImg) {
                $filePath = '../../image/' . $oldImg;
                if (file_exists($filePath)) unlink($filePath);
            }
        }
        // Save new image
        $imgName = uniqid() . '_' . basename($_FILES['image']['name']);
        $target = '../../image/' . $imgName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $stmtUpdate = $conn->prepare("UPDATE gallery SET image=? WHERE id=?");
            $stmtUpdate->bind_param("si", $imgName, $id);
            $stmtUpdate->execute();
        }
    }

    echo json_encode(['success' => true]);
    exit;
}

// DELETE image
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = intval($_GET['id']);
    // Delete image file
    $stmtImg = $conn->prepare("SELECT image FROM gallery WHERE id=?");
    $stmtImg->bind_param("i", $id);
    $stmtImg->execute();
    $resImg = $stmtImg->get_result();
    if ($rowImg = $resImg->fetch_assoc()) {
        $imgName = $rowImg['image'];
        if ($imgName) {
            $filePath = '../../image/' . $imgName;
            if (file_exists($filePath)) unlink($filePath);
        }
    }
    // Delete DB row
    $stmt = $conn->prepare("DELETE FROM gallery WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['error' => 'Invalid request']);
