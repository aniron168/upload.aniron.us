
<!DOCTYPE html>
<html>
<head>
  <title>Manage Users</title>
  <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
<div class="container">
  <h1>Manage Users</h1>
  <!-- The rest of your PHP script here... -->


<?php
require_once('../includes/db.php');
require_once('../includes/functions.php');

if (!check_logged_in() || !check_user_is_admin()) {
    header('Location: ' . SITE_URL);
    exit;
}

// Code to manage users. This might include a form to create a new user, a list of existing users, etc.

// Remember to use password_hash when storing passwords, like this:
// $hashed_password = password_hash($password, PASSWORD_DEFAULT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   /*  $email = validate_form($_POST['email']);
    $password = validate_form($_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO Users (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $hashed_password);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "User created successfully";
    } else {
        echo "Error creating user: " . $stmt->error;
    } */

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    if (!$email) {
        handle_error('Invalid email address.', $_SERVER["PHP_SELF"]);
    }

    if (strlen($password) < 8) {
        handle_error('Password must be at least 8 characters long.', $_SERVER["PHP_SELF"]);
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO Users (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $hashed_password);

    if (!$stmt->execute()) {
        handle_error('Error creating user: ' . $stmt->error, $_SERVER["PHP_SELF"]);
    } else {
        echo "User created successfully";
    }

}

$stmt = $conn->prepare("SELECT * FROM Users");
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "id: " . $row["id"]. " - Email: " . $row["email"]. "<br>";
}

?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  Email: <input type="text" name="email"><br>
  Password: <input type="password" name="password"><br>
  <input type="submit" value="Create User">
</form>

</div>
<script src="/js/scripts.js"></script>
</body>
</html>


 
