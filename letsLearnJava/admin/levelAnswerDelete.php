<?php
    session_start();
    include 'validateAdminSession.php';

    include_once '../connection.php';
    $conn = initDbConnection();
    
    $info = $error = "";
    
    if (isset($_GET['level_id']) && isset($_GET['question_id']) && isset($_GET['answer_id'])) {
        $level_id = $_GET['level_id'];
        $question_id = $_GET['question_id'];
        $answer_id = $_GET['answer_id'];
    }
    else 
        header('Location: levels.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $sql  = "DELETE FROM useranswer WHERE answer_id =" . $answer_id ."; ";    
        $sql .= "DELETE FROM answer WHERE id =" . $answer_id ."; "; 

        if (mysqli_multi_query($conn, $sql)) {
            header('Location: levelAnswers.php?level_id='.$level_id.'&question_id='.$question_id);
        } else {
            $error =  mysqli_error($conn);
        }      
    }

?>
<html>
    <head>
        <title>Level Delete</title>
        <link rel="stylesheet" type="text/css" media="all" href="../Css/styles.css">
        <link rel="stylesheet" type="text/css" media="all" href="../libraries/bootstrap-4.3.1-dist/css/bootstrap.min.css">
        <script src="../libraries/Jquery/jquery-3.3.1.min.js"></script>   
        <script src="../libraries/bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>
    </head>
    <body>

        <?php include '../header.php'; ?>
        
        <?php include '../messages.php'; ?>

        <?php include './levelMenu.php'; ?>
        
        <div class="container page">
            <div>
                <h2>Διαγραφή απάντησης ερώτησης Quiz</h2>
            </div>
            <div>
                <p>Θα πραγματοποιήσετε διαγραφή της απάντησης της ερώτησης καθώς και όλων των στοιχείων που το αποτελούν όπως
                    <ul>
                        <li>Απαντήσεις των χρήστων για το quiz.</li>
                    </ul>
                    Είστε σίγουρος/η?
                </p>
            </div>
            <form method="post" action="levelAnswerDelete.php?level_id=<?php echo $level_id ?>&question_id=<?php echo $question_id ?>&answer_id=<?php echo $answer_id ?>" >             
                <button type="submit" class="btn btn-primary">Διαγραφή</button>     
            </form>
        </div>
        <?php include '../footer.php'; ?>
    </body>
</html>
