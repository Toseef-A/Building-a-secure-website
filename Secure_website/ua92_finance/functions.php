<?php
    // Include the database connection file
    include("connection.php");

    // Function to determine allowed permissions based on the current file
    function get_allowed_permissions($file) {
        // Array mapping filenames to allowed permissions
        $allowed_permissions = [
            'index.php' => ['staff', 'manager', 'admin'],
            'login.php' => ['staff', 'manager', 'admin'],
            'logout.php' => ['staff', 'manager', 'admin'],
            'manager.php' => ['admin'],
            'staff.php' => ['manager', 'admin'],
            'usersessions.php' => ['admin'],
            'Editmanager.php' => ['admin'],
            'Editstaff.php' => ['admin'],
            'EditUser.php' => ['admin'],
            'AddNewmanager.php' => ['admin'],
            'AddNewstaff.php' => ['admin'],
            'AddNewUser.php' => ['admin'],
            'Deletemanager.php' => ['admin'],
            'Deletestaff.php' => ['admin'],
            'DeleteUser.php' => ['admin'],
        ];

        // Get the filename from the provided file path
        $filename = ($file !== NULL) ? basename($file) : '';

        // Return allowed permissions for the file or an empty array if the file is not found
        return $allowed_permissions[$filename] ?? [];
    }

    // Function to check permissions and display a message if access is denied
    function check_permission_and_message($connection, $file) {
        // Get the allowed permissions for the current file
        $allowed_permissions = get_allowed_permissions($file);

        // Check if the allowed permissions array is empty or the user's permission level is not in the allowed list
        if (empty($allowed_permissions) || !in_array($_SESSION['PermissionLevel'], $allowed_permissions)) {
            // Display an error message
            echo "You don't have access to this page. Please contact the administrator.";
            exit();
        }
    }

    // Function to check if a user is logged in and has the required permissions
    function check_login($connection, $allowed_permissions) {
        // Check if UserID is set in the session
        if (isset($_SESSION['UserID'])) {
            // Cast UserID to an integer
            $UserID = (int)$_SESSION['UserID'];

            // SQL query to fetch user data from the 'usersessions' table
            $query = "SELECT * FROM usersessions WHERE UserID = ? LIMIT 1";

            // Prepare and execute the SQL query
            if ($stmt = $connection->prepare($query)) {
                // Bind UserID parameter
                $stmt->bind_param("i", $UserID);
                $stmt->execute();
                $result = $stmt->get_result();

                // Check if query was successful and user was found
                if ($result && $result->num_rows > 0) {
                    // Fetch user data
                    $User_data = $result->fetch_assoc();

                    // Check if the user's permission level is in the allowed list
                    if (in_array($User_data['PermissionLevel'], $allowed_permissions)) {
                        return $User_data; // Return user data if permissions are sufficient
                    }
                }
                $stmt->close(); // Close statement
            } else {
                error_log("Failed to prepare the SQL statement."); // Log error if preparing SQL statement fails
            }
        }
    }

    // Function to generate a random number of a specified length
    function random_num($length) {
        // Ensure the minimum length is 8 characters
        $length = max($length, 8);
        $text = '';
        $usedDigits = [];

        // Generate a random number by appending unique digits
        while (strlen($text) < $length) {
            $digit = mt_rand(0, 9);
            if (!in_array($digit, $usedDigits)) {
                $usedDigits[] = $digit;
                $text .= $digit;
            }
        }

        return $text; // Return the generated random number
    }
?>
