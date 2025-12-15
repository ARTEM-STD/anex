<?php
session_start();
$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = $_POST['password'];
$password_confirm = $_POST['password_confirm'];

if (empty($name) || empty($email) || empty($phone) || empty($password)) {
    header('Location: /pages/register.php?error=empty');
    exit;
}

if ($password != $password_confirm) {
    header('Location: /pages/register.php?error=password_mismatch');
    exit;
}

// Проверяем, есть ли уже такой email
$check_query = "SELECT id FROM clients WHERE email = '$email'";
$check_result = $sql->query($check_query);

if ($check_result->num_rows > 0) {
    header('Location: /pages/register.php?error=email_exists');
    exit;
}

$query = "INSERT INTO clients (name, email, phone, password, created_at) 
          VALUES ('$name', '$email', '$phone', '$password', NOW())";

if ($sql->query($query)) {
    $user_id = $sql->insert_id;
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_name'] = $name;
    $_SESSION['user_email'] = $email;
    
    header('Location: /cabinet/index.php');
} else {
    header('Location: /pages/register.php?error=unknown');
}

$sql->close();
?>