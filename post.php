<?php
session_start();
$CURRENT_PAGE = "Feed";
include("includes/header.php");
include("includes/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $targetDirectory = "uploads/";
    $originalFileName = basename($_FILES["videoToUpload"]["name"]);
    $targetFile = $targetDirectory . $originalFileName;
    $uploadOk = 1;
    $videoFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $title = $_POST["title"];
    $description = $_POST["description"];
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session

    // Check if file already exists and rename it
    $counter = 1;
    while (file_exists($targetFile)) {
        $fileNameWithoutExt = pathinfo($originalFileName, PATHINFO_FILENAME);
        $targetFile = $targetDirectory . $fileNameWithoutExt . '_' . $counter . '.' . $videoFileType;
        $counter++;
    }

    // Allow only certain video formats (you can add more formats as needed)
    if ($videoFileType != "mp4" && $videoFileType != "avi" && $videoFileType != "mov"
        && $videoFileType != "wmv") {
        echo "Sorry, only MP4, AVI, MOV, and WMV files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["videoToUpload"]["tmp_name"], $targetFile)) {
            // File uploaded successfully, now insert details into database
            $conn = new mysqli("localhost", "root", "", "valoclips");

            // Check for connection errors
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $stmt = $conn->prepare("INSERT INTO posts (title, description, user_id, media_url) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssis", $title, $description, $user_id, $targetFile);

            if ($stmt->execute()) {
                echo "The video file " . basename($targetFile) . " has been uploaded.";
                echo "<br>Title: $title";
                echo "<br>Description: $description";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        } else {
            echo "Sorry, there was an error uploading your video file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload</title>
    <style>
        h2 {
            color: white;
        }
        #upload-form {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #101823;
        }
        #upload-form h2 {
            text-align: center;
        }
        #upload-form input[type="file"],
        #upload-form input[type="text"],
        #upload-form textarea {
            display: block;
            margin: 0 auto 20px;
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }
        #upload-form input[type="submit"] {
            display: block;
            margin: 0 auto;
            padding: 10px 20px;
            background-color: #6e2026;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #message {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div id="upload-form">
        <h2>Upload Your Video</h2>
        <form action="post.php" method="post" enctype="multipart/form-data">
            <input type="file" name="videoToUpload" id="videoToUpload" required>
            <input type="text" name="title" placeholder="Title" required>
            <textarea name="description" placeholder="Description" required></textarea>
            <input type="submit" value="Upload Video" name="submit">
        </form>
    </div>
</body>
</html>

<?php include("includes/footer.php"); ?>