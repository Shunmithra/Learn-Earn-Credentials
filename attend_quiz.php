<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if 'pdf_id' is set in the POST data
    if (isset($_POST['pdf_id'])) {
        $pdf_id = $_POST['pdf_id'];

        // Retrieve quiz questions for the selected PDF
        $quiz_query = "SELECT * FROM quiz_questions WHERE pdf_id = ?";
        $stmt = $conn->prepare($quiz_query);
        $stmt->bind_param("i", $pdf_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Process user's quiz answers and calculate the score and credit points
        $score = 0;
        $creditPoints = 0;

        while ($row = $result->fetch_assoc()) {
            $question_id = $row['question_id'];
            $correct_answer = $row['correct_option'];
            $user_answer = $_POST['answer_' . $question_id];

            if ($user_answer == $correct_answer) {
                $score++;
                $creditPoints += 2; // Award 2 points for each correct answer
            }
        }

        // Update the author's credits in the author_credits table
        $update_credits_query = "UPDATE author_credits SET credits = credits + ? WHERE user_id = ?";
        $stmt_update = $conn->prepare($update_credits_query); // Use a different variable name
        $creditPoints = $score * 2; // Calculate the gained credit points
        $stmt_update->bind_param("ii", $creditPoints, $user_id);
        $stmt_update->execute();

        // Display the pop-up message with gained points using JavaScript
        echo '<script>';
        echo 'alert("Quiz Submitted Successfully! You scored ' . $score . ' out of ' . $result->num_rows . ' and earned ' . $creditPoints . ' credit points.");';
        echo 'window.location.href = "view_collections.php";'; // Redirect to view_collections.php
        echo '</script>';
        exit;
    } else {
        echo "PDF ID is missing in the POST data.";
    }
}

if (isset($_GET['pdf_id'])) {
    $selected_pdf_id = $_GET['pdf_id'];

    // Retrieve quiz questions for the selected PDF
    $quiz_query = "SELECT * FROM quiz_questions WHERE pdf_id = ?";
    $stmt = $conn->prepare($quiz_query);
    $stmt->bind_param("i", $selected_pdf_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Display quiz questions for the selected PDF
        ?>
        <!-- Rest of your code for displaying the quiz questions -->
        <?php
    } else {
        // No quiz questions available for the selected PDF
        echo '<p>No quiz questions available for the selected PDF.</p>';
    }
} else {
    // No PDF selected
    echo '<p>No PDF selected.</p>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attend Quiz</title>
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

        form {
            text-align: center;
            max-width: 800px;
            width: 100%;
        }

        .question {
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        label {
            display: block;
            font-size: 16px;
        }

        input[type="radio"] {
            margin-right: 5px;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
            margin-top: 20px;
        }

        button:hover {
            background-color: #444;
        }

        .delete-btn {
            margin-top: 20px;
            font-size: 16px;
            text-decoration: none;
            color: #333;
        }

        .delete-btn:hover {
            color: #444;
        }
    </style>
</head>
<body>
<div class="container">
    <?php
    if (isset($_GET['pdf_id'])) {
        $selected_pdf_id = $_GET['pdf_id'];

        // Retrieve quiz questions for the selected PDF
        $quiz_query = "SELECT * FROM quiz_questions WHERE pdf_id = ?";
        $stmt = $conn->prepare($quiz_query);
        $stmt->bind_param("i", $selected_pdf_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Display quiz questions for the selected PDF
            ?>
            <h3>Attend Quiz</h3>
            <form method="POST">
                <?php
                while ($row = $result->fetch_assoc()) {
                    $question_id = $row['question_id'];
                    $question_text = $row['question_text'];
                    $option_1 = $row['option_1'];
                    $option_2 = $row['option_2'];
                    $option_3 = $row['option_3'];
                    $option_4 = $row['option_4'];

                    echo '<div class="question">';
                    echo '<p>' . $question_text . '</p>';
                    echo '<div class="options">';
                    echo '<label>';
                    echo '<input type="radio" name="answer_' . $question_id . '" value="1"> ' . $option_1;
                    echo '</label>';
                    echo '<label>';
                    echo '<input type="radio" name="answer_' . $question_id . '" value="2"> ' . $option_2;
                    echo '</label>';
                    echo '<label>';
                    echo '<input type="radio" name="answer_' . $question_id . '" value="3"> ' . $option_3;
                    echo '</label>';
                    echo '<label>';
                    echo '<input type="radio" name="answer_' . $question_id . '" value="4"> ' . $option_4;
                    echo '</label>';
                    echo '</div>'; // Close .options
                    echo '</div>'; // Close .question
                }
                ?>
                <input type="hidden" name="pdf_id" value="<?php echo $selected_pdf_id; ?>">
                <button type="submit">Submit Quiz</button>
            </form>
            <a href="view_collections.php" class="delete-btn">Go Back</a>
            
</div>
</body>
</html>


        <?php
    } else {
        // No quiz questions available for the selected PDF
        echo '<p>No quiz questions available for the selected PDF.</p>';
    }
} else {
    // No PDF selected
    echo '<p>No PDF selected.</p>';
}
?>
