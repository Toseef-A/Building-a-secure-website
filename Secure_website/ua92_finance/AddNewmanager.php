<?php
    // Start a new session
    session_start();

    // Include the connection script to connect to the database
    include("connection.php");

    // Include the functions script to use its functions
    include("functions.php");

    // Check user permissions and display messages accordingly
    check_permission_and_message($connection, __FILE__);

    // Initialize variables for manager details
    $manager_Name = ""; // Variable to store manager's name
    $manager_Address = ""; // Variable to store manager's address
    $manager_Email = ""; // Variable to store manager's email
    $manager_PhoneNumber = ""; // Variable to store manager's phone number
    $manager_DateOfBirth = ""; // Variable to store manager's date of birth
    $manager_Gender = ""; // Variable to store manager's gender
    $manager_AnnualSalary = ""; // Variable to store manager's annual salary
    $manager_BackgroundCheck = ""; // Variable to store manager's background check status
    $errorMessage = ""; // Variable to store error messages
    $successMessage = ""; // Variable to store success messages

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the form was submitted via POST method
        // Retrieve and sanitize form data
        $manager_Name = trim($_POST["manager_Name"]); // Get and trim the manager's name from POST data
        $manager_Address = trim($_POST["manager_Address"]); // Get and trim the manager's address from POST data
        $manager_Email = trim($_POST["manager_Email"]); // Get and trim the manager's email from POST data
        $manager_PhoneNumber = trim($_POST["manager_PhoneNumber"]); // Get and trim the manager's phone number from POST data
        $manager_DateOfBirth = $_POST["manager_DateOfBirth"]; // Get the manager's date of birth from POST data (no trim needed for date)
        $manager_Gender = $_POST["manager_Gender"]; // Get the manager's gender from POST data (no trim needed)
        $manager_AnnualSalary = trim($_POST["manager_AnnualSalary"]); // Get and trim the manager's annual salary from POST data
        $manager_BackgroundCheck = $_POST["manager_BackgroundCheck"]; // Get the manager's background check status from POST data (no trim needed)

        // trim() is a function that removes whitespace from the beginning and end of a string.
        // It is used here to clean the input data, ensuring that no leading or trailing spaces are included.
        // This helps prevent potential issues with data validation and storage.

        // Input validation
        if (empty($manager_Name) || empty($manager_Address) || empty($manager_Email) || empty($manager_PhoneNumber) || empty($manager_DateOfBirth) || empty($manager_Gender) || empty($manager_AnnualSalary) || empty($manager_BackgroundCheck)) {
            // Check if any required fields are empty
            $errorMessage = "All fields are required";
        } elseif (strlen($manager_Name) > 255) {
            // Check if the manager's name exceeds 255 characters
            $errorMessage = "Name must be at most 255 characters long.";
        } elseif (strlen($manager_Address) > 255) {
            // Check if the manager's address exceeds 255 characters
            $errorMessage = "Address must be at most 255 characters long.";
        } elseif (!filter_var($manager_Email, FILTER_VALIDATE_EMAIL) || !preg_match("/@gmail\.com$/", $manager_Email)) {
            // Check if the manager's email is valid and ends with @gmail.com
            $errorMessage = "Invalid email format. Must end with @gmail.com";
        } elseif (!preg_match("/^\d{10}$/", $manager_PhoneNumber)) {
            // Check if the manager's phone number is exactly 10 digits
            $errorMessage = "Invalid phone number. Must have 10 digits.";
        } elseif (!ctype_digit($manager_AnnualSalary)) {
            // Check if the manager's annual salary contains only digits
            $errorMessage = "Annual salary must contain only digits.";
        } else {
            // Proceed with insertion using prepared statements
            $AddNewManagerSQL = "INSERT INTO manager (manager_Name, manager_Address, manager_Email, manager_PhoneNumber, manager_DateOfBirth, manager_Gender, manager_AnnualSalary, manager_BackgroundCheck) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            // Prepare the SQL statement using the connection object and the SQL query stored in $AddNewManagerSQL
            $stmt = $connection->prepare($AddNewManagerSQL);
            $stmt->bind_param("ssssssss", $manager_Name, $manager_Address, $manager_Email, $manager_PhoneNumber, $manager_DateOfBirth, $manager_Gender, $manager_AnnualSalary, $manager_BackgroundCheck);
            // Execute the prepared statement with the bound parameters. This sends the SQL statement to the database server for execution.
            $stmt->execute();

            // The bind_param() method is used to bind variables to the parameter markers in the SQL statement.
            // The first argument "ssssssss" specifies the types of the variables being bound, where "s" stands for string.
            // This means that the variables $manager_Name, $manager_Address, $manager_Email, $manager_PhoneNumber, $manager_DateOfBirth, $manager_Gender, $manager_AnnualSalary, and $manager_BackgroundCheck
            // are all expected to be strings. The following variables are the actual values to be bound to the placeholders in the SQL statement.
            // By binding parameters, we ensure that the data is sent separately from the SQL statement, which helps protect against SQL injection.
            // SQL injection is a technique where an attacker can execute arbitrary SQL code on the database by manipulating input data.
            // By using prepared statements and parameter binding, the SQL logic is separated from the data input, thus preventing malicious data from altering the intended SQL command.


            if ($stmt->error) {
                // Check for query errors
                $errorMessage = "Query Error: " . $stmt->error;
            } else {
                // Reset form fields on successful submission
                $manager_Name = "";
                $manager_Address = "";
                $manager_Email = "";
                $manager_PhoneNumber = "";
                $manager_DateOfBirth = "";
                $manager_Gender = "";
                $manager_AnnualSalary = "";
                $manager_BackgroundCheck = "";

                // Set success message
                $successMessage = "Manager has been added";
            }
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Manager</title>
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
    <!-- Add New manager section -->
    <div class="container">
        <!-- Section header -->
        <div class="section-header text-center mb-4">
            <h2 class="title">Add New Manager</h2>
        </div>

        <!-- Form section -->
        <form method="POST" action="AddNewManager.php"> <!-- Form starts, uses POST method, submits to AddNewManager.php -->

            <!-- Manager Name input -->
            <div class="mb-3 row"> <!-- Bootstrap class for margin bottom and row layout -->
                <label for="manager_Name" class="col-sm-3 col-form-label">Manager Name:</label> <!-- Label for the manager name input, occupies 3 columns -->
                <div class="col-sm-6"> <!-- Div to wrap input, occupies 6 columns -->
                    <input type="text" class="form-control" id="manager_Name" name="manager_Name" value="<?php echo htmlspecialchars($manager_Name); ?>" required> <!-- Text input for manager name, prefilled with sanitized PHP variable value, required field -->
                </div>
            </div>

            <!-- Manager Address input -->
            <div class="mb-3 row"> <!-- Bootstrap class for margin bottom and row layout -->
                <label for="manager_Address" class="col-sm-3 col-form-label">Manager Address:</label> <!-- Label for the manager address input, occupies 3 columns -->
                <div class="col-sm-6"> <!-- Div to wrap input, occupies 6 columns -->
                    <input type="text" class="form-control" id="manager_Address" name="manager_Address" value="<?php echo htmlspecialchars($manager_Address); ?>" required> <!-- Text input for manager address, prefilled with sanitized PHP variable value, required field -->
                </div>
            </div>

            <!-- Manager Email input -->
            <div class="mb-3 row"> <!-- Bootstrap class for margin bottom and row layout -->
                <label for="manager_Email" class="col-sm-3 col-form-label">Manager Email:</label> <!-- Label for the manager email input, occupies 3 columns -->
                <div class="col-sm-6"> <!-- Div to wrap input, occupies 6 columns -->
                    <input type="email" class="form-control" id="manager_Email" name="manager_Email" value="<?php echo htmlspecialchars($manager_Email); ?>" required> <!-- Email input for manager email, prefilled with sanitized PHP variable value, required field -->
                </div>
            </div>

            <!-- Manager Phone Number input -->
            <div class="mb-3 row"> <!-- Bootstrap class for margin bottom and row layout -->
                <label for="manager_PhoneNumber" class="col-sm-3 col-form-label">Manager Phone Number:</label> <!-- Label for the manager phone number input, occupies 3 columns -->
                <div class="col-sm-6"> <!-- Div to wrap input, occupies 6 columns -->
                    <input type="tel" class="form-control" id="manager_PhoneNumber" name="manager_PhoneNumber" value="<?php echo htmlspecialchars($manager_PhoneNumber); ?>" required> <!-- Tel input for manager phone number, prefilled with sanitized PHP variable value, required field -->
                </div>
            </div>

            <!-- Manager Date of Birth input -->
            <div class="mb-3 row"> <!-- Bootstrap class for margin bottom and row layout -->
                <label for="manager_DateOfBirth" class="col-sm-3 col-form-label">Manager Date of Birth:</label> <!-- Label for the manager date of birth input, occupies 3 columns -->
                <div class="col-sm-6"> <!-- Div to wrap input, occupies 6 columns -->
                    <input type="date" class="form-control" id="manager_DateOfBirth" name="manager_DateOfBirth" value="<?php echo htmlspecialchars($manager_DateOfBirth); ?>" required> <!-- Date input for manager date of birth, prefilled with sanitized PHP variable value, required field -->
                </div>
            </div>

            <!-- Manager Gender selection -->
            <div class="mb-3 row"> <!-- Bootstrap class for margin bottom and row layout -->
                <label for="manager_Gender" class="col-sm-3 col-form-label">Manager Gender:</label> <!-- Label for the manager gender selection, occupies 3 columns -->
                <div class="col-sm-6"> <!-- Div to wrap select, occupies 6 columns -->
                    <select id="manager_Gender" class="form-select" name="manager_Gender" required> <!-- Select input for manager gender, required field -->
                        <option value="Male" <?php echo ($manager_Gender === 'Male') ? 'selected' : ''; ?>>Male</option> <!-- Option for Male, selected if PHP variable equals 'Male' -->
                        <option value="Female" <?php echo ($manager_Gender === 'Female') ? 'selected' : ''; ?>>Female</option> <!-- Option for Female, selected if PHP variable equals 'Female' -->
                        <option value="Other" <?php echo ($manager_Gender === 'Other') ? 'selected' : ''; ?>>Other</option> <!-- Option for Other, selected if PHP variable equals 'Other' -->
                    </select>
                </div>
            </div>

            <!-- Manager Annual Salary input -->
            <div class="mb-3 row"> <!-- Bootstrap class for margin bottom and row layout -->
                <label for="manager_AnnualSalary" class="col-sm-3 col-form-label">Manager Annual Salary:</label> <!-- Label for the manager annual salary input, occupies 3 columns -->
                <div class="col-sm-6"> <!-- Div to wrap input, occupies 6 columns -->
                    <input type="text" class="form-control" id="manager_AnnualSalary" name="manager_AnnualSalary" value="<?php echo htmlspecialchars($manager_AnnualSalary); ?>" required> <!-- Text input for manager annual salary, prefilled with sanitized PHP variable value, required field -->
                </div>
            </div>

            <!-- Manager Background Check selection -->
            <div class="mb-3 row"> <!-- Bootstrap class for margin bottom and row layout -->
                <label for="manager_BackgroundCheck" class="col-sm-3 col-form-label">Manager Background Check:</label> <!-- Label for the manager background check selection, occupies 3 columns -->
                <div class="col-sm-6"> <!-- Div to wrap select, occupies 6 columns -->
                    <select id="manager_BackgroundCheck" class="form-select" name="manager_BackgroundCheck" required> <!-- Select input for manager background check status, required field -->
                        <option value="Clear" <?php echo ($manager_BackgroundCheck === 'Clear') ? 'selected' : ''; ?>>Clear</option> <!-- Option for Clear, selected if PHP variable equals 'Clear' -->
                        <option value="Pending" <?php echo ($manager_BackgroundCheck === 'Pending') ? 'selected' : ''; ?>>Pending</option> <!-- Option for Pending, selected if PHP variable equals 'Pending' -->
                        <option value="Not Clear" <?php echo ($manager_BackgroundCheck === 'Not Clear') ? 'selected' : ''; ?>>Not Clear</option> <!-- Option for Not Clear, selected if PHP variable equals 'Not Clear' -->
                    </select>
                </div>
            </div>

            <!-- Form submission buttons -->
            <div class="row mb-3"> <!-- Bootstrap class for margin bottom and row layout -->
                <div class="offset-sm-3 col-sm-3 d-grid"> <!-- Div to wrap submit button, occupies 3 columns with offset of 3, uses Bootstrap grid classes for full width -->
                    <button type="submit" class="btn btn-primary">Submit</button> <!-- Submit button, styled with Bootstrap primary button class -->
                </div>
                <div class="col-sm-3 d-grid"> <!-- Div to wrap cancel link, occupies 3 columns, uses Bootstrap grid classes for full width -->
                    <a href="manager.php" class="btn btn-outline-primary">Cancel</a> <!-- Link to cancel, styled with Bootstrap outline primary button class -->
                </div>
            </div>

        </form>
        <!-- End of Form section -->


        <!-- Error or success message handling -->
        <!-- Check if there is an error message to display -->
        <?php if (!empty($errorMessage)) : ?>
            <!-- Alert box for error message, styled with Bootstrap classes for danger alert -->
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <!-- Display the error message -->
                <strong>Error:</strong> <?php echo $errorMessage; ?>
                <!-- Button to close the alert -->
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Check if there is a success message to display -->
        <?php if (!empty($successMessage)) : ?>
            <!-- Alert box for success message, styled with Bootstrap classes for success alert -->
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <!-- Display the success message -->
                <strong>Success:</strong> <?php echo $successMessage; ?>
                <!-- Button to close the alert -->
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Include jQuery from CDN -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Include Bootstrap's JavaScript bundle from CDN for handling Bootstrap components -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
