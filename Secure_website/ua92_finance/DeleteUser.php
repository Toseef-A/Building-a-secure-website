<?php
    // Start a new session
    session_start();

    // Include the connection script to connect to the database
    include("connection.php");

    // Include the functions script to use its functions
    include("functions.php");

    // Check user permissions and display messages accordingly
    check_permission_and_message($connection, __FILE__);
    
    // Retrieve user data
    $user_data = check_login($connection, get_allowed_permissions(__FILE__));

    // Check if 'UsersessionsID' parameter is set in the GET request
    if ( isset($_GET["UsersessionsID"]) ) {
        // Retrieve 'UsersessionsID' from the GET parameters
        $UsersessionsID = $_GET["UsersessionsID"];

        // Construct SQL query to delete a record from 'usersessions' table based on 'UsersessionsID'
        $DeleteUserSQL = "DELETE FROM usersessions WHERE UsersessionsID = $UsersessionsID";

        // Execute the delete query
        $connection->query($DeleteUserSQL);
    }

    // Redirect to the 'usersessions.php' page after processing
    header("location: /ua92_finance/usersessions.php");

    // Exit script to prevent further execution after redirect
    exit;
 ?>