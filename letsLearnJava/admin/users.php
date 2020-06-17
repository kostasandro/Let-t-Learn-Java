<?php 
    session_start();

    include 'validateAdminSession.php'; 
    
    include_once '../connection.php';
    $conn = initDbConnection();

    $users = getData($conn);

    function getData($conn){
        $users = array();

        $sql = "SELECT * FROM user" ;
        if ($result = $conn->query($sql)) {
            $count = $result->num_rows;
            if ($count > 0) {
                while ($row = $result->fetch_assoc()) {
                    $user = new User();
                    $user->id = $row["id"];
                    $user->fname = $row["Fname"];
                    $user->lname = $row["Lname"];
                    $user->email = $row["email"];
                    $user->role = $row["role_id"];
                    $users[] = $user;
                }
            }
        }
        
        return $users;
    }

    class User
    {
        public $id;
        public $fname;
        public $lname;
        public $email;
        public $role;
    }
?>

<html>

<head>
    <title>Users management - Let's learn JAVA</title>
    <link rel="stylesheet" type="text/css" media="all" href="../Css/styles.css">
    <link rel="stylesheet" type="text/css" media="all" href="../libraries/bootstrap-4.3.1-dist/css/bootstrap.min.css">
    <script src="../libraries/Jquery/jquery-3.3.1.min.js"></script>
    <script src="../libraries/bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>
</head>

<body>

    <?php include '../header.php'; ?>
    

    <div class="container page"> 
        <div class="Login">
            <h2>Χρήστες</h2>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Όνομα</th>               
                    <th scope="col">Επώνυμο</th>
                    <th scope="col">Email</th>
                    <th scope="col">Ρόλος</th>
                    <th scope="col">Ενέργεια</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $key=>$user) { ?>
                    <tr>
                        <td><?php echo $user->fname; ?></td>
                        <td><?php echo $user->lname; ?></td>
                        <td><?php echo $user->email; ?></td>
                        <td><?php echo ($user->role == 1) ? "Admin": "Χρήστης"; ?></td>                       
                        <td>
                            <a href="userDetails.php?user_id=<?php echo $user->id?>" class="small">Επεξεργασία</a>
                            | 
                            <a href="userDelete.php?user_id=<?php echo $user->id?>" class="small">Διαγραφή</a>
                        </td>
                    </tr>
                <?php } ?> 
            </tbody>
        </table>

    </div> 
    
    <?php include '../footer.php'; ?>
</body>

</html>