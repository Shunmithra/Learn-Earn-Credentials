<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdf_file']) && isset($_FILES['image_file'])) {
    $pdf_name = $_FILES['pdf_file']['name'];
    $pdf_tmp_name = $_FILES['pdf_file']['tmp_name'];

    $image_name = $_FILES['image_file']['name'];
    $image_tmp_name = $_FILES['image_file']['tmp_name'];

    // Check if both files were uploaded
    if (!empty($pdf_name) && !empty($image_name)) {
        // Read PDF data
        $pdf_data = file_get_contents($pdf_tmp_name);

        // Move the uploaded image to a folder
        $target_image_folder = 'uploaded_img/';
        $target_image_path = $target_image_folder . $image_name;

        if (move_uploaded_file($image_tmp_name, $target_image_path)) {
            // Insert PDF and image data into the database
            $insert_query = "INSERT INTO pdfs (user_id, pdf_name, pdf_data) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("iss", $user_id, $pdf_name, $pdf_data);

            if ($stmt->execute()) {
                $pdf_id = $stmt->insert_id;

                // Insert image information into the images table
                $insert_image_query = "INSERT INTO images (pdf_id, image_name) VALUES (?, ?)";
                $stmt_image = $conn->prepare($insert_image_query);
                $stmt_image->bind_param("is", $pdf_id, $image_name);

                if ($stmt_image->execute()) {
                    $message = "PDF and image uploaded successfully.";
                } else {
                    $error = "Error uploading image to the database: " . $stmt_image->error;
                }

                $stmt_image->close();
            } else {
                $error = "Error uploading PDF file to the database: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $error = "Error moving image file to the server.";
        }
    } else {
        $error = "Please select both a PDF file and an image file to upload.";
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
        <h3>Upload PDF with Image</h3>

        <?php
        if (isset($message)) {
            echo "<p>$message</p>";
        } elseif (isset($error)) {
            echo "<p>$error</p>";
        }
        ?>

        <form action="upload_pdf.php" method="POST" enctype="multipart/form-data">
            <label for="pdf_file">Select PDF File:</label>
            <input type="file" name="pdf_file" id="pdf_file" accept=".pdf" required>

            <label for="image_file">Select Image File:</label>
            <input type="file" name="image_file" id="image_file" accept="image/*" required>

            <input type="submit" value="Upload" class="btn">
        </form>

        <a href="view_pdf.php" class="btn">View Collection</a>
        <a href="author_homepage.html" class="delete-btn">Go Back</a>
    </div>

</div>

</body>
</html>
