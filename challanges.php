<?php
session_start();
$CURRENT_PAGE = "Challenges";
include("includes/header.php");
include("includes/db.php");

// Fetch all challenges from the database
$sql = "SELECT * FROM challenges ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<link rel="stylesheet" href="style/style4.css">
<h1>Challenges</h1>

<p>Participate in community challenges and showcase your skills.</p>

<?php if (isset($_SESSION['user_id'])): ?>
    <a href="add_challenge.php">Add New Challenge</a>
<?php endif; ?>

<div id="challenges">
    <?php while($challenge = $result->fetch_assoc()): ?>
        <div class="challenge" data-challenge-id="<?php echo $challenge['id']; ?>">
            <h2><?php echo htmlspecialchars($challenge['name']); ?></h2>
            <p><?php echo htmlspecialchars($challenge['description']); ?></p>
            <a href="challenge_detail.php?id=<?php echo $challenge['id']; ?>">View Challenge</a>
        </div>
    <?php endwhile; ?>
</div>

<?php include("includes/footer.php"); ?>