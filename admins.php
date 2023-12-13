<?php
include 'config.php';

// Fetch all admin usernames from the admin_table
$query = "SELECT username FROM admin_table";
$result = mysqli_query($conn, $query);

$adminUsernames = [];
while ($row = mysqli_fetch_assoc($result)) {
    $adminUsernames[] = $row['username'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Add your head content here -->
    <style>
        /* Add any additional styles specific to this page here */
        .sidenav {
            height: 100%;
            width: 250px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #333;
            padding-top: 20px;
            padding-left: 10px; /* Add padding to the left */
        }

        .sidenav a {
            display: block;
            padding: 10px;
            width: 90%; /* Reduce width to account for padding */
            background-color: transparent;
            border: none;
            color: white;
            text-align: left;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none; /* Added this line for anchor links */
            margin: 10px auto; /* Center align the links */
        }

        .sidenav a:hover {
            background-color: #555;
        }

        .container {
            margin-left: 260px; /* Adjust margin to make space for sidenav */
            padding: 20px; /* Add padding to the content area */
        }

        h3 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .admin-username {
            font-size: 16px;
            margin: 10px;
        }
    </style>
</head>
<body>
    <!-- Include the navigation from your admin page -->
    <div class="sidenav">
        <a href="admin_profile.php">Admin Profile</a>
        <a href="all_users_profile.php">All User's Profile</a>
        <a href="uploaded_pdfs.php">Uploaded PDFs with Author Profiles</a>
		
        <a href="feedback.php">Feedback</a>
		
		<a href="admin_login.php">LogOut</a>
    </div>

    <div class="container">
        <h3>All Admins</h3>
        
        <!-- Display all admin usernames -->
        <?php
        foreach ($adminUsernames as $username) {
            echo '<div class="admin-username">' . $username . '</div>';
        }
        ?>
    </div>
</body>
</html>
