<?php
include('../connect.php');

function getDateCondition($type, $dateColumn = 'created_at') {
    switch ($type) {
        case 'weekly':
            return "YEARWEEK($dateColumn) = YEARWEEK(CURDATE())";
        case 'monthly':
            return "YEAR($dateColumn) = YEAR(CURDATE()) AND MONTH($dateColumn) = MONTH(CURDATE())";
        default:
            return "DATE($dateColumn) = CURDATE()";
    }
}
?>
