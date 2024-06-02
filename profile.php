<?php
session_start();
$CURRENT_PAGE = "Profile";
include("header.php");
include("db.php");

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

?>

<h1>Profile</h1>
<p>Name: <?php echo $user['name']; ?></p>
<p>Email: <?php echo $user['email']; ?></p>

<?php include("footer.php"); ?>
