<?php
session_start();

// Include your database connection here
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('location: login.php'); // Redirect unauthorized users to the user login page
}

if (isset($_GET['quiz_id'])) {
    $quiz_id = $_GET['quiz_id'];

    // Fetch the quiz based on the provided quiz_id
    $quiz_query = "SELECT * FROM quiz WHERE id = '$quiz_id' AND author_id = '{$_SESSION['user_id']}'";
    $quiz_result = mysqli_query($conn, $quiz_query);

    if (mysqli_num_rows($quiz_result) > 0) {
        // The quiz exists, fetch and display it
        $quiz = mysqli_fetch_assoc($quiz_result);
        $level = $quiz['level'];
        $title = $quiz['title'];
        $description = $quiz['description'];
    // Fetch questions and answers for the quiz
    $questions_query = "SELECT * FROM questions WHERE quiz_id = '$quiz_id'";
    $questions_result = mysqli_query($conn, $questions_query);

    $questions = [];
    while ($row = mysqli_fetch_assoc($questions_result)) {
        $question_id = $row['id'];
        $question_text = $row['question_text'];

        // Fetch correct answer for each question
        $answer_query = "SELECT answer_text FROM answers WHERE question_id = '$question_id' AND is_correct = 1";
        $answer_result = mysqli_query($conn, $answer_query);
        $answer = mysqli_fetch_assoc($answer_result)['answer_text'];

        $questions[] = [
            'question_text' => $question_text,
            'correct_answer' => $answer,
        ];
    }
}
} else {
    // No quiz is created yet, show a pop-up message
    echo '<script>';
    echo 'if (confirm("No quiz is created yet. Click OK to continue.")) {';
    echo "window.location.href = 'quiz_creation.php';";
    echo '}';
    echo '</script>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

    <!-- custom css file link  -->
    
</head>
<body>
    <div class="container">
        <h1>View Quiz</h1>
        <?php if (isset($quiz)): ?>
            <h2>Quiz Details</h2>
            <p>Quiz ID: <?= $quiz_id ?></p>
            <p>Level: <?= $level ?></p>
            <p>Title: <?= $title ?></p>
            <p>Description: <?= $description ?></p>
            <h2>Questions and Correct Answers</h2>
            <?php foreach ($questions as $index => $question): ?>
                <p>Question <?= $index + 1 ?>:</p>
                <p><?= $question['question_text'] ?></p>
                <p>Correct Answer:</p>
                <p><?= $question['correct_answer'] ?></p>
            <?php endforeach; ?>
        <?php endif; ?>
        <a href="view_pdf.php">Back</a>
    </div>
</body>
</html>
