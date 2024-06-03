<?php
session_start();
$CURRENT_PAGE = "Profile";
include("includes/header.php");
include("includes/db.php");

// Check if the 'user_id' key is set in the session
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Establish database connection
    $conn = new mysqli("localhost", "username", "password", "valoclips");

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
?>
        <h1>Hello, <?php echo htmlspecialchars($user['name']); ?></h1>
        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
        <?php if ($user['profile_picture']) { ?>
            <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" style="width:150px;height:150px;">
        <?php } ?>
        <form action="upload_profile_picture.php" method="POST" enctype="multipart/form-data">
            <label for="profile_picture">Upload Profile Picture:</label>
            <input type="file" name="profile_picture" id="profile_picture">
            <button type="submit">Upload</button>
        </form>
<?php
        // Query user's clips
        $sql_posts = "SELECT posts.*, (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) AS like_count FROM posts WHERE user_id='$user_id'";
        $result_posts = $conn->query($sql_posts);

        if ($result_posts->num_rows > 0) {
            echo "<h2>Your Clips</h2>";
            while($post = $result_posts->fetch_assoc()) {
                echo "<div>";
                echo "<h3>" . htmlspecialchars($post['title']) . "</h3>";
                echo "<p>" . htmlspecialchars($post['description']) . "</p>";
                if ($post['media_url']) {
                    echo "<video width='320' height='240' controls>
                            <source src='" . htmlspecialchars($post['media_url']) . "' type='video/mp4'>
                          </video>";
                }
                echo "<p>Likes: " . $post['like_count'] . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No clips found.</p>";
        }

        // Query followers count
        $sql_followers = "SELECT COUNT(*) AS follower_count FROM followers WHERE user_id='$user_id'";
        $result_followers = $conn->query($sql_followers);
        $followers = $result_followers->fetch_assoc();

        // Query following count
        $sql_following = "SELECT COUNT(*) AS following_count FROM followers WHERE follower_id='$user_id'";
        $result_following = $conn->query($sql_following);
        $following = $result_following->fetch_assoc();

        echo "<p>Followers: " . $followers['follower_count'] . "</p>";
        echo "<p>Following: " . $following['following_count'] . "</p>";

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
