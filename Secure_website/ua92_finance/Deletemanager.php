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

    // Check if the 'managerID' parameter is set in the GET request
    if ( isset($_GET["managerID"]) ) {
        $managerID = $_GET["managerID"]; // Retrieve the value of 'managerID' from the GET parameters

        // Construct the SQL query to delete a manager from the 'manager' table based on 'managerID'
        $DeletemanagerSQL = "DELETE FROM manager WHERE managerID = $managerID";

        // Execute the delete query
        $connection->query($DeletemanagerSQL);
    }

    // Redirect to the 'manager.php' page after processing
    header("location: /ua92_finance/manager.php");

    // Exit the script to ensure no further processing occurs after the redirect
    exit;
 ?>