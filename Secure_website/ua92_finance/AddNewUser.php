<?php
    // Start a new session
    session_start();

    // Include the connection script to connect to the database
    include("connection.php");

    // Include the functions script to use its functions
    include("functions.php");

    // Check user permissions and display messages accordingly
    check_permission_and_message($connection, __FILE__);

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the form was submitted via POST method
        // Retrieve and sanitize form data
        $User_Name = trim($_POST['User_Name']); // Get and trim the User's name from POST data
        $User_Password = trim($_POST['User_Password']); // Get and trim the User's password from POST data
        $PermissionLevel = trim($_POST['PermissionLevel']); // Get and trim the User's permission level from POST data

        // trim() is a function that removes whitespace from the beginning and end of a string.
        // It is used here to clean the input data, ensuring that no leading or trailing spaces are included.
        // This helps prevent potential issues with data validation and storage.

        // Input validation
        if (empty($User_Name) || empty($User_Password) || empty($PermissionLevel)) { // Check if any required fields are empty
            // Check if any required fields are empty
            $errorMessage = "All fields are required";
        } elseif (!ctype_alnum($User_Name)) {
            // Check if username contains only alphanumeric characters
            $errorMessage = "Username must contain only letters and digits.";
        } elseif (strlen($User_Password) < 8) {
            // Validate minimum password length
            $errorMessage = "Password must be at least 8 characters long.";
        } elseif (!in_array($PermissionLevel, ["staff", "manager", "admin"])) {
            // Validate permission level selection
            $errorMessage = "Invalid permission level selected.";
        } else {  
            // Generate a random user ID
            $UserID = random_num(8);
            // Hash the password
            $hashedPassword = password_hash($User_Password, PASSWORD_DEFAULT);

            // Proceed with insertion using prepared statements
            $query = "INSERT INTO usersessions (UserID, User_Name, User_Password, PermissionLevel) VALUES (?, ?, ?, ?)";
            // Prepare the SQL statement using the connection object and the SQL query stored in $query
            $stmt = $connection->prepare($query);
            $stmt->bind_param("ssss", $UserID, $User_Name, $hashedPassword, $PermissionLevel);

            // The bind_param() method is used to bind variables to the parameter markers in the SQL statement.
            // The first argument "ssss" specifies the types of the variables being bound, where "s" stands for string.
            // This means that the variables $UserID, $User_Name, $hashedPassword, $PermissionLevel
            // are all expected to be strings. The following variables are the actual values to be bound to the placeholders in the SQL statement.
            // By binding parameters, we ensure that the data is sent separately from the SQL statement, which helps protect against SQL injection.
            // SQL injection is a technique where an attacker can execute arbitrary SQL code on the database by manipulating input data.
            // By using prepared statements and parameter binding, the SQL logic is separated from the data input, thus preventing malicious data from altering the intended SQL command.

            // Execute the prepared statement with the bound parameters. This sends the SQL statement to the database server for execution.
            if ($stmt->execute()) {
                // If the statement executes successfully, redirect the user to usersessions.php
                header("Location: usersessions.php");
                // Ensure that no further code is executed after the header redirection
                die;
            } else {
                // If there is an error executing the statement, display the error message
                echo "Error inserting user: " . $stmt->error;
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <!-- Link to Bootstrap 5.3.1 CSS library -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Set up the main body style */
        body {
            display: flex; /* Use flexbox to center content */
            align-items: center; /* Vertically center content */
            justify-content: center; /* Horizontally center content */
            height: 100vh; /* Full viewport height */
            margin: 0; /* Remove default margin */
            background-color: #f8f9fa; /* Light background color */
        }

        /* Style the container holding the form */
        .container {
            background-color: #ffffff; /* White background for the container */
            padding: 20px; /* Padding inside the container */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
        }

        /* Style for the main button */
        .main-button {
            width: 100%; /* Full width button */
            padding: 10px; /* Padding inside the button */
            font-size: 16px; /* Font size */
            font-weight: bold; /* Bold text */
            border: none; /* Remove border */
            background-color: #007bff; /* Primary button color */
            color: #ffffff; /* White text */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor on hover */
            transition: background-color 0.3s ease; /* Smooth transition for hover effect */
        }

        /* Hover effect for the main button */
        .main-button:hover {
            background-color: #0056b3; /* Darker blue on hover */
            text-decoration: none; /* Remove text underline */
        }

        /* Style for form labels */
        .form-label {
            font-weight: bold; /* Bold text */
        }

        /* Style for form controls (inputs, selects, etc.) */
        .form-control {
            width: 100%; /* Full width input */
            padding: 10px; /* Padding inside the input */
            border-radius: 5px; /* Rounded corners */
            border: 1px solid #ced4da; /* Light gray border */
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Section header -->
        <div class="section-header text-center mb-4">
            <h2 class="title">Add New User</h2>
        </div>
        <!-- Form section -->
        <form method="POST" action="AddNewUser.php">

            <!-- User name input -->
            <div class="mb-3 row"> <!-- Bootstrap class for margin bottom and row layout -->
                <label for="User_Name" class="col-sm-3 col-form-label">Username:</label> <!-- Label for the User name input, occupies 3 columns -->
                <div class="col-sm-9"> <!-- Div to wrap input, occupies 9 columns -->
                    <input type="text" class="form-control" id="User_Name" name="User_Name" required> <!-- Text input for User name, required field -->
                </div>
            </div>

            <!-- User password input -->
            <div class="mb-3 row"> <!-- Bootstrap class for margin bottom and row layout -->
                <label for="User_Password" class="col-sm-3 col-form-label">Password:</label> <!-- Label for the User password input, occupies 3 columns -->
                <div class="col-sm-9"> <!-- Div to wrap input, occupies 9 columns -->
                    <input type="password" class="form-control" id="User_Password" name="User_Password" required> <!-- Text input for User password, required field -->
                </div>
            </div>

            <!-- User permission level selection -->
            <div class="mb-3 row"> <!-- Bootstrap class for margin bottom and row layout -->
                <label for="PermissionLevel" class="col-sm-3 col-form-label">Select Role:</label> <!-- Label for the User permission level selection, occupies 3 columns -->
                <div class="col-sm-9"> <!-- Div to wrap select, occupies 9 columns -->
                    <select id="PermissionLevel" name="PermissionLevel" class="form-select" required> <!-- Select input for User permission level, required field -->
                        <option value="staff" >Staff</option> <!-- Option for Staff -->
                        <option value="manager" >Manager</option> <!-- Option for Manager -->
                        <option value="admin" >Admin</option> <!-- Option for Admin -->
                    </select>
                </div>
            </div>

            <!-- Form submission buttons -->
            <div class="row mb-3"> <!-- Bootstrap class for margin bottom and row layout -->
                <div class="offset-sm-3 col-sm-3 d-grid"> <!-- Div to wrap submit button, occupies 3 columns with offset of 3, uses Bootstrap grid classes for full width -->
                    <button type="submit" class="btn btn-primary">Submit</button> <!-- Submit button, styled with Bootstrap primary button class -->
                </div>
                <div class="col-sm-3 d-grid"> <!-- Div to wrap cancel link, occupies 3 columns, uses Bootstrap grid classes for full width -->
                    <a href="usersessions.php" class="btn btn-outline-primary">Cancel</a> <!-- Link to cancel, styled with Bootstrap outline primary button class -->
                </div>
            </div>

        </form>
        <!-- End of Form section -->
    </div>

    <!-- Include jQuery from CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Bootstrap's JavaScript bundle from CDN for handling Bootstrap components -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

