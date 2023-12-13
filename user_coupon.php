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

// Initialize variables
$generate_coupon = false;
$coupon_code = "";

if (isset($_POST['generate_coupon'])) {
    if ($credit_points >= 10) {
        // Generate a random coupon code
        $coupon_code = generateRandomCouponCode();
        
        // Deduct 10 credits from the user's account
        mysqli_query($conn, "UPDATE `author_credits` SET credits = credits - 10 WHERE user_id = '$user_id'");
        
        // Implement your coupon code insertion logic here
        // Insert the coupon code into the database
        $coupon_code = mysqli_real_escape_string($conn, $coupon_code);
        $description = "Description of the coupon"; // Customize this
        mysqli_query($conn, "INSERT INTO `coupons` (coupon_code, points_required, description) VALUES ('$coupon_code', 0, '$description')");
        
        $generate_coupon = true;
    } else {
        $generate_coupon = false;
    }
}

// Function to generate a random coupon code
function generateRandomCouponCode() {
    // Generate a random alphanumeric code (you can customize this)
    return substr(md5(uniqid(mt_rand(), true)), 0, 8);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coupon Codes</title>

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <div class="profile">
        
        <h3><?php echo isset($fetch['name']) ? $fetch['name'] : ''; ?></h3>
        

        <p><strong>Credit Points:</strong> <?php echo isset($credit_points) ? $credit_points : 0; ?></p>

        <form method="POST">
            <button type="submit" name="generate_coupon">Generate Coupon Code (10 Credits)</button>
        </form>


        <h2>Available Coupon Codes:</h2>
        <ul>
            <?php
            // Query to fetch available coupons (you should customize this query)
            $coupon_query = mysqli_query($conn, "SELECT * FROM `coupons` WHERE redeemed = 0");
            
            if (mysqli_num_rows($coupon_query) > 0) {
                while ($coupon_data = mysqli_fetch_assoc($coupon_query)) {
                    echo '<li>' . $coupon_data['coupon_code'] . ' - ' . $coupon_data['description'] . '</li>';
                }
            }
            ?>
        </ul>

        <!-- Add more profile fields as needed -->
        
        <a href="user_homepage.html" class="delete-btn">Go Back</a>
    </div>
</div>
</body>
</html>