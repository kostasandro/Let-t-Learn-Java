<?php
    session_start();
    include 'validateAdminSession.php';

    include_once '../connection.php';
    $conn = initDbConnection();

    $descriptionErr = $orderingErr = $typeErr = "";
    $info = $error = "";

    $title = $ordering = $description = $level_id = $type_id ="";
    $question_id ="";
    
    if (isset($_GET['level_id'])) 
        $level_id = $_GET['level_id'];
    else
        header('Location: levels.php');

    if (isset($_GET['question_id'])) 
        $question_id = $_GET['question_id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $question_id = $_POST["id"];
        $level_id = $_POST["level_id"];
        $type_id = $_POST["type_id"];
        $ordering = $_POST["ordering"];
        $description = $_POST["description"];

        if (empty($description))
            $descriptionErr = "Η περιγραφή απαιτείται";
        if (empty($ordering))
            $orderingErr = "Η σειρά προτεραιότητας απαιτείται";
        if (empty($type_id))
            $typeErr = "Ο τύπος απαιτείται";

        if($descriptionErr == "" && $orderingErr == "" && $typeErr == "")
        {
            if(empty($question_id)){
                $sql = "INSERT INTO question (type_id, ordering, description, level_id) 
                        VALUES ('".$type_id."', ".$ordering.", '".$description."', ".$level_id.");";
                
                if (mysqli_query($conn, $sql)) {
                    $question_id = mysqli_insert_id($conn);

                    if($type_id == 5) {
                        $sql = "INSERT INTO answer (description, ordering, points, question_id) 
                            VALUES ('Σωστό', 1, 0, ".$question_id."),
                                ('Λάθος', 2, 0, ".$question_id.")";
                        if (mysqli_query($conn, $sql))
                            $info = "Επιτυχής καταχώρηση";
                    }
                    else
                        $info = "Επιτυχής καταχώρηση";
                } else {
                    $error = mysqli_error($conn);
                }             
            }
            else {
                $sql = "UPDATE question 
                        SET type_id = '" . $type_id. "',
                            ordering = " . $ordering. ",
                            description = '" . $description. "'
                        WHERE id = " . $question_id. "
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

    if (!empty($question_id)) {
        $sql = "SELECT Q.* , L.description AS type
                FROM question Q
                LEFT JOIN lookup L ON Q.type_id = L.id 
                WHERE Q.id = " . $question_id;
        if ($result = $conn->query($sql)) {
            $count = $result->num_rows;
            if ($count > 0) {
                while ($row = $result->fetch_assoc()) {
                    $ordering = $row["ordering"];
                    $description = $row["description"];
                    $level_id= $row["level_id"];
                    $type_id= $row["type_id"];
                    $type= $row["type_id"];
                }
            }
        }
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
                <h2>Ερώτηση Quiz</h2>
            </div>
            <form method="post" action="levelQuestionDetails.php?level_id=<?php echo $level_id ?>&question_id=<?php echo $question_id ?>" >
                <div class="form-group">
                    <label for="ordering">Σειρά προτεραιότητας</label> <span class="error">* <?php echo $orderingErr; ?></span>
                    <input type="number" name="ordering" class="form-control" id="ordering" placeholder="πχ. 1" value="<?php echo $ordering ?>">
                </div>
                <div class="form-group">
                    <label for="description">Περιγραφή</label> <span class="error">* <?php echo $descriptionErr; ?></span>
                    <textarea type="number" name="description" class="form-control" id="description" rows="3"><?php echo $description ?></textarea>
                </div>
                <div class="form-group">
                    <label for="type_id">Τύπος</label> <span class="error">* <?php echo $typeErr; ?></span>
                    <select name="type_id" class="form-control" <?php echo empty($question_id) ? "": "disabled"; ?>>
                        <option value="" <?= $type_id ==   '' ? ' selected="selected"' : ''; ?>>-- Επιλογή --</option>
                        <option value="3" <?= $type_id == '3' ? ' selected="selected"' : ''; ?>>Συμπλήρωσης κενού</option>
                        <option value="4" <?= $type_id == '4' ? ' selected="selected"' : ''; ?>>Πολλαπλής επιλογής</option>
                        <option value="5" <?= $type_id == '5' ? ' selected="selected"' : ''; ?>>Σωστό/λάθος</option>
                    </select>
                    <?php if(empty($question_id)) {?>
                        <small class="form-text text-muted">Η επιλογή δε θα μπορεί να αλλάξει</small>
                    <?php }
                    else {?>
                        <input type="text" name="type_id" value="<?php echo $type_id ?>" hidden>
                    <?php }?>
                </div>

                <input type="text" name="level_id" value="<?php echo $level_id ?>" hidden>
                <input type="text" name="id" value="<?php echo $question_id ?>" hidden>
                
                <button type="submit" class="btn btn-primary">Αποθήκευση</button>     
            </form>

        </div>
        <?php include '../footer.php'; ?>
    </body>
</html>
