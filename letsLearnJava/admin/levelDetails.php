<?php
    session_start();
    include 'validateAdminSession.php';

    include_once '../connection.php';
    $conn = initDbConnection();

    $titleErr = $orderingErr = "";
    $info = $error = "";

    $title = $ordering = $published = "";
    $level_id ="";
    
    if (isset($_GET['level_id'])) 
        $level_id = $_GET['level_id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $level_id = $_POST["id"];
        $title = $_POST["title"];
        $ordering = $_POST["ordering"];
        $published = (isset($_POST['published'])) ? $_POST["published"] : 0;

        if (empty($title)){
            $titleErr = "Ο τίτλος απαιτείται";
        }
        if (empty($ordering)){
            $orderingErr = "Η σειρά προτεραιότητας απαιτείται";
        }

        if($titleErr == "" && $orderingErr == "") {
            if(empty($level_id)){
                $sql = "INSERT INTO level (title, ordering, published) 
                        VALUES ('".$title."', ".$ordering.", ".$published.")";
                   
                if (mysqli_query($conn, $sql)) {
                    $level_id = mysqli_insert_id($conn);
                    $info = "Επιτυχής καταχώρηση";
                } else {
                    $error = mysqli_error($conn);
                }             
            }
            else {
                $sql = "UPDATE level 
                        SET title = '" . $title. "',
                            ordering = " . $ordering. ",
                            published = " . $published. "
                        WHERE id = " . $level_id. "
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

    if (!empty($level_id)) {
        $sql = "SELECT * FROM level WHERE id = " . $level_id;
        if ($result = $conn->query($sql)) {
            $count = $result->num_rows;
            if ($count > 0) {
                while ($row = $result->fetch_assoc()) {
                    $title =  $row["title"];
                    $ordering = $row["ordering"];
                    $published =  (int) $row["published"];
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
                <h2>Στοιχεία level</h2>
            </div>
            <form method="post" action="levelDetails.php?level_id=<?php echo $level_id ?>" >
                <div class="form-group">
                    <label for="title">Τίτλος</label> <span class="error">* <?php echo $titleErr; ?></span>
                    <input type="text" name="title" class="form-control" id="title" placeholder="πχ. Εισαγωγή" value="<?php echo $title ?>">              
                </div>
                <div class="form-group">
                    <label for="ordering">Σειρά προτεραιότητας</label> <span class="error">* <?php echo $orderingErr; ?></span>
                    <input type="number" name="ordering" class="form-control" id="ordering" placeholder="πχ. 1" value="<?php echo $ordering ?>">      
                </div>
                <div class="form-group">
                    <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="published" name="published" value="1" <?php echo ($published == 1)? "checked": ""; ?>>
                    <label class="form-check-label" for="published">
                        Ενεργό
                    </label>
                    </div>
                </div>
                <input type="text" name="id" value="<?php echo $level_id ?>" hidden>
                
                <button type="submit" class="btn btn-primary">Αποθήκευση</button>     
            </form>
        </div>
        <?php include '../footer.php'; ?>
    </body>
</html>
