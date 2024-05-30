<?php 
    
    function userExists($conn, $username, $email) {
    $sql = "SELECT username FROM users WHERE username = ? OR email = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die("SQL error");
    }
    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        return false;
    }
    mysqli_stmt_close($stmt);
}

    function usernameExists($conn, $username) {
    $sql = "SELECT COUNT(*) FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_array();

    // If count is more than 0, username exists
    return $row[0] > 0;
}

function emailExists($conn, $email) {
    $sql = "SELECT COUNT(*) FROM users WHERE email = ?";  
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_array();

    // If count is more than 0, email exists
    return $row[0] > 0;
}

function createUser($conn, $username, $password, $email, $firstName, $lastName, $houseNameNum, $street, $townId, $postCode) {
    $sql = "INSERT INTO users (username, password, email, firstName, lastName, houseNameNum, street, townId, postCode) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Debugging: Output the values being bound
    error_log("Creating user: $username, $password, $email, $firstName, $lastName, $houseNameNum, $street, $townId, $postCode");

    $stmt->bind_param("sssssssss", $username, $password, $email, $firstName, $lastName, $houseNameNum, $street, $townId, $postCode);

    if (!$stmt->execute()) {
        // If execution fails, output the error
        error_log("Error in createUser: " . $stmt->error);
        return false;
    }

    return $stmt->affected_rows > 0;
}

function authenticateUser($conn, $username, $password) {
    $sql = "SELECT id, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            return $user['id'];  // Return user's ID if password is correct
        }
    }
    return false;
}

function getUserDetails($conn, $userId) {
    $sql = "SELECT u.username, u.firstName, u.lastName, u.email 
            FROM users u
            LEFT JOIN town t ON u.townId = t.id 
            WHERE u.id = ?"; // Specify 'u.id' instead of just 'id'
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}
function loadRegions($conn){
    $sql = "SELECT * FROM Region;";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        echo "Could not load Regions";
        exit();
    }

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    mysqli_stmt_close($stmt);

    return $result;
}


function createApplication($conn, $username, $password, $email, $firstName, $lastName, $region)
{
    $sql = "INSERT INTO application
        (username, password, email, firstName, lastName, address, 
        street, town, course, applicationDate)
        VALUES(?,?,?,?,?,?,?,?,?,?);";


    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../index.php?error=stmtfailed");
        exit();
    }

    // Automated application date - user does not insert this
    $applicationDate = date("Y-m-d");
    
    // Hashed Password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "ssssssssss", $username, $hashedPassword, $email, $firstName, $lastName,$region, $applicationDate);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // redirect back to sign up page once registration is complete
    header("location: ../index.php?success=true");
}

function loadCourseLevels($conn){
    $sql = "SELECT * FROM CourseLevel;";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        echo "Could not load Course Levels";
        exit();
    }

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    mysqli_stmt_close($stmt);

    return $result;
}

function loadApplication($conn, $id){
    $sql = "SELECT * FROM Application WHERE id = {$id};";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        echo "Could not load Application";
        exit();
    }

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    mysqli_stmt_close($stmt);

    // only returns database record if it exists
    if ($row = mysqli_fetch_assoc($result)){
        return $row;
    }
    else
    {
        return false;
    }
}

function deleteApplication($conn, $id){
    $sql = "DELETE FROM users WHERE id = ?;";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        echo "Could not delete Application";
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: ../index.php");
    exit();
}

function updateApplication($conn, $id, $username, $password, $email, $firstName, $lastName,$address, $street, $town, $course){
    $sql = "UPDATE application
            SET username = ?,
                password = ?,
                email = ?,
                firstName = ?,
                lastName = ?,
                region = ?,
            WHERE id = ?;";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../index.php?error=stmtfailed");
        exit();
    }
    
    // Hashed Password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "ssssssssss", $username, $hashedPassword, $email, $firstName, $lastName,$region,$id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // redirect back to sign up page once registration is complete
    header("location: ../application.php?application={$id}&success=true");
    exit();
}

function loadAllApplications($conn){
    $sql = "SELECT * FROM users";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        echo "Could not load Applications";
        exit();
    }

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    mysqli_stmt_close($stmt);

    return $result;
}

function userExists($conn, $username, $email){
    $sql = "SELECT username, password FROM users WHERE username = ? OR email = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)){
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);

    if ($row = mysqli_fetch_assoc($resultData)){
        return $row;
    }
    else
    {
        $result = false;
        return $result;
    }
}