<?php
session_start();
$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');

$services = $sql->query("SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Услуги - Anex Tour Чита</title>
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
                
                <div class="header-contacts">
                    <div class="phone-number">
                        <i class="fas fa-phone"></i>
                        <a href="tel:+73022123456">+7 (3022) 123-456</a>
                    </div>
                    <div class="work-hours">
                        <i class="far fa-clock"></i>
                        Пн-Пт: 9:00-19:00, Сб: 10:00-16:00
                    </div>
                </div>
            </div>
        </div>
        
        <nav class="main-nav">
            <div class="container">
                <ul class="nav-menu">
                    <li><a href="/"><i class="fas fa-home"></i> Главная</a></li>
                    <li><a href="/pages/tours.php"><i class="fas fa-globe-americas"></i> Туры</a></li>
                    <li><a href="/pages/services.php"><i class="fas fa-concierge-bell"></i> Услуги</a></li>
                    <li><a href="/pages/about.php"><i class="fas fa-info-circle"></i> О нас</a></li>
                    <li><a href="/pages/contacts.php"><i class="fas fa-map-marker-alt"></i> Контакты</a></li>
                </ul>
                
                <div class="header-actions">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="/cabinet/index.php" class="btn btn-primary">
                            <i class="fas fa-user"></i> Личный кабинет
                        </a>
                    <?php else: ?>
                        <a href="/pages/login.php" class="btn btn-outline">
                            <i class="fas fa-sign-in-alt"></i> Войти
                        </a>
                    <?php endif; ?>
                    <a href="/pages/booking.php" class="btn btn-primary">
                        <i class="fas fa-shopping-cart"></i> Бронирование
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <main class="main-content">
        <div class="container">
            <div class="page-header">
                <h1>Услуги турагентства</h1>
                <p>Полный спектр услуг для комфортного путешествия</p>
            </div>
            
            <div class="advantages-grid">
                <?php while ($service = $services->fetch_assoc()): ?>
                    <div class="advantage-card">
                        <div class="advantage-icon">
                            <i class="<?php echo $service['icon']; ?>"></i>
                        </div>
                        <h3><?php echo $service['title']; ?></h3>
                        <p><?php echo $service['description']; ?></p>
                        <?php if ($service['price_from']): ?>
                            <div class="service-price">
                                от <?php echo number_format($service['price_from'], 0, '', ' '); ?> ₽
                            </div>
                        <?php endif; ?>
                        <a href="/pages/contacts.php" class="btn btn-outline">Заказать услугу</a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </main>

    <footer class="site-footer">
        <div class="footer-top">
            <div class="container">
                <div class="footer-column">
                    <h3>Anex Tour Чита</h3>
                    <p>Официальное турагентство Anex Tour в Чите. Подбор и бронирование туров онлайн. Вылеты из вашего города.</p>
                </div>
                
                <div class="footer-column">
                    <h3>Навигация</h3>
                    <ul class="footer-menu">
                        <li><a href="/">Главная</a></li>
                        <li><a href="/pages/tours.php">Туры</a></li>
                        <li><a href="/pages/services.php">Услуги</a></li>
                        <li><a href="/pages/about.php">О нас</a></li>
                        <li><a href="/pages/contacts.php">Контакты</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Контакты</h3>
                    <address>
                        <p><i class="fas fa-map-marker-alt"></i> г. Чита, ул. Ленина, 123</p>
                        <p><i class="fas fa-phone"></i> +7 (3022) 123-456</p>
                        <p><i class="far fa-clock"></i> Пн-Пт: 9:00-19:00<br>Сб: 10:00-16:00</p>
                    </address>
                </div>
                
                <div class="footer-column">
                    <h3>Подписка на акции</h3>
                    <p>Получайте первыми горящие предложения</p>
                    <form action="/forms/process-subscribe.php" method="POST" class="subscribe-form">
                        <input type="email" name="email" placeholder="Ваш email" required>
                        <button type="submit" class="btn btn-primary">Подписаться</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="container">
                <p>&copy; <?php echo date('Y'); ?> Anex Tour Чита. Все права защищены.</p>
            </div>
        </div>
    </footer>
</body>
</html>
<?php $sql->close(); ?>