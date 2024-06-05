<?php
session_start();
$CURRENT_PAGE = "Add Challenge";
include("includes/header.php");
include("includes/db.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You need to log in to add a challenge.";
    include("includes/footer.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];

    // Insert the new challenge into the database
    $sql = "INSERT INTO challenges (name, description, created_at) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $name, $description);

    if ($stmt->execute()) {
        echo "Challenge added successfully!";
    } else {
        echo "Error adding challenge: " . $stmt->error;
    }

    $stmt->close();
}
?>

<link rel="stylesheet" href="style/style4.css">
<h1>Add New Challenge</h1>
<form action="add_challenge.php" method="POST">
    <label for="name">Challenge Name:</label>
    <input type="text" name="name" id="name" required>
    <label for="description">Description:</label>
    <textarea name="description" id="description" required></textarea>
    <button type="submit">Add Challenge</button>
</form>

<?php include("includes/footer.php"); ?>

