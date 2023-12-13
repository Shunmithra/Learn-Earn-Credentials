<?php
include 'config.php';

if(isset($_GET['pdf_id'])){
    // Get the pdf_id from the query parameter
    $pdf_id = $_GET['pdf_id'];

    // Retrieve the PDF data and file name from the database based on pdf_id
    $select_pdf_query = "SELECT pdf_name, pdf_data FROM pdfs WHERE pdf_id = ?";
    $stmt = $conn->prepare($select_pdf_query);
    $stmt->bind_param("i", $pdf_id);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0){
        $stmt->bind_result($pdf_name, $pdf_data);
        $stmt->fetch();

        // Set the appropriate headers for PDF download
        header("Content-type: application/pdf");
        header("Content-Disposition: attachment; filename=\"$pdf_name\"");
        
        // Output the PDF data to the browser
        echo $pdf_data;
        exit;
    } else {
        echo "PDF not found.";
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}
?>
