<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location: login.php');
    exit;
}

// Get the PDF ID from the query parameter
$pdf_id = $_GET['pdf_id'];

// Retrieve the PDF information from the database, including the author's user ID
$pdf_query = "SELECT p.pdf_id, p.pdf_name, p.user_id AS authors_id, p.pdf_data, i.image_name FROM pdfs p LEFT JOIN images i ON p.pdf_id = i.pdf_id WHERE p.pdf_id = ?";
$stmt = $conn->prepare($pdf_query);
$stmt->bind_param("i", $pdf_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $pdf_name = $row['pdf_name'];
    $author_id = $row['authors_id']; // Retrieve the author's user ID
    $pdf_content = $row['pdf_data'];
	
	// Increment the view count for the PDF
    $update_views_query = "UPDATE pdfs SET views = views + 1 WHERE pdf_id = ?";
    $stmt = $conn->prepare($update_views_query);
    $stmt->bind_param("i", $pdf_id);
    $stmt->execute();


    // Increment the author's credit points by 5
    $update_query = "UPDATE author_credits SET credits = credits + 5 WHERE user_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("i", $author_id);
    $stmt->execute();

    // Set the Content-Type header to specify that we are sending a PDF
    header('Content-Type: application/pdf');

    // Set the Content-Disposition header to force download or inline display
    header('Content-Disposition: inline; filename="' . $pdf_name . '"');

    // Output the PDF content
    echo $pdf_content;
} else {
    // PDF not found
    echo "PDF not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View PDF</title>
</head>
<body>
    <h1>Viewing PDF: <?php echo $pdf_name; ?></h1>
    
    <!-- You can add additional HTML content here if needed -->

</body>
</html>
