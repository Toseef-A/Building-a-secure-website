<?php
include("connection.php");
include("functions.php");

session_start();

// Moved the check_permission_and_message function call after including the required files
check_permission_and_message($connection, __FILE__);

// Retrieve user data
$user_data = check_login($connection, get_allowed_permissions(__FILE__));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Sessions</title>
    <!-- Link to Bootstrap 5.3.1 CSS library -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!-- Header section -->
<header>
        <!-- Navigation bar with a dark theme -->
        <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
            <div class="container-fluid">
                <!-- Navbar toggler for small screens -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- Navbar items -->
                <div class="collapse navbar-collapse" id="collapsibleNavbar">
                    <ul class="navbar-nav ms-auto">
                        <!-- Contact link -->
                        <li class="nav-item">
                            <a class="nav-link underline-on-hover" href="index.php">Home Page</a>
                        </li>
                        <!-- Home link -->
                        <li class="nav-item">
                            <a class="nav-link underline-on-hover" href="staff.php">Staff</a>
                        </li>
                        <!-- Home link -->
                        <li class="nav-item">
                            <a class="nav-link underline-on-hover" href="manager.php">Manager</a>
                        </li>
                        <!-- About link -->
                        <li class="nav-item">
                            <a class="nav-link underline-on-hover" href="Usersessions.php">Users</a>
                        </li>
                    </ul>
                </div>
                <!-- /Navbar items -->
            </div>
        </nav>
        <!-- /Navigation bar with a dark theme -->
</header>
    <!--/Header section-->

    <a href="logout.php">Logout</a>
    <h1>User Sessions</h1>
    <br>
    <a class="btn btn-primary" href="AddNewUser.php" role="button">Add New User</a>
    <table class="table">

        <thead>
            <tr>
                <th>UserSessionsID</th>
                <th>UserID</th>
                <th>User_Name</th>
                <th>User_Password</th>
                <th>PermissionLevel</th>
                <th>Functions</th>
            </tr>
        </thead>
       
        <tbody>
        <?php
            $sql = "SELECT * FROM usersessions";
            $result = $connection -> query($sql);
 
            if (!$result) {
                die("Invalid query: " . $connection -> error);
            }
 
            while ($row = $result -> fetch_assoc()) {
                echo "<tr>
                    <td>" . $row["UserSessionsID"] . "</td>
                    <td>" . $row["UserID"] . "</td>
                    <td>" . $row["User_Name"] . "</td>
                    <td>" . $row["User_Password"] . "</td>
                    <td>" . $row["PermissionLevel"] . "</td>
                    <td>
                        <button onclick=\"location.href='EditUser.php?action=EditUser&UserSessionsID={$row['UserSessionsID']}'\">Edit</button>
                        <button onclick=\"location.href='DeleteUser.php?action=DeleteUser&UserSessionsID={$row['UserSessionsID']}'\">Delete</button>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
   
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
 
</body>
</html>