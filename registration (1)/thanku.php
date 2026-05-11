<?php
  session_start();
                
  $urn=$_SESSION['urn'];
  $name=$_SESSION['name'];
  $booking_id = $_SESSION['booking_id'];

    session_destroy();
        
    ?>

              <!DOCTYPE html>
                <html lang='en'>
                <head>
                  <meta charset='UTF-8'>
                  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                  <title>Registration Successful</title>
                  <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css'>
                  <style>
                    .header-img {
                      width: 100%;
                      max-width: 600px;
                      height: auto;
                      display: block;
                      margin: 0 auto;
                    }
                    .outer-container {
                      max-width: 600px;
                      margin: 20px auto;
                      padding: 0 10px;
                    }
                    .inner-container {
                      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
                      padding: 10px;
                      border-radius: 8px;
                      background-color: #fff;
                      margin-top: 20px;
                    }
                    .badge-content {
                      text-align: left;
                      font-size: 12px;
                    }
                    .badge-content h4 {
                      font-weight: bold;
                      text-transform: uppercase;
                      font-size: 14px;
                    }
                    .qr-code {
                      margin-top: 10px;
                      text-align: left;
                    }
                    .qr-code img {
                      border: 1px solid #ddd;
                      padding: 5px;
                    }
                    .btn-download {
                      background-color: #035041;
                      border-color: #035041;
                      color: #fff;
                      margin-top: 10px;
                    }
                    .btn-container {
                      text-align: left;
                    }
                  </style>
                </head>
                <body>
                  <div class='outer-container'>
                    <img src="https://matecia.iddllp.com/ex/logo.jpg" alt="Header Image" class="header-img">
                    <div class='inner-container' id='contentToSave'>
                      <div class='badge-content'>
                        <h4>
                          <!--<p>Dear <strong>" . htmlspecialchars($full_name) . " ,</strong></p>-->
                          <p>Dear <strong><?php echo $name; ?></strong></p>
                          <p>Thank you for your registration.</p>
                          <!-- <p>Your unique ID is: <strong>" . htmlspecialchars($urn) . "</strong></p> -->
                          <p>Your Registration ID is: <strong> <?php echo $urn; ?> </strong></p>
                          <div class='qr-code'>
                            <!-- <img src='" . htmlspecialchars($qrFilePath) . "' alt='QR Code'> -->
                            <img src="qr/<?php echo $urn; ?>.png" alt="QR Code" />                          
                          </div>
                          <br>
                          <p style='color: red;'>Please take a screenshot of this screen. You can get your badge from the Registration Counter at the event by presenting the QR code in the screenshot.</p>
                        </h4>
                        <div class='btn-container'>
                          <!--<button id='downloadPdf' class='btn btn-download'>Download</button>--> 
                        </div>
                      </div>
                    </div>
                  </div>
                </body>
                </html>
                
              