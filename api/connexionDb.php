<?php
$config = require '../config.php';
// Options de connexion
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,     
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC             
];

// Instancier la connexion
$pdo = new PDO(
    "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8",
    $config['dbusername'],
    $config['dbpassword'],
    $options
);

return $pdo;