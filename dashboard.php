<?php
session_start();
$CURRENT_PAGE = "Dashboard";
include("includes/header.php");
include("includes/db.php");

// Fetch users from the database
$sql = "SELECT id, name FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $users = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $users = [];
}

$conn->close();
?>

<h1>User List</h1>
<p>Select a user to message:</p>

<?php
foreach ($users as $user) {
    echo '<a href="messages.php?receiver_id=' . $user['id'] . '">Message ' . htmlspecialchars($user['name']) . '</a><br>';
}
?>

<?php include("includes/footer.php"); ?>
