<?php

require_once('../includes/db.php');
require_once('../includes/functions.php');

if (!check_logged_in()) {
    header('Location: ' . SITE_URL);
    exit;
}

// Rest of your user dashboard code. This might include a list of files available for download, the user's account information, etc.
// Assuming you have a 'files' table in your database with columns 'id', 'filename', 'uploaded_by'
$stmt = $conn->prepare("SELECT * FROM files WHERE uploaded_by = ?");
$stmt->bind_param("s", $_SESSION['email']); // Assuming email is stored in session when user logs in
$stmt->execute();
$files = $stmt->get_result();

?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?>!</h1>

    <h2>Your Files</h2>
    <?php if ($files->num_rows > 0): ?>
        <ul>
            <?php while ($file = $files->fetch_assoc()): ?>
                <li><a href="/files/<?php echo htmlspecialchars($file['filename']); ?>"><?php echo htmlspecialchars($file['filename']); ?></a></li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>You have not uploaded any files.</p>
    <?php endif; ?>

    <!-- Logout button -->
    <form action="/logout.php" method="POST">
        <button type="submit">Logout</button>
    </form>
</body>
</html>

 
