<?php
session_start();
include("includes/header.php");
include("includes/db.php");

if (!isset($_GET['id'])) {
    echo "No challenge ID specified.";
    exit();
}

$challenge_id = $_GET['id'];

// Fetch challenge details
$sql = "SELECT * FROM challenges WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $challenge_id);
$stmt->execute();
$challenge_result = $stmt->get_result();
$challenge = $challenge_result->fetch_assoc();

if (!$challenge) {
    echo "Challenge not found.";
    exit();
}

// Fetch videos for the challenge
$sql_videos = "SELECT challenge_videos.*, users.name AS uploader FROM challenge_videos INNER JOIN users ON challenge_videos.user_id = users.id WHERE challenge_id = ? ORDER BY created_at DESC";
$stmt_videos = $conn->prepare($sql_videos);
$stmt_videos->bind_param("i", $challenge_id);
$stmt_videos->execute();
$videos_result = $stmt_videos->get_result();
?>
<link rel="stylesheet" href="style/style4.css">
<h1><?php echo htmlspecialchars($challenge['name']); ?></h1>
<p><?php echo htmlspecialchars($challenge['description']); ?></p>

<h2>Upload Your Video</h2>
<form action="upload_challenge_video.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="challenge_id" value="<?php echo $challenge_id; ?>">
    <label for="title">Title:</label>
    <input type="text" name="title" id="title" required>
    <label for="description">Description:</label>
    <textarea name="description" id="description" required></textarea>
    <label for="media">Upload Video:</label>
    <input type="file" name="media" id="media" accept="video/*" required>
    <button type="submit">Upload</button>
</form>

<h2>Videos for this Challenge</h2>
<div id="videos">
    <?php while($video = $videos_result->fetch_assoc()): ?>
        <div class="video" data-video-id="<?php echo $video['id']; ?>">
            <h3><?php echo htmlspecialchars($video['title']); ?></h3>
            <p><?php echo htmlspecialchars($video['description']); ?></p>
            <p>Uploaded by: <?php echo htmlspecialchars($video['uploader']); ?></p>
            <?php if ($video['media_url']): ?>
                <video width="320" height="240" controls>
                    <source src="<?php echo htmlspecialchars($video['media_url']); ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</div>

<?php include("includes/footer.php"); ?>
