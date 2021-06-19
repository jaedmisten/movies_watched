<?php

include '../config/connect.php';

$movieId = $_POST['movieId'];

try {
    // Get movie hash to delete image file.
    $sql = "SELECT * FROM movies WHERE `id` = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$movieId]);
    $queryResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $movieHash = $queryResult[0]['hash'];

    // Delete the selected movie.
    $sql = "DELETE FROM movies WHERE `id` = ?";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$movieId]);

    // Delete rows from directors_movies table for the selected movie.
    $sql = "DELETE FROM directors_movies WHERE `movies_id` = ?";
    $stmt = $pdo->prepare($sql);
    $result2 = $stmt->execute([$movieId]);

    // Delete movie image.
    $filePath = getcwd() . '\\uploads\\img\\';
    unlink($filePath . $movieHash . '.jpg');

    echo $result;
} catch (PDOException $e) {
    echo 'Error: ' . $e->getCode() . " - " . $e->getMessage();
    header('HTTP/1.0 500 Movie Deletion Failed');
}