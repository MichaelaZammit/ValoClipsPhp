<?php
session_start();

if (isset($_SESSION['user_id']) && isset($_FILES['profile_picture'])) {
    $user_id = $_SESSION['user_id'];

    // Check if the file was uploaded without errors
    if ($_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
        // Establish database connection
        $conn = new mysqli("localhost", "root", "", "valoclips");

        // Check for connection errors
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Define the target directory and file name
        $target_dir = "uploads/profile_pictures/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // Create the directory if it doesn't exist
        }

        // Generate a unique file name to avoid conflicts
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        $file_extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $new_file_name = $target_dir . $user_id . '.' . $file_extension;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $new_file_name)) {
            // Update user profile picture path in the database
            $stmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
            $stmt->bind_param("si", $new_file_name, $user_id);

            if ($stmt->execute()) {
                header("Location: profile.php"); // Redirect back to profile page
                exit();
            } else {
                echo "Error updating profile picture: " . $stmt->error;
            }

            // Close the statement and connection
            $stmt->close();
        } else {
            echo "Error moving uploaded file.";
        }

        $conn->close();
    } else {
        echo "Error uploading file.";
    }
} else {
    echo "No file uploaded or user not logged in.";
}
?>