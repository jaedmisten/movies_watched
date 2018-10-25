<?php

include '../config/connect.php';

try {
    $sql = 'SELECT * FROM movies ORDER BY date_watched DESC';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //echo '<pre>';
    //var_dump($movies);
    //echo '</pre>';
    //die();

    for($i = 0; $i < count($movies); $i++) {
        $movies[$i]['date_watched'] = date('m-d-Y', strtotime($movies[$i]['date_watched']));
    }

    //echo '<pre>';
    //var_dump($movies);
    //echo '</pre>';
    //die();
    
    echo json_encode($movies);
} catch (PDOException $e) {
    echo 'IT FAILED!!!<br><br>';
    echo '<pre>';
    var_dump($e);
    echo '</pre>';
    echo $e->getMessage();
}


