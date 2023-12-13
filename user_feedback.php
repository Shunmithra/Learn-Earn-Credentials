<?php
include 'config.php'; // Include your database connection configuration
session_start();

if (isset($_POST['submit'])) {
    $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);
    $authorName = mysqli_real_escape_string($conn, $_POST['author_name']); // Get the author's name from the form

    // Insert feedback into the database
    $insertQuery = "INSERT INTO feedback (author_name, user_feedback) VALUES ('$authorName', '$feedback')";

    if (mysqli_query($conn, $insertQuery)) {
        $_SESSION['feedback_success'] = true;
        $message = 'Feedback successfully sent!';
    } else {
        $_SESSION['feedback_error'] = 'Error submitting feedback.';
        $message = 'Error submitting feedback.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Feedback</title>

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

        form {
            text-align: center;
            max-width: 800px;
            width: 100%;
        }

        .question {
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        label {
            display: block;
            font-size: 16px;
        }

        input[type="radio"] {
            margin-right: 5px;
        }

        /* Style for the author's name input field */
        input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
            width: 100%;
        }

        textarea {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
            width: 100%;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
            margin-top: 20px;
        }

        button:hover {
            background-color: #444;
        }

        .delete-btn {
            margin-top: 20px;
            font-size: 16px;
            text-decoration: none;
            color: #333;
        }

        .delete-btn:hover {
            color: #444;
        }
    </style>
	
</head>
<body>
    <div class="container">
        <h3>Submit Feedback</h3>
        
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="author_name" placeholder="Author's Name" required><br>
            <textarea name="feedback" placeholder="Your Feedback" rows="4" required></textarea><br>
            <button type="submit" name="submit">Submit Feedback</button>
        </form>
    </div>
    
    <script>
        <?php if (isset($message)): ?>
        // Display a pop-up message and redirect after a delay
        setTimeout(function() {
            alert("<?php echo $message; ?>");
            window.location.href = "user_homepage.html";
        }, 100);
        <?php endif; ?>
    </script>
</body>
</html>
