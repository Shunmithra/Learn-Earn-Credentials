<?php
include 'config.php'; // Include your database connection configuration
session_start();

// Check if the user is logged in as an admin or has the appropriate session information
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php"); // Redirect to the admin login page if not logged in
    exit();
}

// You can now use the $conn connection object for your database queries.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["deleteUser"])) {
        // Handle user/author deletion
        $userId = $_POST["deleteUser"];
        $deleteUserQuery = "DELETE FROM users WHERE id = $userId";
        $deleteUserResult = mysqli_query($conn, $deleteUserQuery);
    }

    if (isset($_POST["deletePDF"])) {
        // Handle PDF deletion
        $pdfId = $_POST["deletePDF"];
        $deletePDFQuery = "DELETE FROM pdfs WHERE id = $pdfId";
        $deletePDFResult = mysqli_query($conn, $deletePDFQuery);
    }

    if (isset($_POST["deleteFeedback"])) {
        // Handle feedback deletion
        $feedbackId = $_POST["deleteFeedback"];
        $deleteFeedbackQuery = "DELETE FROM feedback WHERE id = $feedbackId";
        $deleteFeedbackResult = mysqli_query($conn, $deleteFeedbackQuery);
    }

    if (isset($_POST["newAdminUsername"])) {
        // Handle authorizing a new admin
        $newAdminUsername = $_POST["newAdminUsername"];
        // Add code to validate the user and set them as an admin in your database.
        // This depends on your database structure.
    }
}

// You may add code to retrieve and display the list of users, authors, PDFs, and feedback here.

$usersQuery = "SELECT * FROM users";
$usersResult = mysqli_query($conn, $usersQuery);

// Retrieve and display Authors
$authorsQuery = "SELECT * FROM authors"; // Adjust your table name if needed
$authorsResult = mysqli_query($conn, $authorsQuery);

// Retrieve and display PDFs
$pdfsQuery = "SELECT * FROM pdfs";
$pdfsResult = mysqli_query($conn, $pdfsQuery);

// Retrieve and display Feedback
$feedbackQuery = "SELECT * FROM feedback";
$feedbackResult = mysqli_query($conn, $feedbackQuery);


?>

<!DOCTYPE html>
<html lang="en">
<h2>Users</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <!-- Add more user-related fields here -->
    </tr>
    <?php
    while ($user = mysqli_fetch_assoc($usersResult)) {
        echo "<tr>";
        echo "<td>{$user['id']}</td>";
        echo "<td>{$user['name']}</td>";
        // Add more columns based on your user data
        echo "</tr>";
    }
    ?>
</table>

<!-- Display Authors -->
<h2>Authors</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <!-- Add more author-related fields here -->
    </tr>
    <?php
    while ($author = mysqli_fetch_assoc($authorsResult)) {
        echo "<tr>";
        echo "<td>{$author['id']}</td>";
        echo "<td>{$author['name']}</td>";
        // Add more columns based on your author data
        echo "</tr>";
    }
    ?>
</table>

<!-- Display PDFs -->
<h2>PDFs</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <!-- Add more PDF-related fields here -->
    </tr>
    <?php
    while ($pdf = mysqli_fetch_assoc($pdfsResult)) {
        echo "<tr>";
        echo "<td>{$pdf['id']}</td>";
        echo "<td>{$pdf['title']}</td>";
        // Add more columns based on your PDF data
        echo "</tr>";
    }
    ?>
</table>

<!-- Display Feedback -->
<h2>Feedback</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Author Name</th>
        <th>User Feedback</th>
        <!-- Add more feedback-related fields here -->
    </tr>
    <?php
    while ($feedback = mysqli_fetch_assoc($feedbackResult)) {
        echo "<tr>";
        echo "<td>{$feedback['id']}</td>";
        echo "<td>{$feedback['author_name']}</td>";
        echo "<td>{$feedback['user_feedback']}</td>";
        // Add more columns based on your feedback data
        echo "</tr>";
    }
    ?>
</table>

</html>
