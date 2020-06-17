<?php
    session_start();
    include 'validateAdminSession.php';

    include_once '../connection.php';
    $conn = initDbConnection();

    $descriptionErr = $orderingErr = $pointsErr = "";
    $info = $error = "";

    $description = $ordering = $level_id = $points ="";
    $question_id = $answer_id ="";
    
    if (isset($_GET['level_id']) && isset($_GET['question_id'])) {
        $level_id = $_GET['level_id'];
        $question_id = $_GET['question_id'];
    }
    else 
        header('Location: levels.php');

    if (isset($_GET['answer_id'])) 
        $answer_id = $_GET['answer_id'];
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $answer_id = $_POST["id"];
        $question_id = $_POST["question_id"];
        $level_id = $_POST["level_id"];
        $points = $_POST["points"];
        $ordering = $_POST["ordering"];
        $description = $_POST["description"];

        if (empty($description))
            $descriptionErr = "Η περιγραφή απαιτείται";
        if (empty($ordering))
            $orderingErr = "Η σειρά προτεραιότητας απαιτείται";
        if (empty($points))
            $points=0;

        if($descriptionErr == "" && $orderingErr == "" && $pointsErr == "")
        {
            if(empty($answer_id)){
                $sql = "INSERT INTO answer (points, ordering, description, question_id) 
                        VALUES ('".$points."', ".$ordering.", '".$description."', ".$question_id.");";
                
                if (mysqli_query($conn, $sql)) {
                    $answer_id = mysqli_insert_id($conn);
                    $info = "Επιτυχής καταχώρηση";
                } else {
                    $error = mysqli_error($conn);
                }             
            }
            else {
                $sql = "UPDATE answer 
                        SET points = " . $points. ",
                            ordering = " . $ordering. ",
                            description = '" . $description. "'
                        WHERE id = " . $answer_id. "
                        ";

                if (mysqli_query($conn, $sql)) {
                    $info = "Επιτυχής ενημέρωση";
                } else {
                    $error = mysqli_error($conn);
                }   
            }
            
            
        }
        else{
            $error = "Παρακαλώ συμπληρώστε τα απαιτούμενα πεδία";
        }
        
    }

    if (!empty($answer_id)) {
        $sql = "SELECT * FROM answer WHERE id =" . $answer_id;
        if ($result = $conn->query($sql)) {
            $count = $result->num_rows;
            if ($count > 0) {
                while ($row = $result->fetch_assoc()) {
                    $ordering = $row["ordering"];
                    $description = $row["description"];
                    $points= (int) $row["points"];
                }
            }
        }
    }

    $question_type = getQuestionType($conn, $question_id);

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

?>
<html>
    <head>
        <title>Login</title>
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
                <h2>Απάντηση ερώτησης Quiz</h2>
            </div>
            <form method="post" action="levelAnswerDetails.php?level_id=<?php echo $level_id ?>&question_id=<?php echo $question_id ?>&answer_id=<?php echo $answer_id ?>" >
                <div class="form-group">
                    <label for="ordering">Σειρά προτεραιότητας</label> <span class="error">* <?php echo $orderingErr; ?></span>
                    <input type="number" name="ordering" class="form-control" id="ordering" placeholder="πχ. 1" value="<?php echo $ordering ?>">
                </div>
                <div class="form-group">
                    <label for="description">Περιγραφή</label> <span class="error">* <?php echo $descriptionErr; ?></span>
                    <textarea type="number" name="description" class="form-control" id="description" rows="3" <?php echo ($question_type != 5) ? "": "disabled"; ?>><?php echo $description ?></textarea>
                </div>
                <div class="form-group">
                    <label for="points">Πόντοι</label> <span class="error">* <?php echo $pointsErr; ?></span>
                    <input type="number" name="points" class="form-control" id="points" placeholder="πχ. 1" value="<?php echo $points ?>">
                </div>

                <?php if($question_type == 5) {?>
                    <input type="text" name="description" value="<?php echo $description ?>" hidden>
                <?php }?>

                <input type="text" name="level_id" value="<?php echo $level_id ?>" hidden>
                <input type="text" name="question_id" value="<?php echo $question_id ?>" hidden>
                <input type="text" name="id" value="<?php echo $answer_id ?>" hidden>

                <button type="submit" class="btn btn-primary">Αποθήκευση</button>     
            </form>

        </div>
        <?php include '../footer.php'; ?>
    </body>
</html>
