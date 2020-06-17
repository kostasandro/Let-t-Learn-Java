<?php
session_start();
include 'validateAdminSession.php';

include_once '../connection.php';
$conn = initDbConnection();

$titleErr = $info = $error = "";
$title = $description = "";
$level_id = "";
$isInsert = false;

if (isset($_GET['level_id']))
    $level_id = $_GET['level_id'];
else
    header('Location: levels.php');

$sql = "SELECT * FROM theory WHERE level_id = " . $level_id;
if ($result = $conn->query($sql)) {
    $count = $result->num_rows;
    if ($count > 0) {
        while ($row = $result->fetch_assoc()) {
            $title =  $row["title"];
            $description = $row["description"];
        }
    } else {
        $isInsert = true;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $level_id = $_POST["level_id"];
    $title = $_POST["title"];
    $description = $_POST["description"];

    if (empty($title))
    {
        $titleErr = "Ο τίτλος απαιτείται";
        $error = "Παρακαλώ συμπληρώστε τα απαιτούμενα πεδία";
    }
    else {
        if ($isInsert) {
            $sql = "INSERT INTO theory (title, description, level_id) 
                    VALUES ('" . $title . "', '" . $description . "', " . $level_id . ")";

            if (mysqli_query($conn, $sql)) {
                $level_id = mysqli_insert_id($conn);
                $info = "Επιτυχής καταχώρηση";
            } else {
                $error = mysqli_error($conn);
            }
        } else {
            $sql = "UPDATE theory 
                    SET title = '" . $title . "',
                    description = '" . $description . "'
                    WHERE level_id = " . $level_id . "
                    ";

            if (mysqli_query($conn, $sql)) {
                $info = "Επιτυχής ενημέρωση";
            } else {
                $error = mysqli_error($conn);
            }
        }
    }
}

?>
<html>

<head>
    <title>Θεωρία</title>
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
            <h2>Θεωρία</h2>
        </div>
        <form method="post" action="levelTheory.php?level_id=<?php echo $level_id ?>">
            <div class="form-group">
                <label for="title">Τίτλος</label> <span class="error">* <?php echo $titleErr; ?></span>
                <input type="text" name="title" class="form-control" id="title" placeholder="πχ. Εισαγωγή" value="<?php echo $title ?>">
            </div>
            <div class="form-group">
                <label for="description">Κείμενο</label>
                <textarea type="number" name="description" class="form-control" id="description" rows="30"><?php echo $description ?></textarea>
            </div>
            <input type="text" name="level_id" value="<?php echo $level_id ?>" hidden>

            <button type="submit" class="btn btn-primary">Αποθήκευση</button>
        </form>
    </div>
    <?php include '../footer.php'; ?>
</body>

</html>