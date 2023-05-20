<?php
require_once('../includes/db.php');
require_once('../includes/functions.php');

if (!check_logged_in()) {
    header('Location: ' . SITE_URL);
    exit;
}

// Code to handle file downloads, remember to use prepared statements for SQL queries

$file_id = validate_form($_GET['file_id']);

$stmt = $conn->prepare("SELECT * FROM Files WHERE id = ?");
$stmt->bind_param("i", $file_id);
$stmt->execute();

$result = $stmt->get_result();
$file = $result->fetch_assoc();

if ($file) {
    $file_url = SITE_URL . '/uploads/' . $file['filename'];

    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary"); 
    header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 

    readfile($file_url);
} else {
    display_error("File not found.");
}

?>
