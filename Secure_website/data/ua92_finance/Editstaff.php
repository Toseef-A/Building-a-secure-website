<?php
    include("connection.php");
    include("functions.php");
    
    session_start();
    
    // Moved the check_permission_and_message function call after including the required files
    check_permission_and_message($connection, __FILE__);
    
    // Retrieve user data
    $user_data = check_login($connection, get_allowed_permissions(__FILE__));

    $staff_Name = "";
    $staff_Address = "";
    $staff_Email = "";
    $staff_PhoneNumber = "";
    $staff_DateOfBirth = "";
    $staff_Gender = "";
    $staff_AnnualSalary = "";
    $staff_BackgroundCheck = "";
    $errorMessage = "";
    $successMessage = "";

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (!isset($_GET["staffID"])) {
            header("location: /ua92_finance/staff.php");
            exit;
        }
    
        $staffID = $_GET["staffID"];
    
        $sql = "SELECT * FROM staff WHERE staffID=?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $staffID);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if (!$result) {
            echo "Error executing query: " . $stmt->error;
            exit;
        }
    
        if ($result->num_rows == 0) {
            echo "No records found for staffID: " . $staffID;
            exit;
        }
    
        $row = $result->fetch_assoc();
    
        if ($row !== null) {
            $staffID = $row["staffID"];
            $staff_Name = $row["staff_Name"];
            $staff_Address = $row["staff_Address"];
            $staff_Email = $row["staff_Email"];
            $staff_PhoneNumber = $row["staff_PhoneNumber"];
            $staff_DateOfBirth = $row["staff_DateOfBirth"];
            $staff_Gender = $row["staff_Gender"];
            $staff_AnnualSalary = $row["staff_AnnualSalary"];
            $staff_BackgroundCheck = $row["staff_BackgroundCheck"];
        }
    
    } elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
        $staffID = $_POST["staffID"];
        $staff_Name = $_POST["staff_Name"];
        $staff_Address = $_POST["staff_Address"];
        $staff_Email = $_POST["staff_Email"];
        $staff_PhoneNumber = $_POST["staff_PhoneNumber"];
        $staff_DateOfBirth = $_POST["staff_DateOfBirth"];
        $staff_Gender = $_POST["staff_Gender"];
        $staff_AnnualSalary = $_POST["staff_AnnualSalary"];
        $staff_BackgroundCheck = $_POST["staff_BackgroundCheck"];

        if (empty($staffID) || empty($staff_Name) || empty($staff_Address) || empty($staff_PhoneNumber) || empty($staff_DateOfBirth) || empty($staff_Gender) || empty($staff_Email) || empty($staff_AnnualSalary) || empty($staff_BackgroundCheck)) {
            $errorMessage = "All fields are required";
        } else {
            // Check for numeric values
            if (!is_numeric($staffID)) {
                $errorMessage = "Invalid staffID";
            } else {
                // Validate name length
                if (strlen($staff_Name) > 255) {
                    $errorMessage = "Name must be at most 255 characters long.";
                }

                // Validate address length
                if (strlen($staff_Address) > 255) {
                    $errorMessage = "Address must be at most 255 characters long.";
                }

                // Validate email format and ensure it ends with @gmail.com
                if (!filter_var($staff_Email, FILTER_VALIDATE_EMAIL) || !preg_match("/@gmail\.com$/", $staff_Email)) {
                    $errorMessage = "Invalid email format. Must end with @gmail.com";
                }

                // Validate phone number format
                if (!preg_match("/^\d{10}$/", $staff_PhoneNumber)) {
                    $errorMessage = "Invalid phone number. Must have 10 digits.";
                }

                // Validate annual salary to have only digits
                if (!ctype_digit($staff_AnnualSalary)) {
                    $errorMessage = "Annual salary must contain only digits.";
                }

                if (empty($errorMessage)) {
                    // Check if a staff is already assigned to the selected 
                    $stmt = $connection->prepare($query);
                    $stmt->bind_param("i", $staffID);
                    $stmt->execute();
                    $result = $stmt->get_result();
    
                    if (!$result) {
                        $errorMessage = "Error fetching staff count: " . $stmt->error;
                    } else {
                        $row = $result->fetch_assoc();
                        $staff_Count = $row["staff_Count"];
    
                        if ($staff_Count > 0) {
                            // Check if the staff to be updated belongs to the selected staffID
                            $CheckstaffSQL = "SELECT staffID FROM staff WHERE staffID = ?";
                            $stmt = $connection->prepare($CheckstaffSQL);
                            $stmt->bind_param("i", $staffID);
                            $stmt->execute();
                            $result = $stmt->get_result();
    
                            if (!$result) {
                                $errorMessage = "Error checking staff: " . $stmt->error;
                            } else {
                                if ($result->num_rows === 0) {
                                    $errorMessage = "The staff does not belong to the selected .";
                                } else {
                                    // Proceed with the update
                                    $UpdatestaffSQL = "UPDATE staff SET staff_Name=?, staff_Address=?, staff_Email=?, staff_PhoneNumber=?, staff_DateOfBirth=?, staff_Gender=?, staff_AnnualSalary=?, staff_BackgroundCheck=? WHERE staffID=?";
                                    $stmt = $connection->prepare($UpdatestaffSQL);
                                    $stmt->bind_param("ssssssssi", $staff_Name, $staff_Address, $staff_Email, $staff_PhoneNumber, $staff_DateOfBirth, $staff_Gender, $staff_AnnualSalary, $staff_BackgroundCheck, $staffID);
                                    $stmt->execute();

                                    if ($stmt->error) {
                                        $errorMessage = "Invalid Query: " . $stmt->error;
                                    } else {
                                        $successMessage = "staff has been updated";
                                    }
                                }
                            }
                        }
                    }
                }
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
    <title>Add New staff</title>
    <!-- Link to Bootstrap 5.3.1 CSS library -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Add New staff section -->
    <div id="Class" class="section">
        <!-- Container-->
        <div class="container">
            <!-- Section header-->
            <div class="section-header text-center">
                <!-- Title-->
                <h2 class="title white-text">Add New staff</h2>
            </div>
            <?php
                // Display error message
                if (!empty($errorMessage)) {
                    echo "
                    <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                        <strong>$errorMessage</strong>
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>
                    ";
                }
            ?>
            <!-- Add New staff -->
            <form class="Class-form row " method="POST" action="Editstaff.php" id="ClassForm">
                <input type="hidden" name="staffID" value="<?php echo $staffID; ?>">

                <div class="mb-6">
                    <!-- Label for the staff Name input field -->
                    <label class="col-sm-3" for="staff_Name">staff Name:</label>
                    <!-- Text input for the name with placeholder and name attribute -->
                    <div class="col-sm-6">
                        <input class="input" type="text" name="staff_Name" id="staff_Name" value="<?php echo $staff_Name; ?>">
                    </div>
                </div>

                <div class="mb-6">
                    <!-- Label for the staff Address input field -->
                    <label class="col-sm-3" for="staff_Address">staff Address:</label>
                    <!-- Text input for the address with placeholder and name attribute -->
                    <div class="col-sm-6">
                        <input class="input" type="text" name="staff_Address" id="staff_Address" value="<?php echo $staff_Address; ?>">
                    </div>
                </div>

                <div class="mb-6">
                    <!-- Label for the staff Email input field -->
                    <label class="col-sm-3" for="staff_Email">staff Email:</label>
                    <!-- Email input for email address with placeholder and name attribute -->
                    <div class="col-sm-6">
                        <input class="input" type="email" name="staff_Email" id="staff_Email" value="<?php echo $staff_Email; ?>">
                    </div>
                </div>

                <div class="mb-6">
                    <!-- Label for the staff Phone Number input field -->
                    <label class="col-sm-3" for="staff_PhoneNumber">staff Phone Number:</label>
                    <!-- Tel input for phone number with placeholder and name attribute -->
                    <div class="col-sm-6">
                        <input class="input" type="tel" name="staff_PhoneNumber" id="staff_PhoneNumber" value="<?php echo $staff_PhoneNumber; ?>">
                    </div>
                </div>

                <div class="mb-6">
                    <!-- Label for the staff Date Of Birth input field -->
                    <label class="col-sm-3" for="staff_DateOfBirth">staff Date Of Birth:</label>
                    <!-- Date Of Birth input -->
                    <div class="col-sm-6">
                        <input class="input" type="date" name="staff_DateOfBirth" id="staff_DateOfBirth" value="<?php echo $staff_DateOfBirth; ?>">
                    </div>
                </div>

                <div class="mb-6">
                    <!-- Label for the staff Gender dropdown -->
                    <label class="col-sm-3" for="staff_Gender">staff Gender:</label>
                    <!-- Dropdown menu for staff Gender with three options -->
                    <div class="col-sm-6">
                        <select class="form-select" name="staff_Gender" id="staff_Gender">
                            <option value="Male" <?php echo ($staff_Gender === 'Male') ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo ($staff_Gender === 'Female') ? 'selected' : ''; ?>>Female</option>
                            <option value="Dont want to specify" <?php echo ($staff_Gender === 'Other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <!-- Label for the staff Annual Salary input field -->
                    <label class="col-sm-3" for="staff_AnnualSalary">staff Annual Salary:</label>
                    <!-- int input for Annual Salary with placeholder and name attribute -->
                    <div class="col-sm-6">
                        <input class="input" type="int" name="staff_AnnualSalary" id="staff_AnnualSalary" value="<?php echo $staff_AnnualSalary; ?>">
                    </div>
                </div>

                <div class="mb-6">
                    <!-- Label for the staff Background Check dropdown -->
                    <label class="col-sm-3" for="staff_BackgroundCheck">staff Background Check:</label>
                    <!-- Dropdown menu for staff background check with three options -->
                    <div class="col-sm-6">
                        <select class="form-select" name="staff_BackgroundCheck" id="staff_BackgroundCheck">
                            <option value="Clear" <?php echo ($staff_BackgroundCheck === 'Clear') ? 'selected' : ''; ?>>Clear</option>
                            <option value="Pending" <?php echo ($staff_BackgroundCheck === 'Pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="Not Clear" <?php echo ($staff_BackgroundCheck === 'Not Clear') ? 'selected' : ''; ?>>Not Clear</option>
                        </select>
                    </div>
                </div>


                <?php
                    // Display success message
                    if (!empty($successMessage)) {
                        echo "
                        <div class='mb-3'>
                            <div class='offset-sm-3 col-sm-6'>
                                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                                    <strong>$successMessage</strong>
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>
                            </div>
                        </div>
                        ";
                    }
                ?>

                <!-- ubmission and cancellation button -->
                <div class="row mb-3">
                    <!-- Submission button -->
                    <div class="offset-sm-3 col-sm-3 d-grid">
                        <button class="main-button underline-on-hover" type="submit">Submit</button>
                    </div>
                    <!-- Cancel button -->
                    <div class="col-sm-3 d-grid">
                        <a class="btn btn-outline-primary" href="staff.php" role="button">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /Add New staff section -->

    <!-- JavaScript libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>