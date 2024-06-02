<?php
session_start();
include("includes/db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php");
    } else {
        echo "Invalid credentials";
    }
}

$conn->close();
?>

<h1>Login</h1>
<form method="post">
    <div class="form-group">
        <label for="email">Email:</label><br>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="password">Password:</label><br>
        <input type="password" class="form-control" id="password" name="password" required>
    </div><br>
    <button type="submit" class="btn btn-default">Login</button>
</form>
