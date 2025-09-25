<?php
// Include the database connection file
include("connection.php");

// Function to determine allowed permissions based on the current file
function get_allowed_permissions($file) {
    $allowed_permissions = [
        // Associative array mapping filenames to allowed permissions
        // This array specifies which permissions are allowed for each PHP file
        'index.php' => ['staff', 'manager', 'admin'],
        'login.php' => ['staff', 'manager', 'admin'],
        'logout.php' => ['staff', 'manager', 'admin'],
        'manager.php' => ['admin'],
        'staff.php' => ['admin'],
        'Usersessions.php' => ['admin'],
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

    // Check if the key (filename) exists in the array, if not, return an empty array
    return isset($allowed_permissions[$filename]) ? $allowed_permissions[$filename] : [];
}

// Function to check permissions and display a message if access is denied
function check_permission_and_message($connection, $file)
{
    // Get the allowed permissions for the current file
    $allowed_permissions = get_allowed_permissions($file);

    // Check if the allowed permissions array is empty or the user's permission level is not in the allowed list
    if (empty($allowed_permissions) || !in_array($_SESSION['PermissionLevel'], $allowed_permissions)) {
        // Display an error message or redirect to an error page
        echo "You don't have access to this page. Please contact the administrator.";
        exit();
    }
}

// Function to check if a user is logged in and has the required permissions
function check_login($connection, $allowed_permissions) {
    if (isset($_SESSION['UserID'])) {
        $UserID = (int)$_SESSION['UserID'];

        // Query to fetch user data from the 'usersessions' table
        $query = "SELECT * FROM usersessions WHERE UserID = ? LIMIT 1";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $UserID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $User_data = $result->fetch_assoc();

            // Check if the user's permission level is in the allowed list
            if (in_array($User_data['PermissionLevel'], $allowed_permissions)) {
                return $User_data;
            }
        }
    }

    // Redirect to the login page if not logged in or permissions are insufficient
    header("Location: login.php");
    die;
}


