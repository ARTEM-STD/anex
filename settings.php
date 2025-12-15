<?php
require_once 'auth.php';

$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');

// Обновление настроек
if (isset($_POST['update_settings'])) {
    // Здесь можно добавить логику обновления настроек
    $message = "Настройки сохранены";
}

// Получение информации о системе
$total_tables = $sql->query("SHOW TABLES")->num_rows;
$db_size_query = $sql->query("SELECT 
    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) as size_mb 
    FROM information_schema.TABLES 
    WHERE table_schema = 'anex_chit'")->fetch_assoc();
$db_size = $db_size_query['size_mb'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Настройки - Anex Tour Чита</title>
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <h1 style="margin: 0; font-size: 20px;">Настройки системы</h1>
            <div>
                <a href="index.php" style="color: white; text-decoration: none; margin-right: 10px;">Назад</a>
                <a href="logout.php" style="color: white; text-decoration: none; background: #e74c3c; padding: 5px 10px; border-radius: 5px;">Выйти</a>
            </div>
        </div>
    </header>
    
    <nav class="admin-nav">
        <div class="container">
            <a href="index.php"><i class="fas fa-tachometer-alt"></i> Главная</a>
            <a href="manage-tours.php"><i class="fas fa-globe-americas"></i> Туры</a>
            <a href="manage-bookings.php"><i class="fas fa-shopping-cart"></i> Бронирования</a>
            <a href="manage-requests.php"><i class="fas fa-phone-alt"></i> Заявки</a>
            <a href="manage-clients.php"><i class="fas fa-users"></i> Клиенты</a>
            <a href="settings.php" class="active"><i class="fas fa-sliders-h"></i> Настройки</a>
        </div>
    </nav>
    
    <main class="main-content">
        <div class="container">
            <h2 style="margin-top: 30px;">Настройки панели управления</h2>
            
            <?php if (isset($message)): ?>
                <div class="success-message"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-top: 30px;">
                
                <!-- Информация о системе -->
                <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                    <h3 style="color: #3498db; margin-bottom: 15px;">
                        <i class="fas fa-info-circle"></i> Информация о системе
                    </h3>
                    <ul style="list-style: none; padding: 0;">
                        <li style="padding: 8px 0; border-bottom: 1px solid #eee;">
                            <strong>Версия PHP:</strong> <?php echo phpversion(); ?>
                        </li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #eee;">
                            <strong>База данных:</strong> MySQL
                        </li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #eee;">
                            <strong>Таблиц в БД:</strong> <?php echo $total_tables; ?>
                        </li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #eee;">
                            <strong>Размер БД:</strong> <?php echo $db_size; ?> MB
                        </li>
                        <li style="padding: 8px 0;">
                            <strong>Время сервера:</strong> <?php echo date('d.m.Y H:i:s'); ?>
                        </li>
                    </ul>
                </div>
                
                <!-- Настройки сайта -->
                <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                    <h3 style="color: #2ecc71; margin-bottom: 15px;">
                        <i class="fas fa-cog"></i> Настройки сайта
                    </h3>
                    <form method="POST">
                        <div class="form-group">
                            <label>Название сайта</label>
                            <input type="text" class="form-control" value="Anex Tour Чита" disabled>
                        </div>
                        <div class="form-group">
                            <label>Контактный телефон</label>
                            <input type="text" class="form-control" value="+7 (3022) 123-456">
                        </div>
                        <div class="form-group">
                            <label>Email для уведомлений</label>
                            <input type="email" class="form-control" value="admin@anex-chita.ru">
                        </div>
                        <button type="submit" name="update_settings" class="btn btn-primary">
                            <i class="fas fa-save"></i> Сохранить изменения
                        </button>
                    </form>
                </div>
                
                <!-- Безопасность -->
                <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                    <h3 style="color: #e74c3c; margin-bottom: 15px;">
                        <i class="fas fa-shield-alt"></i> Безопасность
                    </h3>
                    <div style="margin-bottom: 20px;">
                        <a href="change-password.php" class="btn btn-primary btn-block">
                            <i class="fas fa-key"></i> Сменить пароль
                        </a>
                    </div>
                    <div>
                        <a href="backup.php" class="btn btn-outline btn-block">
                            <i class="fas fa-database"></i> Создать резервную копию
                        </a>
                    </div>
                </div>
                
                <!-- Дополнительные функции -->
                <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                    <h3 style="color: #9b59b6; margin-bottom: 15px;">
                        <i class="fas fa-tools"></i> Дополнительные функции
                    </h3>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <a href="clear-cache.php" class="btn btn-outline">
                            <i class="fas fa-broom"></i> Очистить кеш
                        </a>
                        <a href="logs.php" class="btn btn-outline">
                            <i class="fas fa-clipboard-list"></i> Просмотр логов
                        </a>
                        <a href="system-check.php" class="btn btn-outline">
                            <i class="fas fa-stethoscope"></i> Проверка системы
                        </a>
                    </div>
                </div>
                
            </div>
        </div>
    </main>
</body>
</html>
<?php $sql->close(); ?>