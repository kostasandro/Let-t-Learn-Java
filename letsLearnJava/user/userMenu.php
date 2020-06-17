<?php
$conn = initDbConnection();
include_once 'availableUserLevels.php';

$maxUserLevel = GetUserMaxLevel($conn, $_SESSION["user_id"]);
$nextUserLevel = GetUserNextLevel($conn, $maxUserLevel);

$sql1 = "SELECT * FROM level WHERE published = 1 ORDER BY ordering";
if ($result = $conn->query($sql1)) {
    $count = $result->num_rows;
    if ($count > 0) {
        $menuItem = "";
        while ($row = $result->fetch_assoc()) {
            $id = $row["id"];
            $ordering = (int)$row["ordering"];
            $title = $row["title"];
            $isEnabled = ($ordering <= $nextUserLevel) ? true : false;

            if ($isEnabled)
                $menuItem .= '<a href="/letsLearnJava/user/learn.php?level='.$id.'&step=theory" class="nav-item nav-link">' . $title . '</a>';
            else
                $menuItem .= '<a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">' . $title . '</a>                ';
        }
    }
    $menuItem .= '<a class="nav-item nav-link btn btn-outline-secondary" href="/letsLearnJava/user/quizHistory.php">Το ιστορικό μου</a>';
}

mysqli_close($conn);

?>

<?php echo $menuItem ?>

