<?php 
    session_start();

    include 'validateAdminSession.php'; 
    
    include_once '../connection.php';
    $conn = initDbConnection();

    $levels = getData($conn);

    function getData($conn){
        $levels = array();

        $sql = "SELECT * FROM level ORDER BY ordering ASC" ;
        if ($result = $conn->query($sql)) {
            $count = $result->num_rows;
            if ($count > 0) {
                while ($row = $result->fetch_assoc()) {
                    $level = new Level();
                    $level->id = $row["id"];
                    $level->title = $row["title"];
                    $level->ordering = $row["ordering"];
                    $level->published = $row["published"];
                    $levels[] = $level;
                }
            }
        }
        
        return $levels;
    }

    class Level
    {
        public $id;
        public $title;
        public $ordering;
        public $published;
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
    

    <div class="container page"> 
        <div class="Login">
            <h2>Levels</h2>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Σειρά</th>               
                    <th scope="col">Τίτλος</th>
                    <th scope="col">Ενεργό</th>
                    <th scope="col">Μετάβαση</th>
                    <th scope="col">Ενέργεια</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($levels as $key=>$level) { ?>
                    <tr>
                        <td><?php echo $level->ordering?></td>
                        <td><?php echo $level->title?></td>
                        <td><?php echo ($level->published == 1) ? "Ναι": "Όχι"; ?></td>                       
                        <td>
                            <a href="levelTheory.php?level_id=<?php echo $level->id?>" class="small">Θεωρία</a>
                            |
                            <a href="levelExercises.php?level_id=<?php echo $level->id?>" class="small">Λυμένες Ασκήσεις</a>
                            |
                            <a href="levelQuestions.php?level_id=<?php echo $level->id?>" class="small">Ερωτήσεις Quiz</a>
                        </td>
                        <td>
                            <a href="levelDetails.php?level_id=<?php echo $level->id?>" class="small">Επεξεργασία</a>
                            | 
                            <a href="levelDelete.php?level_id=<?php echo $level->id?>" class="small">Διαγραφή</a>
                        </td>
                    </tr>
                <?php } ?> 
            </tbody>
            <tfoot>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><a href="levelDetails.php" class="small">Προσθήκη</a></td>
            </tfoot>
        </table>

    </div> 
    
    <?php include '../footer.php'; ?>
</body>

</html>