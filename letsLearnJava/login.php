<?php
// Start the session
session_start();
?>
<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" type="text/css" media="all" href="./Css/styles.css">
        <link rel="stylesheet" type="text/css" media="all" href="./libraries/bootstrap-4.3.1-dist/css/bootstrap.min.css">
        <script src="./libraries/Jquery/jquery-3.3.1.min.js"></script>   
        <script src="./libraries/bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>
    </head>
    <body>

        <?php
        include_once 'connection.php';
        $conn = initDbConnection();

        // καθορισμός μεταβλητών και άδεισμα τιμών
        $emailErr = $passwordErr = "";
        $info = $error = "";
        $password = $email = "";
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            //έλεγχος email
            if (empty($_POST["email"])) {
                $emailErr = "Το email απαιτείται";
            } else {
                $email = test_input($_POST["email"]);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailErr = "Η γραμμογράφηση του email δεν είναι σωστή";
                }
            }
            
            //έλεγχος κωδικού πρόσβασης
            if (empty($_POST["password"])) {
                $passwordErr = "Ο κωδικός πρόσβασης απαιτείται";
            }

            if ($emailErr == "" && $passwordErr == "") {                     
                
                //έλεγχος ύπαρξης του συνδιασμού του email και του κωδικού πρόσβασης
                //αν επιστρέψει αποτελέσματα σημαίνει ότι ο χρήστης υπάρχει και κάνει login επιτυχώς
                $sql = "SELECT * FROM USER WHERE EMAIL= '$_POST[email]' AND PASSWORD='$_POST[password]'";
                if ($result = $conn->query($sql)) {
                    $count = $result->num_rows;
                    if ($count > 0) {
                        $info = "Καλώς ήρθατε";
                        while ($row = $result->fetch_assoc()) {                         
                            $_SESSION["user_first_name"] = $row["Fname"];
                            $_SESSION["user_id"] = $row["id"];
                            $_SESSION["user_role"] = $row["role_id"];            
                        }
                    } else {
                        $error = "Δεν βρέθηκε κάποιος χρήστης με αυτά τα στοιχεία";
                    }
                }

                mysqli_close($conn);
            }
            else{
                $error = "Παρακαλώ συμπληρώστε τα απαιτούμενα πεδία";
            }
        }

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        ?>

        <?php include 'header.php'; ?>
        
        <?php include 'messages.php'; ?>

        <div class="container page">
            <div>
                <h2>Login</h2>
            </div>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" >
                <div class="form-group">
                    <label for="exampleInputEmail1">Email</label> <span class="error">* <?php echo $emailErr; ?></span>
                    <input type="text" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="πχ. andronis.konst@gmail.com">              
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Κωδικός Πρόσβασης</label> <span class="error">* <?php echo $passwordErr; ?></span>
                    <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="πχ. myPass1234">      
                </div>

                <button type="submit" class="btn btn-primary">Υποβολή</button>     
            </form>
            <div class="line">
                Δεν είστε μέλος ακόμα;
                <a href="Register.php">Δημιουργία λογαριασμού</a>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    </body>
</html>
