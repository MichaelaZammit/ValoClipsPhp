<!DOCTYPE html>
<html lang="en">
<head>
    <title>ValoClips</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="style/style1.css">
</head>
<body>
<div class="container">
    <ul class="nav nav-pills">
        <li class="nav-item"><a class="nav-link <?php if ($CURRENT_PAGE == "Posts") { echo "active-button"; }?>" href="post.php"><input type="image" id="image" alt="Post" src="images/post.png" width="auto" height="35"></a></li>
        <li class="nav-item"><a class="nav-link <?php if ($CURRENT_PAGE == "Challenges") { echo "active-button"; }?>" href="challanges.php"><input type="image" id="image" alt="Challenges" src="images\challanges.png" width="auto" height="35"></a></li>
        <li class="nav-item"><a class="nav-link <?php if ($CURRENT_PAGE == "Home") { echo "active-button"; }?>" href="index.php"><input type="image" id="image" alt="ValoClips" src="images/ValoClips.png" width="auto" height="35"></a></li>
        <li class="nav-item"><a class="nav-link <?php if ($CURRENT_PAGE == "Messages") { echo "active-button"; }?>" href="messages.php"><input type="image" id="image" alt="Messages" src="images/messages.png" width="auto" height="35"></a></li>
        <li class="nav-item"><a class="nav-link <?php if ($CURRENT_PAGE == "Profile") { echo "active-button"; }?>" href="profile.php"><input type="image" id="image" alt="Profile" src="images/profile.png" width="auto" height="35"></a></li>
    </ul>
</div>
