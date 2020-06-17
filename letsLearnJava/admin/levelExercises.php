<?php 
    session_start();

    include 'validateAdminSession.php'; 
    
    include_once '../connection.php';
    $conn = initDbConnection();

    if (isset($_GET['level_id'])) 
        $level_id = $_GET['level_id'];
    else
        header('Location: levels.php');

    $exercises = getData($conn, $level_id);

    function getData($conn, $level_id){
        $exercises = array();

        $sql = "SELECT * FROM exercise WHERE level_id = ". $level_id ." ORDER BY ordering ASC" ;
        if ($result = $conn->query($sql)) {
            $count = $result->num_rows;
            if ($count > 0) {
                while ($row = $result->fetch_assoc()) {
                    $exercise = new Exercise();
                    $exercise->id = $row["id"];
                    $exercise->title = $row["title"];
                    $exercise->ordering = $row["ordering"];
                    $exercise->description = $row["description"];
                    $exercises[] = $exercise;
                }
            }
        }
        
        return $exercises;
    }

    class Exercise
    {
        public $id;
        public $title;
        public $ordering;
        public $description;
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
            <h2>Λυμένες ασκήσεις</h2>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Σειρά</th>               
                    <th scope="col">Τίτλος</th>
                    <th scope="col">Ενέργεια</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($exercises as $key=>$exercise) { ?>
                    <tr>
                        <td><?php echo $exercise->ordering?></td>
                        <td><?php echo $exercise->title?></td>
                        <td>
                            <a href="levelExerciseDetails.php?level_id=<?php echo $level_id ?>&exercise_id=<?php echo $exercise->id?>" class="small">Επεξεργασία</a>
                            | 
                            <a href="levelExerciseDelete.php?level_id=<?php echo $level_id ?>&exercise_id=<?php echo $exercise->id?>" class="small">Διαγραφή</a>
                        </td>
                    </tr>
                <?php } ?> 
            </tbody>
            <tfoot>
                <td></td>
                <td></td>
                <td><a href="levelExerciseDetails.php?level_id=<?php echo $level_id ?>" class="small">Προσθήκη</a></td>

            </tfoot>
        </table>

    </div> 
    
    <?php include '../footer.php'; ?>
</body>

</html>