<?php
include 'config.php'; // Include your database connection configuration


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pdf_id'])) {
    $pdfId = $_POST['pdf_id'];

    // Perform any necessary validation and authorization checks here

    // Delete the PDF from the database
    $deleteQuery = "DELETE FROM pdfs WHERE pdf_id = $pdfId";

    if (mysqli_query($conn, $deleteQuery)) {
        echo "PDF with ID $pdfId has been deleted.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Fetch uploaded PDFs with author information from the database
$query = "SELECT p.pdf_id, p.pdf_name, u.name FROM pdfs p
          JOIN user_form u ON p.user_id = u.id";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Add your head content here -->
    <style>
        /* Add any additional styles specific to this page here */
        .sidenav {
            height: 100%;
            width: 250px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #333;
            padding-top: 20px;
            padding-left: 10px; /* Add padding to the left */
        }

        .sidenav a {
            display: block;
            padding: 10px;
            width: 90%; /* Reduce width to account for padding */
            background-color: transparent;
            border: none;
            color: white;
            text-align: left;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none; /* Added this line for anchor links */
            margin: 10px auto; /* Center align the links */
        }

        .sidenav a:hover {
            background-color: #555;
        }

        .container {
            margin-left: 260px; /* Adjust margin to make space for sidenav */
            padding: 20px; /* Add padding to the content area */
        }

        h3 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .pdf-entry {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px;
            max-width: 800px;
            width: 100%;
        }

        .pdf-title {
            font-weight: bold;
        }

        .author-name {
            font-weight: bold;
        }

        .delete-button {
            background-color: red;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Include the navigation from your admin page -->
    <div class="sidenav">
        <a href="admin_profile.php">Admin Profile</a>
        <a href="all_profile.php">All User's Profile</a>
        <a href="uploaded_pdfs.php">Uploaded PDFs with Author Profiles</a>
        <a href="feedback.php">Feedback</a>
		
		<a href="admin_login.php">LogOut</a>
    </div>

    <div class="container">
        <h3>Uploaded PDFs with Author Profiles</h3>

        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            $pdfId = $row['pdf_id'];
            $pdfName = $row['pdf_name'];
            $authorName = $row['name'];
        ?>
        <div class="pdf-entry">
            <div class="pdf-title"><?php echo $pdfName; ?></div>
            <div class="author-name">Author: <?php echo $authorName; ?></div>
            <form method="post">
                <input type="hidden" name="pdf_id" value="<?php echo $pdfId; ?>">
                <input class="delete-button" type="submit" value="Delete">
            </form>
        </div>
        <?php
        }
        ?>
    </div>
</body>
</html>

