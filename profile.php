<?php
session_start();
$CURRENT_PAGE = "Profile";
include("includes/header.php");
include("includes/db.php");

// Check if the 'user_id' key is set in the session
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Establish database connection
    $conn = new mysqli("localhost", "root", "", "valoclips");

    // Check for connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query user data
    $sql = "SELECT * FROM users WHERE id='$user_id'";
    $result = $conn->query($sql);

    // Check if the query was successful
    if ($result) {
        $user = $result->fetch_assoc();

        // Query followers count
        $sql_followers = "SELECT COUNT(*) AS follower_count FROM followers WHERE user_id='$user_id'";
        $result_followers = $conn->query($sql_followers);
        $followers = $result_followers->fetch_assoc();

        // Query following count
        $sql_following = "SELECT COUNT(*) AS following_count FROM followers WHERE follower_id='$user_id'";
        $result_following = $conn->query($sql_following);
        $following = $result_following->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="style/style4.css">
</head>
<body>
    <div class="profile-header">
        <?php if (!empty($user['profile_picture']) && file_exists($user['profile_picture'])) { ?>
            <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture">
        <?php } ?>
        <div>
            <h1><?php echo htmlspecialchars($user['name']); ?></h1>
            <p>Followers: <?php echo $followers['follower_count']; ?> | Following: <?php echo $following['following_count']; ?></p>
            <form action="upload_profile_picture.php" method="POST" enctype="multipart/form-data">
                <label for="profile_picture">Upload Profile Picture:</label>
                <input type="file" name="profile_picture" id="profile_picture" required>
                <button type="submit">Upload</button>
            </form>
        </div>
    </div>
    <div class="clips-container">
<?php
        // Query user's clips
        $sql_posts = "SELECT posts.*, (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) AS like_count FROM posts WHERE user_id='$user_id'";
        $result_posts = $conn->query($sql_posts);

        if ($result_posts->num_rows > 0) {
            while($post = $result_posts->fetch_assoc()) {
?>
        <div class="clip">
            <h3><?php echo htmlspecialchars($post['title']); ?></h3>
            <p><?php echo htmlspecialchars($post['description']); ?></p>
            <?php if ($post['media_url']) { ?>
                <video controls>
                    <source src="<?php echo htmlspecialchars($post['media_url']); ?>" type="video/mp4">
                </video>
            <?php } ?>
            <p>Likes: <?php echo $post['like_count']; ?></p>
        </div>
<?php
            }
        } else {
            echo "<p>No clips found.</p>";
        }
?>
    </div>
</body>
</html>
<?php
    } else {
        echo "Error: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    echo "User ID not found in session.";
}
include("includes/footer.php");
?>