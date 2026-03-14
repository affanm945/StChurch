<?php
header('Content-Type: application/json');
require_once '../db_config.php';
$conn = OpenCon();

$method = $_SERVER['REQUEST_METHOD'];

// GET all stats
if ($method === 'GET') {
    $result = $conn->query("SELECT stat_key, stat_value, stat_label FROM church_stats ORDER BY id ASC");
    $stats = [];
    while ($row = $result->fetch_assoc()) {
        $stats[$row['stat_key']] = [
            'value' => intval($row['stat_value']),
            'label' => $row['stat_label']
        ];
    }
    echo json_encode($stats);
    exit;
}

// UPDATE stats (POST)
if ($method === 'POST') {
    $stats = json_decode(file_get_contents('php://input'), true);
    
    if (!$stats || !is_array($stats)) {
        echo json_encode(['success' => false, 'error' => 'Invalid data']);
        exit;
    }
    
    $conn->begin_transaction();
    
    try {
        foreach ($stats as $key => $value) {
            if (is_numeric($value)) {
                $stmt = $conn->prepare("UPDATE church_stats SET stat_value = ? WHERE stat_key = ?");
                $stmt->bind_param("is", $value, $key);
                $stmt->execute();
            }
        }
        
        $conn->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

echo json_encode(['error' => 'Invalid request']);

