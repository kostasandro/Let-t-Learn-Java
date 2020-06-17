<?php 
    session_start();

    include 'validateAdminSession.php'; 
    
    include_once '../connection.php';
    $conn = initDbConnection();

    if (isset($_GET['level_id'])) 
        $level_id = $_GET['level_id'];
    else
        header('Location: levels.php');

    $questions = getData($conn, $level_id);

    function getData($conn, $level_id){
        $questions = array();

        $sql = "SELECT Q.* , L.description AS type
                FROM question Q
                LEFT JOIN lookup L ON Q.type_id = L.id
                WHERE Q.level_id = ". $level_id ." ORDER BY Q.ordering ASC" ;
        if ($result = $conn->query($sql)) {
            $count = $result->num_rows;
            if ($count > 0) {
                while ($row = $result->fetch_assoc()) {
                    $question = new Question();
                    $question->id = $row["id"];
                    $question->description = $row["description"];
                    $question->ordering = $row["ordering"];
                    $question->type = $row["type"];
                    $questions[] = $question;
                }
            }
        }
        
        return $questions;
    }

    class Question
    {
        public $id;
        public $ordering;
        public $description;
        public $type;
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
            <h2>Ερωτήσεις Quiz</h2>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Σειρά</th>               
                    <th scope="col">Περιγραφή</th>
                    <th scope="col">Τύπος</th>
                    <th scope="col">Μετάβαση</th>
                    <th scope="col">Ενέργεια</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($questions as $key=>$question) { ?>
                    <tr>
                        <td><?php echo $question->ordering?></td>
                        <td><?php echo $question->description?></td>
                        <td><?php echo $question->type?></td>
                        <td>
                            <a href="levelAnswers.php?level_id=<?php echo $level_id ?>&question_id=<?php echo $question->id?>" class="small">Απαντήσεις</a>
                        </td>
                        <td>
                            <a href="levelQuestionDetails.php?level_id=<?php echo $level_id ?>&question_id=<?php echo $question->id?>" class="small">Επεξεργασία</a>
                            | 
                            <a href="levelQuestionDelete.php?level_id=<?php echo $level_id ?>&question_id=<?php echo $question->id?>" class="small">Διαγραφή</a>
                        </td>
                    </tr>
                <?php } ?> 
            </tbody>
            <tfoot>
                <td></td>
                <td></td>
                <td></td>
                <td><a href="levelQuestionDetails.php?level_id=<?php echo $level_id ?>" class="small">Προσθήκη</a></td>
            </tfoot>
        </table>

    </div> 
    
    <?php include '../footer.php'; ?>
</body>

</html>