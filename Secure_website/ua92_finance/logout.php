<?php
    // Start a new session
    session_start();

    // Check if $_SESSION['UserID'] is set
    if (isset($_SESSION['UserID'])) {
        // Unset (remove) $_SESSION['UserID']
        unset($_SESSION['UserID']);
    }

    // Redirect to login.php
    header("Location: login.php");

    // Close the database connection
    $connection->close();

    // Terminate script execution
    die;
?>
