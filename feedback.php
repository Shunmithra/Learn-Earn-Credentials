<?php
include 'config.php'; 


// Include your database connection configuration

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['feedback_id'])) {
    $feedbackId = $_POST['feedback_id'];

    // Perform any necessary validation and authorization checks here

    // Delete the feedback entry from the database
    $deleteQuery = "DELETE FROM feedback WHERE id = $feedbackId";

    if (mysqli_query($conn, $deleteQuery)) {
        echo "Feedback with ID $feedbackId has been deleted.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Fetch all feedback entries from the database
$query = "SELECT * FROM feedback";
$result = mysqli_query($conn, $query);
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
        <h3>Feedback Entries</h3>

        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            $feedbackId = $row['id'];
            $authorName = $row['author_name'];
            $feedbackText = $row['user_feedback'];
            $submissionTime = $row['submission_time'];
        ?>
        <div class="feedback-entry">
            <div class="feedback-text"><?php echo $feedbackText; ?></div>
            <div>Author: <?php echo $authorName; ?></div>
            <div>Submission Time: <?php echo $submissionTime; ?></div>
            <form method="post">
                <input type="hidden" name="feedback_id" value="<?php echo $feedbackId; ?>">
                <input class="delete-button" type="submit" value="Delete">
            </form>
        </div>
        <?php
        }
        ?>
    </div>
</body>
</html>
