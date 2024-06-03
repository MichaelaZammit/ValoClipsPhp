$(document).ready(function() {
    // Like a post
    $(".like-btn").click(function() {
        var postId = $(this).data("post-id");
        $.ajax({
            url: "like_post.php",
            type: "POST",
            data: { post_id: postId },
            success: function(response) {
                // Handle success, e.g., update UI
            },
            error: function(xhr, status, error) {
                // Handle error
            }
        });
    });

    // Comment on a post
    $(".comment-btn").click(function() {
        var postId = $(this).data("post-id");
        // Show comment form or load comments dynamically
    });

    // Share a post
    $(".share-btn").click(function() {
        var postId = $(this).data("post-id");
        $.ajax({
            url: "share_post.php",
            type: "POST",
            data: { post_id: postId },
            success: function(response) {
                // Handle success, e.g., update UI
            },
            error: function(xhr, status, error) {
                // Handle error
            }
        });
    });
});
