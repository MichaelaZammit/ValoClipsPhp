<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['user_id']) || !isset($_POST['challenge_id']) || !isset($_POST['title']) || !isset($_POST['description']) || !isset($_FILES['media'])) {
    echo "Missing data.";
    exit();
}

$user_id = $_SESSION['user_id'];
$challenge_id = $_POST['challenge_id'];
$title = $_POST['title'];
$description = $_POST['description'];

// File upload
$target_dir = "uploads/challenge_videos/";
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true); // Create the directory if it doesn't exist
}

$target_file = $target_dir . basename($_FILES["media"]["name"]);
$file_extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
$new_file_name = $target_dir . uniqid() . '.' . $file_extension;

if (move_uploaded_file($_FILES["media"]["tmp_name"], $new_file_name)) {
    // Insert video details into the database
    $sql = "INSERT INTO challenge_videos (challenge_id, user_id, title, description, media_url, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisss", $challenge_id, $user_id, $title, $description, $new_file_name);
    
    if ($stmt->execute()) {
        header("Location: challenge_detail.php?id=" . $challenge_id);
        exit();
    } else {
        echo "Error uploading video: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Error moving uploaded file.";
}

$conn->close();
?>