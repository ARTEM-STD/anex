<?php
session_start();

// Проверяем, вошел ли администратор
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Если не вошел, редиректим на страницу входа
    header('Location: /pages/login.php?admin=1');
    exit;
}

// Дополнительная проверка на наличие нужных данных
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_username'])) {
    // Если нет нужных данных в сессии
    session_destroy();
    header('Location: /pages/login.php?admin=1');
    exit;
}
?>