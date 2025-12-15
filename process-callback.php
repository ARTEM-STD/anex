<?php
$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chita', '3306');

$name = $_POST['name'] ?? '';
$phone = $_POST['phone'] ?? '';
$source = $_POST['source'] ?? 'website';
$message = $_POST['message'] ?? '';

$query = "INSERT INTO requests (type, name, phone, source_page, message) 
          VALUES ('callback', '$name', '$phone', '$source', '$message')";

$sql->query($query);
$sql->close();

header('Location: /pages/thank-you.php?type=callback');
exit;
?>