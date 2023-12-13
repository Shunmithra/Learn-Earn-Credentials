<?php
include 'config.php'; // Include your database connection configuration
session_start();

// Check if the author is logged in or has the appropriate session information
if (!isset($_SESSION['user_id'])) {
    header("Location: author_login.php"); // Redirect to the author login page if not logged in
    exit();
}

$authorId = $_SESSION['user_id'];

// Fetch the author's name from the user_form table
$selectAuthorNameQuery = "SELECT name FROM user_form WHERE id = $authorId";
$authorResult = mysqli_query($conn, $selectAuthorNameQuery);
$authorRow = mysqli_fetch_assoc($authorResult);
$authorName = $authorRow['name'];

// Fetch feedback for the author based on their name from the database
$selectQuery = "SELECT * FROM feedback WHERE author_name = '$authorName' ORDER BY submission_time DESC";
$result = mysqli_query($conn, $selectQuery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Author Feedback</title>

    <!-- Add your custom CSS file link -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        /* Add any additional styles specific to this page here */
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        h3 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        /* Style for feedback entries */
        .feedback-entry {
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            max-width: 800px;
            width: 100%;
        }

        .feedback-author {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .feedback-timestamp {
            font-size: 12px;
            color: #888;
        }

        .feedback-text {
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Feedback for Author: <?php echo $authorName; ?></h3>
        
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $feedbackText = $row['user_feedback'];
                $submission_time = $row['submission_time'];
        ?>
                <div class="feedback-entry">
                    <div class="feedback-author">Author: <?php echo $authorName; ?></div>
                    <div class="feedback-timestamp">Time: <?php echo $submission_time; ?></div>
                    <div class="feedback-text"><?php echo $feedbackText; ?></div>
                </div>
        <?php
            }
        } else {
            echo "<p>No feedback available.</p>";
        }
        ?>
        
        <a href="author_homepage.html" class="btn">Go Back</a>
    </div>
</body>
</html>
