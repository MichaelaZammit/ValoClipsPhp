<?php
// Check if a user exists by username or email
function userExists($conn, $username, $email) {
    $sql = "SELECT username, email FROM users WHERE username = ? OR email = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die("SQL error");
    }
    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        return false;
    }
    mysqli_stmt_close($stmt);
}

// Check if a username exists
function usernameExists($conn, $username) {
    $sql = "SELECT COUNT(*) FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_array();

    return $row[0] > 0;
}

// Check if an email exists
function emailExists($conn, $email) {
    $sql = "SELECT COUNT(*) FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_array();

    return $row[0] > 0;
}

// Create a new user
function createUser($conn, $username, $password, $email, $name, $profile_picture = null) {
    $sql = "INSERT INTO users (username, password, email, name, profile_picture) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt->bind_param("sssss", $username, $hashedPassword, $email, $name, $profile_picture);

    if (!$stmt->execute()) {
        error_log("Error in createUser: " . $stmt->error);
        return false;
    }

    return $stmt->affected_rows > 0;
}

// Authenticate a user
function authenticateUser($conn, $username, $password) {
    $sql = "SELECT id, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            return $user['id'];
        }
    }
    return false;
}

// Get user details
function getUserDetails($conn, $userId) {
    $sql = "SELECT username, name, email, profile_picture FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Load all posts
function loadAllPosts($conn) {
    $sql = "SELECT * FROM posts";
    $result = $conn->query($sql);
    return $result;
}

// Create a new post
function createPost($conn, $title, $description, $user_id, $media_url = null) {
    $sql = "INSERT INTO posts (title, description, user_id, media_url) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssis", $title, $description, $user_id, $media_url);

    if (!$stmt->execute()) {
        error_log("Error in createPost: " . $stmt->error);
        return false;
    }

    return $stmt->affected_rows > 0;
}

// Load all comments for a post
function loadComments($conn, $postId) {
    $sql = "SELECT * FROM comments WHERE post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    return $stmt->get_result();
}

// Create a new comment
function createComment($conn, $post_id, $user_id, $comment) {
    $sql = "INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $post_id, $user_id, $comment);

    if (!$stmt->execute()) {
        error_log("Error in createComment: " . $stmt->error);
        return false;
    }

    return $stmt->affected_rows > 0;
}

// Load all likes for a post
function loadLikes($conn, $postId) {
    $sql = "SELECT * FROM likes WHERE post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    return $stmt->get_result();
}

// Create a new like
function createLike($conn, $post_id, $user_id) {
    $sql = "INSERT INTO likes (post_id, user_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $post_id, $user_id);

    if (!$stmt->execute()) {
        error_log("Error in createLike: " . $stmt->error);
        return false;
    }

    return $stmt->affected_rows > 0;
}

// Load all messages for a user
function loadMessages($conn, $userId) {
    $sql = "SELECT * FROM messages WHERE receiver_id = ? OR sender_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $userId
