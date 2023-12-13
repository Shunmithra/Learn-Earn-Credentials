<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location: login.php');
    exit;
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

// Retrieve all PDFs with associated images from the database, sorted by the selected option
$view_query = "SELECT p.pdf_id, p.pdf_name, i.image_name FROM pdfs p LEFT JOIN images i ON p.pdf_id = i.pdf_id ORDER BY $selectedOption";
$result = $conn->query($view_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All PDFs with Images</title>
    
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
        <h3>E-books</h3>

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
                echo "<a href='view_pdfs.php?pdf_id=$pdf_id'>View PDF</a>";echo "</div>";
                echo "<a href='attend_quiz.php?pdf_id=$pdf_id'>Attend Quiz</a>";
                echo "</div>";

               
            }

            echo '</div>';
        } else {
            echo "<p>No PDFs with images found in the database.</p>";
        }
        ?>

        
        
        <!-- Add a button to go back -->
        <a href="user_homepage.html" class="btn">Go Back</a>
    </div>
</div>

<script>
function changeSorting(sortOption) {
    // Redirect to the same page with the selected sorting option
    window.location.href = `view_collections.php?sort=${sortOption}`;
}
</script>

</body>
</html>
