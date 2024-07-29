<?php
$host = 'localhost';
$user = 'root';
$password = '';
$db_name = 'champions_gym_db';

try {
    // set dsn
    $dsn = 'mysql:host=' . $host . ';dbname=' . $db_name;
    
    // create a PDO instance 
    $conn = new PDO($dsn, $user, $password);
    
    // set the default fetch mode to PDO::FETCH_ASSOC
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
}catch (PDOException $e) {
    echo '<script>show_toast("Server connection failed! Try again later" ,"error");</script>';
}
?>
