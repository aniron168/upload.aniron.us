<?php
require_once('../includes/db.php');
require_once('../includes/functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = validate_form($_POST['email']);
    $password = validate_form($_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Check if 'is_admin' checkbox was checked. If it was, set $is_admin to 1, otherwise set it to 0.
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO Users (email, password, is_admin) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $email, $hashed_password, $is_admin);
    
    if ($stmt->execute()) {
        echo "User created successfully";
    } else {
        echo "Error creating user: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<body>

<h2>Create New User</h2>

<form method="POST">
  Email:<br>
  <input type="text" name="email">
  <br>
  Password:<br>
  <input type="password" name="password">
  <br>
  Is Admin?<br>
  <input type="checkbox" name="is_admin">
  <br><br>
  <input type="submit" value="Submit">
</form> 

</body>
</html>
