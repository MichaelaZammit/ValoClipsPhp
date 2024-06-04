<?php
session_start();
$CURRENT_PAGE = "Feed";
include("includes/header.php");
include("includes/db.php");

// Fetch posts from the database
$sql = "SELECT posts.*, users.name FROM posts INNER JOIN users ON posts.user_id = users.id ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<link rel="stylesheet" href="style/style4.css">
<h1>Your Feed</h1>

<div id="posts">
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="post" data-post-id="<?php echo $row['id']; ?>">
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
            <div class="comments">
                <?php
                // Fetch comments for this post
                $post_id = $row['id'];
                $comment_sql = "SELECT * FROM comments WHERE post_id = '$post_id'";
                $comment_result = $conn->query($comment_sql);
                while ($comment_row = $comment_result->fetch_assoc()): ?>
                    <p><?php echo $comment_row['comment']; ?></p>
                <?php endwhile; ?>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php include("includes/footer.php"); ?>
