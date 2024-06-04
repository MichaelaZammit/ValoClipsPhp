<!DOCTYPE html>
<html lang="en">
<head>
    <title>Footer</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style\style2.css">
</head>
<body>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> ValoClips. All rights reserved.</p>
        <p>
            &nbsp; <!-- Add one space -->
            <a href="contact.php">Contact Us</a>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <!-- Add some spaces -->
            <?php
            // Check if the user is logged in
            if (isset($_SESSION['user_id'])) {
                echo '<a href="logout.php">Logout</a>';
            }
            ?>
        </p>
    </footer>

</body>
</html>
