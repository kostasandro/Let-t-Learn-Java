<?php
// Start the session
session_start();

include_once '../connection.php';
$conn = initDbConnection();

if (isset($_GET['user_id'])) 
    $user_id = $_GET['user_id'];
else 
    header('Location: users.php');

// καθορισμός μεταβλητών και άδεισμα τιμών
$nameErr = $SurnameErr = $emailErr = $passwordErr = $roleErr = "";
$name = $surname = $password = $cpassword = $email = $gender = "";
$info = $error = "";
$password_change = 0;

$sql = "SELECT * FROM user WHERE id = ".$user_id  ;
if ($result = $conn->query($sql)) {
    $count = $result->num_rows;
    if ($count > 0) {
        while ($row = $result->fetch_assoc()) {
            $name = $row["Fname"];
            $surname = $row["Lname"];
            $email = $row["email"];
            $role_id = $row["role_id"];
        }
    }
}
    
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $email =$_POST["email"];
    $password = $_POST["password"];
    $role_id = $_POST["role_id"];
    $password_change = empty($_POST["password_change"]) ? 0 : 1; 

    //έλεγχος ονόματος
    if (empty($name)) {
        $nameErr = "Το όνομα απαιτείται";
    } else {
        $name = test_input($name);
        if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            $nameErr = "Επιτρέπονται μόνο γράμματα και ο κενός χαρακτήρας";
        }
    }

    //έλεγχος επώνυμου
    if (empty($surname)) {
        $SurnameErr = "Το επώνυμο απαιτείται";
    } else {
        $surname = test_input($surname);
        if (!preg_match("/^[a-zA-Z ]*$/", $surname)) {
            $SurnameErr = "Επιτρέπονται μόνο γράμματα και ο κενός χαρακτήρας";
        }
    }

    //έλεγχος email
    if (empty($email)) {
        $emailErr = "Το email απαιτείται";
    } else {
        $email = test_input($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Η γραμμογράφηση του email δεν είναι σωστή";
        }
    }

    if($password_change == 1){
        //έλεγχος κωδικού πρόσβασης και της επιβεβαίωσης
        if (empty($password)) {
            $passwordErr = "Ο κωδικός πρόσβασης απαιτείται";
        } else {
            $password = test_input($password);
            if (strlen($password) < 8) {
                $passwordErr = "Ο κωδικός πρόσβασης πρέπει να αποτελείται από τουλάχιστον 8 χαρακτήρες!";
            } elseif (!preg_match("#[0-9]+#", $password)) {
                $passwordErr = "Ο κωδικός πρόσβασης πρέπει να περιέχει τουλάχιστον έναν αριθμό!";
            } elseif (!preg_match("#[A-Z]+#", $password)) {
                $passwordErr = "Ο κωδικός πρόσβασης πρέπει να περιέχει τουλάχιστον ένα κεφαλαίο χαρακτήρα!";
            } elseif (!preg_match("#[a-z]+#", $password)) {
                $passwordErr = "Ο κωδικός πρόσβασης πρέπει να περιέχει τουλάχιστον έναν πεζό χαρακτήρα!";
            }
        }
    }
    if ($nameErr == "" && $SurnameErr == "" && $emailErr == "" && $passwordErr == ""  && $roleErr == "") {
    
        if ($result = $conn->query("SELECT * FROM user WHERE email= '" . $email . "'")) {
            $count = $result->num_rows;
            if ($count <= 1) {

                if($password_change == 1){
                    $sql = "UPDATE user 
                            SET Fname = '" . $name. "',
                                Lname = '" . $surname. "',
                                email = '" . $email. "',
                                password = '" . $password. "',
                                role_id = '" . $role_id. "'
                            WHERE id = ". $user_id;
                }
                else{
                    $sql = "UPDATE user 
                    SET Fname = '" . $name. "',
                        Lname = '" . $surname. "',
                        email = '" . $email. "',
                        role_id = '" . $role_id. "'
                    WHERE id = ". $user_id;
                }
                
                if (mysqli_query($conn, $sql)) {
                    $info = "Επιτυχής ενημέρωση";;
                } else {
                    $error = mysqli_error($conn);
                }
            } else {
                $emailErr = "Το email που δώσατε χρησιμοποιείται ήδη";
                $error = "Παρακαλώ συμπληρώστε τα σωστά τα απαιτούμενα πεδία";
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

<html>
    <head>
        <title>Επεξεργασία λογαριασμού</title>
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
                <h2>Επεξεργασία λογαριασμού</h2>
            </div>
            <form method="post" action="userDetails.php?user_id=<?php echo $user_id ?>" >
                <div class="form-group row">
                    <label for="name" class="col-4 col-form-label">Όνομα <span class="error">*</span> </label>
                    <div class="col-8">
                        <input type="text" name="name" class="form-control" id="name" value="<?php echo $name; ?>" placeholder="πχ. Κωνσταντίνος">
                        <span class="error"> <?php echo $nameErr; ?></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="surname" class="col-4 col-form-label">Επώνυμο <span class="error">*</span> </label>
                    <div class="col-8">
                        <input type="text" name="surname" class="form-control" id="surname" value="<?php echo $surname; ?>" placeholder="πχ. Ανδρονής">
                        <span class="error"> <?php echo $SurnameErr; ?></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="email" class="col-4 col-form-label">E-mail <span class="error">*</span></label>
                    <div class="col-8">
                        <input type="text" name="email" class="form-control" id="email" value="<?php echo $email; ?>" placeholder="πχ. andronis.konst@gmail.com">
                        <span class="error"> <?php echo $emailErr; ?></span>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-4">Αλλαγή Password</div>
                    <div class="col-sm-8">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="password_change" name="password_change" 
                            <?php echo ($password_change == 1)? 'checked' : '';?>>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="password" class="col-4 col-form-label">Κωδικός Πρόσβασης <span class="error">*</span></label>
                    <div class="col-8">
                        <input type="password" name="password" class="form-control" id="password" value="<?php echo $password; ?>" placeholder="πχ. myPass1234">
                        <span class="error"><?php echo $passwordErr; ?></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="role_id"  class="col-4 col-form-label">Ρόλος<span class="error"> * <?php echo $roleErr; ?></span></label> 
                    <div class="col-8">
                        <select name="role_id" class="form-control">
                            <option value="1" <?= $role_id == '1' ? ' selected="selected"' : ''; ?>>Admin</option>
                            <option value="2" <?= $role_id == '2' ? ' selected="selected"' : ''; ?>>Χρήστης</option>
                        </select>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">Υποβολή</button> <br>
                </div>
            </form>
        </div>
        <?php include '../footer.php'; ?>
    </body>
</html>
