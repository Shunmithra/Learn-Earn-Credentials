<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/style.css">
    <title>Create Quiz</title>
    <style>
        /* Style for the text boxes and radio buttons */
        input[type="text"] {
            border: 2px solid black; /* Add a black border */
            padding: 5px; /* Add padding for better visual appearance */
        }
        .level-selector {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Create a Quiz</h1>

    <form method="post" action="process_quiz.php">
        <label for="quiz_name">Quiz Name:</label>
        <input type="text" id="quiz_name" name="quiz_name" required><br><br>

        <!-- Level Selector -->
        <div class="level-selector">
            <label>Select Quiz Level:</label><br>
            <input type="radio" id="beginner" name="quiz_level" value="Beginner" checked>
            <label for="beginner">Beginner</label><br>

            <input type="radio" id="intermediate" name="quiz_level" value="Intermediate">
            <label for="intermediate">Intermediate</label><br>

            <input type="radio" id="advanced" name="quiz_level" value="Advanced">
            <label for="advanced">Advanced</label><br>
        </div>

        <!-- Quiz Questions -->
        <h2>Quiz Questions</h2>
        <?php for ($i = 1; $i <= 5; $i++): ?>
            <label for="question<?= $i ?>">Question <?= $i ?>:</label>
            <input type="text" id="question<?= $i ?>" name="question<?= $i ?>" required><br>
            <label for="answer<?= $i ?>">Answer <?= $i ?>:</label>
            <input type="text" id="answer<?= $i ?>" name="answer<?= $i ?>" required><br>
        <?php endfor; ?>

        <br>
        <input type="submit" value="Create Quiz">
    </form>
</body>
</html>
