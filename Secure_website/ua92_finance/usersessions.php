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
                            <a class="nav-link underline-on-hover" href="manager.php">Manager</a>
                        </li>
                        <!-- Usersession link -->
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
    <!-- /Header section -->

    <div class="container mt-4">
        <!-- Logout button -->
        <a href="logout.php" class="btn btn-danger">Logout</a>
        <!-- Title -->
        <h1>User Sessions</h1>
        <br>
        <!-- Add New User button -->
        <a class="btn btn-primary mb-3" href="AddNewUser.php" role="button">Add New User</a>
        
        <!-- Table to display user sessions -->
        <table class="table table-striped">
            <!-- Table Head -->
            <thead>
                <tr>
                    <th>UsersessionsID</th>
                    <th>UserID</th>
                    <th>User_Name</th>
                    <th>PermissionLevel</th>
                    <th>Functions</th>
                </tr>
            </thead>
            <!-- /Table Head -->

            <!-- Table body -->
            <tbody>
            <?php
                // Retrieve users data from database
                $sql = "SELECT * FROM usersessions";
                $result = $connection->query($sql);

                // Check if query execution was successful
                if (!$result) {
                    die("Invalid query: " . $connection->error); // Display error message and terminate script if query fails
                }
                // Display user data in table rows
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>" . htmlspecialchars($row["UsersessionsID"]) . "</td>
                        <td>" . htmlspecialchars($row["UserID"]) . "</td>
                        <td>" . htmlspecialchars($row["User_Name"]) . "</td>
                        <td>" . htmlspecialchars($row["PermissionLevel"]) . "</td>
                        <td>
                            <!-- Edit user link -->
                            <a href='EditUser.php?action=EditUser&UsersessionsID=" . htmlspecialchars($row['UsersessionsID']) . "' class='btn btn-primary btn-sm'>Edit</a>
                            <!-- Delete user link -->
                            <a href='DeleteUser.php?action=DeleteUser&UsersessionsID=" . htmlspecialchars($row['UsersessionsID']) . "' class='btn btn-danger btn-sm'>Delete</a>
                        </td>
                    </tr>";
                }
            ?>
            </tbody>
            <!-- /Table body -->
        </table>
        <!-- /Table to display usersessions data -->
    </div>
    <!-- /Container -->
   
    <!-- Include jQuery from CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Bootstrap's JavaScript bundle from CDN for handling Bootstrap components -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
