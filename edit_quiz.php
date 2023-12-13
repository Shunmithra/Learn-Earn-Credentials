<?php
session_start();

// Include your database connection here
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('location: login.php'); // Redirect unauthorized users to the user login page
}

if (isset($_GET['quiz_id'])) {
    $quiz_id = $_GET['quiz_id'];
    // Fetch quiz details and questions from the database
    // Implement quiz editing logic here
}

if (isset($_POST['add_questions'])) {
    // Handle adding 5 questions and their correct answers to the quiz
    $quiz_id = mysqli_real_escape_string($conn, $_POST['quiz_id']);
    
    for ($i = 1; $i <= 5; $i++) {
        $question_text = mysqli_real_escape_string($conn, $_POST["question_$i"]);
        $correct_answer = mysqli_real_escape_string($conn, $_POST["correct_answer_$i"]);
        
        // Insert the question into the questions table
        $sql = "INSERT INTO questions (quiz_id, question_text) VALUES ('$quiz_id', '$question_text')";
        mysqli_query($conn, $sql);
        $question_id = mysqli_insert_id($conn); // Get the question's ID
        
        // Insert the correct answer into the answers table
        $sql = "INSERT INTO answers (question_id, answer_text, is_correct) VALUES ('$question_id', '$correct_answer', 1)";
        mysqli_query($conn, $sql);
    }
    
    // Redirect to the quiz editing page or display a success message
    header("location: edit_quiz.php?quiz_id=$quiz_id");
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
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Quiz</h1>
        <?php if (isset($quiz_id)): ?>
            <h2>Quiz ID: <?= $quiz_id ?></h2>
            <form method="post">
                <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">
                <h2>Add 5 Questions and Correct Answers:</h2>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <label for="question_<?= $i ?>">Question <?= $i ?>:</label>
                    <input type="text" name="question_<?= $i ?>" required>
                    <br>
                    <label for="correct_answer_<?= $i ?>">Correct Answer <?= $i ?>:</label>
                    <input type="text" name="correct_answer_<?= $i ?>" required>
                    <br>
                <?php endfor; ?>
                <button type="submit" name="add_questions">Add Questions</button>
            </form>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <p>Invalid quiz ID</p>
        <?php endif; ?>
    </div>
</body>
</html>
