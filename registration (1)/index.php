<?php
require('connection.php');
// Fetch sessions with prices from DB.
$all_sessions_res = mysqli_query($conn, "SELECT id, day, time_slot, sessions_name, IFNULL(price,0) AS price, IFNULL(value,0) AS discount_eligible FROM day1_sessions");
$sessions = [];
while ($row = mysqli_fetch_assoc($all_sessions_res)) {
    $sessions[$row['id']] = $row;
}

// Prepare separate result sets per day/time for populating selects (preserve original structure)
$d1_morning = mysqli_query($conn, "SELECT id, sessions_name FROM day1_sessions WHERE day='day 1' AND time_slot='morning'");
$d1_afternoon = mysqli_query($conn, "SELECT id, sessions_name FROM day1_sessions WHERE day='day 1' AND time_slot='afternoon'");
$d1_full = mysqli_query($conn, "SELECT id, sessions_name FROM day1_sessions WHERE day='day 1' AND time_slot='full'");

$d2_morning = mysqli_query($conn, "SELECT id, sessions_name FROM day1_sessions WHERE day='day 2' AND time_slot='morning'");
$d2_afternoon = mysqli_query($conn, "SELECT id, sessions_name FROM day1_sessions WHERE day='day 2' AND time_slot='afternoon'");
$d2_full = mysqli_query($conn, "SELECT id, sessions_name FROM day1_sessions WHERE day='day 2' AND time_slot='full'");

$d3_morning = mysqli_query($conn, "SELECT id, sessions_name FROM day1_sessions WHERE day='day 3' AND time_slot='morning'");
$d3_afternoon = mysqli_query($conn, "SELECT id, sessions_name FROM day1_sessions WHERE day='day 3' AND time_slot='afternoon'");
$d3_full = mysqli_query($conn, "SELECT id, sessions_name FROM day1_sessions WHERE day='day 3' AND time_slot='full'");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
        <style>
        body {
            background: #e9edf2;
            font-family: "Segoe UI", Roboto, sans-serif;
        }

        .reg-card {
            max-width: 940px;
            margin: auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border-left: 6px solid #fcca01;
            border-right: 6px solid #fcca01;
        }


        .accent-bar {
            height: 6px;
            /* background: #fcca01; */

        }

        .reg-header {
            padding: 22px 30px 10px;
            text-align: center;
        }

        .reg-header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .reg-header p {
            font-size: 14px;
            color: #6c757d;
        }

        .reg-body {
            padding: 30px;

        }

        .mobile-hint {
            font-weight: 500;
            font-style: normal;
            font-size: 13px;
            display: block;
            margin-top: 4px;
        }


        label {
            font-weight: 500;
            font-size: 14px;
        }

        .form-control {
            border-radius: 6px;
            padding: 10px 12px;
        }


        .submit-btn {
            padding: 10px 44px;
            font-size: 16px;
            border-radius: 6px;
            border-color: #3f90c8;
        }
        
        .site-footer {
            background: #f8f8f8;
            padding: 15px;
            text-align: center;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 18px;
            font-size: 10px;
            border-top: 1px solid #ddd;
        }
        
        .site-footer a {
            color: #c4c4c4;
            text-decoration: none;
        }
        
        .site-footer a:hover {
            color: #000;
            text-decoration: underline;
        }


        @media (max-width: 576px) {
            .reg-body {
                padding: 20px;
            }

        }
        #totalPrice{
            font-size: 24px;
            font-weight:500;    
        }
        

        /* Session container */
        .session-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 20px;
            min-height: 170px;
            transition: box-shadow 0.2s ease;
        }

        .session-card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        }

        /* Day heading */
        .session-card h5 {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 12px;
        }

        /* Question label */
        .session-card label {
            font-size: 14px;
            color: #374151;
        }

        /* Radio row */
        .session-card label input[type="radio"] {
            margin-left: 6px;
            margin-right: 12px;
        }

        /* Dropdowns */
        .session-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 14px 16px;
            /* ⬅ smaller padding */
            min-height: auto;
            /* ⬅ remove big height */
            transition: box-shadow 0.2s ease;
        }

        .session-card:hover {
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.05);
        }

        .session-card h5 {
            font-size: 15px;
            /* ⬅ smaller */
            font-weight: 600;
            margin-bottom: 8px;
        }

        .session-card label {
            font-size: 13px;
            /* ⬅ compact */
            font-weight: 500;
            color: #374151;
        }

        .session-card select {
            width: 100%;
            margin-top: 4px;
            padding: 6px 8px;
            /* ⬅ smaller height */
            font-size: 13px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
        }

        /* MOBILE FIX */
        @media (max-width: 768px) {
            .session-card {
                margin-bottom: 15px;
            }
        }



        /* Session section spacing */
        #lcsession,
        #lfsession {
            font-weight: 500;
            font-size: 14px;
            margin-top: 10px;
            display: inline-block;
        }

        /* Session dropdown styling */
        #csession,
        #fsession {
            width: 100%;
            max-width: 420px;
            margin-top: 6px;
            padding: 10px 12px;
            border-radius: 6px;
            border: 1px solid #ced4da;
            font-size: 14px;
            background-color: #fff;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        /* Focus effect (same as inputs) */
        #csession:focus,
        #fsession:focus {
            outline: none;
            border-color: #3f90c8;
            box-shadow: 0 0 0 0.2rem rgba(63, 144, 200, 0.25);
        }

        /* When dropdown is hidden */
        #csession[style*="display:none"],
        #fsession[style*="display:none"] {
            margin-top: 0;
        }

        /* Radio buttons spacing */
        input[type="radio"] {
            margin-right: 6px;
        }

        /* Session radio labels */
        .col-md-12 label>input[type="radio"] {
            margin-left: 6px;
            margin-right: 12px;
        }

        .qty-box {
            display: inline-flex;
            align-items: center;
            border: 1px solid #ced4da;
            border-radius: 6px;
            overflow: hidden;
            height: 42px;
            /* max-width: 200px; */
            width: 200px;
            background: #fff;
        }

        .qty-btn {
            width: 70px;
            height: 100%;
            border: none;
            background: #f1f5f9;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .qty-btn:hover {
            background: #e2e8f0;
        }

        .qty-input {
            width: 60px;
            text-align: center;
            border: none;
            outline: none;
            font-size: 14px;
            font-weight: 500;
            background: #fff;
        }

        /* Mobile friendly */
        @media (max-width: 576px) {
            .qty-box {
                max-width: 160px;
            }
        }
        /* Discount code input + Apply button styling (inlined as requested) */
        .session-option {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px;
            background: #fbfdff;
            border: 1px solid #e6eef7;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .session-option strong { flex: 1 1 auto; margin-right: 8px; }
        .session-option input[id^="discount_"] {
            width: 220px;
            max-width: 45%;
            padding: 8px 10px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            background: #fff;
            transition: box-shadow .12s ease, border-color .12s ease;
        }
        .session-option input[id^="discount_"]:focus {
            outline: none;
            border-color: #3f90c8;
            box-shadow: 0 0 0 4px rgba(63,144,200,0.08);
        }
        .session-option button {
            padding: 8px 12px;
            background: linear-gradient(135deg, #06b6d4, #0ea5e9);
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            box-shadow: 0 6px 14px rgba(14,165,233,0.12);
            transition: transform .08s ease, box-shadow .12s ease, opacity .12s ease;
        }
        .session-option button:hover { transform: translateY(-2px); }
        .session-option button:active { transform: translateY(0); }
        .session-option button[disabled] { opacity: .6; cursor: not-allowed; box-shadow: none; }
        .session-option .text-danger { margin-left: 12px; display: block; }
        @media (max-width: 576px) {
            .session-option { flex-direction: column; align-items: stretch; }
            .session-option input[id^="discount_"] { width: 100%; max-width: 100%; }
            .session-option button { width: 100%; }
        }
        input::placeholder {
  font-style: italic;
}

.session-card { padding: 10px; background: #f5f5f5; margin-bottom: 10px; }
    </style>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    
    <script src="threedays5.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <div class="container my-5">
        <div class="reg-card">

            <div class="header-img">
                <img src="https://matecia.iddllp.com/ex/logo.jpg" alt="kriya 2026" class="image-fluid" height="50%" width="100%">
            </div>
            <div class="accent-bar"></div>

            <div class="reg-header">
                <h1><b>Festival Participation Registration</b></h1>
                <p>Kriya Mahotsav Film Festival welcomes artists, creators and cultural practitioners from across India to participate in screenings, discussions and networking sessions.</p>
            </div>
            <!-- <div id="formErrors" class="mb-3"></div> -->
            <p class="text-end"><a href="#" data-bs-toggle="modal" data-bs-target="#alreadyRegisteredModal">Already Registered? Retrieve Your Festival Entry Pass (QR Code)</a></p>

            <div class="reg-body">
                <form id="Form" action="pay.php" method="POST" novalidate>

                    <div class="row g-3">

                        <div class="col-12">
                            <label for="fname">Participant’s Full Name*</label>
                            <input type="text" name="fname" id="fname" class="form-control" required value="">
                            <span class="form-text text-muted"></span>                                
                        </div>

                        <div class="col-md-6">
                            <label for="mobile">Mobile Number (WhatsApp Preferred) *</label> <br>
                            <input type="tel" name="mobile" id="mobile" class="form-control" placeholder="10-digit mobile number" maxlength="10" pattern="[0-9]{10}" required value="">
                            <!--<small class="form-text text-muted mobile-hint">-->
                            <!--    Please enter your WhatsApp number-->
                            <!--</small>-->

                        </div>

                        <div class="col-md-6">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" class="form-control" required value="">
                        </div>

                        <div class="col-md-6">
                            <label for="city">City *</label>
                            <input type="text" name="city" id="city" class="form-control" required value="">
                        </div>

                        <div class="col-md-6">
                            <label for="pincode">Pincode *</label>
                            <input type="text" name="pincode" id="pincode" class="form-control" placeholder="6 digit pincode" maxlength="6" pattern="[0-9]{6}" required value="">
                        </div>
                        
                        <style>
                            .vip-checkbox {
                                width: 22px;
                                height: 22px;
                                border: 2px solid #000;   /* Strong border */
                                border-radius: 4px;
                                cursor: pointer;
                            }
                        </style>
                        
                        <div class="col-md-12 mb-3">
                            <div class="form-check d-flex align-items-start">
                                <input class="form-check-input me-3 vip-checkbox"
                                       type="radio"
                                       name="vip_gala_dinner"
                                       value="vip"
                                       id="vip_gala_dinner">
                        
                                <label class="form-check-label" for="vip_gala_dinner">
                                    <small>I would like to attend</small><br>
                                    <strong>VIP Gala Dinner & Networking Reception</strong>
                                    <span class="text-muted">(Optional – ₹ 25,000)</span><br>
                                    
                                    <small class="text-muted">
                                        <i>Includes VIP filmmaker interaction and artist networking session</i>
                                    </small>
                                </label>
                            </div>
                        </div>
                        
                        
                        <div class="col-md-12 mb-3">
                            <div class="form-check d-flex align-items-start">
                                <input class="form-check-input me-3 vip-checkbox"
                                       type="radio"
                                       name="vip_gala_dinner"
                                       value="gala"
                                       id="vip_gala_dinner_gala">
                        
                                <label class="form-check-label" for="gala_dinner">
                                    <strong>Gala Dinner & Networking Reception</strong>
                                    <span class="text-muted">(Optional – ₹ 15,000)</span><br>
                                    <small>I would like to attend</small><br>
                                    <small class="text-muted">
                                        <i>Includes Filmmaker interaction and artist networking session</i>
                                    </small>
                                </label>
                            </div>
                        </div>




                        <div class="row session-row mt-3">
                            <div class="col-md-12 session-card">
                                <input class="form-check-input me-3 vip-checkbox" type="checkbox" name="threedays" value="3days" id="threedays">
                                
                                <label class="form-check-label" for="three_days">
                                    <strong>Select 3 Day's Pass</strong></label>
                                
                            </div>
                            
                        </div>




                        <div class="row session-row mt-3">
                            <span id="err_limit" style="color:red; font-weight:bold; font-size:20px;"></span>
                            <label for="session_type" name="session_type">Select Your Festival Attendance Preference *</label>

                            <!-- DAY 1 -->
                            <div class="col-md-12 session-card">
                                <h5>Day 1: Friday 13th March 2026</h5>

                                <!-- <label>Which Session *</label><br> -->
                                <label><input type="radio" name="event_day1" onclick="toggleSession(1,'custom')">Selected Sessions</label>
                                <label><input type="radio" name="event_day1" onclick="toggleSession(1,'full')">Full-Day Access Pass</label>

                                <br><br>

                                <label id="lcmorning1" style="display:none;">Morning Session</label>
                                <select id="cmorning1" name="day1_morning" style="display:none;">
                                    <option value="">Select</option>
                                       <?php while ($r = mysqli_fetch_assoc($d1_morning)) { ?>
                                        <option value="<?=$r['id']?>"><?=$r['sessions_name']?></option>
                                    <?php } ?>   
                                </select>

                                <label id="lcafternoon1" style="display:none;">Afternoon Session</label>
                                <select id="cafternoon1" name="day1_afternoon" style="display:none;">
                                    <option value="">Select</option>
                                   
                                      <?php while ($r = mysqli_fetch_assoc($d1_afternoon)) { ?>
                                        <option value="<?=$r['id']?>"><?=$r['sessions_name']?></option>
                                    <?php } ?>
                                </select>

                                <label id="lfsession1" style="display:none;">Full Day Session</label>
                                <select id="fsession1" name="day1_full" style="display:none;">
                                    <option value="">Select</option>
                                  
                                      <?php while ($r = mysqli_fetch_assoc($d1_full)) { ?>
                                        <option value="<?=$r['id']?>"><?=$r['sessions_name']?></option>
                                    <?php } ?>
                                    
                                </select>
                            </div>
                       

                            <!-- DAY 2 -->
                            <div class="col-md-12 session-card">
                                <span id="err_limit2" style="color:red; font-weight:bold; font-size:20px;"></span>
                                <h5>Day 2: Saturday 14th March 2026</h5>
                                <label><input type="radio" name="event_day2" onclick="toggleSession(2,'custom')"> Selected Sessions</label>
                                <label><input type="radio" name="event_day2" onclick="toggleSession(2,'full')">Full-Day Access Pass</label>

                                <br><br>

                                <label id="lcmorning2" style="display:none;">Morning Session</label>
                                <select id="cmorning2" name="day2_morning" style="display:none;">
                                    <option value="">Select</option>
                                      <?php while ($r = mysqli_fetch_assoc($d2_morning)) { ?>
                                        <option value="<?=$r['id']?>"><?=$r['sessions_name']?></option>
                                    <?php } ?>
                                    
                                </select>

                                <label id="lcafternoon2" style="display:none;">Afternoon Session</label>
                                <select id="cafternoon2" name="day2_afternoon" style="display:none;">
                                    <option value="">Select</option>
                                   
                                      <?php while ($r = mysqli_fetch_assoc($d2_afternoon)) { ?>
                                        <option value="<?=$r['id']?>"><?=$r['sessions_name']?></option>
                                    <?php } ?>
                                    
                                </select>

                                <label id="lfsession2" style="display:none;">Full Day Session</label>
                                <select id="fsession2" name="day2_full" style="display:none;">
                                    <option value="">Select</option>
                                   
                                      <?php while ($r = mysqli_fetch_assoc($d2_full)) { ?>
                                        <option value="<?=$r['id']?>"><?=$r['sessions_name']?></option>
                                    <?php } ?>
                                    
                                </select>
                            </div>


                            <!-- DAY 3 -->
                            
                            <div class="col-md-12 session-card">
                                <span id="err_limit3" style="color:red; font-weight:bold; font-size:20px;"></span>
                                <h5>Day 3: Sunday 15th March 2026</h5>

                                <label><input type="radio" name="event_day3" onclick="toggleSession(3,'custom')">Selected Sessions</label>
                                <label><input type="radio" name="event_day3" onclick="toggleSession(3,'full')"> Full-Day Access Pass</label>

                                <br><br>

                                <label id="lcmorning3" style="display:none;">Morning Session</label>
                                <select id="cmorning3" name="day3_morning" style="display:none;">
                                    <option value="">Select</option>
                                   
                                                                   
                                      <?php while ($r = mysqli_fetch_assoc($d3_morning)) { ?>
                                        <option value="<?=$r['id']?>"><?=$r['sessions_name']?></option>
                                    <?php } ?>
                                    
                                </select>

                                <label id="lcafternoon3" style="display:none;">Afternoon Session</label>
                                <select id="cafternoon3" name="day3_afternoon" style="display:none;">
                                    <option value="">Select</option>
                                 
                                                                    
                                      <?php while ($r = mysqli_fetch_assoc($d3_afternoon)) { ?>
                                        <option value="<?=$r['id']?>"><?=$r['sessions_name']?></option>
                                    <?php } ?>
                                    

                                    
                                </select>

                                <label id="lfsession3" style="display:none;">Full Day Session</label>
                                <select id="fsession3" name="day3_full" style="display:none;">
                                    <option value="">Select</option>
                                    
                                                                    
                                      <?php while ($r = mysqli_fetch_assoc($d3_full)) { ?>
                                        <option value="<?=$r['id']?>"><?=$r['sessions_name']?></option>
                                    <?php } ?>
                                    

                                    
                                </select>
                            </div>
    
                        </div>

                        <!-- Cart -->
                        <div id="cartItems" class="mt-4">
                            <!-- Cart items will be rendered here -->
                        </div>

                        <hr>
                        <div class="mb-3">
                            <label class="form-label"><h5>Total Amount</h5></label>
                            <input type="text" class="form-control" id="totalPrice" name="totalPrice" readonly="">
                            <input type="hidden" id="total_amount" name="total_amount" value="0">
                            <span id="err_price" class="text-danger"></span>
                        </div>
                        
                    </div>
                    <p style="align-items:center;"><small><i>Your festival entry pass and schedule details will be shared via WhatsApp and email after confirmation. <br><strong>Your contact details cannot be used for another registration.</strong></i></small></p>

                    <div class="text-center mt-5">
                        <button type="submit" class="btn btn-primary submit-btn razorpay-payment-button" id="submit">
                            <span id="btnText">SUBMIT</span>
                        </button>

                    </div>

                </form>
            </div>
           <footer class="site-footer">
    <a href="pdf/contactus.pdf" target="_blank">Contact Us</a>
    <a href="pdf/shipping.pdf" target="_blank">Shipping Policy</a>
    <a href="pdf/terms.pdf" target="_blank">Terms & Conditions</a>
    <a href="pdf/refund.pdf" target="_blank">Cancellations & Refunds</a>
    <a href="pdf/privacy_policy.pdf" target="_blank">Privacy Policy</a>
</footer>


        </div>
        <!-- Modal -->
        <div class="modal fade" id="alreadyRegisteredModal" tabindex="-1" aria-labelledby="alreadyRegisteredModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="alreadyRegisteredModalLabel">Enter Your Registered Mobile Number</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="searchForm" action="searchqr.php" method="POST">
                            <div class="mb-3">
                                <label for="searchmobile" class="form-label">Enter Mobile Number</label>
                                <input type="tel" class="form-control" id="searchmobile" name="searchmobile"
                                    pattern="[0-9]{10}" required>
                                <div class="invalid-feedback">
                                    Please provide your phone number.
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" style="width:100%">Search</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

<script>
    
    //let sessionName = $("#cmorning1 option:selected").text();
    function toggleSession(day, type) {
        if (type === 'custom') {
            document.getElementById('lcmorning' + day).style.display = 'block';
            document.getElementById('cmorning' + day).style.display = 'block';
            document.getElementById('lcafternoon' + day).style.display = 'block';
            document.getElementById('cafternoon' + day).style.display = 'block';
            document.getElementById('lfsession' + day).style.display = 'none';
            document.getElementById('fsession' + day).style.display = 'none';
        } else if (type === 'full') {
            document.getElementById('lcmorning' + day).style.display = 'none';
            document.getElementById('cmorning' + day).style.display = 'none';
            document.getElementById('lcafternoon' + day).style.display = 'none';
            document.getElementById('cafternoon' + day).style.display = 'none';
            document.getElementById('lfsession' + day).style.display = 'block';
            document.getElementById('fsession' + day).style.display = 'block';
        }
    }

    const SESSION_DATA = <?php echo json_encode($sessions); ?>;

    const discountApplied = {};
    const discountInvalid  = {};

    function getPrice(sessionId) {
        if (!sessionId) return 0;
        const id = String(sessionId);
        if (SESSION_DATA && SESSION_DATA[id] && SESSION_DATA[id].price) {
            return Number(SESSION_DATA[id].price) || 0;
        }
        return 0;
    }

    function formatCurrency(n) {
        return '₹ ' + n;
    }

    function calculateTotal() {
        let total = 0;
        const cart = [];

        const selectedSelects = [
            'cmorning1', 'cafternoon1', 'fsession1',
            'cmorning2', 'cafternoon2', 'fsession2',
            'cmorning3', 'cafternoon3', 'fsession3'
        ];

        selectedSelects.forEach(selId => {
            const sel = document.getElementById(selId);
            if (sel && sel.value) {
                const sid = String(sel.value);
                const price = getPrice(sid);
                let discountedPrice = price;
                let discountInfo = null;

                if (discountApplied[sid]) {
                    discountInfo = discountApplied[sid];
                    if (discountInfo.type === 'percent') {
                        discountedPrice = price * (1 - discountInfo.value / 100);
                    } else if (discountInfo.type === 'fixed') {
                        discountedPrice = Math.max(0, price - discountInfo.value);
                    }
                }

                total += discountedPrice;

                cart.push({
                    sid: sid,
                    name: SESSION_DATA[sid].sessions_name,
                    price: price,
                    discountedPrice: discountedPrice,
                    discountEligible: SESSION_DATA[sid].discount_eligible == 1,
                    discountInfo: discountInfo
                });
            }
        });

        /* -----------------------------
           FIXED: VIP / GALA DINNER LOGIC
        ------------------------------*/
        
        const galaSelected = document.querySelector('input[name="vip_gala_dinner"]:checked');

        if (galaSelected) {
            let price = 0;
            let dinner_name = "";

            if (galaSelected.value === "vip") {
                price = 25000;
                dinner_name = "VIP GALA DINNER";
            } else if (galaSelected.value === "gala") {
                price = 15000;
                dinner_name = "GALA DINNER";
            }

            total += price;

            cart.push({
                sid: galaSelected.value,
                name: dinner_name,
                price: price,
                discountedPrice: price,
                discountEligible: false,
                discountInfo: null
            });
        }
        
/* -----------------------------
   3 DAYS FESTIVAL PASS
------------------------------*/
const threedays = document.querySelector('input[name="threedays"]:checked');

if (threedays) {
    const threedays_price = 15000;
    const pass_name = "3-Days Festival Pass";

    total += threedays_price;

    cart.push({
        sid: "three_days_pass",
        name: pass_name,
        price: threedays_price,
        discountedPrice: threedays_price,
        discountEligible: false,
        discountInfo: null
    });
}


        // render cart
        const cartEl = document.getElementById('cartItems');
        if (cartEl) {
            let html = '<h5>Selected Items</h5>';
            if (cart.length === 0) {
                html += '<p>No items selected.</p>';
            } else {
                cart.forEach(item => {
                    html += `<div class="session-option">
                        <strong>${item.name}</strong><br>
                        Price: ${formatCurrency(item.price)}`;

                    if (item.discountInfo) {
                        html += ` (Discount: ${item.discountInfo.type} ${item.discountInfo.value})`;
                    }

                    if (item.discountEligible) {
                        const disabled = item.discountInfo ? 'disabled' : '';
                        const value = item.discountInfo ? `value="${item.discountInfo.code}"` : '';
                        html += `<br><input type="text" placeholder="Delegate Code" id="discount_${item.sid}" ${value} ${disabled}> 
                                 <button type="button" onclick="applyDiscountToSession('${item.sid}')" ${disabled}>Apply</button>`;

                        if (discountInvalid[item.sid]) {
                            html += `<span class="text-danger">${discountInvalid[item.sid]}</span>`;
                        }
                    }

                    html += '</div>';
                });
            }
            cartEl.innerHTML = html;
        }

        document.getElementById("totalPrice").value = total;
        document.getElementById("total_amount").value = formatCurrency(total);
    }

    async function applyDiscountToSession(sessionId) {
        const codeEl = document.getElementById(`discount_${sessionId}`);
        if (!codeEl) return;

        const code = codeEl.value.trim();
        if (!code) {
            delete discountApplied[sessionId];
            delete discountInvalid[sessionId];
            calculateTotal();
            return;
        }

        const usedCodes = Object.values(discountApplied).map(d => d.code);
        if (usedCodes.includes(code)) {
            discountInvalid[sessionId] = 'This discount code is already used on another session.';
            calculateTotal();
            return;
        }

        try {
            const response = await fetch('apply_discount.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ code: code, sessionId: sessionId })
            });

            const result = await response.json();
            if (result.success) {
                discountApplied[sessionId] = result.discount;
                delete discountInvalid[sessionId];
            } else {
                delete discountApplied[sessionId];
                discountInvalid[sessionId] = result.message;
            }
        } catch (error) {
            discountInvalid[sessionId] = 'Error applying discount';
        }

        calculateTotal();
    }

    function injectDiscountsToForm() {
        document.querySelectorAll('input[name^="discounts["]').forEach(el => el.remove());

        Object.keys(discountApplied).forEach(sid => {
            const disc = discountApplied[sid];
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `discounts[${sid}][code]`;
            input.value = disc.code;
            document.getElementById('Form').appendChild(input);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {

        calculateTotal();

        const selects = [
            'cmorning1', 'cafternoon1', 'fsession1',
            'cmorning2', 'cafternoon2', 'fsession2',
            'cmorning3', 'cafternoon3', 'fsession3'
        ];

        selects.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('change', calculateTotal);
        });

        // FIXED: Gala Dinner Listener
        document.querySelectorAll('input[name="vip_gala_dinner"]').forEach(r => {
            r.addEventListener('change', calculateTotal);
        });
document.querySelectorAll('input[name="threedays"]').forEach(r => {
    r.addEventListener('change', calculateTotal);
});
        document.getElementById('Form').addEventListener('submit', injectDiscountsToForm);
    });
</script>
        <script src="script.js?v=1.0.1"></script>
        <script src="limitjs66.js?v=1.0.1"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        
        <script>

	</script>

</body>

</html>
