<?php

class Movie {
    public $title;
    public $description;
    public $notes;

    public static function getAllMovies() {
        try {
            $sql = 'SELECT * FROM movies';
            $queryAll = $pdo->prepare($sql);
            $result = $queryAll->execute();
        
            echo '<pre>';
            var_dump($result);
            echo '</pre>';
        } catch (PDOException $e) {
            echo 'IT FAILED!!!<br><br>';
            echo '<pre>';
            var_dump($e);
            echo '</pre>';
            echo $e->getMessage();
        }
    } 
}