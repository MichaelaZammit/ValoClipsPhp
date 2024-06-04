<?php
session_start();
$CURRENT_PAGE = "User List";
include("includes/header.php");

// Example user data
$users = [
    ['id' => 1, 'name' => 'User 1'],
    ['id' => 2, 'name' => 'User 2'],
    // Add more users as needed
];
?>

<h1>User List</h1>
<p>Select a user to message:</p>

<?php
foreach ($users as $user) {
    echo '<a href="messages.php?receiver_id=' . $user['id'] . '">Message ' . htmlspecialchars($user['name']) . '</a><br>';
}
?>

<?php include("includes/footer.php"); ?>
