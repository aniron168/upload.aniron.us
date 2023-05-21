<?php

require_once('config.php'); 
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
    // It should return true if the currently logged in user is an admin, and false otherwise.
}

function handle_error($message, $redirect_url = SITE_URL) {
    $_SESSION['error_message'] = $message;
    header('Location: ' . $redirect_url);
    exit;
}

?>


