<?php

//echo "addDirector.php called<br><br>";

include '../config/connect.php';

//var_dump($pdo);

//echo '<br><br>';

$postData = file_get_contents("php://input");
/*
echo '<pre>';
print_r($postData);
echo '<pre>';
*/
$request = json_decode($postData);
/*
echo '<pre>';
print_r($request);
echo '</pre>';
*/
if ( isset($request->first_name) ) {
    $firstName = $request->first_name;
} else {
    $firstName = null;
}
if ( isset($request->middle_name) ) {
    $middleName = $request->middle_name;
} else {
    $middleName = null;
}
if ( isset($request->last_name) ) {
    $lastName = $request->last_name;
} else {
    $lastName = null;
}
//echo $firstName . ' ' . $middleName . ' ' . $lastName;

//die();
       
try {
    $sql = 'INSERT INTO directors (`first_name`, `middle_name`, `last_name`) VALUES (?, ?, ?)';
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$firstName, $middleName, $lastName]);

    /*
    echo '<pre>';
    print_r($result);
    echo '</pre>';
    die();
    */

    $newDirectorId = $pdo->lastInsertId();
    $sql = 'SELECT * FROM directors WHERE id = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$newDirectorId]);
    $director = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($director);
    
} catch (PDOException $e) {
    echo 'IT FAILED!!!<br><br>';
    echo '<pre>';
    var_dump($e);
    echo '</pre>';
    echo $e->getMessage();

    return $movieInserted = false;
} 




