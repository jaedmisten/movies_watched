<?php

include '../config/connect.php';

$postData = file_get_contents("php://input");
$request = json_decode($postData);

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
       
try {
    $sql = 'INSERT INTO directors (`first_name`, `middle_name`, `last_name`) VALUES (?, ?, ?)';
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$firstName, $middleName, $lastName]);

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




