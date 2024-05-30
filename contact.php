<?php
    require_once "includes/functions.php";

    require_once "includes/dbh.php";
    require_once "includes/db-functions.php";
    
    include 'includes/header.php';
?>

    <h1>Contact Us</h1>
    <form action="send_email.php" method="post">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="message">Message:</label><br>
        <textarea id="message" name="message" rows="4" cols="50" required></textarea><br><br>
        
        <input type="submit" value="Send">
    </form>
</body>
</html>

<?php
    include 'includes/footer.php';
?>