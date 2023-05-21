<?php
require_once('./includes/db.php');
require_once('./includes/functions.php');

// Unset specific session variables
unset($_SESSION['user_id']);
unset($_SESSION['email']);
unset($_SESSION['is_admin']);

header('Location: /index.php');
exit;
?>