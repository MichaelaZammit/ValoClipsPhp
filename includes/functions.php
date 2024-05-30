<?php 

function passwordsMatch($password, $confPassword){

    $result = false;
    if($password == $confPassword){
        $result = true;
    }
    return $result; 
}

function emptyInputs($inputs){
    foreach($inputs as $input){
        if(empty($input)==true){
            return true;
        }
    }
}

function invalidEmail($email){
    if(!filter_var($email,FILTER_VALIDATE_EMAIL))
    {
        return true;
    }
    else{
        return false;
    }
}

function invalidUsername($username){
    $result = false;

    if(!preg_match("/^[a-zA-Z0-9]*$/", $username)){
        $result = true;
    }

    return $result;
}

function loginUser($conn, $username, $password){
    $userExists = userExists($conn, $username, $username);

    if ($userExists === false){
        header("location: ../login.php?error=incorrectlogin");
        exit();
    }

    $hashedPassword = $userExists["password"];
    $checkPassword = password_verify($password,$hashedPassword);

    if ($checkPassword === false){
        header("location: ../login.php?error=incorrectlogin");
        exit();
    }
    elseif($checkPassword === true){
        session_start();
        $_SESSION["username"] = $userExists["username"];

        header("location: ../applications.php");
        exit();
    }
}