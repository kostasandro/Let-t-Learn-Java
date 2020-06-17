<nav class="navbar navbar-expand-md navbar-light ">
    <div class="container">
        <a class="navbar-brand logo" href="/letsLearnJava/index.php">
            <img src="/letsLearnJava/images/java.png" width="30" height="30" class="d-inline-block align-top" alt="" loading="lazy">
            <span class="sub">Let's Learn</span>
            <span class="name">JAVA</span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse " id="navbarNavAltMarkup">
            <ul class="navbar-nav mr-auto">
            </ul>
            <div class="navbar-nav text-center">

                <?php if (!isset($_SESSION["user_id"])) { ?>
                    <a class="nav-item nav-link" href="/letsLearnJava/login.php">Login / Δημιουργία λογαριασμού</a>
                <?php } else { ?>
                    
                <?php } ?>
                <?php
                if (isset($_SESSION["user_role"]) && $_SESSION["user_role"] == 1) {
                    include 'admin/adminMenu.php';
                }
                if (isset($_SESSION["user_role"]) && $_SESSION["user_role"] == 2) {
                    include 'user/userMenu.php';
                }
                ?>
            </div>
        </div>
    </div>
</nav>