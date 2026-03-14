<?php
header('Content-Type: application/json');
require_once '../db_config.php';
$conn = OpenCon();

$method = $_SERVER['REQUEST_METHOD'];

// GET all timings
if ($method === 'GET') {
    $days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
    $data = [];
    foreach ($days as $day) {
        $stmt = $conn->prepare("SELECT * FROM mass_timings WHERE day=? ORDER BY time ASC");
        $stmt->bind_param("s", $day);
        $stmt->execute();
        $result = $stmt->get_result();
        $data['regular'][$day] = $result->fetch_all(MYSQLI_ASSOC);
    }
    // Special timings (now with date)
    $result = $conn->query("SELECT * FROM special_mass_timings ORDER BY date DESC, time ASC");
    $data['special'] = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($data);
    exit;
}

// ADD new timing
if ($method === 'POST' && !isset($_POST['_method'])) {
    $type = $_POST['type'] ?? 'regular';
    if ($type === 'special') {
        $date = $_POST['date'] ?? '';
        $day = $_POST['day'] ?? '';
        $time = $_POST['time'] ?? '';
        $desc = $_POST['description'] ?? '';
        $stmt = $conn->prepare("INSERT INTO special_mass_timings (date, day, time, description) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $date, $day, $time, $desc);
        $stmt->execute();
        echo json_encode(['success' => true, 'id' => $conn->insert_id]);
        exit;
    } else {
        $day = $_POST['day'] ?? '';
        $time = $_POST['time'] ?? '';
        $desc = $_POST['description'] ?? '';
        $stmt = $conn->prepare("INSERT INTO mass_timings (day, time, description) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $day, $time, $desc);
        $stmt->execute();
        echo json_encode(['success' => true, 'id' => $conn->insert_id]);
        exit;
    }
}

// UPDATE timing
if ($method === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
    $type = $_POST['type'] ?? 'regular';
    $id = intval($_POST['id']);
    if ($type === 'special') {
        $date = $_POST['date'] ?? '';
        $day = $_POST['day'] ?? '';
        $time = $_POST['time'] ?? '';
        $desc = $_POST['description'] ?? '';
        $stmt = $conn->prepare("UPDATE special_mass_timings SET date=?, day=?, time=?, description=? WHERE id=?");
        $stmt->bind_param("ssssi", $date, $day, $time, $desc, $id);
        $stmt->execute();
        echo json_encode(['success' => true]);
        exit;
    } else {
        $day = $_POST['day'] ?? '';
        $time = $_POST['time'] ?? '';
        $desc = $_POST['description'] ?? '';
        $stmt = $conn->prepare("UPDATE mass_timings SET day=?, time=?, description=? WHERE id=?");
        $stmt->bind_param("sssi", $day, $time, $desc, $id);
        $stmt->execute();
        echo json_encode(['success' => true]);
        exit;
    }
}

// DELETE timing
if ($method === 'DELETE') {
    $type = $_GET['type'] ?? 'regular';
    $id = intval($_GET['id']);
    if ($type === 'special') {
        $stmt = $conn->prepare("DELETE FROM special_mass_timings WHERE id=?");
    } else {
        $stmt = $conn->prepare("DELETE FROM mass_timings WHERE id=?");
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['error' => 'Invalid request']);