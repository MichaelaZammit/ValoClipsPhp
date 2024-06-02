<?php
session_start();
$CURRENT_PAGE = "Posts";
include("includes/header.php");
include("includes/db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $user_id = $_SESSION['user_id'];
    $media_url = 'uploads/' . basename($_FILES["media"]["name"]);

    if (move_uploaded_file($_FILES["media"]["tmp_name"], $media_url)) {
        $sql = "INSERT INTO posts (title, description, user_id, media_url) VALUES ('$title', '$description', '$user_id', '$media_url')";
        if ($conn->query($sql) === TRUE) {
            echo "New post created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>

<h1>Create a Post</h1>
<form method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">Title:</label>
        <input type="text" class="form-control" id="title" name="title" required>
    </div>
    <div class="form-group">
        <label for="description">Description:</label>
        <textarea class="form-control" id="description" name="description" required></textarea>
    </div>
    <div class="form-group">
        <label for="media">Media:</label>
        <input type="file" class="form-control" id="media" name="media" required>
    </div>
    <button type="submit" class="btn btn-default">Submit</button>
</form>

<?php include("includes/footer.php"); ?>
