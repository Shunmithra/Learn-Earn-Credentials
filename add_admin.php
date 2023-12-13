<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_admin'])) {
    $newAdminUsername = mysqli_real_escape_string($conn, $_POST['admin_username']);
    $newAdminPassword = mysqli_real_escape_string($conn, md5($_POST['admin_password']));

    // Perform necessary validation and checks

    $insertNewAdminQuery = "INSERT INTO admin_table (username, password) VALUES ('$newAdminUsername', '$newAdminPassword')";

    if (mysqli_query($conn, $insertNewAdminQuery)) {
        // Admin added successfully
        echo '<script>alert("Admin added successfully.");</script>';
    } else {
        echo "Error: " . mysqli_error($conn);
    }
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

        .success-message {
            text-align: center;
            font-weight: bold;
            color: green;
            font-size: 18px;
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
		<a href="admins.php">Admins</a>
		<a href="admin_login.php">LogOut</a>
    </div>

    <div class="container">
        <h3>Add New Admin</h3>
        
        <!-- Form to input new admin details -->
        <form method="post">
            <input type="text" name="admin_username" placeholder="Username" required>
            <input type="password" name="admin_password" placeholder="Password" required>
            <button type="submit" name="add_admin">Save and Confirm</button>
        </form>

        <!-- Success message will be displayed here -->
        <div class="success-message"></div>
    </div>

    <script>
        // Check if the page has a success message and display it as a pop-up
        var successMessage = document.querySelector('.success-message');
        if (successMessage && successMessage.textContent.trim() !== '') {
            alert(successMessage.textContent);
            window.location.href = 'admin_profile.php'; // Redirect to all_users_profile.php
        }
    </script>
</body>
</html>
