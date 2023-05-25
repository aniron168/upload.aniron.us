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
    if (!isset($_SESSION['user_id'])) {
        return false;
    }

    global $conn;
    $stmt = $conn->prepare("SELECT is_admin FROM Users WHERE id = ?");
    $stmt->bind_param('i', $_SESSION['user_id']);

    $stmt->execute();

    $stmt->bind_result($is_admin);

    $stmt->fetch();

    $stmt->close();

    return $is_admin == 1;
}

function handle_error($message, $redirect_url = SITE_URL) {
    $_SESSION['error_message'] = $message;
    header('Location: ' . $redirect_url);
    exit;
}

function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function create_user($email, $password) {
    global $conn;
    $hashed_password = hash_password($password);
    $sql = "INSERT INTO Users (email, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $hashed_password);

    if ($stmt->execute()) {
        return "User created successfully.";
    } else {
        return "Error creating user: " . $stmt->error;
    }
}

function get_all_users() {
    global $conn;
    $sql = "SELECT * FROM Users";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $users = [];
        while($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    } else {
        return [];
    }
}

function delete_user($id) {
    global $conn;
    $sql = "DELETE FROM Users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

function change_user_password($id, $new_password) {
    global $conn;
    $hashed_password = hash_password($new_password);
    $sql = "UPDATE Users SET password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $hashed_password, $id);
    $stmt->execute();
}

function assign_admin_privilege($id) {
    global $conn;
    $sql = "UPDATE Users SET is_admin = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

function upload_file($name, $path, $uploaded_by) {
    global $conn;
    $sql = "INSERT INTO Files (name, path, uploaded_by) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $name, $path, $uploaded_by);
    $stmt->execute();
}

function delete_file($id) {
    global $conn;
    $sql = "DELETE FROM Files WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

function get_all_files() {
    global $conn;  // Assumes $db is your database connection

    $sql = "SELECT * FROM files";
    $result = $conn->query($sql);

    return $result;
}


?>
