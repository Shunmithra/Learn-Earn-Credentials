<?php

include 'config.php';
session_start();

if (isset($_POST['submit'])) {

   $username = mysqli_real_escape_string($conn, $_POST['username']);
   $password = mysqli_real_escape_string($conn, $_POST['password']); // Note: You should use secure password hashing here.

   $select = mysqli_query($conn, "SELECT * FROM `admin_table` WHERE username = '$username' AND password = '$password'") or die('Query failed');

   if (mysqli_num_rows($select) > 0) {
      $row = mysqli_fetch_assoc($select);
      $_SESSION['admin_id'] = $row['id']; // Store admin ID in session
      header('location: all_profile.php');
   } else {
      $message[] = 'Incorrect username or password!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Login</title>

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<div class="form-container">

   <form action="" method="post" enctype="multipart/form-data">
      <h3>Admin Login</h3>
      <?php
      if (isset($message)) {
         foreach ($message as $message) {
            echo '<div class="message">' . $message . '</div>';
         }
      }
      ?>
      <input type="text" name="username" placeholder="Enter username" class="box" required>
      <input type="password" name="password" placeholder="Enter password" class="box" required>
      <input type="submit" name="submit" value="Login now" class="btn">
     
   </form>

</div>

</body>
</html>
