<?php

include '../config/connect.php';
/*
echo '<pre>';
var_dump($_POST);
echo '</pre>';

echo '<pre>';
var_dump($_POST['movieId']);
echo '</pre>';
*/

$movieId = $_POST['movieId'];

try {
    $sql = "DELETE FROM movies WHERE `id` = ?";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$movieId]);

    /*
     * Need to also delete rows in `directors_movies` that have same movie id 
     * in `movies_id` column.
     * 
     */
    $sql = "DELETE FROM directors_movies WHERE `movies_id` = ?";
    $stmt = $pdo->prepare($sql);
    $result2 = $stmt->execute([$movieId]);

    echo $result;
} catch (PDOException $e) {
    echo 'Error: ' . $e->getCode() . " - " . $e->getMessage();
    header('HTTP/1.0 500 Movie Deletion Failed');
}