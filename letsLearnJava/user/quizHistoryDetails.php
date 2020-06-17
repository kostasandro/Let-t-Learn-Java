
<?php 
    session_start();

    include 'validateUserSession.php';
    
    include_once '../connection.php';
    $conn = initDbConnection();

    if (isset($_GET['questionnaire_id'])) {
        $questionnaire_id = $_GET['questionnaire_id'];
    } else {
        $message = "Fail";
    }

    $quizHistoryDetails_data = getData($conn, $questionnaire_id);

    $successPoints = $totalQuizPoints = 0;
    foreach ($quizHistoryDetails_data as $data){
        $successPoints += $data->points;
        $totalQuizPoints += $data->totalPoints;
    }


    function getData($conn, $questionnaire_id){
        $quizHistoryDetails_data = array();
        $quizLevel = 0;

        $sql = "SELECT * FROM useranswer WHERE questionnaire_id = " . $questionnaire_id;
        if ($result = $conn->query($sql)) {
            $count = $result->num_rows;
            if ($count > 0) {
                while ($row = $result->fetch_assoc()) {
                    $quizLevel = (int)$row["level_id"];
                    $quizLevel = (int)$row["level_id"];
                }
            }
        }

        $sql = "SELECT Q.description, UA.questionnaire_id, UA.timestamp, IFNULL(SUM(A.points),0) points,
                        (SELECT SUM(points) 
                            FROM answer AT 
                            INNER JOIN question QT on AT.question_id = QT.id
                            WHERE QT.id = Q.id) totalPoints
                FROM useranswer UA
                INNER JOIN question Q on UA.question_id = Q.id
                LEFT JOIN answer A ON (    (A.id = UA.answer_id 
                                            AND UA.answer_id IS NOT NULL)
                                        OR  (A.description = UA.answer_text 
                                            AND A.question_id = UA.question_id 
                                            AND UA.answer_id IS NULL)
                                        )
                WHERE UA.user_id = " . $_SESSION["user_id"] . "
                    AND UA.questionnaire_id = " . $questionnaire_id . "
                GROUP BY Q.description
                ORDER BY Q.ordering" ;

        $sql = "SELECT Q.description, 
                        IFNULL((SELECT SUM(A1.points) 
                                FROM useranswer UA
                                INNER JOIN question Q1 on UA.question_id = Q1.id
                                LEFT JOIN answer A1 ON (    (A1.id = UA.answer_id 
                                                            AND UA.answer_id IS NOT NULL)
                                                        OR  (A1.description = UA.answer_text 
                                                            AND A1.question_id = UA.question_id 
                                                            AND UA.answer_id IS NULL)
                                                        )
                                WHERE UA.user_id = " . $_SESSION["user_id"] . "
                                    AND UA.questionnaire_id = " . $questionnaire_id . "
                                    AND Q1.id = Q.id
                                )
                            ,0) points,
                        (SELECT SUM(points) 
                            FROM answer AT 
                            INNER JOIN question QT on AT.question_id = QT.id
                            WHERE QT.id = Q.id) totalPoints
                FROM question Q
                LEFT JOIN answer A ON Q.id = A.question_id
                WHERE Q.level_id = " . $quizLevel. "
                GROUP BY Q.id";
        if ($result = $conn->query($sql)) {
            $count = $result->num_rows;
            if ($count > 0) {
                while ($row = $result->fetch_assoc()) {
                    $quizSummary = new QuizDetailsSummary();
                    $quizSummary->question = $row["description"];
                    $quizSummary->points = $row["points"];
                    $quizSummary->totalPoints = $row["totalPoints"];
                    $quizHistoryDetails_data[] = $quizSummary;
                }
            }
        }

        return $quizHistoryDetails_data;
    }

    class QuizDetailsSummary
    {
        public $question;
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
        <div class="row">
            <div class="col">
                <a href="quizHistory.php" class="small">
                    <svg class="bi bi-arrow-bar-left" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M5.854 4.646a.5.5 0 00-.708 0l-3 3a.5.5 0 000 .708l3 3a.5.5 0 00.708-.708L3.207 8l2.647-2.646a.5.5 0 000-.708z" clip-rule="evenodd"/>
                        <path fill-rule="evenodd" d="M10 8a.5.5 0 00-.5-.5H3a.5.5 0 000 1h6.5A.5.5 0 0010 8zm2.5 6a.5.5 0 01-.5-.5v-11a.5.5 0 011 0v11a.5.5 0 01-.5.5z" clip-rule="evenodd"/>
                    </svg>
                    Πίσω
                </a>
            </div>
        </div>

        <div class="Login">
            <h2>Το ιστορικό μου</h2>
        </div>
        <div class="row justify-content-center text-center ">
            <div class="col-4 points">
                <span class="success_points"><?php echo $successPoints?></span>   
                <span class="total_points">/</span>
                <span class="total_points"><?php echo $totalQuizPoints?></span>    
            </div>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Ερώτηση</th>               
                    <th scope="col">Ποσοστό επιτυχίας</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($quizHistoryDetails_data as $key=>$quiz) { ?>
                    <tr>
                        <td><?php echo $quiz->question?></td>
                        <td><?php echo $quiz->points . "/" . $quiz->totalPoints?></td>
                    </tr>
                <?php } ?> 
            </tbody>
        </table>

    </div> 
    
    <?php include '../footer.php'; ?>
</body>

</html>