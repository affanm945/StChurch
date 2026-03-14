<?php
header('Content-Type: application/json');
require_once '../db_config.php';
$conn = OpenCon();

// Helper: get event by id
function getEvent($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM events WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res->fetch_assoc();
}

function getEventGallery($conn, $event_id) {
    $stmt = $conn->prepare("SELECT id, image FROM event_gallery WHERE event_id=?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $images = [];
    while ($row = $res->fetch_assoc()) {
        $images[] = $row['image'];
    }
    return $images;
}

function handleGalleryDeletions($conn, $event_id, $delete_gallery_json) {
    $toDelete = json_decode($delete_gallery_json, true);
    if (is_array($toDelete)) {
        foreach ($toDelete as $imgName) {
            $stmtDel = $conn->prepare("DELETE FROM event_gallery WHERE event_id=? AND image=?");
            $stmtDel->bind_param("is", $event_id, $imgName);
            $stmtDel->execute();
            $filePath = '../../image/' . $imgName;
            if (file_exists($filePath)) unlink($filePath);
        }
    }
}

// GET all events or single event
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $event = getEvent($conn, $id);
        if ($event) {
            $event['gallery'] = getEventGallery($conn, $id);
        }
        echo json_encode($event);
        exit;
    } else {
        $result = $conn->query("SELECT * FROM events ORDER BY date DESC");
        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
        echo json_encode($events);
    }
    exit;
}

// UPDATE event (PUT)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
    $id = intval($_POST['id']);
    $title = $_POST['title'];
    $date = $_POST['date'];
    $start_time = $_POST['start_time'] ?? null;
    $end_time = $_POST['end_time'] ?? null;
    $location = $_POST['location'] ?? null;
    $description = $_POST['description'] ?? null;
    $instructor = $_POST['instructor'] ?? null;

    $stmt = $conn->prepare("UPDATE events SET title=?, date=?, start_time=?, end_time=?, location=?, description=?, instructor=? WHERE id=?");
    $stmt->bind_param("sssssssi", $title, $date, $start_time, $end_time, $location, $description, $instructor, $id);
    $stmt->execute();

    // Handle gallery image deletion (if any)
    if (isset($_POST['delete_gallery'])) {
        handleGalleryDeletions($conn, $id, $_POST['delete_gallery']);
    }

    // Handle main image deletion (if any)
    if (isset($_POST['delete_main_image']) && $_POST['delete_main_image'] == '1') {
        // Get current image filename
        $stmtImg = $conn->prepare("SELECT image FROM events WHERE id=?");
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
        // Set image column to NULL
        $stmtNull = $conn->prepare("UPDATE events SET image=NULL WHERE id=?");
        $stmtNull->bind_param("i", $id);
        $stmtNull->execute();
    }

    // Handle new main image upload (if any)
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Delete old image if exists
        $stmtImg = $conn->prepare("SELECT image FROM events WHERE id=?");
        $stmtImg->bind_param("i", $id);
        $stmtImg->execute();
        $resImg = $stmtImg->get_result();
        if ($rowImg = $resImg->fetch_assoc()) {
            $oldImgName = $rowImg['image'];
            if ($oldImgName) {
                $filePath = '../../image/' . $oldImgName;
                if (file_exists($filePath)) unlink($filePath);
            }
        }
        
        // Upload new image
        $imgName = uniqid() . '_' . basename($_FILES['image']['name']);
        $target = '../../image/' . $imgName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $stmtUpdate = $conn->prepare("UPDATE events SET image=? WHERE id=?");
            $stmtUpdate->bind_param("si", $imgName, $id);
            $stmtUpdate->execute();
        }
    }

    // Handle new gallery images (if any)
    if (!empty($_FILES['gallery_images'])) {
        $stmtCount = $conn->prepare("SELECT COUNT(*) as cnt FROM event_gallery WHERE event_id=?");
        $stmtCount->bind_param("i", $id);
        $stmtCount->execute();
        $cntRes = $stmtCount->get_result()->fetch_assoc();
        $currentCount = $cntRes['cnt'];

        $galleryFiles = $_FILES['gallery_images'];
        $remaining = 10 - $currentCount;
        $count = min(count($galleryFiles['name']), $remaining);
        for ($i = 0; $i < $count; $i++) {
            if ($galleryFiles['error'][$i] === UPLOAD_ERR_OK) {
                $imgName = uniqid() . '_' . basename($galleryFiles['name'][$i]);
                $target = '../../image/' . $imgName;
                if (move_uploaded_file($galleryFiles['tmp_name'][$i], $target)) {
                    $stmt2 = $conn->prepare("INSERT INTO event_gallery (event_id, image) VALUES (?, ?)");
                    $stmt2->bind_param("is", $id, $imgName);
                    $stmt2->execute();
                }
            }
        }
    }

    echo json_encode(['success' => true]);
    exit;
}

// ADD new event (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $start_time = $_POST['start_time'] ?? null;
    $end_time = $_POST['end_time'] ?? null;
    $location = $_POST['location'] ?? null;
    $description = $_POST['description'] ?? null;
    $instructor = $_POST['instructor'] ?? null;
    $image = null;

    // Handle main image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imgName = uniqid() . '_' . basename($_FILES['image']['name']);
        $target = '../../image/' . $imgName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image = $imgName;
        }
    }

    $stmt = $conn->prepare("INSERT INTO events (title, date, start_time, end_time, location, description, image, instructor) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $title, $date, $start_time, $end_time, $location, $description, $image, $instructor);
    $stmt->execute();
    $event_id = $conn->insert_id;

    // Handle gallery images (max 10)
    if (isset($_FILES['gallery_images'])) {
        $galleryFiles = $_FILES['gallery_images'];
        $count = min(count($galleryFiles['name']), 10);
        for ($i = 0; $i < $count; $i++) {
            if ($galleryFiles['error'][$i] === UPLOAD_ERR_OK) {
                $imgName = uniqid() . '_' . basename($galleryFiles['name'][$i]);
                $target = '../../image/' . $imgName;
                if (move_uploaded_file($galleryFiles['tmp_name'][$i], $target)) {
                    $stmt2 = $conn->prepare("INSERT INTO event_gallery (event_id, image) VALUES (?, ?)");
                    $stmt2->bind_param("is", $event_id, $imgName);
                    $stmt2->execute();
                }
            }
        }
    }

    echo json_encode(['success' => true, 'id' => $event_id]);
    exit;
}

// DELETE event
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM events WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['error' => 'Invalid request']); 