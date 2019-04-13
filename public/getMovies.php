<?php

include '../config/connect.php';

try {
    $sql = 'SELECT * FROM movies ORDER BY date_watched DESC';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    for($i = 0; $i < count($movies); $i++) {
        // Update format of `date_watched` column.
        $movies[$i]['date_watched'] = date('Y-m-d', strtotime($movies[$i]['date_watched']));

        // Get director(s) for current movie.
        $sql = 'SELECT dm.*, d.*
                FROM `directors_movies` dm
                INNER JOIN `directors` d ON dm.directors_id = d.id
                WHERE dm.movies_id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$movies[$i]['id']]);
        $directors = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $movies[$i]['directors'] = $directors;
    }
    
    echo json_encode($movies);
} catch (PDOException $e) {
    echo 'IT FAILED!!!<br><br>';
    echo '<pre>';
    var_dump($e);
    echo '</pre>';
    echo $e->getMessage();
}


