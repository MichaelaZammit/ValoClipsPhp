<?php
session_start();
$CURRENT_PAGE = "Feed";
include("includes/header.php");
include("includes/db.php");

// Fetch posts from the database
$sql = "SELECT posts.*, users.name FROM posts INNER JOIN users ON posts.user_id = users.id ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<h1>Feed</h1>
<p>View and interact with posts from other users.</p>

<div id="posts">
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="post">
            <h2><?php echo $row['title']; ?></h2>
            <p><?php echo $row['description']; ?></p>
            <p>Posted by: <?php echo $row['name']; ?></p>
            <?php if ($row['media_url']): ?>
                <video width="320" height="240" controls>
                    <source src="<?php echo $row['media_url']; ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            <?php endif; ?>
            <div class="interaction">
                <button class="like-btn" data-post-id="<?php echo $row['id']; ?>">Like</button>
                <button class="comment-btn" data-post-id="<?php echo $row['id']; ?>">Comment</button>
                <button class="share-btn" data-post-id="<?php echo $row['id']; ?>">Share</button>
            </div>
            <div class="comments"></div>
        </div>
    <?php endwhile; ?>
</div>

<?php include("includes/footer.php"); ?>
