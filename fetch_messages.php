<?php
session_start();
include("includes/db.php");

$current_user_id = $_SESSION['user_id'];
$receiver_id = $_GET['receiver_id']; // Get receiver ID from GET parameter

$sql = "SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY created_at ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $current_user_id, $receiver_id, $receiver_id, $current_user_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = array();
while($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
?>
