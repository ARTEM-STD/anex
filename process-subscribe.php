<?php
$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');

$email = $_POST['email'] ?? '';

if (!empty($email)) {
    $check = $sql->query("SELECT id FROM subscribers WHERE email = '$email'");
    if ($check->num_rows == 0) {
        $sql->query("INSERT INTO subscribers (email) VALUES ('$email')");
    }
}

$sql->close();
header('Location: /pages/thank-you.php?type=subscribe');
exit;
?>