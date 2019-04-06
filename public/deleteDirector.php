<?php

include '../config/connect.php';
/*
echo '<pre>';
var_dump($_POST);
echo '</pre>';

echo '<pre>';
var_dump($_POST['directorId']);
echo '</pre>';
*/

$directorId = $_POST['directorId'];

try {
    $sql = "DELETE FROM directors WHERE `id` = ?";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$directorId]);

    /*
     * Need to also delete rows in `directors_movies` that have same director id 
     * in `directors_id` column.
     */
    $sql = "DELETE FROM directors_movies WHERE `directors_id` = ?";
    $stmt = $pdo->prepare($sql);
    $result2 = $stmt->execute([$directorId]);

    echo $result;
} catch (PDOException $e) {
    echo 'Error: ' . $e->getCode() . " - " . $e->getMessage();
    header('HTTP/1.0 500 Director Deletion Failed');
}