
<?php
error_reporting(0);

$conn = mysqli_connect('localhost', 'root', '', 'test');


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
