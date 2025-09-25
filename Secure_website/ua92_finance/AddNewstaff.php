<?php
    // Start a new session
    session_start();

    // Include the connection script to connect to the database
    include("connection.php");

    // Include the functions script to use its functions
    include("functions.php");

    // Check user permissions and display messages accordingly
    check_permission_and_message($connection, __FILE__);

    // Initialize variables for staff details
    $staff_Name = ""; // Variable to store staff's name
    $staff_Address = ""; // Variable to store staff's address
    $staff_Email = ""; // Variable to store staff's email
    $staff_PhoneNumber = ""; // Variable to store staff's phone number
    $staff_DateOfBirth = ""; // Variable to store staff's date of birth
    $staff_Gender = ""; // Variable to store staff's gender
    $staff_AnnualSalary = ""; // Variable to store staff's annual salary
    $staff_BackgroundCheck = ""; // Variable to store staff's background check status
    $errorMessage = ""; // Variable to store error messages
    $successMessage = ""; // Variable to store success messages

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the form was submitted via POST method
        // Retrieve and sanitize form data
        $staff_Name = trim($_POST["staff_Name"]); // Get and trim the staff's name from POST data
        $staff_Address = trim($_POST["staff_Address"]); // Get and trim the staff's address from POST data
        $staff_Email = trim($_POST["staff_Email"]); // Get and trim the staff's email from POST data
        $staff_PhoneNumber = trim($_POST["staff_PhoneNumber"]); // Get and trim the staff's phone number from POST data
        $staff_DateOfBirth = $_POST["staff_DateOfBirth"]; // Get the staff's date of birth from POST data (no trim needed for date)
        $staff_Gender = $_POST["staff_Gender"]; // Get the staff's gender from POST data (no trim needed)
        $staff_AnnualSalary = trim($_POST["staff_AnnualSalary"]); // Get and trim the staff's annual salary from POST data
        $staff_BackgroundCheck = $_POST["staff_BackgroundCheck"]; // Get the staff's background check status from POST data (no trim needed)

        // trim() is a function that removes whitespace from the beginning and end of a string.
        // It is used here to clean the input data, ensuring that no leading or trailing spaces are included.
        // This helps prevent potential issues with data validation and storage.

        // Input validation
        if (empty($staff_Name) || empty($staff_Address) || empty($staff_Email) || empty($staff_PhoneNumber) || empty($staff_DateOfBirth) || empty($staff_Gender) || empty($staff_AnnualSalary) || empty($staff_BackgroundCheck)) {
            // Check if any required fields are empty
            $errorMessage = "All fields are required";
        } elseif (strlen($staff_Name) > 255) {
            // Check if the staff's name exceeds 255 characters
            $errorMessage = "Name must be at most 255 characters long.";
        } elseif (strlen($staff_Address) > 255) {
            // Check if the staff's address exceeds 255 characters
            $errorMessage = "Address must be at most 255 characters long.";
        } elseif (!filter_var($staff_Email, FILTER_VALIDATE_EMAIL) || !preg_match("/@gmail\.com$/", $staff_Email)) {
            // Check if the staff's email is valid and ends with @gmail.com
            $errorMessage = "Invalid email format. Must end with @gmail.com";
        } elseif (!preg_match("/^\d{10}$/", $staff_PhoneNumber)) {
            // Check if the staff's phone number is exactly 10 digits
            $errorMessage = "Invalid phone number. Must have 10 digits.";
        } elseif (!ctype_digit($staff_AnnualSalary)) {
            // Check if the staff's annual salary contains only digits
            $errorMessage = "Annual salary must contain only digits.";
        } else {
            // Proceed with insertion using prepared statements
            $AddNewstaffSQL = "INSERT INTO staff (staff_Name, staff_Address, staff_Email, staff_PhoneNumber, staff_DateOfBirth, staff_Gender, staff_AnnualSalary, staff_BackgroundCheck) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            // Prepare the SQL statement using the connection object and the SQL query stored in $AddNewstaffSQL
            $stmt = $connection->prepare($AddNewstaffSQL);
            $stmt->bind_param("ssssssss", $staff_Name, $staff_Address, $staff_Email, $staff_PhoneNumber, $staff_DateOfBirth, $staff_Gender, $staff_AnnualSalary, $staff_BackgroundCheck);
            // Execute the prepared statement with the bound parameters. This sends the SQL statement to the database server for execution.
            $stmt->execute();

            // The bind_param() method is used to bind variables to the parameter markers in the SQL statement.
            // The first argument "ssssssss" specifies the types of the variables being bound, where "s" stands for string.
            // This means that the variables $staff_Name, $staff_Address, $staff_Email, $staff_PhoneNumber, $staff_DateOfBirth, $staff_Gender, $staff_AnnualSalary, and $staff_BackgroundCheck
            // are all expected to be strings. The following variables are the actual values to be bound to the placeholders in the SQL statement.
            // By binding parameters, we ensure that the data is sent separately from the SQL statement, which helps protect against SQL injection.
            // SQL injection is a technique where an attacker can execute arbitrary SQL code on the database by manipulating input data.
            // By using prepared statements and parameter binding, the SQL logic is separated from the data input, thus preventing malicious data from altering the intended SQL command.


            if ($stmt->error) {
                // Check for query errors
                $errorMessage = "Query Error: " . $stmt->error;
            } else {
                // Reset form fields on successful submission
                $staff_Name = "";
                $staff_Address = "";
                $staff_Email = "";
                $staff_PhoneNumber = "";
                $staff_DateOfBirth = "";
                $staff_Gender = "";
                $staff_AnnualSalary = "";
                $staff_BackgroundCheck = "";

                // Set success message
                $successMessage = "staff has been added";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Staff</title>
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
    <!-- Add New staff section -->
    <div class="container">
        <!-- Section header -->
        <div class="section-header text-center mb-4">
            <h2 class="title">Add New Staff</h2>
        </div>

        <!-- Form section -->
        <form method="POST" action="AddNewstaff.php"> <!-- Form starts, uses POST method, submits to AddNewstaff.php -->

        <!-- staff Name input -->
        <div class="mb-3 row"> <!-- Bootstrap class for margin bottom and row layout -->
            <label for="staff_Name" class="col-sm-3 col-form-label">Staff Name:</label> <!-- Label for the staff name input, occupies 3 columns -->
            <div class="col-sm-6"> <!-- Div to wrap input, occupies 6 columns -->
                <input type="text" class="form-control" id="staff_Name" name="staff_Name" value="<?php echo htmlspecialchars($staff_Name); ?>" required> <!-- Text input for staff name, prefilled with sanitized PHP variable value, required field -->
            </div>
        </div>

        <!-- staff Address input -->
        <div class="mb-3 row"> <!-- Bootstrap class for margin bottom and row layout -->
            <label for="staff_Address" class="col-sm-3 col-form-label">Staff Address:</label> <!-- Label for the staff address input, occupies 3 columns -->
            <div class="col-sm-6"> <!-- Div to wrap input, occupies 6 columns -->
                <input type="text" class="form-control" id="staff_Address" name="staff_Address" value="<?php echo htmlspecialchars($staff_Address); ?>" required> <!-- Text input for staff address, prefilled with sanitized PHP variable value, required field -->
            </div>
        </div>

        <!-- staff Email input -->
        <div class="mb-3 row"> <!-- Bootstrap class for margin bottom and row layout -->
            <label for="staff_Email" class="col-sm-3 col-form-label">Staff Email:</label> <!-- Label for the staff email input, occupies 3 columns -->
            <div class="col-sm-6"> <!-- Div to wrap input, occupies 6 columns -->
                <input type="email" class="form-control" id="staff_Email" name="staff_Email" value="<?php echo htmlspecialchars($staff_Email); ?>" required> <!-- Email input for staff email, prefilled with sanitized PHP variable value, required field -->
            </div>
        </div>

        <!-- staff Phone Number input -->
        <div class="mb-3 row"> <!-- Bootstrap class for margin bottom and row layout -->
            <label for="staff_PhoneNumber" class="col-sm-3 col-form-label">Staff Phone Number:</label> <!-- Label for the staff phone number input, occupies 3 columns -->
            <div class="col-sm-6"> <!-- Div to wrap input, occupies 6 columns -->
                <input type="tel" class="form-control" id="staff_PhoneNumber" name="staff_PhoneNumber" value="<?php echo htmlspecialchars($staff_PhoneNumber); ?>" required> <!-- Tel input for staff phone number, prefilled with sanitized PHP variable value, required field -->
            </div>
        </div>

        <!-- staff Date of Birth input -->
        <div class="mb-3 row"> <!-- Bootstrap class for margin bottom and row layout -->
            <label for="staff_DateOfBirth" class="col-sm-3 col-form-label">Staff Date of Birth:</label> <!-- Label for the staff date of birth input, occupies 3 columns -->
            <div class="col-sm-6"> <!-- Div to wrap input, occupies 6 columns -->
                <input type="date" class="form-control" id="staff_DateOfBirth" name="staff_DateOfBirth" value="<?php echo htmlspecialchars($staff_DateOfBirth); ?>" required> <!-- Date input for staff date of birth, prefilled with sanitized PHP variable value, required field -->
            </div>
        </div>

        <!-- staff Gender selection -->
        <div class="mb-3 row"> <!-- Bootstrap class for margin bottom and row layout -->
            <label for="staff_Gender" class="col-sm-3 col-form-label">Staff Gender:</label> <!-- Label for the staff gender selection, occupies 3 columns -->
            <div class="col-sm-6"> <!-- Div to wrap select, occupies 6 columns -->
                <select id="staff_Gender" class="form-select" name="staff_Gender" required> <!-- Select input for staff gender, required field -->
                    <option value="Male" <?php echo ($staff_Gender === 'Male') ? 'selected' : ''; ?>>Male</option> <!-- Option for Male, selected if PHP variable equals 'Male' -->
                    <option value="Female" <?php echo ($staff_Gender === 'Female') ? 'selected' : ''; ?>>Female</option> <!-- Option for Female, selected if PHP variable equals 'Female' -->
                    <option value="Other" <?php echo ($staff_Gender === 'Other') ? 'selected' : ''; ?>>Other</option> <!-- Option for Other, selected if PHP variable equals 'Other' -->
                </select>
            </div>
        </div>

        <!-- staff Annual Salary input -->
        <div class="mb-3 row"> <!-- Bootstrap class for margin bottom and row layout -->
            <label for="staff_AnnualSalary" class="col-sm-3 col-form-label">Staff Annual Salary:</label> <!-- Label for the staff annual salary input, occupies 3 columns -->
            <div class="col-sm-6"> <!-- Div to wrap input, occupies 6 columns -->
                <input type="text" class="form-control" id="staff_AnnualSalary" name="staff_AnnualSalary" value="<?php echo htmlspecialchars($staff_AnnualSalary); ?>" required> <!-- Text input for staff annual salary, prefilled with sanitized PHP variable value, required field -->
            </div>
        </div>

        <!-- staff Background Check selection -->
        <div class="mb-3 row"> <!-- Bootstrap class for margin bottom and row layout -->
            <label for="staff_BackgroundCheck" class="col-sm-3 col-form-label">Staff Background Check:</label> <!-- Label for the staff background check selection, occupies 3 columns -->
            <div class="col-sm-6"> <!-- Div to wrap select, occupies 6 columns -->
                <select id="staff_BackgroundCheck" class="form-select" name="staff_BackgroundCheck" required> <!-- Select input for staff background check status, required field -->
                    <option value="Clear" <?php echo ($staff_BackgroundCheck === 'Clear') ? 'selected' : ''; ?>>Clear</option> <!-- Option for Clear, selected if PHP variable equals 'Clear' -->
                    <option value="Pending" <?php echo ($staff_BackgroundCheck === 'Pending') ? 'selected' : ''; ?>>Pending</option> <!-- Option for Pending, selected if PHP variable equals 'Pending' -->
                    <option value="Not Clear" <?php echo ($staff_BackgroundCheck === 'Not Clear') ? 'selected' : ''; ?>>Not Clear</option> <!-- Option for Not Clear, selected if PHP variable equals 'Not Clear' -->
                </select>
            </div>
        </div>

        <!-- Form submission buttons -->
        <div class="row mb-3"> <!-- Bootstrap class for margin bottom and row layout -->
            <div class="offset-sm-3 col-sm-3 d-grid"> <!-- Div to wrap submit button, occupies 3 columns with offset of 3, uses Bootstrap grid classes for full width -->
                <button type="submit" class="btn btn-primary">Submit</button> <!-- Submit button, styled with Bootstrap primary button class -->
            </div>
            <div class="col-sm-3 d-grid"> <!-- Div to wrap cancel link, occupies 3 columns, uses Bootstrap grid classes for full width -->
                <a href="staff.php" class="btn btn-outline-primary">Cancel</a> <!-- Link to cancel, styled with Bootstrap outline primary button class -->
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
