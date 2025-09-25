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

    // Check if 'staffID' parameter is set in the GET request
    if ( isset($_GET["staffID"]) ) {
        // Retrieve 'staffID' from the GET parameters
        $staffID = $_GET["staffID"];

        // Construct SQL query to delete a staff member from 'staff' table based on 'staffID'
        $DeletestaffSQL = "DELETE FROM staff WHERE staffID = $staffID";

        // Execute the delete query
        $connection->query($DeletestaffSQL);
    }

    // Redirect to the 'staff.php' page after processing
    header("location: /ua92_finance/staff.php");

    // Exit script to prevent further execution after redirect
    exit;
 ?>