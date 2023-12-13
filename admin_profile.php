<?php
include 'config.php';
session_start();

$adminId = $_SESSION['admin_id'];

// Fetch and display the logged-in admin's profile information
$selectAdminQuery = "SELECT * FROM admin_table WHERE id = $adminId";
$result = mysqli_query($conn, $selectAdminQuery);

if ($row = mysqli_fetch_assoc($result)) {
    $adminUsername = $row['username'];
    $adminImageData = $row['image_file']; // Assuming you have an image column
}

// Add Admin Button Logic
if (isset($_POST['make_admin'])) {
    $newAdminUsername = mysqli_real_escape_string($conn, $_POST['new_admin_username']);
    // You should perform necessary validation and checks here
    $insertNewAdminQuery = "INSERT INTO admin_table (username, image_file) VALUES ('$new_admin_username', null)";
    mysqli_query($conn, $insertNewAdminQuery);
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

        .feedback-entry {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px;
            max-width: 800px;
            width: 100%;
        }

        .feedback-text {
            font-size: 16px;
        }

        .delete-button {
            background-color: red;
            color: white;
            border: none;
            cursor: pointer;
        }

        
    </style>
</head>
<body>
    <!-- Include the navigation from your admin page -->
    <div class="sidenav">
        <a href="admin_profile.php">Admin Profile</a>
        <a href="all_profile.php">All User's Profile</a>
        <a href="uploaded_pdfs.php">Uploaded PDFs with Author Profiles</a>
        <a href="feedback.php">Feedback</a>
		
		<a href="admin_login.php">LogOut</a>
    </div>

    <div class="container">
        <h3>Admin Profile</h3>
        
        <!-- Display the logged-in admin's profile information -->
        <p>Username: <?php echo $adminUsername; ?></p>
       

        <!-- Button to redirect to add_admin.php -->
        
    </div>
</body>
</html>
