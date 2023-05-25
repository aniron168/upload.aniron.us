<?php

require_once('../includes/db.php');
require_once('../includes/functions.php');

if (!check_logged_in() || !check_user_is_admin()) {
    header('Location: ' . SITE_URL);
    exit;
}

$action = isset($_GET['action']) ? $_GET['action'] : '';

// Fetch all files and users
$files = get_all_files();
$users = get_all_users();

//...

switch ($action) {
    /* case 'create_user':
        // handle form submission for creating user
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
            create_user($email, $password);
            header('Location: ' . SITE_URL . '/admin/index.php');
            exit;
        } else {
            ?>
            <form method="POST">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
    
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
    
                <input type="submit" value="Create User">
            </form>
            <?php
        }
        break; */

    case 'create_user':
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = validate_form($_POST['email']);
            $password = validate_form($_POST['password']);
            create_user($email, $password);
        }
        break;
    case 'change_password':
        // handle form submission for changing user password
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
            $password = $_POST['password'];
            change_user_password($id, $password);
            header('Location: ' . SITE_URL . '/admin/index.php');
            exit;
        } else {
            ?>
            <form method="POST">
                <label for="user_id">User ID:</label>
                <input type="number" id="user_id" name="user_id" required>
    
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" required>
    
                <input type="submit" value="Change Password">
            </form>
            <?php
        }
        break;
    // Similarly, for the rest of the cases, add forms for inputting necessary information. 
    //...
    case 'delete_user':
        // handle user deletion
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
            delete_user($id);
            header('Location: ' . SITE_URL . '/admin/index.php');
            exit;
        } else {
            ?>
            <form method="POST">
                <label for="user_id">User ID:</label>
                <input type="number" id="user_id" name="user_id" required>
    
                <input type="submit" value="Delete User">
            </form>
            <?php
        }
        break;
    case 'make_admin':
        // handle form submission for making user admin
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
            assign_admin_privilege($id);
            header('Location: ' . SITE_URL . '/admin/index.php');
            exit;
        } else {
            ?>
            <form method="POST">
                <label for="user_id">User ID:</label>
                <input type="number" id="user_id" name="user_id" required>
    
                <input type="submit" value="Assign Admin">
            </form>
            <?php
        }
        break;
        
    case 'upload_file':
        // handle form submission for uploading file
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ensure a file was uploaded
            if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
                $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
                $uploaded_by = $_SESSION['user_id'];
    
                // Define your upload directory. Make sure this is writeable by your server.
                $uploadDir = '/path/to/your/uploads/directory';
    
                // Create a unique name for the file to prevent overwriting existing files
                $fileName = uniqid() . '_' . basename($_FILES['file']['name']);
                $uploadFilePath = $uploadDir . '/' . $fileName;
    
                // Move the file to your upload directory. Check if the move was successful.
                if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFilePath)) {
                    upload_file($name, $uploadFilePath, $uploaded_by);
    
                    header('Location: ' . SITE_URL . '/admin/index.php');
                    exit;
                } else {
                    // Error handling
                    echo "An error occurred while moving the uploaded file.";
                }
            } else {
                // Error handling. In a real application, you should provide more detailed error messages
                echo "An error occurred while uploading the file.";
            }
        } else {
            ?>
            <form method="POST" enctype="multipart/form-data">
                <label for="name">File Name:</label>
                <input type="text" id="name" name="name" required>
    
                <label for="file">File:</label>
                <input type="file" id="file" name="file" required>
        
                <input type="submit" value="Upload File">
            </form>
            <?php
        }
        break;
    case 'delete_file':
        // handle file deletion
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'file_id', FILTER_SANITIZE_NUMBER_INT);
            delete_file($id);
            header('Location: ' . SITE_URL . '/admin/index.php');
            exit;
        } else {
            ?>
            <form method="POST">
                <label for="file_id">File ID:</label>
                <input type="number" id="file_id" name="file_id" required>
    
                <input type="submit" value="Delete File">
            </form>
            <?php
        }
        break;
    default:
        // show dashboard
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Welcome, Admin!</h1>

    <h2>All Files</h2>
    <?php if ($files->num_rows > 0): ?>
        <ul>
            <!-- In file listing -->
            <?php foreach ($files as $file): ?>
                <li>
                    <a href="/files/<?php echo htmlspecialchars($file['filename']); ?>"><?php echo htmlspecialchars($file['filename']); ?></a>
                    - Uploaded by <?php echo htmlspecialchars($file['uploaded_by']); ?>
                    <a href="index.php?action=delete_file&file_id=<?php echo $file['id']; ?>">Delete File</a>
                    
                    <!-- Hidden input form -->
                    <form action="index.php?action=delete_file" method="POST" id="delete_file_<?php echo $file['id']; ?>" style="display: none;">
                        <input type="hidden" name="file_id" value="<?php echo $file['id']; ?>">
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No files uploaded yet.</p>
    <?php endif; ?>

    <h2>All Users</h2>
    <?php if (count($users) > 0): ?>
        <ul>
            <?php foreach ($users as $user): ?>
                <li>
                    <?php echo htmlspecialchars($user['email']); ?>
                    <a href="index.php?action=change_password&user_id=<?php echo $user['id']; ?>">Change User Password</a>
                    <a href="index.php?action=delete_user&user_id=<?php echo $user['id']; ?>">Delete User</a>
                    <a href="index.php?action=make_admin&user_id=<?php echo $user['id']; ?>">Assign Admin</a>

                    <!-- Hidden input forms -->
                    <form action="index.php?action=change_password" method="POST" id="change_password_<?php echo $user['id']; ?>" style="display: none;">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    </form>
                    <form action="index.php?action=delete_user" method="POST" id="delete_user_<?php echo $user['id']; ?>" style="display: none;">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    </form>
                    <form action="index.php?action=make_admin" method="POST" id="make_admin_<?php echo $user['id']; ?>" style="display: none;">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <p>No users have been registered yet.</p>
    <?php endif; ?>

    <a href="index.php?action=create_user">Create User</a>
    <a href="index.php?action=upload_file">Upload File</a>

    <!-- User creation form -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . '?action=create_user');?>" method="post">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br>

        <input type="submit" value="Create User">
    </form>

    <?php
    // Display message
    if (isset($_SESSION['message'])) {
        echo '<p>' . $_SESSION['message'] . '</p>';
        unset($_SESSION['message']);
    }  
    ?>  

    <!-- Logout button -->
    <form action="/logout.php" method="POST">
        <button type="submit">Logout</button>
    </form>
</body>
</html>
