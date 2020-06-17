<?php
    session_start();
    include 'validateAdminSession.php';

    include_once '../connection.php';
    $conn = initDbConnection();
    
    $info = $error = "";
    
    if (isset($_GET['user_id'])) 
        $user_id = $_GET['user_id'];
    else 
        header('Location: users.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $sql  = "DELETE FROM useranswer WHERE user_id = " . $user_id ."; ";    
        $sql .= "DELETE FROM user WHERE id = " . $user_id. "; ";

        if (mysqli_multi_query($conn, $sql)) {
            header('Location: users.php');
        } else {
            $error = mysqli_error($conn);
        }      
    }

?>
<html>
    <head>
        <title>Διαγραφή χρήστη</title>
        <link rel="stylesheet" type="text/css" media="all" href="../Css/styles.css">
        <link rel="stylesheet" type="text/css" media="all" href="../libraries/bootstrap-4.3.1-dist/css/bootstrap.min.css">
        <script src="../libraries/Jquery/jquery-3.3.1.min.js"></script>   
        <script src="../libraries/bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>
    </head>
    <body>

        <?php include '../header.php'; ?>
        
        <?php include '../messages.php'; ?>

        <div class="container page">
            <div>
                <h2>Διαγραφή χρήστη</h2>
            </div>
            <div>
                <p>Θα πραγματοποιήσετε διαγραφή του χρήστη καθώς και όλων των στοιχείων που το αποτελούν 
                    όπως οι απαντήσεις για τα quiz που έχει ολοκληρώσει
                    Είστε σίγουρος/η?
                </p>
            </div>
            <form method="post" action="userDelete.php?user_id=<?php echo $user_id ?>" >
                <button type="submit" class="btn btn-primary">Διαγραφή</button>     
            </form>
        </div>
        <?php include '../footer.php'; ?>
    </body>
</html>
