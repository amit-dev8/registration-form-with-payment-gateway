<?php
session_start();
require("connection.php");

if ($conn->connect_error) {
    die("DB Connection Failed: " . $conn->connect_error);
}

$success = "";
$error   = "";

$name      = $_SESSION['name'] ?? '';
$mobile    = $_SESSION['mobile'] ?? '';
$email     = $_SESSION['email'] ?? '';
$city      = $_SESSION['city'] ?? '';
$pincode   = $_SESSION['pincode'] ?? '';
$gala_dinner = $_SESSION['gala_dinner'] ?? '';
$sessions  = $_SESSION['sessions'] ?? '';
$codes     = $_SESSION['codes'] ?? '';
$threedays = $_SESSION['threedays'] ?? '';

$codesString2 = implode(',', array_unique($codes));

$check = $conn->prepare("SELECT id FROM free WHERE mobile = ?");
$check->bind_param("s", $mobile);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {

    $error = "Mobile number already registered";

} else {

    /* =======================
       INSERT DATA
    ======================= */
    $stmt = $conn->prepare("
        INSERT INTO free 
        (fname, mobile, email, city, pincode, sessions_data, threedays, galadinner, coupons_codes)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "sssssssss",
        $name,
        $mobile,
        $email,
        $city,
        $pincode,
        $sessions,
        $threedays,
        $gala_dinner,
        $codesString
    );

    if ($stmt->execute()) {

        $newid = $conn->insert_id;
        $urn   = "KRIF" . str_pad($newid, 5, "0", STR_PAD_LEFT);

        /* =======================
           UPDATE URN
        ======================= */
        $update = $conn->prepare("UPDATE free SET urn = ? WHERE id = ?");
        $update->bind_param("si", $urn, $newid);
        $update->execute();
        $update->close();

        //$success = "Registration Successful!";
            // QR code data
    $qrData = "$urn:$name::::$city::$email:$mobile";
    $encodedData = urlencode($qrData);
    $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=$encodedData";

    $qrFolder = 'qr/';
    if (!is_dir($qrFolder)) {
        mkdir($qrFolder, 0777, true);
        }

    $qrFilePath = $qrFolder . $urn . ".png";
    $qrImageContent = file_get_contents($qrUrl);

    if (file_put_contents($qrFilePath, $qrImageContent)) {
        // Update coupon status only if coupons were used
        if (!empty($codes)) {
            // Create placeholders (?, ?, ?)
            $placeholders = implode(',', array_fill(0, count($codes), '?'));

            $sql = "
                UPDATE discounts
                SET status = 'used'
                WHERE status = 'pending'
                AND code IN ($placeholders)
            ";

            $stmt2 = $conn->prepare($sql);

            // Bind dynamically
            $types2 = str_repeat('s', count($codes));
            $stmt2->bind_param($types2, ...$codes);

            $stmt2->execute();
            $stmt2->close();
        }
        $_SESSION['urn']  = $urn;
        $_SESSION['name'] = $name;
        //$_SESSION['booking_id'] = $booking_id;
        header("Location: thanku.php");
        exit;

$stmt2->close();

    } else {
        // Failed to save QR code       
    }



    } else {
        $error = "Database Error!";
    }

    $stmt->close();
}

$check->close();

/* =======================
   CLEAR SESSION
======================= */
session_destroy();

?>
 
