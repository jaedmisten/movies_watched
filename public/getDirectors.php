<?php

include '../config/connect.php';

try {
    $sql = 'SELECT * FROM directors ORDER BY last_name ASC';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $directors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($directors);
} catch (PDOException $e) {
    echo 'IT FAILED!!!<br><br>';
    echo '<pre>';
    var_dump($e);
    echo '</pre>';
    echo $e->getMessage();
}


