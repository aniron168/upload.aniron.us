<?php

require_once('../includes/db.php');
require_once('../includes/functions.php');

if (!check_logged_in() || !check_user_is_admin()) {
    header('Location: ' . SITE_URL);
    exit;
}

// Rest of your admin dashboard code. This might include a list of users, a list of files, etc.
?>


