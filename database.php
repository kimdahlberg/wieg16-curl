<?php
$host = 'localhost';
$db = 'curlform';
$user = 'root';
$password = 'root';
$dsn = "mysql:host=$host;dbname=$db;";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false];
$pdo = new PDO($dsn, $user, $password, $options);

if (isset($_POST['submit'])) {


    $sql = "INSERT INTO `users` (`name`, `email`) VALUES (:name, :email)";

    $stm_insert = $pdo->prepare($sql);
    $success = $stm_insert->execute([
        'name' => $_POST['name'],
        'email' => $_POST['email']
    ]);
    echo "Välkommen";

}

