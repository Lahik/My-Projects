<?php 
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $db_name = 'restaurantdb';

    // set dsn
    $dsn = 'mysql:host='. $host . ';dbname=' . $db_name;

    // create a PDO instance 
    $conn = new PDO($dsn, $user, $password);
?>
