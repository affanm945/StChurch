<?php
header('Content-Type: application/json');
require_once '../db_config.php';
$conn = OpenCon();

$method = $_SERVER['REQUEST_METHOD'];

// GET current priest
if ($method === 'GET') {
    // Get the current priest (is_current = 1)
    $stmt = $conn->prepare("SELECT * FROM parish_priest WHERE is_current = 1 ORDER BY id DESC LIMIT 1");
    $stmt->execute();
    $result = $stmt->get_result();
    $priest = $result->fetch_assoc();
    
    if ($priest) {
        echo json_encode($priest);
    } else {
        echo json_encode(['error' => 'No current priest found']);
    }
    exit;
}

// ADD new priest (POST)
if ($method === 'POST' && !isset($_POST['_method'])) {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $serving_since = $_POST['serving_since'] ?? '';
    $diocese = $_POST['diocese'] ?? '';
    $is_current = isset($_POST['is_current']) ? intval($_POST['is_current']) : 1;
    $image = null;

    // If setting as current, set all others to not current
    if ($is_current == 1) {
        $stmtUpdate = $conn->prepare("UPDATE parish_priest SET is_current = 0");
        $stmtUpdate->execute();
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imgName = uniqid() . '_' . basename($_FILES['image']['name']);
        $target = '../../image/' . $imgName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image = $imgName;
        }
    }

    if ($image) {
        $stmt = $conn->prepare("INSERT INTO parish_priest (name, image, description, serving_since, diocese, is_current) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $name, $image, $description, $serving_since, $diocese, $is_current);
    } else {
        $stmt = $conn->prepare("INSERT INTO parish_priest (name, description, serving_since, diocese, is_current) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $name, $description, $serving_since, $diocese, $is_current);
    }
    $stmt->execute();
    echo json_encode(['success' => true, 'id' => $conn->insert_id]);
    exit;
}

// UPDATE priest (PUT via POST + _method=PUT)
if ($method === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
    $id = intval($_POST['id']);
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $serving_since = $_POST['serving_since'] ?? '';
    $diocese = $_POST['diocese'] ?? '';
    $is_current = isset($_POST['is_current']) ? intval($_POST['is_current']) : 0;

    // If setting as current, set all others to not current
    if ($is_current == 1) {
        $stmtUpdate = $conn->prepare("UPDATE parish_priest SET is_current = 0 WHERE id != ?");
        $stmtUpdate->bind_param("i", $id);
        $stmtUpdate->execute();
    }

    // Update name, description, serving_since, diocese, is_current
    $stmt = $conn->prepare("UPDATE parish_priest SET name=?, description=?, serving_since=?, diocese=?, is_current=? WHERE id=?");
    $stmt->bind_param("ssssii", $name, $description, $serving_since, $diocese, $is_current, $id);
    $stmt->execute();

    // If new image uploaded, replace old one
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Get old image
        $stmtImg = $conn->prepare("SELECT image FROM parish_priest WHERE id=?");
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
            $stmtUpdate = $conn->prepare("UPDATE parish_priest SET image=? WHERE id=?");
            $stmtUpdate->bind_param("si", $imgName, $id);
            $stmtUpdate->execute();
        }
    }

    echo json_encode(['success' => true]);
    exit;
}

// DELETE priest
if ($method === 'DELETE') {
    $id = intval($_GET['id']);
    
    // Delete image file
    $stmtImg = $conn->prepare("SELECT image FROM parish_priest WHERE id=?");
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
    $stmt = $conn->prepare("DELETE FROM parish_priest WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['error' => 'Invalid request']);

