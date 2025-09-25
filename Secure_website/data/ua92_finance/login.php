<?php
// Start a new session or resume the existing session
session_start();

// Include the database connection file and functions
include("connection.php");
include("functions.php");

// Check if the HTTP request method is POST (form submission)
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Retrieve user input from the login form
    $User_Name = $_POST['User_Name'];
    $User_Password = $_POST['User_Password'];
    $PermissionLevel = $_POST['User_role'];

    // Check if the input fields are not empty and the username is not numeric
    if (!empty($User_Name) && !empty($User_Password) && !empty($PermissionLevel) && !is_numeric($User_Name)) {
        // Prepare a SQL query to select user data based on the provided username and role
        $query = "SELECT * FROM usersessions WHERE User_Name = ? AND PermissionLevel = ? LIMIT 1";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("ss", $User_Name, $PermissionLevel);
        $stmt->execute();

        // Check for query execution errors
        if ($stmt->error) {
            echo "Query execution error: " . $stmt->error;
            exit();
        }

        // Get the result of the query
        $result = $stmt->get_result();

        // Check if a user with the provided credentials exists
        if ($result->num_rows > 0) {
            $User_data = $result->fetch_assoc();
        
            // Verify the password using password_verify
            if (password_verify($User_Password, $User_data['User_Password'])) {
                // Set session variables to store user data
                $_SESSION['UserID'] = $User_data['UserID'];
                $_SESSION['PermissionLevel'] = $User_data['PermissionLevel'];

                // Redirect the user to the index page upon successful login
                header("Location: index.php");
                exit();
            } else {
                $error_message = "Wrong password!";
            }
        } else {
            $error_message = "No user found with provided credentials!";
        }
    } else {
        $error_message = "Please enter valid information!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login page</title>
</head>
<body>
    <!-- Login form -->
    <form method="post" action="login.php">
        <!-- Display an error message if it's set -->
        <?php if (isset($error_message)) echo "<p>$error_message</p>"; ?>

        <!-- Select user role -->
        <label for="User_role">Select Role:</label>
        <select id="User_role" name="User_role">
            <option value="staff">staff</option>
            <option value="manager">manager</option>
            <option value="admin">admin</option>
        </select>

        <br><br>

        <!-- Input field for username -->
        <input type="text" name="User_Name"><br><br>

        <!-- Input field for password -->
        <input type="password" name="User_Password"><br><br>

        <!-- Submit button for form submission -->
        <input id="button" type="submit" value="Login"><br><br>
    </form>
</body>
</html>

