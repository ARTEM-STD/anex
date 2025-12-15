<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /pages/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль - Anex Tour Чита</title>
    <link rel="stylesheet" href="/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="site-header">
        <div class="header-top">
            <div class="container">
                <div class="logo">
                    <h1><a href="/">Anex Tour <span class="city-label">Чита</span></a></h1>
                    <p class="tagline">Профиль пользователя</p>
                </div>
                
                <div class="header-contacts">
                    <div class="phone-number">
                        <i class="fas fa-user"></i>
                        <?php echo $_SESSION['user_name']; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <nav class="main-nav">
            <div class="container">
                <ul class="nav-menu">
                    <li><a href="/"><i class="fas fa-home"></i> На сайт</a></li>
                    <li><a href="/cabinet/index.php"><i class="fas fa-tachometer-alt"></i> Личный кабинет</a></li>
                    <li><a href="/pages/profile.php" class="active"><i class="fas fa-user"></i> Профиль</a></li>
                    <li><a href="/pages/tours.php"><i class="fas fa-globe-americas"></i> Туры</a></li>
                    <li><a href="/pages/contacts.php"><i class="fas fa-map-marker-alt"></i> Контакты</a></li>
                </ul>
                
                <div class="header-actions">
                    <a href="/forms/logout.php" class="btn btn-secondary">
                        <i class="fas fa-sign-out-alt"></i> Выйти
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <main class="main-content">
        <div class="container">
            <div class="page-header">
                <h1>Профиль пользователя</h1>
                <p>Основная информация о вашем аккаунте</p>
            </div>
            
            <div class="booking-form-container">
                <div style="text-align: center; margin-bottom: 30px;">
                    <div style="width: 100px; height: 100px; background: #3498db; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <i class="fas fa-user fa-3x" style="color: white;"></i>
                    </div>
                    <h2><?php echo $_SESSION['user_name']; ?></h2>
                    <p><?php echo $_SESSION['user_email']; ?></p>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px;">
                    <div style="text-align: center; padding: 20px; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-suitcase fa-2x" style="color: #3498db; margin-bottom: 15px;"></i>
                        <h3>Мои бронирования</h3>
                        <p>Просмотр и управление заказами</p>
                        <a href="/cabinet/my-bookings.php" class="btn btn-outline btn-sm">Перейти</a>
                    </div>
                    
                    <div style="text-align: center; padding: 20px; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-user-cog fa-2x" style="color: #27ae60; margin-bottom: 15px;"></i>
                        <h3>Настройки профиля</h3>
                        <p>Изменение данных аккаунта</p>
                        <a href="/cabinet/my-profile.php" class="btn btn-outline btn-sm">Перейти</a>
                    </div>
                    
                    <div style="text-align: center; padding: 20px; background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-file-alt fa-2x" style="color: #f39c12; margin-bottom: 15px;"></i>
                        <h3>Мои документы</h3>
                        <p>Туристические документы</p>
                        <a href="/cabinet/my-documents.php" class="btn btn-outline btn-sm">Перейти</a>
                    </div>
                </div>
                
                <div style="margin-top: 40px;">
                    <h3>Быстрые ссылки</h3>
                    <div style="display: flex; gap: 15px; margin-top: 20px; flex-wrap: wrap;">
                        <a href="/pages/tours.php" class="btn btn-outline">
                            <i class="fas fa-search"></i> Найти тур
                        </a>
                        <a href="/pages/booking.php" class="btn btn-outline">
                            <i class="fas fa-shopping-cart"></i> Новое бронирование
                        </a>
                        <a href="/pages/contacts.php" class="btn btn-outline">
                            <i class="fas fa-headset"></i> Служба поддержки
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="site-footer">
        <div class="footer-top">
            <div class="container">
                <div class="footer-column">
                    <h3>Anex Tour Чита</h3>
                    <p>Официальное турагентство Anex Tour в Чите</p>
                </div>
                
                <div class="footer-column">
                    <h3>Контакты</h3>
                    <address>
                        <p><i class="fas fa-map-marker-alt"></i> г. Чита, ул. Ленина, 123</p>
                        <p><i class="fas fa-phone"></i> +7 (3022) 123-456</p>
                    </address>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="container">
                <p>&copy; <?php echo date('Y'); ?> Anex Tour Чита</p>
            </div>
        </div>
    </footer>
</body>
</html>