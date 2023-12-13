<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit;
}

$search_results = array();

// Handle the search form submission
if (isset($_POST['search'])) {
    $search_term = $_POST['search_term'];
    
    // Perform a database query to search for users based on the criteria
    $search_query = "SELECT * FROM `user_form` WHERE `name` LIKE ? OR `email` LIKE ?";
    $stmt = $conn->prepare($search_query);
    $search_term = "%$search_term%"; // Add wildcard '%' to the search term
    $stmt->bind_param("ss", $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Store the search results in an array
    while ($row = $result->fetch_assoc()) {
        $search_results[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload pdf</title>

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">

    <div class="profile">
        <h3>User Search</h3>

        <!-- Search Form -->
        <form method="post">
            <input type="text" name="search_term" placeholder="Enter search term">
            <button type="submit" name="search">Search</button>
        </form>

        <!-- Display Search Results -->
        <?php if (!empty($search_results)) : ?>
            <ul>
                <?php foreach ($search_results as $result) : ?>
                    <li>
                        <a href="user_profile.php?user_id=<?php echo $result['id']; ?>">
                            Name: <?php echo $result['name']; ?><br>
                            Email: <?php echo $result['email']; ?><br>
                            <!-- Add more user profile information here -->
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php elseif (isset($_POST['search'])) : ?>
            <p>No users found.</p>
        <?php endif; ?>

        <a href="user_homepage.html" class="btn">Go Back </a>
        
    </div>

</div>

</body>
</html>
