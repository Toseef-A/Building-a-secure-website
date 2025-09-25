 <?php
    include("connection.php");
    include("functions.php");
    // Start session
    session_start();
    
    // Moved the check_permission_and_message function call after including the required files
    check_permission_and_message($connection, __FILE__);
    
    // Retrieve user data
    $user_data = check_login($connection, get_allowed_permissions(__FILE__));

    // Check if staff id is received
    if ( isset($_GET["staffID"]) ) {
        // Get staff id
        $staffID = $_GET["staffID"];
        // Delete the data from the database
        $DeletestaffSQL = "DELETE FROM staff WHERE staffID = $staffID";
        $connection -> query($DeletestaffSQL);
    }

    header("location: /ua92_finance/staff.php");
    exit;

 ?>