<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];
$fetch = [];

// Check if the profile has been viewed before
if (!isset($_SESSION['profile_viewed'])) {
    // Update the profile view count in the database
    mysqli_query($conn, "UPDATE `user_form` SET profile_views = profile_views + 1 WHERE id = '$user_id'") or die('query failed');

    // Set a session variable to mark this profile as viewed
    $_SESSION['profile_viewed'] = true;
}

// Fetch user data from the database
$select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('query failed');
if (mysqli_num_rows($select) > 0) {
    $fetch = mysqli_fetch_assoc($select);
}

// Fetch the user's credit points from the database
$credit_select = mysqli_query($conn, "SELECT credits FROM `author_credits` WHERE user_id = '$user_id'");
$credit_fetch = mysqli_fetch_assoc($credit_select);
$credit_points = $credit_fetch['credits'];

// Fetch the user's profile views count from the database
$profile_views_select = mysqli_query($conn, "SELECT profile_views FROM `user_form` WHERE id = '$user_id'");
$profile_views_fetch = mysqli_fetch_assoc($profile_views_select);
$profile_views = $profile_views_fetch['profile_views'];

// Count the number of followers for the current user
$followers_count_query = "SELECT COUNT(*) AS followers_count FROM user_follows WHERE following_id = '$user_id'";
$followers_count_result = mysqli_query($conn, $followers_count_query);
$followers_count = 0;

if ($followers_count_result && mysqli_num_rows($followers_count_result) > 0) {
    $followers_data = mysqli_fetch_assoc($followers_count_result);
    $followers_count = $followers_data['followers_count'];
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
        <?php
        if (isset($fetch['image']) && $fetch['image'] != '') {
            echo '<img src="uploaded_img/' . $fetch['image'] . '">';
        } else {
            echo '<img src="images/default-avatar.png">';
        }
        ?>
        <h3><?php echo isset($fetch['name']) ? $fetch['name'] : ''; ?></h3>
        <h3><?php echo isset($fetch['author']) ? $fetch['author'] : ''; ?></h3>
        <p><strong>Email:</strong> <?php echo isset($fetch['email']) ? $fetch['email'] : ''; ?></p>

        <p><strong>Profile Views:</strong> <?php echo isset($profile_views) ? $profile_views : 0; ?></p>
        <p><strong>Credit Points:</strong> <?php echo isset($credit_points) ? $credit_points : 0; ?></p>
		<p><strong>Followers:</strong> <?php echo $followers_count; ?></p>


        <!-- Add more profile fields as needed -->
        <a href="update_user_profile.php" class="btn">Update Profile</a>
        <a href="user_homepage.html" class="delete-btn">Go Back</a>
    </div>
</div>
</body>
</html>
