<?php
require_once('../includes/db.php');
require_once('../includes/functions.php');

if (!check_logged_in() || !check_user_is_admin()) {
    header('Location: ' . SITE_URL);
    exit;
}

// Code to process file upload. This should check if a file was uploaded, move the file to the appropriate directory, and store the file's information in the database.


// Code to process file upload, remember to use validate_form function for form data
?>
