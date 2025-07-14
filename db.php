<?php
$host = 'localhost';
$dbname = 'dbkmwxwnpuj79n';
$username = 'ugrj543f7lree';
$password = 'cgmq43woifko';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
