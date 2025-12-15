<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: /cabinet/index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация - Anex Tour Чита</title>
    <link rel="stylesheet" href="/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="site-header">
        <div class="header-top">
            <div class="container">
                <div class="logo">
                    <h1><a href="/">Anex Tour <span class="city-label">Чита</span></a></h1>
                    <p class="tagline">Официальное турагентство в Чите</p>
                </div>
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            <div class="login-container">
                <div class="login-header">
                    <h1>Регистрация</h1>
                    <p>Создайте аккаунт для бронирования туров</p>
                </div>
                
                <?php if (isset($_GET['error'])): ?>
                    <div class="error">
                        <?php 
                        $errors = [
                            'email_exists' => 'Email уже зарегистрирован',
                            'empty' => 'Заполните все поля',
                            'password_mismatch' => 'Пароли не совпадают'
                        ];
                        echo $errors[$_GET['error']] ?? 'Ошибка регистрации';
                        ?>
                    </div>
                <?php endif; ?>
                
                <form action="/forms/process-register.php" method="POST">
                    <div class="form-group">
                        <label>Имя</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Телефон</label>
                        <input type="tel" name="phone" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Пароль</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Повторите пароль</label>
                        <input type="password" name="password_confirm" class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">
                        Зарегистрироваться
                    </button>
                </form>
                
                <div style="text-align: center; margin-top: 20px;">
                    <p>Уже есть аккаунт? <a href="/pages/login.php">Войти</a></p>
                    <a href="/">← Вернуться на сайт</a>
                </div>
            </div>
        </div>
    </main>
    
</body>
</html><?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: /cabinet/index.php');
    exit;
}
?>
 