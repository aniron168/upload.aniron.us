<?php
$servername = "localhost";
$username = "upload";
$password = "]s_FPm3RIm5eq[Qd";
$dbname = "upload";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
 
