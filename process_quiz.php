<?php
// Initialize arrays to store quiz data for each level
$beginner_quiz = [];
$intermediate_quiz = [];
$advanced_quiz = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get quiz name from the form
    $quiz_name = $_POST['quiz_name'];

    // Loop through form fields to collect questions and answers for each level
    for ($i = 1; $i <= 5; $i++) {
        // Beginner Level
        $beginner_question = $_POST['beginner_q' . $i];
        $beginner_answer = $_POST['beginner_a' . $i];
        $beginner_quiz[] = ['question' => $beginner_question, 'answer' => $beginner_answer];

        // Intermediate Level
        $intermediate_question = $_POST['intermediate_q' . $i];
        $intermediate_answer = $_POST['intermediate_a' . $i];
        $intermediate_quiz[] = ['question' => $intermediate_question, 'answer' => $intermediate_answer];

        // Advanced Level
        $advanced_question = $_POST['advanced_q' . $i];
        $advanced_answer = $_POST['advanced_a' . $i];
        $advanced_quiz[] = ['question' => $advanced_question, 'answer' => $advanced_answer];
    }

    // Now you have quiz data stored in arrays for each level
    // You can perform further processing or save this data to a database

    // Example: Save quiz data to a database (you will need to adapt this part)
    // Include your database connection code here
    
    $conn = new mysqli('localhost','root','','user_db');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert quiz details and questions into the database
    // (You will need to define your database schema)

    // Close the database connection
    $conn->close();
    

    // Redirect to a success page or display a success message
    header('Location: quiz_created.php');
    exit;
} else {
    // Handle invalid requests or direct access to this script
    echo "Invalid request.";
}
?>
