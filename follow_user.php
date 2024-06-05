<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['user_id']) || !isset($_POST['user_id'])) {
    echo json_encode(array("status" => "error", "message" => "Unauthorized access."));
    exit();
}

$follower_id = $_SESSION['user_id'];
$user_id = $_POST['user_id'];

// Check if the user is already following the other user
$sql = "SELECT * FROM followers WHERE user_id = ? AND follower_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $follower_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Unfollow the user
    $sql = "DELETE FROM followers WHERE user_id = ? AND follower_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $follower_id);
    if ($stmt->execute()) {
        echo json_encode(array("status" => "success"));
    } else {
        echo json_encode(array("status" => "error", "message" => "Error unfollowing user."));
    }
} else {
    // Follow the user
    $sql = "INSERT INTO followers (user_id, follower_id, created_at) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $follower_id);
    if ($stmt->execute()) {
        echo json_encode(array("status" => "success"));
    } else {
        echo json_encode(array("status" => "error", "message" => "Error following user."));
    }
}

$stmt->close();
$conn->close();
?>