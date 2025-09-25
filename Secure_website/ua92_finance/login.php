<?php
    // Start a new session
    session_start();

    // Include the connection script to connect to the database
    include("connection.php");

    // Include the functions script to use its functions
    include("functions.php");

    // Initialize error message variable
    $error_message = '';

    // Check if there's an error message stored in session
    if (isset($_SESSION['error_message'])) {
        $error_message = $_SESSION['error_message'];
        unset($_SESSION['error_message']); // Clear the error message from session after displaying it
    }

    // Check if the HTTP request method is POST (form submission)
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        // Retrieve user input from the login form
        $User_Name = trim($_POST['User_Name']);
        $User_Password = trim($_POST['User_Password']);
        $PermissionLevel = trim($_POST['User_role']);

        // Check if the input fields are not empty and the username is not numeric
        if (!empty($User_Name) && !empty($User_Password) && !empty($PermissionLevel) && !is_numeric($User_Name)) {
            // Prepare a SQL query to select user data based on the provided username and role
            $query = "SELECT * FROM usersessions WHERE User_Name = ? AND PermissionLevel = ? LIMIT 1";
            if ($stmt = $connection->prepare($query)) {
                $stmt->bind_param("ss", $User_Name, $PermissionLevel);
                $stmt->execute();

                // Check for query execution errors
                if ($stmt->error) {
                    error_log("Query execution error: " . $stmt->error);
                    $_SESSION['error_message'] = "An error occurred. Please try again later.";
                } else {
                    // Get the result of the query
                    $result = $stmt->get_result();

                    // Check if a user with the provided credentials exists
                    if ($result->num_rows > 0) {
                        $User_data = $result->fetch_assoc();
                    
                        // Verify the password using password_verify
                        if (password_verify($User_Password, $User_data['User_Password'])) {
                            // Regenerate session ID to prevent session fixation
                            session_regenerate_id();

                            // Set session variables to store user data
                            $_SESSION['UserID'] = $User_data['UserID'];
                            $_SESSION['PermissionLevel'] = $User_data['PermissionLevel'];

                            // Redirect the user to the index page upon successful login
                            header("Location: index.php");
                            exit();
                        } else {
                            $_SESSION['error_message'] = "Wrong password!";
                        }
                    } else {
                        $_SESSION['error_message'] = "No user found with provided credentials!";
                    }
                }

                $stmt->close();
            } else {
                error_log("Failed to prepare the SQL statement.");
                $_SESSION['error_message'] = "An error occurred. Please try again later.";
            }

            // Redirect to the login page to display the error message
            header("Location: login.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Please enter valid information!";
            
            // Redirect to the login page to display the error message
            header("Location: login.php");
            exit();
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
        font-family: Arial, sans-serif; /* Set the font family for the entire page */
        background-color: #f0f0f0; /* Set a light gray background color */
        display: flex; /* Use flexbox for layout */
        justify-content: center; /* Center horizontally */
        align-items: center; /* Center vertically */
        height: 100vh; /* Full viewport height */
        margin: 0; /* Remove default margin */
        background-image: url('https://tass.gov.uk/wp-content/uploads/2020/06/UA92-logo-Full-MCR-192-SPOT.png'); /* Background image URL */
        background-size: cover; /* Cover the entire background */
        background-position: center; /* Center the background image */
    }

    .login-container {
        background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent white background */
        padding: 20px; /* Padding inside the container */
        border-radius: 5px; /* Rounded corners */
        box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1); /* Box shadow for a subtle depth effect */
        width: 300px; /* Width of the login container */
        margin-top: 50px; /* Margin from the top to create space */
    }

    .form-group {
        margin-bottom: 15px; /* Bottom margin for form groups */
    }

    .form-group label {
        display: block; /* Display labels as blocks for proper alignment */
        font-weight: bold; /* Bold font weight for labels */
        margin-bottom: 5px; /* Bottom margin for labels */
    }

    .form-group input[type="text"],
    .form-group input[type="password"],
    .form-group select {
        width: calc(100% - 16px); /* Adjusted width to accommodate padding */
        padding: 8px; /* Padding inside inputs and selects */
        border: 1px solid #ccc; /* Light gray border */
        border-radius: 4px; /* Rounded corners for inputs and selects */
        box-sizing: border-box; /* Include padding and border in the element's total width/height */
        margin-bottom: 10px; /* Bottom margin for inputs and selects */
    }

    .form-group input[type="submit"] {
        background-color: #007bff; /* Background color for submit button */
        color: white; /* Text color for submit button */
        border: none; /* Remove default border */
        padding: 10px 20px; /* Padding inside the submit button */
        cursor: pointer; /* Pointer cursor on hover */
        border-radius: 4px; /* Rounded corners for submit button */
        width: 100%; /* Full width for submit button */
    }

    .form-group input[type="submit"]:hover {
        background-color: #0056b3; /* Darker blue background on hover */
    }

    .error-message {
        color: red; /* Red color for error messages */
        font-weight: bold; /* Bold font weight for error messages */
        text-align: center; /* Center align error messages */
        margin-top: 10px; /* Top margin for error messages */
    }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Login form -->
        <form method="post" action="login.php">
            <!-- Display an error message if it's set -->
            <?php if (!empty($error_message)) echo "<p class='error-message'>" . htmlspecialchars($error_message) . "</p>"; ?>
            
            <!-- Input field for username -->
            <div class="form-group">
                <label for="User_Name">Username:</label> <!-- Label for the User name -->
                <input type="text" id="User_Name" name="User_Name" required> <!-- Text input for User name, required field -->
            </div>
            
            <!-- Input field for password -->
            <div class="form-group">
                <label for="User_Password">Password:</label> <!-- Label for the User password -->
                <input type="password" id="User_Password" name="User_Password" required> <!-- Text input for User password, required field -->
            </div>
            
            <!-- Select user role -->
            <div class="form-group">
                <label for="User_role">Select Role:</label> <!-- Label for the User role -->
                <select id="User_role" name="User_role" required> <!-- Select input for User role, required field -->
                    <option value="staff">Staff</option> <!-- Option for Staff role -->
                    <option value="manager">Manager</option> <!-- Option for Manager role -->
                    <option value="admin">Admin</option> <!-- Option for Admin role -->
                </select>
            </div>
            
            <!-- Submit button for form submission -->
            <div class="form-group">
                <input type="submit" value="Login"> <!-- Submit button labeled "Login" -->
            </div>
        </form>
    </div>
</body>
</html>
