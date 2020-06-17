<div>
    <div class="container user">
        <div class="row">
            <div class="col text-right">
                <?php
                if (isset($_SESSION["user_first_name"])) {
                    echo "Hello, " . $_SESSION["user_first_name"] . " | " .
                        ' <a href="/letsLearnJava/logout.php" class="logout"> Log Out</a>';
                } else {
                    echo "&nbsp";
                }
                ?>
            </div>
        </div>
    </div>
    <?php include 'menu.php'; ?>

</div>