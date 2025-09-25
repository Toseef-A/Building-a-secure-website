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
    $user_data = check_login($connection, get_allowed_permissions(__FILE__));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index page</title>
    <!-- Link to Bootstrap 5.3.1 CSS library -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .text-centered {
            display: flex; /* Use flexbox layout */
            justify-content: center; /* Center content horizontally */
            align-items: center; /* Center content vertically */
            height: 100vh; /* Ensures full viewport height */
            margin: 0; /* Remove default margin */
        }

        .content {
            text-align: center; /* Center text horizontally */
        }

        .centered {
            display: flex; /* Use flexbox layout */
            flex-direction: column; /* Stack items vertically */
            align-items: center; /* Center items horizontally */
            justify-content: center; /* Center items vertically */
            height: 100%; /* Take up full height of parent */
        }
    </style>
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
                        <!-- Home Page link -->
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
    <!-- /Header section -->

    <!-- Logout link -->
    <a href="logout.php" class="btn btn-danger">Logout</a>
    
    <!-- Main content -->
    <div class="content">
        <div class="text-centered">
            <!-- Display user's name if logged in -->
            <?php if (isset($user_data['User_Name'])): ?>
                <h1>Hello, <?php echo htmlspecialchars($user_data['User_Name']); ?></h1>
            <?php endif; ?>
        </div>
    </div>
    <!-- /Main content -->

    <!-- Include jQuery from CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Bootstrap's JavaScript bundle from CDN for handling Bootstrap components -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
