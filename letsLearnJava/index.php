<?php
// Start the session
session_start();
include_once './connection.php';

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


        <?php include 'header.php'; ?>
        <div class="container page">
            <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
                    <li data-target="#carouselExampleCaptions" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                    <img src="./images/register.png" class="d-block w-100" alt="register">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>ΒΗΜΑ 1ο</h5>
                        <p>Δημιούργησε τον λογαριασμό σου και κάνε login</p>
                    </div>
                    </div>
                    <div class="carousel-item">
                    <img src="./images/theory.png" class="d-block w-100" alt="theory">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>ΒΗΜΑ 2ο</h5>
                        <p>Ξεκίνα με την θεωρία</p>
                    </div>
                    </div>
                    <div class="carousel-item">
                    <img src="./images/exercise.png" class="d-block w-100" alt="exercise">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>ΒΗΜΑ 3ο</h5>
                        <p>Συνέχισε με τις λυμένες ασκήσεις</p>
                    </div>
                    </div>
                    <div class="carousel-item">
                    <img src="./images/quiz.png" class="d-block w-100" alt="quiz">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>ΒΗΜΑ 4ο</h5>
                        <p>Αρίστευσε στο Quiz</p>
                    </div>
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        <?php include 'footer.php'; ?>
    </body>
</html>
