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
            <h2><?php echo htmlspecialchars($row['title']); ?></h2>
            <p><?php echo htmlspecialchars($row['description']); ?></p>
            <p>Posted by: <?php echo htmlspecialchars($row['name']); ?></p>
            <?php if ($row['media_url']): ?>
                <video width="320" height="240" controls>
                    <source src="<?php echo htmlspecialchars($row['media_url']); ?>" type="video/mp4">
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
                    <p><?php echo htmlspecialchars($comment_row['comment']); ?></p>
                <?php endwhile; ?>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Like button click
    $(".like-btn").click(function() {
        var post_id = $(this).data("post-id");
        $.post("like_post.php", { post_id: post_id }, function(data) {
            var response = JSON.parse(data);
            if (response.status == "success") {
                alert("Post liked!");
            } else {
                alert(response.message);
            }
        });
    });

    // Share button click
    $(".share-btn").click(function() {
        var post_id = $(this).data("post-id");
        $.post("share_post.php", { post_id: post_id }, function(data) {
            var response = JSON.parse(data);
            if (response.status == "success") {
                alert("Post shared!");
            } else {
                alert(response.message);
            }
        });
    });
});
</script>

<?php include("includes/footer.php"); ?>
code.jquery.com