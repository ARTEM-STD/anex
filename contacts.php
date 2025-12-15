<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Контакты - Anex Tour Чита</title>
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
                <h1>Контакты</h1>
                <p>Свяжитесь с нами любым удобным способом</p>
            </div>
            
            <div class="contacts-grid">
                <div class="contact-info-card">
                    <i class="fas fa-map-marker-alt"></i>
                    <h3>Адрес офиса</h3>
                    <p>г. Чита, ул. Ленина, 123</p>
                    <p>Офис 45, 4 этаж</p>
                </div>
                
                <div class="contact-info-card">
                    <i class="fas fa-phone"></i>
                    <h3>Телефоны</h3>
                    <p>+7 (3022) 123-456</p>
                    <p>+7 (914) 123-45-67</p>
                    <p>WhatsApp/Telegram: +7 (914) 123-45-67</p>
                </div>
                
                <div class="contact-info-card">
                    <i class="far fa-clock"></i>
                    <h3>Режим работы</h3>
                    <p>Понедельник - Пятница: 9:00 - 19:00</p>
                    <p>Суббота: 10:00 - 16:00</p>
                    <p>Воскресенье: выходной</p>
                </div>
                
                <div class="contact-info-card">
                    <i class="far fa-envelope"></i>
                    <h3>Электронная почта</h3>
                    <p>info@anex-chita.ru</p>
                    <p>booking@anex-chita.ru</p>
                    <p>manager@anex-chita.ru</p>
                </div>
            </div>
            
            <div class="map-container">
                <!-- Здесь можно вставить iframe с Яндекс.Картами или Google Maps -->
                <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3Ace0ec4da7df0a11e1475db6ef54d9ab616b4e2ec1d3ea33a73603a54a3d0e1af&amp;source=constructor" 
                        width="100%" height="400" frameborder="0"></iframe>
            </div>
            
            <div class="contact-form-container">
                <h2>Отправить сообщение</h2>
                <form action="/forms/process-callback.php" method="POST" class="booking-form-container">
                    <input type="hidden" name="source" value="contacts_page">
                    
                    <div class="form-row">
                        <div class="form-group" style="flex: 1;">
                            <label>Ваше имя *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Телефон *</label>
                            <input type="tel" name="phone" class="form-control" required>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Сообщение *</label>
                        <textarea name="message" class="form-control" rows="5" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane"></i> Отправить сообщение
                    </button>
                </form>
                
                <div style="margin-top: 40px; text-align: center;">
                    <h3>Мы в социальных сетях</h3>
                    <div style="display: flex; justify-content: center; gap: 20px; margin-top: 20px;">
                        <a href="https://vk.com" target="_blank" class="social-icon">
                            <i class="fab fa-vk"></i>
                        </a>
                        <a href="https://telegram.org" target="_blank" class="social-icon">
                            <i class="fab fa-telegram"></i>
                        </a>
                        <a href="https://whatsapp.com" target="_blank" class="social-icon">
                            <i class="fab fa-whatsapp"></i>
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