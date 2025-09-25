<?php
    include("connection.php");
    include("functions.php");
    //
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
    <title>staff</title>
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
                            <a class="nav-link underline-on-hover" href="manager.php">manager</a>
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
    <!-- Logout button -->
    <a href="logout.php">Logout</a>
    <!-- Title -->
    <h1>staff</h1>
    <br>
    <!-- Add new staff button -->
    <a class="btn btn-primary" href="AddNewstaff.php" role="button">Add New staff</a>
    <table class="table">
        <!-- Table Head -->
        <thead>
            <tr>
                <!-- Table Attributes -->
                <th>staffID</th>
                <th>staff_Name</th>
                <th>staff_Address</th>
                <th>staff_Email</th>
                <th>staff_PhoneNumber</th>
                <th>staff_DateOfBirth</th>
                <th>staff_Gender</th>
                <th>staff_AnnualSalary<th>
                <th>staff_BackgroundCheck<th>
                <th>Functions</th>
            </tr>
        </thead>
        <!-- /Table Head -->
       <!-- Table body -->
        <tbody>
        <?php
            // Connect staff table
            $sql = "SELECT * FROM staff";
            $result = $connection -> query($sql);
    
            if (!$result) {
                die("Invalid query: " . $connection -> error);
            }
            // Get the Attributes from the table for specific ID
            while ($row = $result -> fetch_assoc()) {
                echo "<tr>
                    <td>" . $row["staffID"] . "</td>
                    <td>" . $row["staff_Name"] . "</td>
                    <td>" . $row["staff_Address"] . "</td>
                    <td>" . $row["staff_Email"] . "</td>
                    <td>" . $row["staff_PhoneNumber"] . "</td>
                    <td>" . $row["staff_DateOfBirth"] . "</td>
                    <td>" . $row["staff_Gender"] . "</td>
                    <td>" . $row["staff_AnnualSalary"] . "</td>
                    <td>" . $row["staff_BackgroundCheck"] . "</td>
                    <td>
                        <button onclick=\"location.href='Editstaff.php?action=Editstaff&staffID={$row['staffID']}'\">Edit</button>
                        <button onclick=\"location.href='Deletestaff.php?action=Deletestaff&staffID={$row['staffID']}'\">Delete</button>
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