<?php
    // Include the database connection file
    include("connection.php");

    // Function to hash passwords and update the database
    function hashAndStorePassword($connection, $UserSessionsID, $plaintextPassword) {
        // Hash the plaintext password using bcrypt algorithm
        $hashedPassword = password_hash($plaintextPassword, PASSWORD_DEFAULT);
        
        // SQL query to update the User_Password field in the usersessions table
        $query = "UPDATE usersessions SET User_Password = ? WHERE UserSessionsID = ?";
        
        // Prepare the SQL query
        $stmt = $connection->prepare($query);
        
        // Bind parameters to the prepared statement
        $stmt->bind_param("si", $hashedPassword, $UserSessionsID);
        
        // Execute the prepared statement
        $stmt->execute();
        
        // Close the prepared statement
        $stmt->close();
    }

    // Example usage: Hash and store passwords for specific UserSessionsIDs
    hashAndStorePassword($connection, 65, 'toseef');
    hashAndStorePassword($connection, 66, 'toseef');

    // Output success message
    echo "Passwords have been hashed and updated in the database.";
?>
