<?php
require '../New folder/vendor/autoload.php';
require '../smtp1/PHPMailerAutoload.php';
require('config.php');
session_start();

/* ---------- DB CONNECTION ---------- */
$conn = mysqli_connect($host, $user, $pass, $db);
/*if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}*/

/* ---------- RAZORPAY ---------- */
require('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

$success = true;
$error   = "Payment Failed";

$keyId = 'rzp_test_MFboRbFioap5rK';
$keySecret = 'uT7G4Yem6nY4EG0VJB4AYX3i';

/* ---------- PAYMENT VERIFICATION ---------- */
if (!empty($_POST['razorpay_payment_id']) === false) {

    $api = new Api($keyId, $keySecret);

    try {
        $attributes = array(
            'razorpay_order_id'   => $_SESSION['razorpay_order_id'],
            'razorpay_payment_id'=> $_POST['razorpay_payment_id'],
            'razorpay_signature' => $_POST['razorpay_signature']
        );
        $api->utility->verifyPaymentSignature($attributes);
    } catch (SignatureVerificationError $e) {
        $success = false;
        $error = 'Razorpay Error: ' . $e->getMessage();
    }
    $booking_id = $_SESSION['booking_id'] ?? '';
}

/* ---------- SUCCESS FLOW ---------- */
if ($success === true) {

    /* ---------- SESSION DATA ---------- */
    $razorpay_order_id   = $_SESSION['razorpay_order_id'];
    $razorpay_payment_id = $_POST['razorpay_payment_id'];


    $name      = $_SESSION['name'];
    $mobile    = $_SESSION['mobile'];
    $email     = $_SESSION['email'];
    $city      = $_SESSION['city'];
    $pincode   = $_SESSION['pincode'];
    $gala_dinner = $_SESSION['gala_dinner'];
    $price  = (float) ($_SESSION['price'] ?? 0);
    $sessions  = $_SESSION['sessions'] ?? '';
    $codes     = $_SESSION['codes'];
    $booking_id = $_SESSION['booking_id'];
    $threedays = $_SESSION['threedays'];

    $codesString2 = implode(',', array_unique($codes));

    /* ---------- INSERT ---------- */
    $sql = "INSERT INTO registrations
        (urn, fname, mobile, email, city, pincode, galadinner, coupons_codes,
        total_amount, sessions_data, threedays, payment_id, order_id, payment_status, booking_id)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $status = 'success';
    $urn = '';

    $stmt->bind_param(
        "ssssssssdssssss",
        $urn,
        $name,
        $mobile,
        $email,
        $city,
        $pincode,
        $gala_dinner,
        $codesString2,
        $price,
        $sessions,
        $threedays,
        $razorpay_payment_id,
        $razorpay_order_id,
        $status,
        $booking_id
    );

    if (!$stmt->execute()) {
        die("Insert failed: " . $stmt->error);
    }

    $newid = mysqli_insert_id($conn);
    $stmt->close();

    /* ---------- UPDATE URN ---------- */
    $urn = "KRI" . str_pad($newid, 3, "0", STR_PAD_LEFT);

    $update = $conn->prepare("UPDATE registrations SET urn = ? WHERE id = ?");
    if ($update) {
        $update->bind_param("si", $urn, $newid);
        $update->execute();
        $update->close();
    }

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
        
        
                        $mail = new PHPMailer;
                        $mail->IsSMTP();
                        $mail->Host = 'smtp.hostinger.com';
                        $mail->Port = '587';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'no-reply@b.interfacedataanddesignllp.com';
                        $mail->Password = 'Interface@68928';
                        $mail->SMTPSecure = 'tls';
                        $mail->From = 'no-reply@b.interfacedataanddesignllp.com';
                        $mail->FromName = 'INTERFACE DATA AND DESIGN REGISTRATION';
                        $mail->AddAddress($email);
                        $mail->WordWrap = 50;
                        $mail->IsHTML(true);
                        $mail->Subject = 'VISITOR REGISTRATION SUCCESSFULL';
                        $mail->Body = '
                        <html>
                        <body>
                        <p>Dear '. $name . ',</p>
                        <p>Thank you for your registration. We are delighted to have you join us for this event.</p>
                        <p>Show this QR code at the spot registration desk to collect badge</p>
                        <p>Best Regards,<br><strong>Team Interface data and design llp</strong></p>
                        <img src="cid:'.$urn.'" width="40%" height="40%">
                        </body>
                        </html>';
                        
                        $image_path = $qrFilePath;
                        $image_data = file_get_contents($image_path);
                        $mail->addStringEmbeddedImage($image_data, $urn, $urn.'.png', 'base64', 'image/png');
                        $mail->AltBody = '';

                        $result = $mail->Send();

                        if ($output == '') {
                            //header("Location: verify.php");
                            //exit();
                        }     
        
        
        

        
        
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

        // Optional
        // echo "Updated rows: " . $stmt->affected_rows;

        $_SESSION['urn']  = $urn;
        $_SESSION['name'] = $name;
        $_SESSION['booking_id'] = $booking_id;
        header("Location: thanku.php");
        exit;

$stmt2->close();

    } else {
        // Failed to save QR code       
    }

    /* ---------- STORE SESSION ---------- */


} else {
    echo "Payment failed";
    exit;
}

mysqli_close($conn);
?>
