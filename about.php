<?php
session_start();
$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>О нас - Anex Tour Чита</title>
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
                <h1>О нашем агентстве</h1>
                <p>Узнайте больше о Anex Tour в Чите</p>
            </div>
            
            <div class="booking-form-container">
                <div style="text-align: center; margin-bottom: 40px;">
                    <h2 style="color: #2c3e50;">Anex Tour в Чите</h2>
                    <p style="font-size: 18px; color: #666; max-width: 800px; margin: 20px auto;">Официальное турагентство международного туроператора Anex Tour в городе Чита</p>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-bottom: 40px;">
                    <div>
                        <h3><i class="fas fa-history" style="color: #3498db; margin-right: 10px;"></i> Наша история</h3>
                        <p>Мы работаем на туристическом рынке Забайкальского края с 2015 года, являясь официальным представительством туроператора Anex Tour в Чите.</p>
                    </div>
                    
                    <div>
                        <h3><i class="fas fa-bullseye" style="color: #e74c3c; margin-right: 10px;"></i> Наша миссия</h3>
                        <p>Сделать отдых доступным и комфортным для каждого жителя Читы и Забайкальского края, предоставляя лучшие туры по оптимальным ценам.</p>
                    </div>
                    
                    <div>
                        <h3><i class="fas fa-chart-line" style="color: #27ae60; margin-right: 10px;"></i> Наши достижения</h3>
                        <p>Более 5000 довольных клиентов, вылеты из Читы в 15 стран мира, партнерские отношения с лучшими отелями и авиакомпаниями.</p>
                    </div>
                </div>
                
                <div style="background: #f8f9fa; padding: 30px; border-radius: 10px; margin-top: 40px;">
                    <h3 style="text-align: center; margin-bottom: 30px;">Наши преимущества</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                        <div style="text-align: center;">
                            <div style="font-size: 40px; color: #3498db; margin-bottom: 10px;">8+</div>
                            <div>Лет опыта</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 40px; color: #e74c3c; margin-bottom: 10px;">5000+</div>
                            <div>Довольных клиентов</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 40px; color: #27ae60; margin-bottom: 10px;">15+</div>
                            <div>Стран направления</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 40px; color: #f39c12; margin-bottom: 10px;">24/7</div>
                            <div>Поддержка клиентов</div>
                        </div>
                    </div>
                </div>
                
                <div style="margin-top: 40px; text-align: center;">
                    <h3>Приходите к нам в офис</h3>
                    <p style="margin: 20px 0;">Мы находимся в центре Читы и всегда рады помочь вам с выбором тура</p>
                    <a href="/pages/contacts.php" class="btn btn-primary">
                        <i class="fas fa-map-marker-alt"></i> Как нас найти
                    </a>
                </div>
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