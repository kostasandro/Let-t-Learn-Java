<?php 
    session_start();

    include 'validateUserSession.php';
    
    include_once '../connection.php';
    $conn = initDbConnection();

    $quizHistory_data = getData($conn);

    function getData($conn){
        $quizHistory_data = array();

        $sql = "SELECT Q.level_id, UA.questionnaire_id, UA.timestamp, IFNULL(SUM(A.points),0) points,
                        (SELECT SUM(points) 
                            FROM answer AT 
                            INNER JOIN question QT on AT.question_id = QT.id
                            WHERE QT.level_id = Q.level_id) totalPoints
                FROM useranswer UA
                INNER JOIN question Q on UA.question_id = Q.id
                LEFT JOIN answer A ON (    (A.id = UA.answer_id 
                                            AND UA.answer_id IS NOT NULL)
                                        OR  (A.description = UA.answer_text 
                                            AND A.question_id = UA.question_id 
                                            AND UA.answer_id IS NULL)
                                        )
                WHERE UA.user_id = " . $_SESSION["user_id"] . "
                GROUP BY UA.questionnaire_id, UA.timestamp, Q.level_id
                ORDER BY timestamp DESC" ;
        if ($result = $conn->query($sql)) {
            $count = $result->num_rows;
            if ($count > 0) {
                while ($row = $result->fetch_assoc()) {
                    $quizSummary = new QuizSummary();
                    $quizSummary->level_id = $row["level_id"];
                    $quizSummary->questionnaire_id = $row["questionnaire_id"];
                    $quizSummary->timestamp = $row["timestamp"];
                    $quizSummary->points = $row["points"];
                    $quizSummary->totalPoints = $row["totalPoints"];
                    $quizHistory_data[] = $quizSummary;
                }
            }
        }
        
        return $quizHistory_data;
    }

    class QuizSummary
    {
        public $level_id;
        public $questionnaire_id;
        public $timestamp;
        public $points;
        public $totalPoints;
    }
?>

<html>

<head>
    <title>Quiz History- Let's learn JAVA</title>
    <link rel="stylesheet" type="text/css" media="all" href="../Css/styles.css">
    <link rel="stylesheet" type="text/css" media="all" href="../libraries/bootstrap-4.3.1-dist/css/bootstrap.min.css">
    <script src="../libraries/Jquery/jquery-3.3.1.min.js"></script>
    <script src="../libraries/bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>
</head>

<body>

    <?php include '../header.php'; ?>
    

    <div class="container page"> 
        <div class="Login">
            <h2>Το ιστορικό μου</h2>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Level</th>               
                    <th scope="col">Ημερομηνία</th>
                    <th scope="col">Ποσοστό επιτυχίας</th>
                    <th scope="col">Ενέργεια</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($quizHistory_data as $key=>$quiz) { ?>
                    <tr>
                        <td><?php echo $quiz->level_id?></td>
                        <td><?php echo $quiz->timestamp?></td>
                        <td><?php echo $quiz->points . "/" . $quiz->totalPoints?></td>
                        <td><a href="quizHistoryDetails.php?questionnaire_id=<?php echo $quiz->questionnaire_id?>" class="small">Προβολή αποτελεσμάτων</a></td>
                    </tr>
                <?php } ?> 
            </tbody>
        </table>

    </div> 
    
    <?php include '../footer.php'; ?>
</body>

</html>