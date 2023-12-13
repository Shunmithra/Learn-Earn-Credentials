<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit;
}

// Function to delete a PDF by PDF ID
function deletePDF($pdf_id) {
    global $conn;
    $delete_query = "DELETE FROM pdfs WHERE pdf_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $pdf_id);
    if ($stmt->execute()) {
        // PDF deleted successfully
        return true;
    } else {
        // Error occurred while deleting the PDF
        return false;
    }
}

// Retrieve user's PDFs with associated images and view counts from the database
$view_query = "SELECT p.pdf_id, p.pdf_name, i.image_name, p.views FROM pdfs p LEFT JOIN images i ON p.pdf_id = i.pdf_id WHERE p.user_id = ?";
$stmt = $conn->prepare($view_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View PDFs with Images</title>

    <!-- custom css file link -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        /* CSS for the grid layout */
        .grid-container {
            display: flex;
            flex-wrap: wrap;
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

        /* Add styling for the select dropdown */
        .pdf-actions select {
            padding: 5px;
            font-size: 16px;
        }
		  .view-count {
        font-size: 12px; /* Adjust the font size as needed */
    }
    </style>
</head>
<body>

<div class="container">

    <div class="profile">
        <h3>View PDFs with Images</h3>

        <?php
        if ($result->num_rows > 0) {
            echo '<div class="grid-container">';

            while ($row = $result->fetch_assoc()) {
                $pdf_id = $row['pdf_id'];
                $pdf_name = $row['pdf_name'];
                $image_name = $row['image_name'];
                $views = $row['views'];

                echo "<div class='pdf-entry'>";
                echo "<a href='download_pdf.php?pdf_id=$pdf_id'>$pdf_name</a>";

                // Display the associated image if available
                if (!empty($image_name)) {
                    echo "<img src='uploaded_img/$image_name' alt='Image for $pdf_name'>";
                }

                // Display the view count
                echo "<p class='view-count' >Views: $views</p>";

                // Add a dropdown select for each PDF
                echo "<div class='pdf-actions'>";
                echo "<select onchange='handleAction(this.value, $pdf_id)'>";
                echo "<option value='' selected>Mores</option>";
                echo "<option value='delete'>Delete PDF</option>";
                echo "<option value='create-quiz'>Create Quiz</option>";
                echo "</select>";
                echo "</div>";

                echo "</div>";
            }

            echo '</div>';
        } else {
            echo "<p>No PDFs with images uploaded yet.</p>";
        }
        ?>

        <a href="upload_pdf.php" class="btn">Upload PDF with Image</a>
        
        <!-- Add a button to go back -->
        <a href="author_homepage.html" class="delete-btn">Go Back</a>
    </div>

</div>

<script>
function handleAction(action, pdfId) {
    if (action === 'delete') {
        if (confirm('Are you sure you want to delete this PDF?')) {
            // Redirect to delete_pdf.php with the PDF ID
            window.location.href = `delete_pdf.php?pdf_id=${pdfId}`;
        }
    } else if (action === 'create-quiz') {
        // Redirect to makequiz.php with the PDF ID
        window.location.href = `quiz_creation.php?pdf_id=${pdfId}`;
    }
}
</script>

</body>
</html>
