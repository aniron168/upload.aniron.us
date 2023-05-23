<?php

require_once('config.php'); 
require_once('db.php'); 
session_start();

/**
* Checks if user is logged in by checking if a user_id session variable is set
* 
* @return bool Returns true if user is logged in, false if not
*/
function check_logged_in() {
    return isset($_SESSION['user_id']);
}


function validate_form($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function display_error($error_message) {
    echo "<div class='error-message'>$error_message</div>";
}

function check_user_is_admin() {
    // You'll need to implement this function based on your users table.
    // It should return true if the currently logged in user is an admin, and false otherwise.function check_user_is_admin() {
    // Ensure the user is logged in before checking if they're an admin

    if (!isset($_SESSION['user_id'])) {
        return false;
    }

    // Prepare a SQL statement
    $stmt = $GLOBALS['conn']->prepare("SELECT is_admin FROM Users WHERE id = ?");
    $stmt->bind_param('i', $_SESSION['user_id']);

    // Execute the SQL statement
    $stmt->execute();

    // Bind the result to a variable
    $stmt->bind_result($is_admin);

    // Fetch the result. This will populate $is_admin with the value from the database
    $stmt->fetch();

    // Close the statement
    $stmt->close();

    // If $is_admin is true (1), return true. Otherwise, return false
    return $is_admin == 1;
}

function handle_error($message, $redirect_url = SITE_URL) {
    $_SESSION['error_message'] = $message;
    header('Location: ' . $redirect_url);
    exit;
}

function create_user($email, $password) {
    global $db;
    $hashed_password = hash_password($password);
    $sql = "INSERT INTO Users (email, password) VALUES (?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ss", $email, $hashed_password);
    $stmt->execute();
}

function delete_user($id) {
    global $db;
    $sql = "DELETE FROM Users WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

function change_user_password($id, $new_password) {
    global $db;
    $hashed_password = hash_password($new_password);
    $sql = "UPDATE Users SET password = ? WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("si", $hashed_password, $id);
    $stmt->execute();
}

function assign_admin_privilege($id) {
    global $db;
    $sql = "UPDATE Users SET is_admin = 1 WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

function upload_file($name, $path, $uploaded_by) {
    global $db;
    $sql = "INSERT INTO Files (name, path, uploaded_by) VALUES (?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ssi", $name, $path, $uploaded_by);
    $stmt->execute();
}

function delete_file($id) {
    global $db;
    $sql = "DELETE FROM Files WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

?>


