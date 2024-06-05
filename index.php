<?php
session_start();
$CURRENT_PAGE = "Feed";
include("includes/header.php");
include("includes/db.php");

// Fetch posts from the database
$sql = "SELECT posts.*, users.name, users.id AS user_id FROM posts INNER JOIN users ON posts.user_id = users.id ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<link rel="stylesheet" href="style/style4.css">
<link rel="stylesheet" href="style/style6.css">
<h1>Your Feed</h1>

<div id="posts">
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="post" data-post-id="<?php echo $row['id']; ?>">
            <h2><?php echo htmlspecialchars($row['title']); ?></h2>
            <p><?php echo htmlspecialchars($row['description']); ?></p>
            <p>
                Posted by: <?php echo htmlspecialchars($row['name']); ?>
                <?php if ($row['user_id'] != $_SESSION['user_id']): ?>
                    <button class="follow-btn" data-user-id="<?php echo $row['user_id']; ?>">
                        <?php
                        // Check if the current user is already following this user
                        $follow_sql = "SELECT * FROM followers WHERE user_id = ? AND follower_id = ?";
                        $follow_stmt = $conn->prepare($follow_sql);
                        $follow_stmt->bind_param("ii", $row['user_id'], $_SESSION['user_id']);
                        $follow_stmt->execute();
                        $follow_result = $follow_stmt->get_result();
                        if ($follow_result->num_rows > 0) {
                            echo "Unfollow";
                        } else {
                            echo "Follow";
                        }
                        $follow_stmt->close();
                        ?>
                    </button>
                <?php endif; ?>
            </p>
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
                $comment_sql = "SELECT comments.*, users.name AS commenter_name FROM comments INNER JOIN users ON comments.user_id = users.id WHERE post_id = '$post_id'";
                $comment_result = $conn->query($comment_sql);
                while ($comment_row = $comment_result->fetch_assoc()): ?>
                    <div class="comment" data-comment-id="<?php echo $comment_row['id']; ?>">
                        <p class="comment-text"><?php echo htmlspecialchars($comment_row['comment']); ?> - <em>by <?php echo htmlspecialchars($comment_row['commenter_name']); ?></em></p>
                        <?php if ($comment_row['user_id'] == $_SESSION['user_id']): ?>
                            <button class="delete-comment-btn" data-comment-id="<?php echo $comment_row['id']; ?>">Delete</button>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
                <form class="comment-form" data-post-id="<?php echo $post_id; ?>">
                    <input type="text" name="comment" placeholder="Write a comment..." required>
                    <button type="submit">Post</button>
                </form>
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

    // Comment form submission
    $(".comment-form").submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var post_id = form.data("post-id");
        var comment = form.find("input[name='comment']").val();
        
        $.post("add_comment.php", { post_id: post_id, comment: comment }, function(data) {
            var response = JSON.parse(data);
            if (response.status == "success") {
                var newComment = $("<div class='comment'><p class='comment-text'>" + comment + " - <em>by You</em></p></div>");
                form.before(newComment);
                form.find("input[name='comment']").val(""); // Clear the input
            } else {
                alert(response.message);
            }
        });
    });

    // Delete comment button click
    $(document).on("click", ".delete-comment-btn", function() {
        var comment_id = $(this).data("comment-id");
        $.post("delete_comment.php", { comment_id: comment_id }, function(data) {
            var response = JSON.parse(data);
            if (response.status == "success") {
                $("div[data-comment-id='" + comment_id + "']").remove();
            } else {
                alert(response.message);
            }
        });
    });

    // Follow button click
    $(document).on("click", ".follow-btn", function() {
        var user_id = $(this).data("user-id");
        var button = $(this);
        $.post("follow_user.php", { user_id: user_id }, function(data) {
            var response = JSON.parse(data);
            if (response.status == "success") {
                if (button.text() === "Follow") {
                    button.text("Unfollow");
                } else {
                    button.text("Follow");
                }
            } else {
                alert(response.message);
            }
        });
    });
});
</script>

<?php include("includes/footer.php"); ?>