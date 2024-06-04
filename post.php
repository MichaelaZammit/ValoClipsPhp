<?php
session_start();
$CURRENT_PAGE = "Posts";
include("includes/header.php");
include("includes/db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $user_id = $_SESSION['user_id'];
    $media_name = $_FILES["media"]["name"];
    $media_tmp_name = $_FILES["media"]["tmp_name"];
    $media_url = 'uploads/' . basename($media_name);

    // Create the "uploads" directory if it doesn't exist
    if (!file_exists('uploads')) {
        mkdir('uploads', 0777, true);
    }

    if (move_uploaded_file($media_tmp_name, $media_url)) {
        $sql = "INSERT INTO posts (title, description, user_id, media_url) VALUES ('$title', '$description', '$user_id', '$media_url')";
        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert success'>New post created successfully</div>";
        } else {
            echo "<div class='alert error'>Error: " . $sql . "<br>" . $conn->error . "</div>";
        }
    } else {
        echo "<div class='alert error'>Failed to move uploaded file</div>";
    }
}

$conn->close();
?>

