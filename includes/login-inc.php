<?php
    if(isset($_POST["submit"])) {
        require_once "dbh.php";
        require_once "db-functions.php";
        require_once "functions.php";

        $username = $_POST["username"];
        $password = $_POST["password"];
        
        if (emptyInputs([$username, $password]) == true) {
            header("location: ../login.php?error=emptyinput");
            exit();
        }

        loginUser($conn, $username, $password);
    }
    else
    {
        header("location: ../login.php");
        exit();
    }
?>