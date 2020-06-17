<?php
    session_start();
    include 'validateAdminSession.php';

    include_once '../connection.php';
    $conn = initDbConnection();

    $titleErr = $orderingErr = "";
    $info = $error = "";

    $title = $ordering = $description = $solution = $level_id = "";
    $exercise_id ="";
    
    if (isset($_GET['level_id'])) 
        $level_id = $_GET['level_id'];
    else
        header('Location: levels.php');

    if (isset($_GET['exercise_id'])) 
        $exercise_id = $_GET['exercise_id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $exercise_id = $_POST["id"];
        $level_id = $_POST["level_id"];
        $title = $_POST["title"];
        $ordering = $_POST["ordering"];
        $description = $_POST["description"];
        $solution = $_POST["solution"];

        if (empty($title))
            $titleErr = "Ο τίτλος απαιτείται";
        if (empty($ordering))
            $orderingErr = "Η σειρά προτεραιότητας απαιτείται";
        
        if($titleErr == "" && $orderingErr == "" )
        {
            if(empty($exercise_id)){
                $sql = "INSERT INTO exercise (title, ordering, description, solution, level_id) 
                        VALUES ('".$title."', ".$ordering.", '".$description."', '".$solution."', ".$level_id.")";
                   
                if (mysqli_query($conn, $sql)) {
                    $exercise_id = mysqli_insert_id($conn);
                    $info = "Επιτυχής καταχώρηση";
                } else {
                    $error = mysqli_error($conn);
                }             
            }
            else {
                $sql = "UPDATE exercise 
                        SET title = '" . $title. "',
                            ordering = " . $ordering. ",
                            description = '" . $description. "',
                            solution = '" . $solution. "'
                        WHERE id = " . $exercise_id. "
                        ";

                if (mysqli_query($conn, $sql)) {
                    $info = "Επιτυχής ενημέρωση";
                } else {
                    $error = mysqli_error($conn);
                }   
            }
        }
        else {
            $error = "Παρακαλώ συμπληρώστε τα απαιτούμενα πεδία";
        } 
    }

    if (!empty($exercise_id)) {
        $sql = "SELECT * FROM exercise WHERE id = " . $exercise_id;
        if ($result = $conn->query($sql)) {
            $count = $result->num_rows;
            if ($count > 0) {
                while ($row = $result->fetch_assoc()) {
                    $title =  $row["title"];
                    $ordering = $row["ordering"];
                    $description = $row["description"];
                    $solution = $row["solution"];
                    $level_id= $row["level_id"];
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
                <h2>Στοιχεία λυμένης άσκησης</h2>
            </div>
            <form method="post" action="levelExerciseDetails.php?level_id=<?php echo $level_id ?>&exercise_id=<?php echo $exercise_id ?>" >
                <div class="form-group">
                    <label for="title">Τίτλος</label> <span class="error">* <?php echo $titleErr; ?></span>
                    <input type="text" name="title" class="form-control" id="title" placeholder="πχ. Εισαγωγή" value="<?php echo $title ?>">
                </div>
                <div class="form-group">
                    <label for="ordering">Σειρά προτεραιότητας</label> <span class="error">* <?php echo $orderingErr; ?></span>
                    <input type="number" name="ordering" class="form-control" id="ordering" placeholder="πχ. 1" value="<?php echo $ordering ?>">
                </div>
                <div class="form-group">
                    <label for="description">Εκφώνιση</label>
                    <textarea type="number" name="description" class="form-control" id="description" rows="3"><?php echo $description ?></textarea>
                </div>
                <div class="form-group">
                    <label for="solution">Λύση</label>
                    <textarea type="number" name="solution" class="form-control" id="solution" rows="30"><?php echo $solution ?></textarea>
                </div>
                <input type="text" name="level_id" value="<?php echo $level_id ?>" hidden>
                <input type="text" name="id" value="<?php echo $exercise_id ?>" hidden>
                
                <button type="submit" class="btn btn-primary">Αποθήκευση</button>     
            </form>
        </div>
        <?php include '../footer.php'; ?>
    </body>
</html>
