<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('connection.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "No data submitted.";
    exit;
}

$selectedSessions = [];
$selectIds = ['day1_morning', 'day1_afternoon', 'day1_full', 'day2_morning', 'day2_afternoon', 'day2_full', 'day3_morning', 'day3_afternoon', 'day3_full'];
foreach ($selectIds as $sel) {
    if (!empty($_POST[$sel])) {
        $sid = intval($_POST[$sel]);
        $selectedSessions[] = $sid;
    }
}

if (empty($_POST['gala_dinner']) && empty($selectedSessions)) $errors[] = "At least one session or Gala Dinner must be selected.";

if (!empty($errors)) {
    echo "<h2>Validation Errors:</h2><ul>";
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul><a href='index.php'>Go Back</a>";
    exit;
}


// store mobile

$fname      = $_POST['fname'] ?? '';
$email      = $_POST['email'] ?? '';
$city       = $_POST['city'] ?? '';
$mobile     = $_POST['mobile'] ?? '';
$pincode    = $_POST['pincode'] ?? '';
$gala_dinner = $_POST['gala_dinner'] ?? '';
$totalPrice = $_POST['totalPrice'] ?? '';
$total_amount = $_POST['total_amount'] ?? 0;

/* ===============================
//   BUILD SESSIONS + NAMES (JSON)
//   =============================== */

$sessionsData = [];

$discounts = $_POST['discounts'] ?? [];

foreach ($selectedSessions as $sid) {
    // Get session name
    $sessName = "Session {$sid}";
    $q = mysqli_query($conn, "SELECT sessions_name FROM day1_sessions WHERE id={$sid} LIMIT 1");
    if ($q && $r = mysqli_fetch_assoc($q)) {
        $sessName = $r['sessions_name'];
    }

    $code = $discounts[$sid]['code'] ?? '';

    $sessionsData[$sid] = [
        'session' => $sessName,
        'code' => $code
    ];
}

if ($gala_dinner === 'yes') {
    $sessionsData['gala'] = [
        'session' => 'Gala Dinner',
        'code' => ''
    ];
}

/* Convert to comma separated */
$sessionsList = [];
$codesList = [];
foreach ($sessionsData as $info) {
    $sessionsList[] = $info['session'];
    if (!empty($info['code'])) {
        $codesList[] = $info['code'];
    }
}
$sessionsString = implode(',', $sessionsList);
//$codesString = implode(',', array_unique($codesList));
// make sure codes are unique
// $codesList = array of codes you want to match

$codes = array_values(array_unique($codesList));

if (!empty($codes)) {

    // Create placeholders (?, ?, ?)
    $placeholders = implode(',', array_fill(0, count($codes), '?'));

    $sql = "
        UPDATE discounts
        SET status = 'pending'
        WHERE status = 'new'
        AND code IN ($placeholders)
    ";

    $stmt = $conn->prepare($sql);

    // Bind dynamically
    $types = str_repeat('s', count($codes));
    $stmt->bind_param($types, ...$codes);

    $stmt->execute();

    // Optional
    // echo "Updated rows: " . $stmt->affected_rows;

    $stmt->close();
}
/* ===============================
   INSERT INTO DB (ONE TABLE) - COMMENTED OUT
//   =============================== */

/*
$stmt = $conn->prepare("
    INSERT INTO registrations
    (fname, mobile, email, city, pincode, attend_16th, total_amount, sessions, codes)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "sssssisss",   // ✅ FIXED
    $fname,
    $mobile,
    $email,
    $city,
    $pincode,
    $gala_dinner,
    $total_amount,
    $sessionsString,
    $codesString
);

$stmt->execute();
if ($stmt->error) {
    echo "DB Error: " . $stmt->error;
    exit;
}
$insertId = $stmt->insert_id;
$stmt->close();
*/
$insertId = 0; // dummy
?>

<!DOCTYPE html>
<html>
<head>
    <title>Submitted Data</title>
    <style>
        body { font-family: Arial; line-height: 1.8; }
        h3 { margin-top: 20px; }
    </style>
</head>
<body>

<h2>Submitted Details</h2>

<b>Full Name:</b> <?php echo $_POST['fname']; ?><br>
<b>Mobile:</b> <?php echo $_POST['mobile']; ?><br>
<b>Email:</b> <?php echo $_POST['email']; ?><br>
<b>City:</b> <?php echo $_POST['city']; ?><br>
<b>Pincode:</b> <?php echo $_POST['pincode']; ?><br>
<b>Gala Dinner:</b> <?php echo $_POST['gala_dinner'] ?? 'no'; ?><br>

<hr>

<h3>Selected Sessions</h3>

<?php
if (!empty($sessionsData)) {
    foreach ($sessionsData as $sid => $info) {
        echo "<b>" . htmlspecialchars($info['session']) . "</b> (Code: " . htmlspecialchars($info['code']) . ")<br>";
        $temp = $info['session'];
        echo $temp;
        echo "<br>";
    }
} else {
    echo "<small>No sessions selected</small>";
}
?>

<hr>

<b>All Codes Used:</b> <?= htmlspecialchars($temp2) ?>

<hr>
<b>Total Amount:</b> <?php echo $_POST['totalPrice']; ?><br>


<hr>
<b>DB Record ID:</b> <?= $insertId ?>

</body>
</html>
