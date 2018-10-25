<?php

//echo "addMovie.php called<br><br>";

include '../config/connect.php';

//var_dump($pdo);

//echo '<br><br>';
/*
echo '<pre>';
var_dump($_POST);
echo '</pre>';
die();
*/
if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    if ( isset($_POST['title']) ) {
        $title = trim($_POST['title']);
    }
    if ( isset($_POST['description']) ) {
        $description = trim($_POST['description']);
    }
    if ( isset($_POST['notes']) && $_POST['notes'] !== '' ) {
        $notes = trim($_POST['notes']);
    }
    if ( isset($_POST['director']) ) {
        $director = trim($_POST['director']);
    }
    if ( isset($_POST['year_released']) ) {
        $yearReleased = $_POST['year_released'];
    }
    if ( isset($_POST['date_watched']) ) {
        $dateWatched = date('Y-m-d', strtotime($_POST['date_watched']));
    }
    $hash = md5(time() . uniqid());
    if ( isset($_FILES['picture']) ) {
        // Handle uploaded picture.
        $currentDir = getcwd();
        $uploadsDir = '\\uploads\\img\\';
        $fileExtension = strtolower(end(explode('.', $_FILES['picture']['name'])));
        $fileName = $hash . '.' . $fileExtension;
        $uploadsPath = $currentDir . $uploadsDir . basename($fileName);
        move_uploaded_file($_FILES['picture']['tmp_name'], $uploadsPath);
    }
       
    try {
        $sql = 'INSERT INTO movies (`title`, `description`, `notes`, `director`, `hash`, `date_watched`, `year_released`) VALUES (?, ?, ?, ?, ?, ?, ?)';
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$title, $description, $notes, $director, $hash, $dateWatched, $yearReleased]);

        $movieInserted = true;
        header('Location: index.php');
    } catch (PDOException $e) {
        echo 'IT FAILED!!!<br><br>';
        echo '<pre>';
        var_dump($e);
        echo '</pre>';
        echo $e->getMessage();

        return $movieInserted = false;
    } 
    
}



