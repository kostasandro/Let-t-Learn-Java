<?php
function GetUserMaxLevel($conn, $user_id){
    $userLevel = "0";
    /*$sql = "SELECT max(L.ordering) AS ordering 
            FROM useranswer UA
            INNER JOIN level L ON L.id = UA.level_id
            WHERE UA.user_id = " . $user_id;
    */

    $sql = "SELECT MAX(B.ordering) ordering
            FROM (
                SELECT MAX(A.points) points, A.ordering, A.totalPoints
                FROM (
                    SELECT L.ordering, UA.questionnaire_id, IFNULL(SUM(A.points),0) points,
                            (SELECT SUM(points) 
                                FROM answer AT 
                                INNER JOIN question QT on AT.question_id = QT.id
                                WHERE QT.level_id = Q.level_id) totalPoints
                    FROM useranswer UA
                    INNER JOIN question Q on UA.question_id = Q.id
                    LEFT JOIN level L on L.id = Q.level_id
                    LEFT JOIN answer A ON (    (A.id = UA.answer_id 
                                                AND UA.answer_id IS NOT NULL)
                                            OR  (A.description = UA.answer_text 
                                                AND A.question_id = UA.question_id 
                                                AND UA.answer_id IS NULL)
                                            )
                    WHERE UA.user_id = " . $user_id . "
                    GROUP BY UA.questionnaire_id, UA.timestamp, L.ordering
                    ORDER BY timestamp DESC
                ) AS A
                GROUP BY A.ordering, A.totalPoints
            ) AS B
            WHERE B.points/B.totalPoints >= 0.5
            ";

    if ($result = $conn->query($sql)) {
        $count = $result->num_rows;
        if ($count > 0) {
            while ($row = $result->fetch_assoc()) {
                $userLevel =  (int) $row["ordering"];
            }
        }
    }
    return $userLevel;
}



function GetUserNextLevel($conn, $userLevel){
    $nextLevel = "";
    $sql = "SELECT min(ordering) AS ordering
            FROM level 
            WHERE published = 1 
                AND ordering > ". $userLevel;
    if ($result = $conn->query($sql)) {
        $count = $result->num_rows;
        if ($count > 0) {
            while ($row = $result->fetch_assoc()) {
                $nextLevel =  (int) $row["ordering"];
            }
        }
    }
    return $nextLevel;
}
?>