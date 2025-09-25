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
    $user_data = check_login($connection, get_allowed_permissions(__FILE__)); // Verify user login status and retrieve user data
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Managers</title>
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
                        <!-- Home link -->
                        <li class="nav-item">
                            <a class="nav-link underline-on-hover" href="index.php">Home Page</a>
                        </li>
                        <!-- Staff link -->
                        <li class="nav-item">
                            <a class="nav-link underline-on-hover" href="staff.php">Staff</a>
                        </li>
                        <!-- Manager link -->
                        <li class="nav-item">
                            <a class="nav-link underline-on-hover" href="manager.php">Managers</a>
                        </li>
                        <!-- Usersessions link -->
                        <li class="nav-item">
                            <a class="nav-link underline-on-hover" href="usersessions.php">Users</a>
                        </li>
                    </ul>
                </div>
                <!-- /Navbar items -->
            </div>
        </nav>
        <!-- /Navigation bar with a dark theme -->
    </header>
    <!--/Header section-->

    <div class="container mt-4">
        <!-- Logout button -->
        <a href="logout.php" class="btn btn-danger">Logout</a>
        <!-- Title -->
        <h1>Managers</h1>
        <br>
        <!-- Add new manager button -->
        <a class="btn btn-primary mb-3" href="AddNewmanager.php" role="button">Add New Manager</a>
        <!-- Table to display manager data -->
        <table class="table table-striped">
            <!-- Table Head -->
            <thead>
                <tr>
                    <th>Manager ID</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Date of Birth</th>
                    <th>Gender</th>
                    <th>Annual Salary</th>
                    <th>Background Check</th>
                    <th>Functions</th>
                </tr>
            </thead>
            <!-- /Table Head -->

            <!-- Table body -->
            <tbody>
                <?php
                // Retrieve manager data from database
                $sql = "SELECT * FROM manager";
                $result = $connection->query($sql);

                // Check if query execution was successful
                if (!$result) {
                    die("Invalid query: " . $connection->error); // Display error message and terminate script if query fails
                }

                // Display manager data in table rows
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row["managerID"]) . "</td>
                            <td>" . htmlspecialchars($row["manager_Name"]) . "</td>
                            <td>" . htmlspecialchars($row["manager_Address"]) . "</td>
                            <td>" . htmlspecialchars($row["manager_Email"]) . "</td>
                            <td>" . htmlspecialchars($row["manager_PhoneNumber"]) . "</td>
                            <td>" . htmlspecialchars($row["manager_DateOfBirth"]) . "</td>
                            <td>" . htmlspecialchars($row["manager_Gender"]) . "</td>
                            <td>" . htmlspecialchars($row["manager_AnnualSalary"]) . "</td>
                            <td>" . htmlspecialchars($row["manager_BackgroundCheck"]) . "</td>
                            <td>
                                <!-- Edit manager link -->
                                <a href='Editmanager.php?action=Editmanager&managerID=" . htmlspecialchars($row['managerID']) . "' class='btn btn-primary btn-sm functions-btn'>Edit</a>
                                <!-- Delete manager link -->
                                <a href='Deletemanager.php?action=Deletemanager&managerID=" . htmlspecialchars($row['managerID']) . "' class='btn btn-danger btn-sm functions-btn'>Delete</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
            <!-- /Table body -->
        </table>
        <!-- /Table to display manager data -->
    </div>
    <!-- /Container -->

    <!-- Include jQuery from CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Bootstrap's JavaScript bundle from CDN for handling Bootstrap components -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
