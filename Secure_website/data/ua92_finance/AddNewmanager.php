<?php
    include("connection.php");
    include("functions.php");
    
    session_start();
    
    // Moved the check_permission_and_message function call after including the required files
    check_permission_and_message($connection, __FILE__);
    
    // Retrieve user data
    $user_data = check_login($connection, get_allowed_permissions(__FILE__));

    $manager_Name = "";
    $manager_Address = "";
    $manager_Email = "";
    $manager_PhoneNumber = "";
    $manager_DateOfBirth = "";
    $manager_Gender = "";
    $manager_AnnualSalary = "";
    $manager_BackgroundCheck = "";
    $errorMessage = "";
    $successMessage = "";


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $manager_Name = $_POST["manager_Name"];
        $manager_Address = $_POST["manager_Address"];
        $manager_Email = $_POST["manager_Email"];
        $manager_PhoneNumber = $_POST["manager_PhoneNumber"];
        $manager_DateOfBirth = $_POST["manager_DateOfBirth"];
        $manager_Gender = $_POST["manager_Gender"];
        $manager_AnnualSalary = $_POST["manager_AnnualSalary"];
        $manager_BackgroundCheck = $_POST["manager_BackgroundCheck"];


        // Validation
        if (empty($manager_Name) || empty($manager_Address) || empty($manager_Email) || empty($manager_PhoneNumber) || empty($manager_DateOfBirth) || empty($manager_Gender) || empty($manager_AnnualSalary) || empty($manager_BackgroundCheck)) {
            $errorMessage = "All fields are required";
        } else {
            // Validate name length
            if (strlen($manager_Name) > 255) {
                $errorMessage = "Name must be at most 255 characters long.";
            }

            // Validate address length
            if (strlen($manager_Address) > 255) {
                $errorMessage = "Address must be at most 255 characters long.";
            }

            // Validate email format and ensure it ends with @gmail.com
            if (!filter_var($manager_Email, FILTER_VALIDATE_EMAIL) || !preg_match("/@gmail\.com$/", $manager_Email)) {
                $errorMessage = "Invalid email format. Must end with @gmail.com";
            }

            // Validate phone number format
            if (!preg_match("/^\d{10}$/", $manager_PhoneNumber)) {
                $errorMessage = "Invalid phone number. Must have 10 digits.";
            }

            // Validate annual salary to have only digits
            if (!ctype_digit($manager_AnnualSalary)) {
                $errorMessage = "Annual salary must contain only digits.";
            
            } else{
                    $row = $result->fetch_assoc();
                    $manager_Count = $row["manager_Count"];

                    // Proceed with the insertion
                    $AddNewmanagerSQL = "INSERT INTO manager (manager_Name, manager_Address, manager_Email, manager_PhoneNumber, manager_DateOfBirth, manager_Gender, manager_AnnualSalary, manager_BackgroundCheck)" . 
                        "VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $connection->prepare($AddNewmanagerSQL);
                    $stmt->bind_param("ssssssss", $manager_Name, $manager_Address, $manager_Email, $manager_PhoneNumber, $manager_DateOfBirth, $manager_Gender, $manager_AnnualSalary, $manager_BackgroundCheck);
                    $stmt->execute();

                    if ($stmt->error) {
                        $errorMessage = "Invalid Query: " . $stmt->error;
                    } else {
                        $manager_Name = "";
                        $manager_Address = "";
                        $manager_Email = "";
                        $manager_PhoneNumber = "";
                        $manager_DateOfBirth = "";
                        $manager_Gender = "";
                        $manager_AnnualSalary = "";
                        $manager_BackgroundCheck = "";

                        $successMessage = "manager has been added";
                    }
                }
            }
        }

    
    

    $connection->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New manager</title>
    <!-- Link to Bootstrap 5.3.1 CSS library -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Class section -->
    <div id="Class" class="section">
        <!-- Container-->
        <div class="container">
            <!-- Section header-->
            <div class="section-header text-center">
                <!-- Title-->
                <h2 class="title white-text">Add New manager</h2>
            </div>
                <?php
                    if ( !empty($errorMessage) ) {
                        echo "
                        <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                            <strong>$errorMessage</strong>
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>
                        ";
                    }
                ?>
                    <!-- Class form within a row -->
                    <form class="Class-form row " method="POST" action="AddNewmanager.php" id="ClassForm">
                        <input type="hidden" name="managerID" value="<?php echo $managerID; ?>">

                            <div class="row-mb-3">
                                <!-- Label for the First name input field -->
                                <label class="col-sm-3" for="manager_Name">manager Name:</label>
                                <!-- Text input for the name with placeholder and name attribute -->
                                <div class="col-sm-6">
                                    <input class="input" type="text" name="manager_Name" id="manager_Name" value="<?php echo $manager_Name; ?>">
                                </div>
                            </div>

                            <div class="row-mb-3">
                                <!-- Label for the StudentiD input field -->
                                <label class="col-sm-3" for="manager_Address">manager Address:</label>
                                <!-- Text input for the name with placeholder and name attribute -->
                                <div class="col-sm-6">
                                    <input class="input" type="text" name="manager_Address" id="manager_Address" value="<?php echo $manager_Address; ?>">
                                </div>
                            </div>

                            <div class="row-mb-3">
                                <!-- Label for the email input field -->
                                <label class="col-sm-3" for="manager_Email">manager Email:</label>
                                <!-- Email input for email address with placeholder and name attribute -->
                                <div class="col-sm-6">
                                    <input class="input" type="email" name="manager_Email" id="manager_Email" value="<?php echo $manager_Email; ?>">
                                </div>
                            </div>
                                
                            <div class="row-mb-3">
                                <!-- Label for the phone input field -->
                                <label class="col-sm-3" for="manager_PhoneNumber">manager Phone Number:</label>
                                <!-- Tel input for phone number with placeholder and name attribute -->
                                <div class="col-sm-6">
                                    <input class="input" type="tel" name="manager_PhoneNumber" id="manager_PhoneNumber" value="<?php echo $manager_PhoneNumber; ?>">
                                </div>
                            </div>

                            <div class="row-mb-3">
                                <!-- Label for the Date Of Birth input field -->
                                <label class="col-sm-3" for="manager_DateOfBirth">manager Date Of Birth:</label>
                                <!-- Date Of Birth input -->
                                <div class="col-sm-6">
                                    <input class="input" type="date" name="manager_DateOfBirth" id="manager_DateOfBirth" value="<?php echo $manager_DateOfBirth; ?>">
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

                            <div class="row-mb-3">
                                <!-- Label for the Date Of Birth input field -->
                                <label class="col-sm-3" for="manager_AnnualSalary">manager Annual Salary:</label>
                                <!-- Date Of Birth input -->
                                <div class="col-sm-6">
                                    <input class="input" type="text" name="manager_AnnualSalary" id="manager_AnnualSalary" value="<?php echo $manager_AnnualSalary; ?>">
                                </div>
                            </div>

                            <div class="row-mb-3">
                                <!-- Label for the manager BackgroundCheck dropdown -->
                                <label class="col-sm-3" for="manager_BackgroundCheck">manager BackgroundCheck:</label>
                                <!-- Dropdown menu for manager BackgroundCheck with three options -->
                                <div class="col-sm-6">
                                    <select class="form-select" name="manager_BackgroundCheck" id="manager_BackgroundCheck">
                                        <option value="Clear" <?php echo ($manager_BackgroundCheck === 'Clear') ? 'selected' : ''; ?>>Clear</option>
                                        <option value="Pending" <?php echo ($manager_BackgroundCheck === 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Not Clear" <?php echo ($manager_BackgroundCheck === 'Not Clear') ? 'selected' : ''; ?>>Not Clear</option>
                                    </select>
                                </div>
                            </div>


                        <?php
                            if ( !empty($successMessage) ) {
                                echo "
                                <div class='row mb-3'>
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

                        <!-- Full-width column for the submission button -->
                        <div class="row mb-3">
                            <div class="offset-sm-3 col-sm-3 d-grid">
                                <!-- Button for submitting the Class form -->
                                <button class="main-button underline-on-hover" type="submit">Submit</button>
                            </div>
                            <div class="col-sm-3 d-grid">
                                <a class="btn btn-outline-primary" href="manager.php" role="button">Cancel</a>
                            </div>
                        </div>
                    </form>
        </div>
    </div>
    <!-- Class section -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>