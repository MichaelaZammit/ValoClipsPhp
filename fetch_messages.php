<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit();
}

$sender_id = $_SESSION['user_id'];
$receiver_id = $_GET['receiver_id'];

$sql = "SELECT messages.*, sender.name AS sender_name, receiver.name AS receiver_name
        FROM messages
        INNER JOIN users AS sender ON messages.sender_id = sender.id
        INNER JOIN users AS receiver ON messages.receiver_id = receiver.id
        WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
        ORDER BY created_at ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($messages);
?>