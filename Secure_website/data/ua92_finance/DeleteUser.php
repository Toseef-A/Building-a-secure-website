<?php
    include("connection.php");
    include("functions.php");
    
    session_start();
    
    // Moved the check_permission_and_message function call after including the required files
    check_permission_and_message($connection, __FILE__);
    
    // Retrieve user data
    $user_data = check_login($connection, get_allowed_permissions(__FILE__));

    if ( isset($_GET["UserSessionsID"]) ) {
        $UserSessionsID = $_GET["UserSessionsID"];

        $DeleteUserSQL = "DELETE FROM usersessions WHERE UserSessionsID = $UserSessionsID";
        $connection -> query($DeleteUserSQL);
    }

    header("location: /ua92_finance/UserSessions.php");
    exit;

 ?>