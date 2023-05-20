<?php
require_once('../includes/db.php');
require_once('../includes/functions.php');

if (!check_logged_in()) {
    header('Location: ' . SITE_URL);
    exit;
}

// Code to manage user account, remember to use validate_form function for form data and prepared statements for SQL queries

// Code to manage users...

// Code to manage account. This might include a form to update the user's email or password.

// Remember to use password_hash when storing passwords, like this:
// $hashed_password = password_hash($password, PASSWORD_DEFAULT);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = validate_form($_POST['email']);
    $password = validate_form($_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE Users SET email = ?, password = ? WHERE id = ?");
    $stmt->bind_param("ssi", $email, $hashed_password, $_SESSION['user_id']);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Account updated successfully";
    } else {
        echo "Error updating account: " . $stmt->error;
    }
}

$stmt = $conn->prepare("SELECT * FROM Users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  Email: <input type="text" name="email" value="<?php echo $user['email'];?>"><br>
  Password: <input type="password" name="password"><br>
  <input type="submit" value="Update Account">
</form>

