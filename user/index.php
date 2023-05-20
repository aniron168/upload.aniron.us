<?php
require_once('../includes/db.php');
require_once('../includes/functions.php');

if (!check_logged_in()) {
    header('Location: ' . SITE_URL);
    exit;
}

// Rest of your user dashboard code. This might include a list of files available for download, the user's account information, etc.
?>
 
