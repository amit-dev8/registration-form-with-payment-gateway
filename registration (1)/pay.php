<html>
    <head>
        <title></title>
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>       
    </head>
    <body>
        
    </body>
</html>
<?php
$host = "localhost";
$username = "u630071588_kriyamahotsav";
$password = "Kriya@68928";
$dbname = "u630071588_kriyamahotsav";


$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

session_start();

ini_set('display_errors', '0');
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);

if (!empty($_SESSION['booking_complete'])) {
    header('Location: thankyou.php');
    exit;
}

$keyId = 'rzp_test_MFboRbFioap5rK';
$keySecret = 'uT7G4Yem6nY4EG0VJB4AYX3i';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {



$selectedSessions = [];
$selectIds = ['day1_morning', 'day1_afternoon', 'day1_full', 'day2_morning', 'day2_afternoon', 'day2_full', 'day3_morning', 'day3_afternoon', 'day3_full'];
foreach ($selectIds as $sel) {
    if (!empty($_POST[$sel])) {
        $sid = intval($_POST[$sel]);
        $selectedSessions[] = $sid;
    }
}

//if (empty($_POST['gala_dinner']) && empty($selectedSessions)) $errors[] = "At least one session or Gala Dinner must be selected.";

$hasSessions = !empty($selectedSessions);   // TRUE if user picked any sessions
$hasThreeDays = !empty($_POST['threedays']); // TRUE if user selected 3-day pass

if (!$hasSessions && !$hasThreeDays) {
    $errors[] = "Please select at least one session or choose the 3-Days Pass.";
}


if (!empty($errors)) {
    echo "<h2>Validation Errors:</h2><ul>";
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul><a href='index.php'>Go Back</a>";
    exit;
}
/*
$fname      = $_POST['fname'] ?? '';
$email      = $_POST['email'] ?? '';
$city       = $_POST['city'] ?? '';
$mobile     = $_POST['mobile'] ?? '';
$pincode    = $_POST['pincode'] ?? '';
$gala_dinner = $_POST['gala_dinner'] ?? '';
$totalPrice = $_POST['totalPrice'] ?? '';
$total_amount = $_POST['total_amount'] ?? 0;
*/

    $name = htmlspecialchars($_POST['fname'] ?? '');
    $mobile = htmlspecialchars($_POST['mobile'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $city = htmlspecialchars($_POST['city'] ?? '');
    $pincode = htmlspecialchars($_POST['pincode'] ?? '');
    $gala_dinner = htmlspecialchars($_POST['vip_gala_dinner'] ?? '');
    $threedays = htmlspecialchars($_POST['threedays'] ?? '');
    //$totalPrice = htmlspecialchars($_POST['totalPrice']);

    $totalPriceRaw = $_POST['totalPrice'] ?? '0';
    // Remove any non-numeric characters except decimal point
    $totalPrices   = (float) preg_replace('/[^0-9.]/', '', $totalPriceRaw);


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

if ($gala_dinner === 'gala') {
    $sessionsData['gala'] = [
        'session' => 'Gala Dinner',
        'code' => ''
    ];
}

if($gala_dinner === 'vip'){
    $sessionsData['vip'] = [
        'session' => 'Vip Dinner',
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


$ccodes = array_values(array_unique($codesList));

// Update coupon status only if coupons were used
if (!empty($ccodes)) {
    // Create placeholders (?, ?, ?)
    $placeholders = implode(',', array_fill(0, count($ccodes), '?'));

    $sql = "
        UPDATE discounts
        SET status = 'pending'
        WHERE status = 'new'
        AND code IN ($placeholders)
    ";

    $stmt2 = $conn->prepare($sql);

    // Bind dynamically
    $types2 = str_repeat('s', count($ccodes));
    $stmt2->bind_param($types2, ...$ccodes);

    $stmt2->execute();
    $stmt2->close();
}

    if (!$name) {
        die('Invalid input. Please check your details and try again.');
    }

    $stmt = $conn->prepare("SELECT * FROM registrations WHERE mobile = ?");
    $stmt->bind_param("s", $mobile);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $stmt->close();
        //die("Mobile number already registered");
            die( "<script> Swal.fire({
            icon: 'error',
            title: 'Sorry...',
            text: 'Mobile number already registered',
              }).then(() => {
            window.location = 'index.php';
            
            });
            </script>");  
    }


    $_SESSION['name'] = $name;
    $_SESSION['mobile'] = $mobile;
    $_SESSION['email'] = $email;
    $_SESSION['city'] = $city;
    $_SESSION['pincode'] = $pincode;
    $_SESSION['gala_dinner'] = $gala_dinner;
    $_SESSION['price'] = $totalPrices;
    $_SESSION['sessions'] = $sessionsString;
    $_SESSION['codes'] = $ccodes;
    $_SESSION['threedays'] = $threedays;

    function generate_booking_id($name, $conn) {
        $prefix = 'KRIYA';
        do {
            $random = strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
            $bookingId = $prefix . '-' . $random;

            $check = $conn->prepare("SELECT id FROM registrations WHERE booking_id = ?");
            $check->bind_param("s", $bookingId);
            $check->execute();
            $check->store_result();
            $exists = $check->num_rows > 0;
            $check->close();

        } while ($exists);

        return $bookingId;
    }

    $_SESSION['booking_id'] = generate_booking_id($name, $conn);

    /* ================= RAZORPAY ORDER CREATE (CA FIXED) ================= */

    // Debug: Log the price calculation
    $amountInPaise = (int) round($totalPrices * 100);
    error_log("DEBUG - totalPriceRaw: {$totalPriceRaw}, totalPrices: {$totalPrices}, amountInPaise: {$amountInPaise}");
    
    // Validate minimum amount (₹1 = 100 paise)
    if ($amountInPaise < 100) {
        //die("Error: Total amount must be at least ₹1. Current amount: ₹{$totalPrices}");
    $_SESSION['name'] = $name;
    $_SESSION['mobile'] = $mobile;
    $_SESSION['email'] = $email;
    $_SESSION['city'] = $city;
    $_SESSION['pincode'] = $pincode;
    $_SESSION['gala_dinner'] = $gala_dinner;
    //$_SESSION['price'] = $totalPrices;
    $_SESSION['sessions'] = $sessionsString;
    $_SESSION['codes'] = $ccodes;
    $_SESSION['threedays'] = $threedays;

        header("Location: redirect.php");
        exit;
    }

    $orderData = [
        'receipt'         => uniqid(),
        'amount'          => $amountInPaise,
        'currency'        => 'INR',
        'payment_capture' => 1
    ];


    $ch = curl_init("https://api.razorpay.com/v1/orders");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_USERPWD        => $keyId . ":" . $keySecret,
        CURLOPT_HTTPHEADER     => ["Content-Type: application/json"],
        CURLOPT_POSTFIELDS     => json_encode($orderData),

        // ✅ CA CERTIFICATE IMPLEMENTATION
        CURLOPT_CAINFO         => "/home/u630071588/domains/iddllp.com/public_html/cacert.pem",
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_SSLVERSION     => CURL_SSLVERSION_TLSv1_2,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
    ]);

    // Execute cURL request (ONLY ONCE)
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if ($response === false) {
        $curlError = curl_error($ch);
        curl_close($ch);
        die("Payment gateway connection failed: " . $curlError);
    }

    // Validate HTTP response code
    if ($httpCode !== 200) {
        curl_close($ch);
        // Log the actual error response for debugging
        error_log("Razorpay Order Creation Failed - HTTP Code: {$httpCode}, Response: " . $response);
        
        $errorData = json_decode($response, true);
        $errorMsg = isset($errorData['error']['description']) ? $errorData['error']['description'] : 'Unable to create payment order';
        die("Payment Error: " . htmlspecialchars($errorMsg));
    }
    curl_close($ch);

    // Safely decode and validate JSON response
    $razorpayOrder = json_decode($response, true);
    if (!$razorpayOrder || !isset($razorpayOrder['id'])) {
        error_log("Invalid Razorpay response: " . $response);
        die("Payment gateway returned invalid response. Please try again.");
    }
    $_SESSION['razorpay_order_id'] = $razorpayOrder['id'];

    /* ================= CHECKOUT DATA ================= */

    $checkoutData = [
        "key"         => $keyId,
        "amount"      => $orderData['amount'],
        "name"        => "INTERFACE DATA AND DESIGN LLP",
        "description" => "Payment for Registration",
        "image"       => "https://matecia.iddllp.com/ex/logo.jpg",
        "order_id"    => $_SESSION['razorpay_order_id'],
        "prefill"     => [
            "name"    => $name,
            "email"   => $email,
            "contact" => $mobile,
        ],
        "theme" => [
            "color" => "#efd266"
        ]
    ];

    $jsonCheckoutData = json_encode($checkoutData);
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST') : ?>
        <form id="razorpay-form">
<script>
    var options = <?php echo $jsonCheckoutData; ?>;

    // show a processing overlay during final submission to verify.php
    function showProcessingOverlay(text) {
        var el = document.getElementById('processingOverlay');
        if (!el) {
            el = document.createElement('div');
            el.id = 'processingOverlay';
            el.style.position = 'fixed';
            el.style.left = 0;
            el.style.top = 0;
            el.style.width = '100%';
            el.style.height = '100%';
            el.style.background = 'rgba(0,0,0,0.6)';
            el.style.zIndex = 99999;
            el.style.display = 'flex';
            el.style.alignItems = 'center';
            el.style.justifyContent = 'center';
            el.style.color = '#fff';
            el.innerHTML = '<div style="text-align:center">\n  <div class="spinner-border text-light" role="status" style="width:4rem;height:4rem;"></div>\n  <div style="margin-top:1rem;font-weight:700">'+(text||'Processing your payment...')+'</div>\n</div>';
            document.body.appendChild(el);
        } else {
            el.style.display = 'flex';
            el.innerText = text || 'Processing your payment...';
        }
    }

    // redirect to index (used on checkout dismiss or failure) — slight delay to make UX natural
    function redirectToIndex() {
        setTimeout(function () { window.location = 'index.php'; }, 300);
    }

    // If user completes payment, show a processing spinner and POST to verify.php
    options.handler = function (response) {
        // show overlay and send all required fields to verify.php
        showProcessingOverlay('Verifying payment, please wait...');
        var form = document.createElement("form");
        form.method = "POST";
        form.action = "verify.php";

        //var addHidden = function(name, value) {
            var paymentInput = document.createElement("input");
            paymentInput.type = "hidden";
            paymentInput.name = "razorpay_payment_id";
            paymentInput.value = response.razorpay_payment_id;
            form.appendChild(paymentInput);
        //};

/*        addHidden('razorpay_payment_id', response.razorpay_payment_id);
        addHidden('razorpay_order_id', response.razorpay_order_id || options.order_id || '');
        addHidden('razorpay_signature', response.razorpay_signature);
*/
        document.body.appendChild(form);
        // keep overlay visible during navigation
        showProcessingOverlay('Verifying payment, please wait...');
        form.submit();
    };

    // if the checkout modal is dismissed by the user, redirect back to index to avoid leaving a blank page
    options.modal = options.modal || {};
    options.modal.ondismiss = function() {
        // small delay to ensure checkout closed animation finishes
        redirectToIndex();
    };

    var rzp = new Razorpay(options);

    // If the payment fails, redirect to index so the user does not remain on a blank page
    rzp.on('payment.failed', function (response) {
        // log to console and redirect to home
        console.error('Razorpay payment failed', response);
        redirectToIndex();
    });

    rzp.open();

    // Prevent back navigation from the payment page - redirect to homepage
    (function() {
        history.pushState(null, null, location.href);
        window.addEventListener('popstate', function () {
            window.location.replace('index.php');
        });
    })();
</script>

        </form>
    <?php else : ?>
        <!-- <form action="" method="POST">
            
            <label>Name: <input type="text" name="customername" required></label><br>
            <label>Email: <input type="email" name="email" required></label><br>
            <label>Contact No: <input type="text" name="contactno" required pattern="[0-9]{10}"></label><br>
            <input type="hidden" name="price" value="1">
            <button type="submit">Proceed to Pay</button>

        </form> -->
    <?php endif; ?>
</body>
</html>
