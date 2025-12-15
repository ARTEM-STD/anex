<?php
session_start();
$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');

$email = $_POST['email'];
$password = $_POST['password'];

if (empty($email) || empty($password)) {
    header('Location: /pages/login.php?error=empty');
    exit;
}

$query = "SELECT * FROM clients WHERE email = '$email' AND password = '$password'";
$result = $sql->query($query);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    
    header('Location: /cabinet/index.php');
} else {
    header('Location: /pages/login.php?error=invalid');
}

$sql->close();
?>