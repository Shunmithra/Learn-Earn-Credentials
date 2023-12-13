<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location: login.php');
    exit;
}

// Check if the profile has been viewed before
if (!isset($_SESSION['profile_viewed'])) {
    // Update the profile view count in the database
    mysqli_query($conn, "UPDATE `user_form` SET profile_views = profile_views + 1 WHERE id = '$user_id'") or die('query failed');

    // Set a session variable to mark this profile as viewed
    $_SESSION['profile_viewed'] = true;
}

// Define an array of sorting options
$sortingOptions = array(
    'pdf_name' => 'PDF Name (A-Z)',
    'pdf_name DESC' => 'PDF Name (Z-A)',
    'pdf_id' => 'PDF ID (Asc)',
    'pdf_id DESC' => 'PDF ID (Desc)'
);

// Get the selected sorting option from the URL or use the default
$selectedOption = $_GET['sort'] ?? 'pdf_name';

// Define a variable to store the target user's ID (replace 'TARGET_USER_ID' with the actual user ID)
$targetUserId = $_GET['user_id'] ?? 'TARGET_USER_ID';

// Retrieve PDFs with associated images uploaded by the target user, sorted by the selected option
$view_query = "SELECT p.pdf_id, p.pdf_name, i.image_name
               FROM pdfs p 
               LEFT JOIN images i ON p.pdf_id = i.pdf_id 
               WHERE p.user_id = $targetUserId 
               ORDER BY $selectedOption";
$result = $conn->query($view_query);

// Fetch user data from the database
$select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$targetUserId'") or die('query failed');
if (mysqli_num_rows($select) > 0) {
    $fetch = mysqli_fetch_assoc($select);
}

// Check if the logged-in user is following the viewed user
$followed = false;
$follow_query = "SELECT * FROM user_follows WHERE follower_id = ? AND following_id = ?";
$stmt = $conn->prepare($follow_query);
$stmt->bind_param("ii", $user_id, $targetUserId);
$stmt->execute();
$follow_result = $stmt->get_result();

if ($follow_result->num_rows > 0) {
    $followed = true;
}

// Check if the follow/unfollow form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['follow'])) {
        // Check if the user is currently following the viewed user
        if ($followed) {
            // If they are following, unfollow by deleting the entry from the 'user_follows' table
            $unfollow_query = "DELETE FROM user_follows WHERE follower_id = ? AND following_id = ?";
            $stmt = $conn->prepare($unfollow_query);
            $stmt->bind_param("ii", $user_id, $targetUserId);
            $stmt->execute();
            $followed = false; // Update the status
        } else {
            // If they are not following, follow by inserting a new entry into the 'user_follows' table
            $follow_query = "INSERT INTO user_follows (follower_id, following_id) VALUES (?, ?)";
            $stmt = $conn->prepare($follow_query);
            $stmt->bind_param("ii", $user_id, $targetUserId);
            $stmt->execute();
            $followed = true; // Update the status
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View PDFs Uploaded by User</title>
    
    <!-- Add your custom CSS file link -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        .grid-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .pdf-entry {
            flex: 0 0 calc(33.33% - 20px); /* Adjust the column width as needed */
            margin: 10px;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        .pdf-entry img {
            max-width: 100%;
            height: auto;
        }

        /* Style for the sorting dropdown button */
        .sorting-dropdown {
            flex: 1 0 100%; /* Full-width for the dropdown within the grid */
            text-align: right;
        }

        /* Style for the dropdown select */
        .sorting-dropdown select {
            width: 200px; /* Adjust the width as needed */
        }
    </style>
</head>
<body>

<div class="container">
    <div class="profile">
        <!-- User Profile Image -->
        <?php
         if (isset($fetch['image']) && $fetch['image'] != '') {
            echo '<img src="uploaded_img/' . $fetch['image'] . '">';
        } else {
            echo '<img src="images/default-avatar.png">';
        }
        ?>
        <h3><?php echo isset($fetch['name']) ? $fetch['name'] : ''; ?></h3>
        <h3><?php echo isset($fetch['author']) ? $fetch['author'] : ''; ?></h3>
        <h3><strong>Email:</strong><?php echo isset($fetch['email']) ? $fetch['email'] : ''; ?></h3>

      
        <!-- Sorting dropdown button -->
        <div class="sorting-dropdown">
            <label for="sortSelect">Sort By:</label>
            <select id="sortSelect" onchange="changeSorting(this.value)">
                <?php
                foreach ($sortingOptions as $optionValue => $optionLabel) {
                    $selected = ($selectedOption === $optionValue) ? 'selected' : '';
                    echo "<option value='$optionValue' $selected>$optionLabel</option>";
                }
                ?>
            </select>
        </div>

        <?php
        if ($result->num_rows > 0) {
            echo '<div class="grid-container">';

            while ($row = $result->fetch_assoc()) {
                $pdf_id = $row['pdf_id'];
                $pdf_name = $row['pdf_name'];
                $image_name = $row['image_name'];

                echo "<div class='pdf-entry'>";
                echo $pdf_name;

                // Display the associated image if available
                if (!empty($image_name)) {
                    echo "<img src='uploaded_img/$image_name' alt='Image for $pdf_name'>";
                }

                // Add buttons for each PDF
                echo "<div class='pdf-actions'>";
                echo "<a href='view_pdfs.php?pdf_id=$pdf_id'>View PDF</a>";
                echo "<a href='attend_quiz.php?pdf_id=$pdf_id'>Attend Quiz</a>";
                echo "</div>";
                echo "</div>";
            }

            echo '</div>';
        } else {
            echo "<p>No PDFs with images found for this user.</p>";
        }
        ?>

        <!-- Follow/Unfollow Button -->
        <form method="post">
            <?php if ($followed): ?>
                <input type="submit" name="follow" value="Unfollow" class="btn">
            <?php else: ?>
                <input type="submit" name="follow" value="Follow" class="btn">
            <?php endif; ?>
        </form>
        <a href="author_search.php" class="btn">Go Back</a>
    </div>
</div>

<script>
function changeSorting(sortOption) {
    // Redirect to the same page with the selected sorting option
    window.location.href = `user_profile.php?user_id=<?php echo $targetUserId; ?>&sort=${sortOption}`;
}
</script>

</body>
</html>
