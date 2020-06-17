<?php 
    session_start();

    include 'validateAdminSession.php'; 
    
    include_once '../connection.php';
    $conn = initDbConnection();

    $level_id = $question_id = "";

    if (isset($_GET['level_id']) && isset($_GET['question_id'])) {
        $level_id = $_GET['level_id'];
        $question_id = $_GET['question_id'];
    }
    else 
        header('Location: levels.php');

    $answers = getData($conn, $question_id);

    $question_type = getQuestionType($conn, $question_id);

    function getData($conn, $question_id){
        $answers = array();

        $sql = "SELECT * FROM answer WHERE question_id = ". $question_id ." ORDER BY ordering ASC" ;
        if ($result = $conn->query($sql)) {
            $count = $result->num_rows;
            if ($count > 0) {
                while ($row = $result->fetch_assoc()) {
                    $question = new Answer();
                    $question->id = $row["id"];
                    $question->description = $row["description"];
                    $question->ordering = $row["ordering"];
                    $question->points = (int)$row["points"];
                    $answers[] = $question;
                }
            }
        }
        
        return $answers;
    }

    function getQuestionType($conn, $question_id)
    {
        $questionType="";
        
        $sql = "SELECT * FROM question WHERE id = ". $question_id;
        if ($result = $conn->query($sql)) {
            $count = $result->num_rows;
            if ($count > 0) {
                while ($row = $result->fetch_assoc()) {
                    $questionType = (int)$row["type_id"];
                }
            }
        }
        
        return $questionType;
    }

    class Answer
    {
        public $id;
        public $ordering;
        public $description;
        public $points;
    }
?>

<html>

<head>
    <title>Levels management - Let's learn JAVA</title>
    <link rel="stylesheet" type="text/css" media="all" href="../Css/styles.css">
    <link rel="stylesheet" type="text/css" media="all" href="../libraries/bootstrap-4.3.1-dist/css/bootstrap.min.css">
    <script src="../libraries/Jquery/jquery-3.3.1.min.js"></script>
    <script src="../libraries/bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>
</head>

<body>

    <?php include '../header.php'; ?>
    
    <?php include './levelMenu.php'; ?>

    <div class="container page"> 

        <div class="Login">
            <h2>Απαντήσεις ερώτησης Quiz</h2>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Σειρά</th>               
                    <th scope="col">Περιγραφή</th>
                    <th scope="col">Πόντοι</th>
                    <th scope="col">Ενέργεια</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($answers as $key=>$answer) { ?>
                    <tr>
                        <td><?php echo $answer->ordering?></td>
                        <td><?php echo $answer->description?></td>
                        <td><?php echo $answer->points?></td>
                        <td>
                            <a href="levelAnswerDetails.php?level_id=<?php echo $level_id ?>&question_id=<?php echo $question_id?>&answer_id=<?php echo $answer->id?>" class="small">Επεξεργασία</a>
                            <?php if($question_type != 5) { ?>
                                | 
                                <a href="levelAnswerDelete.php?level_id=<?php echo $level_id ?>&question_id=<?php echo $question_id?>&answer_id=<?php echo $answer->id?>" class="small">Διαγραφή</a>
                            <?php } ?> 
                        </td>
                    </tr>
                <?php } ?> 
            </tbody>
            <?php if($question_type != 5) { ?>
            <tfoot>
                <td></td>
                <td></td>
                <td></td>
                <td><a href="levelAnswerDetails.php?level_id=<?php echo $level_id ?>&question_id=<?php echo $question_id?>" class="small">Προσθήκη</a></td>
            </tfoot>
            <?php } ?> 
        </table>

    </div> 
    
    <?php include '../footer.php'; ?>
</body>

</html>