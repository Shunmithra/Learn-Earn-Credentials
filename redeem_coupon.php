<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $coupon_code = $_POST['coupon_code']; // Get the coupon code entered by the user

    // Query to check if the coupon code exists and is not already redeemed
    $coupon_query = mysqli_query($conn, "SELECT * FROM `coupons` WHERE coupon_code = '$coupon_code' AND redeemed = 0");

    if (mysqli_num_rows($coupon_query) > 0) {
        // Coupon code is valid and not redeemed
        $coupon_data = mysqli_fetch_assoc($coupon_query);

        // Implement your coupon redemption logic here

        // Calculate the discount based on your coupon rules
        $discount = calculateDiscount($coupon_data);

        // Calculate the new order total after applying the discount
        $order_total = calculateOrderTotal($discount);

        // Update the coupon's status to redeemed
        $coupon_id = $coupon_data['coupon_id'];
        mysqli_query($conn, "UPDATE `coupons` SET redeemed = 1 WHERE coupon_id = '$coupon_id'");

        // Display the discount and updated order total to the user
        $message = "Coupon code successfully redeemed! You received a $discount% discount. Your new total is $order_total.";
    } else {
        $error_message = "Invalid or already redeemed coupon code.";
    }
}

// Function to calculate the discount based on coupon rules
function calculateDiscount($coupon_data) {
    // Implement your coupon discount calculation logic here
    // For example, if the coupon offers a 10% discount, return 10
    return 10;
}

// Function to calculate the new order total after applying the discount
function calculateOrderTotal($discount) {
    // Implement your order total calculation logic here
    // For example, if the original order total is $100 and the discount is 10%, return $90
    return 100 - (199 * $discount / 100);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redeem Coupon</title>
    
    <!-- Add your custom CSS file link -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        /* Add any additional styles specific to redeem_coupon.php here */
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        .coupon-form {
            text-align: center;
        }

        .coupon-form label {
            font-size: 18px;
            margin-right: 10px;
        }

        .coupon-form input[type="text"] {
            padding: 10px;
            font-size: 16px;
            width: 200px;
        }

        .coupon-form button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .coupon-form button:hover {
            background-color: #444;
        }

        .coupon-result {
            font-size: 18px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Redeem Coupon</h2>
    <form class="coupon-form" action="redeem_coupon.php" method="POST">
        <label for="coupon_code">Enter Coupon Code:</label>
        <input type="text" id="coupon_code" name="coupon_code" required>
        <button type="submit">Validate Coupon</button>
    </form>
    
    <?php if (isset($message)): ?>
        <div class="coupon-result"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <?php if (isset($error_message)): ?>
        <div class="coupon-result"><?php echo $error_message; ?></div>
    <?php endif; ?>
    
    <a href="food-order.html" class="btn">Back to Food Order Website</a>
</div>

</body>
</html>
