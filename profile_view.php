<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location: login.php');
    exit; // Make sure to exit after the redirect
}

// Get the user_id of the profile being viewed from the URL parameter
if (isset($_GET['user_id'])) {
    $viewed_user_id = $_GET['user_id'];

    // Check if the viewer is not the owner of the profile
    if ($viewed_user_id != $user_id) {
        // Insert a record in the profile_views table to track the view
        $insert_view_query = "INSERT INTO profile_views (user_id, viewer_user_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_view_query);
        $stmt->bind_param("ii", $viewed_user_id, $user_id);
        $stmt->execute();
    }

    // Retrieve information about the viewed user from the database
    $user_query = "SELECT name, author, email FROM `user_form` WHERE `id` = ?";
    $stmt = $conn->prepare($user_query);
    $stmt->bind_param("i", $viewed_user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $viewed_user = $result->fetch_assoc();
    } else {
        // Redirect or show an error if the user is not found
        header('location: search_users.php');
        exit;
    }
} else {
    // Redirect or show an error if user_id is not provided in the URL
    header('location: search_users.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">

    <div class="profile">
        <h3>User Profile</h3>

        <!-- Display User Profile Information -->
        <h4>Name: <?php echo $viewed_user['name']; ?></h4>
        <h4>Author: <?php echo $viewed_user['author']; ?></h4>
        <p>Email: <?php echo $viewed_user['email']; ?></p>

        <!-- Display Profile View Count -->
        <?php
        // Count the number of profile views
        $view_count_query = "SELECT COUNT(*) AS view_count FROM profile_views WHERE user_id = ?";
        $stmt = $conn->prepare($view_count_query);
        $stmt->bind_param("i", $viewed_user_id);
        $stmt->execute();
        $view_count_result = $stmt->get_result();

        if ($view_count_result->num_rows > 0) {
            $view_count_row = $view_count_result->fetch_assoc();
            $view_count = $view_count_row['view_count'];
        } else {
            $view_count = 0;
        }
        echo "<p>Profile Views: $view_count</p>";
        ?>

        <!-- Display Uploaded PDFs for the Viewed User -->
        <h4>Uploaded PDFs</h4>
        <?php
        // Retrieve PDFs uploaded by the viewed user
        $pdf_query = "SELECT * FROM `pdfs` WHERE `user_id` = ?";
        $stmt = $conn->prepare($pdf_query);
        $stmt->bind_param("i", $viewed_user_id);
        $stmt->execute();
        $pdf_result = $stmt->get_result();

        if ($pdf_result->num_rows > 0) {
            while ($pdf_row = $pdf_result->fetch_assoc()) {
                $pdf_name = $pdf_row['pdf_name'];
                $pdf_id = $pdf_row['pdf_id'];
                echo "<a href='download_pdf.php?pdf_id=$pdf_id'>$pdf_name</a><br>";
            }
        } else {
            echo "<p>No PDFs uploaded by this user.</p>";
        }
        ?>

        <a href="search_users.php" class="btn">Back to Search</a>
        <a href="login.php" class="delete-btn">Logout</a>
    </div>

</div>

</body>
</html>
