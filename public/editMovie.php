<?php

include '../config/connect.php';

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    if ( !isset($_POST['id']) ) {
        // Movie id needs to be available or issues with the update can occur.
        die('There was an error. Please try agan.');
    }
    $movieId = $_POST['id'];
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
    if ( isset($_POST['hash']) ) {
        $hash = $_POST['hash'];
    }
    if ( isset($_FILES['picture']) && $_FILES['picture']['name'] != "" ) {
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
        if ($imageUploaded) {
            $sql = 'UPDATE movies 
                    SET `title` = ?, `description` = ?, `notes` = ?, `image_uploaded` = ? ,  `date_watched` = ?, `year_released` = ? 
                    WHERE `id` = ?';
        } else  {
            $sql = 'UPDATE movies 
                    SET `title` = ?, `description` = ?, `notes` = ?, `date_watched` = ?, `year_released` = ? 
                    WHERE `id` = ?';
        }
        $stmt = $pdo->prepare($sql);
        if ($imageUploaded) {
            $result = $stmt->execute([$title, $description, $notes, $imageUploaded, $dateWatched, $yearReleased, $movieId]);
        } else {
            $result = $stmt->execute([$title, $description, $notes, $dateWatched, $yearReleased, $movieId]);
        }

        // Remove any current entries in `directors_movies` for this movie to avoid duplicates or keeping incorrect directors.
        $sql = 'DELETE FROM directors_movies WHERE `movies_id` = ?';
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$movieId]);

        // Save each director for movie to `directors_movies` table.
        if (!empty($_POST['director'])) {
            $numDirectors = count($_POST['director']);
            for ($i = 0; $i < $numDirectors; $i++) {
                $sql = 'INSERT INTO directors_movies (`directors_id`, `movies_id`) VALUES (?, ?)';
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute([$_POST['director'][$i], $movieId]);
            }
        }
        

        $movieEdited = true;
        header('Location: index.php');
    } catch (PDOException $e) {
        echo 'IT FAILED!!!<br><br>';
        echo '<pre>';
        var_dump($e);
        echo '</pre>';
        echo $e->getMessage();

        return $movieEdited = false;
    } 
    
}