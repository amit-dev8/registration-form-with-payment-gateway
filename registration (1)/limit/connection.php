<?php
error_reporting(0);


$conn = mysqli_connect('localhost', 'u630071588_kriyamahotsav', 'Kriya@68928', 'u630071588_kriyamahotsav');


if ($conn) 
{
    //   echo "Connection ok";
} 
else 
{
    echo "failed".mysqli_connect_error();
}

// $conn->close();
?>
