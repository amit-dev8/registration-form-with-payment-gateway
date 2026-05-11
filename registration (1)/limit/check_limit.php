<?php
$host    = 'localhost';
$db      = 'u630071588_kriyamahotsav';
$user    = 'u630071588_kriyamahotsav';
$pass    = 'Kriya@68928';
$charset = 'utf8mb4';
include 'connection.php';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

header('Content-Type: text/plain; charset=utf-8');

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    if (empty($_POST['city'])) {
        echo 'no_city_provided';
        exit;
    }

    //$city = trim($_POST['city']);
    $city = (int) $_POST['city'];
    //$city = 1;

    // =============================
$limitStmt = $pdo->prepare("
    SELECT limit_value, sessions_name 
    FROM day1_sessions 
    WHERE id = :city
    LIMIT 1
");

$limitStmt->execute(['city' => $city]);
$limitRow = $limitStmt->fetch(PDO::FETCH_ASSOC);

$limit_value = $limitRow['limit_value'];
$sessions_name = $limitRow['sessions_name'];

    if (!$limit_value) {
        echo 'available';
        exit;
    }

    $limit = (int)$limit_value;

    // =============================
    // 2. Count registrations that contain city name inside sessions_data
    // =============================
    $countStmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM registrations 
        WHERE sessions_data LIKE :searchText
    ");

    $countStmt->execute(['searchText' => "%{$sessions_name}%"]);
    $totalUsed = (int)$countStmt->fetchColumn();

    // =============================
    // 3. Compare limit vs current used
    // =============================
    echo ($limit >= $totalUsed) ? 'exists' : 'available';

} catch (PDOException $e) {
    echo 'error';
}

