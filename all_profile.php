<?php
include 'config.php'; 


// Include your database connection configuration

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];

    // Perform any necessary validation and authorization checks here

    // Delete the user from the user_form table
    $deleteQuery = "DELETE FROM user_form WHERE id = $userId";
    
    if (mysqli_query($conn, $deleteQuery)) {
        echo "User with ID $userId has been deleted.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Fetch all user profiles from the user_form table
$query = "SELECT * FROM user_form";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    
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

        .user-profile {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px;
            max-width: 800px;
            width: 100%;
        }

        .user-name {
            font-weight: bold;
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
        <h3>All User's Profile</h3>
        
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            $userId = $row['id'];
            $userName = $row['name'];
			
            $isAuthor = $row['author'];
        ?>
        <div class="user-profile">
		
            <div class="user-name"><?php echo $userName; ?></div>
            <div><?php echo $isAuthor ? 'Author' : 'User'; ?></div>
            <form method="post">
                <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                <input class="delete-button" type="submit" value="Delete">
            </form>
        </div>
        <?php
        }
        ?>
    </div>
</body>
</html>
