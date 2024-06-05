<?php
session_start();
include("includes/db.php");

if(isset($_SESSION['user_id']) && isset($_POST['post_id'])) {
    $user_id = $_SESSION['user_id'];
    $post_id = $_POST['post_id'];

    // Check if the user already shared the post
    $sql = "SELECT * FROM shares WHERE user_id = ? AND post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 0) {
        // User has not shared the post, insert share
        $sql = "INSERT INTO shares (post_id, user_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $post_id, $user_id);
        if ($stmt->execute()) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "error", "message" => "Error sharing post."));
        }
    } else {
        echo json_encode(array("status" => "error", "message" => "You have already shared this post."));
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(array("status" => "error", "message" => "Unauthorized access."));
}
?>