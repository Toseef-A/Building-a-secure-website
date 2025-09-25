<?php
    include("connection.php");
    include("functions.php");
    
    session_start();
    
    // Moved the check_permission_and_message function call after including the required files
    check_permission_and_message($connection, __FILE__);
    
    // Retrieve user data
    $user_data = check_login($connection, get_allowed_permissions(__FILE__));

    $UserSessionsID = "";
    $UserID = "";
    $User_Name = "";
    $User_Password = "";
    $errorMessage = "";
    $successMessage = "";


    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (!isset($_GET["UserSessionsID"])) {
            header("location: /ua92_finance/UserSessions.php");
            exit;
        }

        $UserSessionsID = $_GET["UserSessionsID"];

        $sql = "SELECT * FROM usersessions WHERE UserSessionsID=?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $UserSessionsID);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            echo "Error executing query: " . $stmt->error;
            exit;
        }

        if ($result->num_rows == 0) {
            echo "No records found for UserSessionsID: " . $UserSessionsID;
            exit;
        }

        $row = $result->fetch_assoc();
        $UserSessionsID = $row["UserSessionsID"];
        $UserID = $row["UserID"];
        $User_Name = $row["User_Name"];
        $User_Password = $row["User_Password"];
        
    } else {
        $UserSessionsID = $_POST["UserSessionsID"];
        $UserID = $_POST["UserID"];
        $User_Name = $_POST["User_Name"];
        $User_Password = $_POST["User_Password"];

        $hashedPassword = password_hash($User_Password, PASSWORD_DEFAULT);

        if (empty($UserSessionsID) || empty($UserID) || empty($User_Name) || empty($User_Password) ) {
            $errorMessage = "All the fields are required";
        } else {
            // Check for numeric values
            if (!is_numeric($UserSessionsID)) {
                $errorMessage = "Invalid UserSessionsID";
            } else {
                $EditUserSQL = "UPDATE usersessions SET UserID=?, User_Name=?, User_Password=? WHERE UserSessionsID=?";
                $stmt = $connection->prepare($EditUserSQL);
                $stmt->bind_param("issi", $UserID, $User_Name, $hashedPassword, $UserSessionsID);
                $stmt->execute();

                if ($stmt->error) {
                    $errorMessage = "Error updating User: " . $stmt->error;
                } else {
                    $successMessage = "User updated correctly";
                }
            }
        }
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <!-- Link to Bootstrap 5.3.1 CSS library -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Class section -->
    <div id="Class" class="section">
        <!-- Container-->
        <div class="container">
            <!-- Row-->
            <div class="row">
                <!-- Column for Class form on medium and small screens -->
                <div class="col-md-12 col-md-offset-1 col-sm-10 col-sm-offset-1">

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
                    <form class="Class-form row " method="POST" action="EditUser.php" id="ClassForm">
                        <input type="hidden" name="UserSessionsID" value="<?php echo $UserSessionsID; ?>">

                            <div class="row-mb-3">
                                <!-- Label for the First name input field -->
                                <label class="col-sm-3" for="UserID">UserID:</label>
                                <!-- Text input for the name with placeholder and name attribute -->
                                <div class="col-sm-6">
                                    <input class="input" type="text" name="UserID" id="UserID" value="<?php echo $UserID; ?>">
                                </div>
                            </div>

                            <div class="row-mb-3">
                                <!-- Label for the StudentiD input field -->
                                <label class="col-sm-3" for="User_Name">User name:</label>
                                <!-- Text input for the name with placeholder and name attribute -->
                                <div class="col-sm-6">
                                    <input class="input" type="text" name="User_Name" id="User_Name" value="<?php echo $User_Name; ?>">
                                </div>
                            </div>

                            <div class="row-mb-3">
                                <!-- Label for the email input field -->
                                <label class="col-sm-3" for="User_Password">User Password:</label>
                                <!-- Email input for email address with placeholder and name attribute -->
                                <div class="col-sm-6">
                                    <input class="input" type="password" name="User_Password" id="User_Password" value="<?php echo $User_Password; ?>">
                                </div>
                            </div>

                        <?php
                            if ( !empty($successMessage) ) {
                                echo "
                                <div class='row mb-3'>
                                    <div class='offset-sm-3' col-sm-6>
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
                                <a class="btn btn-outline-primary" href="UserSessions.php" role="button">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Class section -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>