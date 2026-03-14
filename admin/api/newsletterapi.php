<?php
header('Content-Type: application/json');
require_once '../db_config.php';
$conn = OpenCon();

// GET all newsletters or single newsletter
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        // Get single newsletter
        $id = intval($_GET['id']);
        $stmt = $conn->prepare("SELECT * FROM newsletters WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $newsletter = $result->fetch_assoc();
        echo json_encode($newsletter ? $newsletter : null);
        exit;
    } else {
        // Get all newsletters
        $result = $conn->query("SELECT * FROM newsletters ORDER BY date DESC, created_at DESC");
        $newsletters = [];
        while ($row = $result->fetch_assoc()) {
            $newsletters[] = $row;
        }
        echo json_encode($newsletters);
        exit;
    }
}

// ADD new newsletter (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['_method'])) {
    $title = $_POST['title'] ?? '';
    $date = $_POST['date'] ?? date('Y-m-d');
    $file = null;
    $download_url = $_POST['download_url'] ?? '';

    // Handle file upload if present
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileName = uniqid() . '_' . basename($_FILES['file']['name']);
        $target = '../../newsletters/' . $fileName;
        
        // Create newsletters directory if it doesn't exist
        if (!file_exists('../../newsletters')) {
            mkdir('../../newsletters', 0777, true);
        }
        
        if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
            $file = $fileName;
        }
    }

    if ($file || $download_url) {
        $stmt = $conn->prepare("INSERT INTO newsletters (title, date, file, download_url) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $date, $file, $download_url);
        $stmt->execute();
        echo json_encode(['success' => true, 'id' => $conn->insert_id]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Either file or download URL is required.']);
    }
    exit;
}

// UPDATE newsletter (PUT via POST + _method=PUT)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
    $id = intval($_POST['id']);
    $title = $_POST['title'] ?? '';
    $date = $_POST['date'] ?? '';
    $download_url = $_POST['download_url'] ?? '';

    // If new file uploaded, replace old one
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        // Get old file
        $stmtOld = $conn->prepare("SELECT file FROM newsletters WHERE id=?");
        $stmtOld->bind_param("i", $id);
        $stmtOld->execute();
        $resOld = $stmtOld->get_result();
        if ($rowOld = $resOld->fetch_assoc()) {
            $oldFile = $rowOld['file'];
            if ($oldFile) {
                $filePath = '../../newsletters/' . $oldFile;
                if (file_exists($filePath)) unlink($filePath);
            }
        }
        
        // Save new file
        $fileName = uniqid() . '_' . basename($_FILES['file']['name']);
        $target = '../../newsletters/' . $fileName;
        
        if (!file_exists('../../newsletters')) {
            mkdir('../../newsletters', 0777, true);
        }
        
        if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
            $stmt = $conn->prepare("UPDATE newsletters SET title=?, date=?, file=?, download_url=? WHERE id=?");
            $stmt->bind_param("ssssi", $title, $date, $fileName, $download_url, $id);
            $stmt->execute();
        }
    } else {
        // Update without file change
        $stmt = $conn->prepare("UPDATE newsletters SET title=?, date=?, download_url=? WHERE id=?");
        $stmt->bind_param("sssi", $title, $date, $download_url, $id);
        $stmt->execute();
    }

    echo json_encode(['success' => true]);
    exit;
}

// DELETE newsletter
if ($_SERVER['REQUEST_METHOD'] === 'DELETE' || ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'DELETE')) {
    $id = isset($_GET['id']) ? intval($_GET['id']) : intval($_POST['id']);
    
    // Delete file
    $stmtFile = $conn->prepare("SELECT file FROM newsletters WHERE id=?");
    $stmtFile->bind_param("i", $id);
    $stmtFile->execute();
    $resFile = $stmtFile->get_result();
    if ($rowFile = $resFile->fetch_assoc()) {
        $fileName = $rowFile['file'];
        if ($fileName) {
            $filePath = '../../newsletters/' . $fileName;
            if (file_exists($filePath)) unlink($filePath);
        }
    }
    
    // Delete DB row
    $stmt = $conn->prepare("DELETE FROM newsletters WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['error' => 'Invalid request']);

