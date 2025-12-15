<?php
session_start();
$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    header('Location: /pages/login.php?admin=1&error=empty');
    exit;
}

// Проверяем в таблице users (админы)
$query = "SELECT * FROM users WHERE username = '$username'";
$result = $sql->query($query);

if ($result->num_rows === 0) {
    header('Location: /pages/login.php?admin=1&error=invalid');
    exit;
}

$user = $result->fetch_assoc();

// Проверяем пароль
if ($password === $user['password']) {
    $_SESSION['admin_id'] = $user['id'];
    $_SESSION['admin_username'] = $user['username'];
    $_SESSION['admin_role'] = $user['role'];
    $_SESSION['admin_logged_in'] = true;
    
    header('Location: /admin/index.php');
} else {
    header('Location: /pages/login.php?admin=1&error=invalid');
}

$sql->close();
?>