<?php
include("connection.php");
include("functions.php");

if (session_status() == PHP_SESSION_NONE) {
    // Only start the session if it's not already started
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $User_Name = $_POST['User_Name'];
    $User_Password = $_POST['User_Password'];
    $PermissionLevel = $_POST['PermissionLevel'];

    if (!empty($User_Name) && !empty($User_Password) && !empty($PermissionLevel)) {
        // Check if there is already an administration account
        $query = "SELECT COUNT(*) as AdminCount FROM usersessions WHERE PermissionLevel = 'admin'";
        $result = mysqli_query($connection, $query);

        if (!$result) {
            echo "Error checking administration account: " . mysqli_error($connection);
            die;
        }

        $row = mysqli_fetch_assoc($result);
        $AdminCount = $row['AdminCount'];

        if ($AdminCount == 0) {
            // No administration account exists, proceed with insertion
            $UserID = random_num(8);
            $hashedPassword = password_hash($User_Password, PASSWORD_DEFAULT);

            $query = "INSERT INTO usersessions (UserID, User_Name, User_Password, PermissionLevel) VALUES ('$UserID', '$User_Name', '$hashedPassword', '$PermissionLevel')";
            mysqli_query($connection, $query);

            header("Location: UserSessions.php");
            die;
        } else {
            echo "An administration account already exists.";
        }
    } else {
        echo "Please enter valid information!";
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
</head>
<body>
    <div class="container">
        <!-- Update the form to include the role selection -->
        <form method="post">
            <div style="font-size: 20px; margin: 10px; color: white;">Add New User</div>

            <div class="row-mb-3">
                <!-- Label for the Date Of Birth input field -->
                <label class="col-sm-3" for="User_Name">Username:</label>
                <!-- Date Of Birth input -->
                <div class="col-sm-6">
                    <input type="text" name="User_Name" id="User_Name">
                </div>
            </div>

            <div class="row-mb-3">
                <!-- Label for the Date Of Birth input field -->
                <label class="col-sm-3" for="User_Password">Password:</label>
                <!-- Date Of Birth input -->
                <div class="col-sm-6">
                    <input type="password" name="User_Password" id="User_Password">
                </div>
            </div>

            <label for="PermissionLevel">Select Role:</label>
            <select id="PermissionLevel" name="PermissionLevel">
                <option value="parent">Parent</option>
                <option value="pupil">Pupil</option>
                <option value="teacher">Teacher</option>
                <option value="administration">Admin</option>
            </select>

            <br><br>

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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

