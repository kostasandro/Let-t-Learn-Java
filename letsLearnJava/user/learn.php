<?php
// Start the session
session_start();

include 'validateUserSession.php';
include_once 'availableUserLevels.php';

//local variables
$info = $error = "";

$level_id = "";
$step = "";
$theory = new Theory();
$exercises = array();
$questions = array();

include_once '../connection.php';
$conn = initDbConnection();

if (isset($_GET['level']) && isset($_GET['step'])) {
    $level_id = $_GET['level'];
    $step = $_GET['step'];
} else {
    $error = "Λάθος παράμετροι.";
}

$leverOrdering = GetLevelOrdering($conn, $level_id);
$maxUserLevel = GetUserMaxLevel($conn, $_SESSION["user_id"]);
$nextUserLevel = GetUserNextLevel($conn, $maxUserLevel);


if ($leverOrdering > $nextUserLevel) {
    $error = "Έχεις ακόμα ". strval($level_id - $nextUserLevel) . " level για να φτάσεις εδώ. Συνέχισε την εξάσκηση :)";
} else {
    if ($step == "theory") {
        $theory = GetTheory($conn, $level_id);
    } else if ($step == "exercise") {
        $exercises = GetExercises($conn, $level_id);
    } else if ($step == "quiz") {
        $questions = GetQuestions($conn, $level_id);
    } else {
        $error = "Λάθος παράμετρος.";
    }
}

class Theory
{
    public $title;
    public $description;
}

class Exercise
{
    public $id;
    public $title;
    public $description;
    public $solution;
    public $ordering;
}

class Question
{
    public $id;
    public $description;
    public $ordering;
    public $type_id;
    public $answers = array();
}

class Answer
{
    public $id;
    public $description;
    public $ordering;
    public $points;
}

class UserAnswer{
    public $id;
    public $timestamp;
    public $user_id;
    public $question_id;
    public $answer_id;
    public $answer_text;
}

function GetLevelOrdering($conn, $level_id){
    $leverOrdering = 0;
    $sql = "SELECT ordering AS ordering
            FROM level 
            WHERE level_id = " . $level_id;
    if ($result = $conn->query($sql)) {
        $count = $result->num_rows;
        if ($count > 0) {
            while ($row = $result->fetch_assoc()) {
                $leverOrdering =  (int) $row["ordering"];
            }
        }
    }
    return $leverOrdering;
}

function GetTheory($conn, $level)
{
    $theory = new Theory();
    $sql = "SELECT * FROM theory WHERE level_id =" . $level;
    if ($result = $conn->query($sql)) {
        $count = $result->num_rows;
        if ($count > 0) {
            while ($row = $result->fetch_assoc()) {
                $theory->title = $row["title"];
                $theory->description = $row["description"];
            }
        }
    }
    return $theory;
}

function GetExercises($conn, $level)
{
    $exercises = array();
    $sql = "SELECT * FROM exercise WHERE level_id =" . $level . " ORDER BY ordering";
    if ($result = $conn->query($sql)) {
        $count = $result->num_rows;
        if ($count > 0) {
            while ($row = $result->fetch_assoc()) {
                $exercise = new Exercise();
                $exercise->id = $row["id"];
                $exercise->title = $row["title"];
                $exercise->description = $row["description"];
                $exercise->solution = $row["solution"];
                $exercise->ordering = $row["ordering"];
                $exercises[] = $exercise;
            }
        }
    }

    return $exercises;
}

function GetQuestions($conn, $level)
{
    $questions = array();
    $sql = "SELECT * FROM question WHERE level_id =" . $level . " ORDER BY ordering";
    if ($result = $conn->query($sql)) {
        $count = $result->num_rows;
        if ($count > 0) {
            while ($row = $result->fetch_assoc()) {
                $question = new Question();
                $question->id = $row["id"];
                $question->description = $row["description"];
                $question->ordering = $row["ordering"];
                $question->type_id = $row["type_id"];
                $question->answers = GetAnswers($conn, $row["id"]);
                $questions[] = $question;
            }
        }
    }
    return $questions;
}

function GetAnswers($conn, $question_id)
{
    $answers = array();
    $sql = "SELECT * FROM answer WHERE question_id =" . $question_id . " ORDER BY ordering";
    if ($result = $conn->query($sql)) {
        $count = $result->num_rows;
        if ($count > 0) {
            while ($row = $result->fetch_assoc()) {
                $answer = new Answer();
                $answer->id = $row["id"];
                $answer->description = htmlentities ($row["description"]);
                $answer->ordering = $row["ordering"];
                $answer->points = $row["points"];
                $answers[] = $answer;
            }
        }
    }
    return $answers;
}

function GetUserAnswers($questions){
    $userAnswers = array();
    foreach ($questions as $key=>$question){
        
        if ($question->type_id == 3){
            $answer_text = $_POST[$question->id];
            //κεφαλαία σε πεζά και διαγραφή των κόμμα, τελεία και πολλαπλών κενών χαρακτήρων
            $answer_text = strtolower($answer_text);
            $answer_text = str_replace(',', ' ', $answer_text);
            $answer_text = str_replace('.', ' ', $answer_text);
            $answer_text = trim(preg_replace('/\s\s+/', ' ', $answer_text));

            //διαχωρισμός του κειμένου σε λέξεις
            $answer_array = explode(" ", $answer_text);
            //φιλτράρισμα των επαναλαμβανόμενων λέξεων
            $answer_array = array_unique($answer_array);

            foreach ($answer_array as $answer) {
                $userAnswer = CreateUserAnswer($question);     
                $userAnswer->answer_text = $answer;
                $userAnswers[] = $userAnswer;
            }
        }
        if ($question->type_id == 4)
        { 
            if(isset($_POST[$question->id]))
            {
                foreach ($_POST[$question->id] as $answer) {
                    $userAnswer = CreateUserAnswer($question);
                    $userAnswer->answer_id = $answer;
                    $userAnswers[] = $userAnswer;
                }
            }
            else{
                $userAnswer = CreateUserAnswer($question);
                $userAnswers[] = $userAnswer;
            }
        }
        if ($question->type_id == 5){
            if(isset($_POST[$question->id])){
                $userAnswer = CreateUserAnswer($question);  
                $userAnswer->answer_id = $_POST[$question->id];
                $userAnswers[] = $userAnswer;
            }
            else{
                $userAnswer = CreateUserAnswer($question);
                $userAnswers[] = $userAnswer;
            }
        }
    }
    return $userAnswers;
}

function CreateUserAnswer($question){
    $userAnswer = new UserAnswer();
    $userAnswer->user_id = $_SESSION["user_id"];
    $userAnswer->question_id = $question->id;
    $userAnswer->answer_text = "";
    $userAnswer->answer_id = null;

    return $userAnswer;
}

function GetQuestionnaireId($conn){
    $questionnaire_id = 0;
    $sql = "SELECT MAX(questionnaire_id) as max FROM UserAnswer";
    if ($result = $conn->query($sql)) {
        $count = $result->num_rows;
        if ($count > 0) {
            while ($row = $result->fetch_assoc()) {
                $max = $row["max"];
                $questionnaire_id =  !is_null($max) ? $max : $questionnaire_id;
            }
        }
    }

    return $questionnaire_id + 1;
}

function InsertUserAnswersOnDB($conn, $userAnswers, $level_id){
    $questionnaire_id = GetQuestionnaireId($conn);
    
    $insertSql = "INSERT INTO UserAnswer (questionnaire_id, timestamp, user_id, level_id, question_id, answer_id, answer_text) VALUES ";
    foreach ($userAnswers as $userAnswer){
        $insertSql .= "("
            . $questionnaire_id . ", " 
            ."now(), " 
            . strval($userAnswer->user_id) . ", " 
            . strval($level_id) . ", " 
            . strval($userAnswer->question_id) . ", " 
            . (!is_null($userAnswer->answer_id) ? strval($userAnswer->answer_id) : "null") . ", "
            . (!empty($userAnswer->answer_text) ? "'" . strval($userAnswer->answer_text) . "'" : "null") . ""
            . "),";
    }
    // remove last "," character from query and add a ";"
    $insertSql = substr($insertSql, 0, -1) . ";";

    $result = "";
    if (mysqli_query($conn, $insertSql)) {
        $result = $questionnaire_id;
    } else {
        $result = 0;
    }

    return $result;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userAnswers = GetUserAnswers($questions);
    $questionnaire_id = InsertUserAnswersOnDB($conn, $userAnswers, $level_id);
    if($questionnaire_id != 0)
        header("LOCATION: ./quizHistoryDetails.php?questionnaire_id=".$questionnaire_id);
    else
        $error = mysqli_error($conn);
}

?>

<html>

<head>
    <title>L<?php echo $level_id ?>/<?php echo $step ?> - Let's learn JAVA</title>
    <link rel="stylesheet" type="text/css" media="all" href="../Css/styles.css">
    <link rel="stylesheet" type="text/css" media="all" href="../libraries/bootstrap-4.3.1-dist/css/bootstrap.min.css">
    <script src="../libraries/Jquery/jquery-3.3.1.min.js"></script>
    <script src="../libraries/bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>
</head>

<body>

    <?php include '../header.php'; ?>
    
    <?php include 'learnMenu.php'; ?>

    <div class="container page">
        
        <?php include '../messages.php'; ?>

        <?php include 'learnTheory.php'; ?>

        <?php include 'learnExercise.php'; ?>

        <?php include 'learnQuiz.php'; ?>
        
        <?php include 'learnNavigation.php'; ?>

    </div>
    
    <?php include '../footer.php'; ?>
</body>

</html>