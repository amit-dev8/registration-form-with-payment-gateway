<?php
include('connection.php');

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$code = $data['code'] ?? '';
$sessionId = $data['sessionId'] ?? '';

if (!$code || !$sessionId) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

// Check if session is eligible
$query = mysqli_query($conn, "SELECT value FROM day1_sessions WHERE id = " . intval($sessionId));
if (!$query || !($row = mysqli_fetch_assoc($query)) || $row['value'] != 1) {
    echo json_encode(['success' => false, 'message' => 'Session not eligible for discount']);
    exit;
}

// Check discount code
$query = mysqli_query($conn, "SELECT * FROM discounts WHERE code = '" . mysqli_real_escape_string($conn, $code) . "' AND status = 'new'");
if (!$query || !($discount = mysqli_fetch_assoc($query))) {
    echo json_encode(['success' => false, 'message' => 'Invalid discount code']);
    exit;
}

// Check if applies to session (if applies_to is set, but for now assume all)
echo json_encode(['success' => true, 'discount' => $discount]);
?>