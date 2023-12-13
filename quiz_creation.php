<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdf_id = $_POST['pdf_id'];
    $question_text = $_POST['question_text'];
    $option_1 = $_POST['option_1'];
    $option_2 = $_POST['option_2'];
    $option_3 = $_POST['option_3'];
    $option_4 = $_POST['option_4'];
    $correct_option = $_POST['correct_option'];

    // Insert the quiz question into the database
    $insert_query = "INSERT INTO quiz_questions (pdf_id, question_text, option_1, option_2, option_3, option_4, correct_option) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("isssssi", $pdf_id, $question_text, $option_1, $option_2, $option_3, $option_4, $correct_option);

    if ($stmt->execute()) {
        // Quiz question added successfully
        header("Location: quiz_creation.php?pdf_id=$pdf_id");
        exit;
    } else {
        // Error occurred while adding the question
        echo "Error: " . $stmt->error;
    }
}

$pdf_id = $_GET['pdf_id'];

// Retrieve the PDF name for display
$pdf_query = "SELECT pdf_name FROM pdfs WHERE pdf_id = ?";
$stmt = $conn->prepare($pdf_query);
$stmt->bind_param("i", $pdf_id);
$stmt->execute();
$result = $stmt->get_result();
$pdf_name = $result->fetch_assoc()['pdf_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Quiz for <?php echo $pdf_name; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <style>
    /* Add any additional styles specific to this page here */
    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        flex-direction: column;
    }

    h3 {
        font-size: 24px;
        margin-bottom: 20px;
    }

    /* Style for the form */
    .quiz-form {
        text-align: center;
        max-width: 500px;
        width: 100%;
        padding: 20px;
        background-color: #f5f5f5;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .quiz-form label {
        display: block;
        font-size: 18px;
        margin-top: 10px;
    }

    .quiz-form input[type="text"],
    .quiz-form input[type="number"],
    .quiz-form textarea {
        padding: 10px;
        font-size: 16px;
        width: 100%;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .quiz-form textarea {
        resize: vertical; /* Allow vertical resizing of the textarea */
    }

    .quiz-form button {
        padding: 10px 20px;
        font-size: 16px;
        background-color: #333;
        color: #fff;
        border: none;
        cursor: pointer;
    }

    .quiz-form button:hover {
        background-color: #444;
    }

    .existing-quiz {
        font-size: 18px;
        margin-top: 20px;
    }
</style>

</head>
<body>

<div class="container">
    <h3>Create Quiz for <?php echo $pdf_name; ?></h3>

    <!-- Add a form to allow the author to add quiz questions -->
    <form class="quiz-form" method="POST">
        <input type="hidden" name="pdf_id" value="<?php echo $pdf_id; ?>">
		<br>
		<br><br>
		<br><br>
		<br>
        <label for="question_text">Question Text:</label>
        <textarea name="question_text" rows="4" cols="50" required></textarea><br>
        <label for="option_1">Option 1:</label>
        <input type="text" name="option_1" required><br>
        <label for="option_2">Option 2:</label>
        <input type="text" name="option_2" required><br>
        <label for="option_3">Option 3:</label>
        <input type="text" name="option_3" required><br>
        <label for="option_4">Option 4:</label>
        <input type="text" name="option_4" required><br>
        <label for="correct_option">Correct Option (1, 2, 3, or 4):</label>
        <input type="number" name="correct_option" min="1" max="4" required><br>
        <button type="submit">Add Question</button>
    </form>

    <!-- Display existing quiz questions for this PDF -->
    <?php
    $quiz_query = "SELECT * FROM quiz_questions WHERE pdf_id = ?";
    $stmt = $conn->prepare($quiz_query);
    $stmt->bind_param("i", $pdf_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<h4 class="existing-quiz">Existing Quiz Questions:</h4>';
        while ($row = $result->fetch_assoc()) {
            echo '<p>' . $row['question_text'] . '</p>';
        }
    } else {
        echo '<p class="existing-quiz">No quiz questions added yet.</p>';
    }
    ?>
    
    <a href="author_homepage.html" class="delete-btn">Go Back</a>
</div>

</body>
</html>
