<?php
require_once 'auth.php';

$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');

$message = '';

if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Проверка текущего пароля
    $admin_id = $_SESSION['admin_id'];
    $check_query = $sql->query("SELECT * FROM users WHERE id = $admin_id");
    $admin = $check_query->fetch_assoc();
    
    if ($current_password !== $admin['password']) {
        $message = '<div class="error-message">Текущий пароль неверен</div>';
    } elseif ($new_password !== $confirm_password) {
        $message = '<div class="error-message">Новые пароли не совпадают</div>';
    } elseif (strlen($new_password) < 6) {
        $message = '<div class="error-message">Новый пароль должен быть не менее 6 символов</div>';
    } else {
        // Обновление пароля
        $update_query = "UPDATE users SET password = '$new_password' WHERE id = $admin_id";
        if ($sql->query($update_query)) {
            $message = '<div class="success-message">Пароль успешно изменен</div>';
        } else {
            $message = '<div class="error-message">Ошибка при изменении пароля</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Смена пароля - Anex Tour Чита</title>
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <h1 style="margin: 0; font-size: 20px;">Смена пароля администратора</h1>
            <div>
                <a href="settings.php" style="color: white; text-decoration: none; margin-right: 10px;">Назад</a>
                <a href="logout.php" style="color: white; text-decoration: none; background: #e74c3c; padding: 5px 10px; border-radius: 5px;">Выйти</a>
            </div>
        </div>
    </header>
    
    <nav class="admin-nav">
        <div class="container">
            <a href="index.php"><i class="fas fa-tachometer-alt"></i> Главная</a>
            <a href="settings.php"><i class="fas fa-sliders-h"></i> Настройки</a>
            <a href="change-password.php" class="active"><i class="fas fa-key"></i> Смена пароля</a>
        </div>
    </nav>
    
    <main class="main-content">
        <div class="container">
            <div style="max-width: 500px; margin: 50px auto;">
                <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                    <h2 style="margin-bottom: 20px; color: #2c3e50;">Смена пароля</h2>
                    
                    <?php echo $message; ?>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label>Текущий пароль *</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Новый пароль *</label>
                            <input type="password" name="new_password" class="form-control" required minlength="6">
                        </div>
                        
                        <div class="form-group">
                            <label>Подтвердите новый пароль *</label>
                            <input type="password" name="confirm_password" class="form-control" required minlength="6">
                        </div>
                        
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                            <small>
                                <strong>Требования к паролю:</strong><br>
                                • Минимум 6 символов<br>
                                • Рекомендуется использовать буквы, цифры и специальные символы
                            </small>
                        </div>
                        
                        <button type="submit" name="change_password" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Изменить пароль
                        </button>
                        
                        <div style="text-align: center; margin-top: 20px;">
                            <a href="settings.php">← Вернуться к настройкам</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
<?php $sql->close(); ?>