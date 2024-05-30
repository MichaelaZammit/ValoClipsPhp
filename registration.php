<?php 
    require_once "includes/functions.php";

    require_once "includes/dbh.php";
    require_once "includes/db-functions.php";
    
    include "includes/header.php";
?>

<link rel="stylesheet" href="style\style3.css">

<header class="container-fluid bg-light border-bottom border-secondary p-4">
    <div class="row">
        <div class="col-12">
            <h1>Register</h1>
        </div>
    </div>
</header>

<form action="includes/login-inc.php" method="post">
    <div class="container">
        <div class="row mt-5">
            <div class="col-12">
                <div class="mb-3">
                    <label for="input-username" class="form-label">Username:</label>
                    <input type="text" name="username" id="input-username" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="input-password" class="form-label">Password:</label>
                    <input type="password" name="password" id="input-password" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="input-email" class="form-label">Email:</label>
                    <input type="email" name="email" id="input-email" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="input-firstName" class="form-label">First Name:</label>
                    <input type="firtextstName" name="firstName" id="input-firstName" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="input-password" class="form-label">Last Name:</label>
                    <input type="text" name="lastName" id="input-lastName" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="input-password" class="form-label">Region:</label>
                    <input type="text" name="region" id="input-region" class="form-control">
                </div>

                <br>

                <div class="d-grid">
                    <button type="submit" name="submit" class="btn btn-primary">Register</button>    
                </div>
                <br>
                <p>
                <a href="login.php">Log In</a>
                </p>
            </div>
        </div>
    </div>
</form>





<?php include "includes/footer.php" ?>