<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
  <!--<img src="images/ikpe.png" class="img-fluid" alt="...">-->

  <?php
    include('connection.php');
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $searchmobile = htmlspecialchars($_POST['searchmobile']);

      // Prepare and bind
      $stmt = $conn->prepare("SELECT mobile,urn,fname FROM registrations WHERE mobile = ?");
      $stmt->bind_param("s", $searchmobile);

      // Execute the statement
      $stmt->execute();

      // Get the result
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
        // Fetch the first result
        $row = $result->fetch_assoc();
        $mobile = $row["mobile"];
        $urn = $row["urn"];
        $name = $row["fname"];
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title>Registration Successful</title>
          <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
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
              padding: 0 10px; /* Adjusted padding for better fit */
            }
            .inner-container {
              box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
              padding: 10px; /* Reduced padding */
              border-radius: 8px;
              background-color: #fff;
              margin-top: 20px;
            }
            .badge-content {
              text-align: left;
              font-size: 12px; /* Reduced font size */
            }
            .badge-content h4 {
              font-weight: bold;
              text-transform: uppercase;
              font-size: 14px; /* Reduced font size */
            }
            .qr-code {
              margin-top: 10px; /* Reduced margin */
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
              margin-top: 10px; /* Reduced margin */
            }
            .btn-container {
              text-align: left;
            }
          </style>
        </head>
        <body>
          <div class="outer-container">
            <img src="https://matecia.iddllp.com/ex/logo.jpg" alt="Header Image" class="header-img">
            <div class="inner-container" id="contentToSave">
              <div class="badge-content">
                <h4>
                  <p>Dear <strong><?php echo htmlspecialchars($name); ?>,</strong></p>
                  <p>Thank you for your registration.</p>
                  <p>Your unique ID is: <strong><?php echo htmlspecialchars($urn); ?></strong></p>
                  <div class="qr-code">
                    <img src="<?php echo htmlspecialchars('qr/' . $urn . '.png'); ?>" alt="QR Code">
                  </div>
                  <br>
                  <p style="color: red;">Please take a screenshot of this screen. You can get your badge from the Registration Counter at the event by presenting the QR code in the screenshot or in the confirmation email received.</p>
                  
                </h4>
                <div class="btn-container">
                  <!--<button id="downloadPdf" class="btn btn-download">Download</button>-->
                </div>
              </div>
            </div>
          </div>

          <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
          <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
          <script type="text/javascript">
            document.getElementById('downloadPdf').addEventListener('click', async function () {
              const { jsPDF } = window.jspdf;
              const doc = new jsPDF('p', 'mm', 'a4'); // A4 size

              const content = document.getElementById('contentToSave');
              const headerImg = document.querySelector('.header-img').src;

              // Temporarily hide the download button
              const downloadBtn = document.getElementById('downloadPdf');
              downloadBtn.style.display = 'none';

              // Capture the content as an image
              const canvas = await html2canvas(content, { useCORS: true });
              const imgData = canvas.toDataURL('image/png');

              // Add header image
              const headerImgData = await fetch(headerImg)
                .then(response => response.blob())
                .then(blob => new Promise((resolve, reject) => {
                  const reader = new FileReader();
                  reader.onloadend = () => resolve(reader.result);
                  reader.onerror = reject;
                  reader.readAsDataURL(blob);
                }));

              // Get PDF page dimensions
              const pageWidth = doc.internal.pageSize.getWidth();
              const pageHeight = doc.internal.pageSize.getHeight();

              // Add header image
              const headerImgObj = new Image();
              headerImgObj.src = headerImgData;

              headerImgObj.onload = () => {
                const headerImgWidth = pageWidth;
                const headerImgHeight = headerImgObj.height * (headerImgWidth / headerImgObj.width);

                // Add the header image to the PDF
                doc.addImage(headerImgData, 'PNG', 0, 0, headerImgWidth, headerImgHeight);

                // Calculate position for content
                const contentStartY = headerImgHeight + 10; // 10 units gap between header and content

                // Add the content image below the header
                const contentImgWidth = pageWidth - 20; // 10 units padding on each side
                const contentImgHeight = canvas.height * (contentImgWidth / canvas.width);

                if (contentStartY + contentImgHeight > pageHeight) {
                  // Adjust content size to fit within the page
                  const maxContentHeight = pageHeight - contentStartY - 10; // 10 units margin at the bottom
                  const scaleFactor = maxContentHeight / contentImgHeight;
                  doc.addImage(imgData, 'PNG', 10, contentStartY, contentImgWidth * scaleFactor, maxContentHeight);
                } else {
                  // Add the content image to the PDF
                  doc.addImage(imgData, 'PNG', 10, contentStartY, contentImgWidth, contentImgHeight);
                }

                // Save the PDF with the URN as the filename
                doc.save(`${urn}.pdf`);

                // Show the download button again
                downloadBtn.style.display = 'block';
              };
            });
          </script>
        </body>
        </html>
        <?php
      } else {
        echo "<script>alert('RECORDS NOT FOUND'); window.location='index.php';</script>";
      }

      // Close the statement
      $stmt->close();
    }

    // Close the connection
    $conn->close();
  ?>
</body>
</html>
