<?php

include '../config/connect.php';

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

    if ( isset($_POST['year_released']) ) {
        $yearReleased = $_POST['year_released'];
    }
    if ( isset($_POST['date_watched']) ) {
        $dateWatched = date('Y-m-d', strtotime($_POST['date_watched']));
    }
    $hash = md5(time() . uniqid());
    if ( isset($_FILES['picture']['name']) && $_FILES['picture']['name'] != "" ) {
        $imageUploaded = true;
        
        // Handle uploaded picture.
        $currentDir = getcwd();
        $uploadsDir = '\\uploads\\img\\';
        $fileExtension = strtolower(end(explode('.', $_FILES['picture']['name'])));
        $fileName = $hash . '.' . $fileExtension;
        $uploadsPath = $currentDir . $uploadsDir . basename($fileName);
        move_uploaded_file($_FILES['picture']['tmp_name'], $uploadsPath);
    } else {
        $imageUploaded = false;
    }
       
    try {
        // Save new movie
        $sql = 'INSERT INTO movies (`title`, `description`, `notes`, `hash`, `image_uploaded`, `date_watched`, `year_released`) VALUES (?, ?, ?, ?, ?, ?, ?)';
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$title, $description, $notes, $hash, $imageUploaded, $dateWatched, $yearReleased]);
        $newMovieId = $pdo->lastInsertId();

        // Save each director for movie to `directors_movies` table.
        if (!empty($_POST['director'])) {
            $numDirectors = count($_POST['director']);
            for ($i = 0; $i < $numDirectors; $i++) {
                $sql = 'INSERT INTO directors_movies (`directors_id`, `movies_id`) VALUES (?, ?)';
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute([$_POST['director'][$i], $newMovieId]);
            }
        }
        
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



