<?php

include '../config/connect.php';

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    if ( !isset($_POST['id']) ) {
        // Director id needs to be available or issues with the update can occur.
        die('There was an error. Please try agan.');
    }
    $directorId = $_POST['id'];
    if ( isset($_POST['first_name']) ) {
        $firstName = trim($_POST['first_name']);
    } else {
        $firstName = '';
    }
    if ( isset($_POST['middle_name']) ) {
        $middleName = trim($_POST['middle_name']);
    } else {
        $middleName = '';
    }
    if ( isset($_POST['last_name']) ) {
        $lastName = trim($_POST['last_name']);
    } else {
        $lastName = '';
    }
     
    try {
        $sql = 'UPDATE directors 
                SET `first_name` = ?, `middle_name` = ?, `last_name` = ? 
                WHERE `id` = ?';
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$firstName, $middleName, $lastName, $directorId]);

        return true;
    } catch (PDOException $e) {
        echo 'IT FAILED!!!<br><br>';
        echo '<pre>';
        var_dump($e);
        echo '</pre>';
        echo $e->getMessage();

        return $movieEdited = false;
    }   
}