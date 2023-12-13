<?php
include 'config.php';

// Check if the PDF ID is set in the URL
if (isset($_GET['pdf_id'])) {
    $pdf_id = $_GET['pdf_id'];

    // Add code here to delete the PDF from the database
    // You may need to perform additional checks and validation

    // Example SQL query to delete the PDF by its ID
    $delete_query = "DELETE FROM pdfs WHERE pdf_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $pdf_id);

    if ($stmt->execute()) {
        // PDF deleted successfully
        header('location: view_pdf.php'); // Redirect back to the PDF viewing page
        exit;
    } else {
        // Error occurred while deleting the PDF
        echo "Error deleting the PDF.";
    }
} else {
    // PDF ID is not provided in the URL
    echo "PDF ID is missing.";
}
?>
