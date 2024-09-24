<?php

try {
    $connection = new PDO(
        'mysql:host=localhost;dbname=php_pdo',
        'hammad',
        'My@2530',
        array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        )
    );
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
