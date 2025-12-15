
<?php require_once 'auth.php'; 
session_start();
if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');
    
    $query = "SELECT * FROM users WHERE username = '$username' AND role = 'admin'";
    $result = $sql->query($query);
    $user = $result->fetch_assoc();
    
    if ($user && $password == 'admin123') {
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_name'] = $user['username'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'Неверные данные';
    }
    
    $sql->close();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в админку - Anex Tour Чита</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Anex Tour Чита</h1>
            <p>Панель управления</p>
        </div>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Имя пользователя</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Пароль</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">
                Войти
            </button>
        </form>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="/" style="color: #666;">← Вернуться на сайт</a>
        </div>
    </div>
</body>
</html>