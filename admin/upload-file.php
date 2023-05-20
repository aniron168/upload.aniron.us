<!-- <?php
require_once('../includes/db.php');
require_once('../includes/functions.php');

if (!check_logged_in() || !check_user_is_admin()) {
    header('Location: ' . SITE_URL);
    exit;
}

// Code to display file upload form. This might include an HTML form with an input of type "file".

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
       
        }
    }
}

?> -->

<?php
require_once('../includes/db.php');
require_once('../includes/functions.php');

if (!check_user_is_admin()) {
    handle_error('You do not have permission to access this page.', SITE_URL);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($target_file)) {
        handle_error("Sorry, file already exists.", $_SERVER["PHP_SELF"]);
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "gif" 
        && $fileType != "mp4" && $fileType != "mov" ) {
        handle_error("Sorry, only JPG, JPEG, PNG, GIF, MP4 & MOV files are allowed.", $_SERVER["PHP_SELF"]);
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        handle_error("Sorry, your file was not uploaded.", $_SERVER["PHP_SELF"]);
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        } else {
            handle_error("Sorry, there was an error uploading your file.", $_SERVER["PHP_SELF"]);
        }
    }
}
?>


<form method="post" enctype="multipart/form-data">
  Select file to upload:
  <input type="file" name="fileToUpload" id="fileToUpload">
  <input type="submit" value="Upload File" name="submit">
</form>

