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

        // Initialize variables for user details
    $UsersessionsID = ""; // Variable to store User sessions ID
    $UserID = ""; // Variable to store User's ID
    $User_Name = ""; // Variable to store User's name
    $User_Password = ""; // Variable to store User's password
    $PermissionLevel = ""; // Variable to store permission level
    $errorMessage = ""; // Variable to store usersession's name
    $successMessage = ""; // Variable to store usersession's name

    // Check if the request method is GET
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        // Check if UsersessionID is not set in GET parameters
        if (!isset($_GET["UsersessionsID"])) {
            header("location: /ua92_finance/usersession.php"); // Redirect to usersession.php if UsersessionsID is not provided in GET parameters
            exit; // Exit the script to stop further execution
        }

        $UsersessionsID = $_GET["UsersessionsID"]; // Assign UsersessionsID from GET parameters to $UsersessionsID variable

        // Retrieve user session details from the database based on UsersessionsID
        $sql = "SELECT * FROM usersessions WHERE UsersessionsID=?";
        $stmt = $connection->prepare($sql); // Prepare the SQL statement using the connection object
        $stmt->bind_param("i", $UsersessionsID); // Bind UsersessionsID parameter to the prepared statement
        $stmt->execute(); // Execute the prepared statement
        $result = $stmt->get_result(); // Get result set from the executed statement

        // Check if there was an error executing the query
        if (!$result) {
            echo "Error executing query: " . $stmt->error; // Output error message if query execution fails
            exit; // Exit the script
        }

        // Check if any records were found for the provided UsersessionsID
        if ($result->num_rows == 0) {
            echo "No records found for UsersessionsID: " . $UsersessionsID; // Output message if no records were found for the UsersessionsID
            exit; // Exit the script
        }

        // Fetch user session data from the result set
        $row = $result->fetch_assoc();

        // Assign fetched data to variables
        $UsersessionsID = $row["UsersessionsID"];
        $UserID = $row["UserID"];
        $User_Name = $row["User_Name"];
        // Don't fetch the hashed password for display
        $User_Password = "";
        $PermissionLevel = $row["PermissionLevel"];

        // Check if the request method is POST
    } elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve and sanitize form data
        $UsersessionsID = $_POST["UsersessionsID"];
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
        } elseif (!is_numeric($UsersessionsID)) {
            // Validate numeric values
            $errorMessage = "Invalid UsersessionsID";
        } elseif (!ctype_alnum($User_Name)) {
            // Check if username contains only alphanumeric characters
            $errorMessage = "Username must contain only letters and digits.";
        } elseif (!in_array($PermissionLevel, ["staff", "manager", "admin"])) {
            // Validate permission level selection
            $errorMessage = "Invalid permission level selected.";
        } elseif (strlen($User_Password) < 8) {
            // Validate minimum password length
            $errorMessage = "Password must be at least 8 characters long.";
        } else {
            // Hash the password
            $hashedPassword = password_hash($User_Password, PASSWORD_DEFAULT);

            // Proceed with update using prepared statements
            $EditUserSQL = "UPDATE usersessions SET User_Name=?, User_Password=?, PermissionLevel=? WHERE UsersessionsID=?";
            // Prepare the SQL statement using the connection object and the SQL query stored in $EditUserSQL
            $stmt = $connection->prepare($EditUserSQL);
            $stmt->bind_param("sssi", $User_Name, $hashedPassword, $PermissionLevel, $UsersessionsID);
            
            // The bind_param() method is used to bind variables to the parameter markers in the SQL statement.
            // The first argument "ssss" specifies the types of the variables being bound, where "s" stands for string and "i" stands for integer.
            // This means that the variables $UserID, $User_Name, $hashedPassword, $PermissionLevel
            // are all expected to be strings and $UsersessionsID is expected to be an integer. The following variables are the actual values to be bound to the placeholders in the SQL statement.
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
                echo "Error updating user: " . $stmt->error;
            }
        }
    }

    // Close the database connection
    $connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
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
            <h2 class="title">Edit User</h2>
        </div>
        <!-- Form section -->
        <form method="POST" action="EditUser.php">

            <!-- Hidden input for UsersessionsID -->
            <input type="hidden" name="UsersessionsID" value="<?php echo htmlspecialchars($UsersessionsID); ?>">

            <!-- User name input -->
            <div class="mb-3 row"> <!-- Bootstrap class for margin bottom and row layout -->
                <label for="User_Name" class="col-sm-3 col-form-label">Username:</label> <!-- Label for the User name input, occupies 3 columns -->
                <div class="col-sm-9"> <!-- Div to wrap input, occupies 9 columns -->
                    <input type="text" class="form-control" id="User_Name" name="User_Name" value="<?php echo htmlspecialchars($User_Name); ?>" required> <!-- Text input for User name, prefilled with sanitized PHP variable value, required field -->
                </div>
            </div>

            <!-- User password input -->
            <div class="mb-3 row"> <!-- Bootstrap class for margin bottom and row layout -->
                <label for="User_Password" class="col-sm-3 col-form-label">Password:</label> <!-- Label for the User password input, occupies 3 columns -->
                <div class="col-sm-9"> <!-- Div to wrap input, occupies 9 columns -->
                    <input type="password" class="form-control" id="User_Password" name="User_Password" value="<?php echo htmlspecialchars($User_Password); ?>" required> <!-- Text input for User password, prefilled with sanitized PHP variable value, required field -->
                </div>
            </div>

            <!-- User permission level selection -->
            <div class="mb-3 row"> <!-- Bootstrap class for margin bottom and row layout -->
                <label for="PermissionLevel" class="col-sm-3 col-form-label">Select Role:</label> <!-- Label for the User permission level selection, occupies 3 columns -->
                <div class="col-sm-9"> <!-- Div to wrap select, occupies 9 columns -->
                    <select id="PermissionLevel" name="PermissionLevel" class="form-select" required> <!-- Select input for User permission level, required field -->
                        <option value="staff" <?php echo ($PermissionLevel === 'Staff') ? 'selected' : ''; ?>>Staff</option> <!-- Option for Staff, selected if PHP variable equals 'Staff' -->
                        <option value="manager" <?php echo ($PermissionLevel === 'Manager') ? 'selected' : ''; ?>>Manager</option> <!-- Option for Manager, selected if PHP variable equals 'Manager' -->
                        <option value="admin" <?php echo ($PermissionLevel === 'Admin') ? 'selected' : ''; ?>>Admin</option> <!-- Option for Admin, selected if PHP variable equals 'Admin' -->
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

