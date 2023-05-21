<?php
require_once('./includes/db.php');
require_once('./includes/functions.php');

// Check if the user is logged in
if (check_logged_in()) {
    // If they are, redirect them to their dashboard
    header('Location: ' . (check_user_is_admin() ? 'admin' : 'user') . '/index.php');
    exit;
}

// Code to display the homepage, including a login form. Remember to use validate_form function for form data and prepared statements for SQL queries

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = validate_form($_POST['email']);
    $password = validate_form($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM Users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['is_admin'] = $user['is_admin'];
        header('Location: ' . (check_user_is_admin() ? 'admin' : 'user') . '/index.php');
        exit;
    } else {
        display_error("Invalid email or password.");
    }
}
?>
// Code to display the login form...
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="/css/styles.css">
</head>
<body>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="Login">
    </form>
    <script src="/js/scripts.js"></script>
</body>
</html>


