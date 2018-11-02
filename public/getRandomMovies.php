<?php

include '../config/connect.php';

try {
    $sql = 'SELECT * FROM movies';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //echo '<pre>';
    //var_dump($movies);
    //echo '</pre>';
    //die();

    $numMovies = count($movies);
    if ( $numMovies >= 3 ) {
        $randomMovieHashes = [];
        if ( $numMovies >= 6 ) {
            $randomKeys = array_rand($movies, 6);
        } else {
            $randomKeys = array_rand($movies, 3);
        }

        // Get first row of movie hashes for home page.
        for ($i = 0; $i <= 2; $i++) {
            $randomMovieHashes[] = $movies[$randomKeys[$i]]['hash'];
        }

        if ( $numMovies >= 6 ) {
            // Get second row of movie hashes for home page.
            for (; $i <= 5; $i++) {
                $randomMovieHashes[] = $movies[$randomKeys[$i]]['hash'];
            }
        }

        echo json_encode($randomMovieHashes);
    } else {
        return false;
    }  
} catch (PDOException $e) {
    echo 'IT FAILED!!!<br><br>';
    echo '<pre>';
    var_dump($e);
    echo '</pre>';
    echo $e->getMessage();
}