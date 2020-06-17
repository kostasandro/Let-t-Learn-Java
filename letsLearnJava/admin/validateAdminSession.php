<?php
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != 1) {
    header('Location: login.php');
}
?>