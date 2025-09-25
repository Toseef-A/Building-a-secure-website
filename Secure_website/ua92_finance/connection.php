<?php
    $servername = "localhost"; // Define the server name or IP address where the database server is running
    $username = "root"; // Username used to connect to the MySQL database server
    $password = ""; // Password associated with the username for database access
    $database = "ua92_finance"; // Name of the database to connect to

    // Create connection
    $connection = new mysqli($servername, $username, $password, $database); // Create a new MySQLi connection object

    // Check connection
    if ($connection->connect_error) {
        // Check if connection to the database failed
        die("Connection failed: " . $connection->connect_error); // Output an error message and terminate the script
    }
?>
