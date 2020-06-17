<?php
    session_start();
    include 'validateAdminSession.php';

    include_once '../connection.php';
    $conn = initDbConnection();
    
    $info = $error = "";
    
    if (isset($_GET['level_id']) && isset($_GET['exercise_id'])) {
        $level_id = $_GET['level_id'];
        $exercise_id = $_GET['exercise_id'];
    }
    else 
        header('Location: levels.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $sql  = "DELETE FROM exercise WHERE id = " . $exercise_id;   

        if (mysqli_query($conn, $sql)) {
            header('Location: levelExercises.php?level_id='.$level_id);
        } else {
            $error = mysqli_error($conn);
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
                <h2>Διαγραφή λυμένης άσκησης</h2>
            </div>
            <div>
                <p>Θα πραγματοποιήσετε διαγραφή της συγκεκριμένη λυμένης άσκησης. Είστε σίγουρος/η?
                </p>
            </div>
            <form method="post" action="levelExerciseDelete.php?level_id=<?php echo $level_id ?>&exercise_id=<?php echo $exercise_id ?>" >             
                <button type="submit" class="btn btn-primary">Διαγραφή</button>     
            </form>
        </div>
        <?php include '../footer.php'; ?>
    </body>
</html>
