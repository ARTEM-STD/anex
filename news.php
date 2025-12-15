<?php
session_start();
$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');

 $news_query = "SELECT * FROM news WHERE is_published = 1 ORDER BY published_at DESC LIMIT 10";
$news_result = $sql->query($news_query);
$news = [];
while ($row = $news_result->fetch_assoc()) {
    $news[] = $row;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Новости - Anex Tour Чита</title>
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
                    <li><a href="/pages/news.php" class="active"><i class="far fa-newspaper"></i> Новости</a></li>
                    <li><a href="/pages/about.php"><i class="fas fa-info-circle"></i> О нас</a></li>
                    <li><a href="/pages/contacts.php"><i class="fas fa-map-marker-alt"></i> Контакты</a></li>
                </ul>
                
                <div class="header-actions">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="/cabinet/index.php" class="btn btn-outline">
                            <i class="fas fa-user"></i> Кабинет
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
                <h1>Новости и статьи</h1>
                <p>Актуальная информация о туризме и путешествиях</p>
            </div>
            
            <?php if (!empty($news)): ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 30px; margin-top: 40px;">
                    <?php foreach ($news as $item): ?>
                        <div class="tour-card" style="height: auto;">
                            <?php if ($item['image']): ?>
                                <div class="tour-image" style="height: 200px;">
                                    <img src="/images/news/<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>">
                                </div>
                            <?php endif; ?>
                            <div class="tour-info">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                    <span style="font-size: 12px; color: #666; background: #f8f9fa; padding: 3px 8px; border-radius: 3px;">
                                        <?php 
                                        $categories = [
                                            'news' => 'Новость',
                                            'article' => 'Статья',
                                            'promo' => 'Акция'
                                        ];
                                        echo $categories[$item['category']] ?? $item['category'];
                                        ?>
                                    </span>
                                    <span style="font-size: 12px; color: #666;">
                                        <?php echo date('d.m.Y', strtotime($item['published_at'])); ?>
                                    </span>
                                </div>
                                
                                <h3 class="tour-title" style="margin-bottom: 10px;"><?php echo $item['title']; ?></h3>
                                
                                <?php if ($item['excerpt']): ?>
                                    <p style="color: #666; margin-bottom: 15px;"><?php echo $item['excerpt']; ?></p>
                                <?php endif; ?>
                                
                                <div style="margin-top: auto;">
                                    <a href="/pages/news-detail.php?id=<?php echo $item['id']; ?>" class="btn btn-outline">
                                        <i class="fas fa-book-open"></i> Читать далее
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 60px 20px;">
                    <i class="far fa-newspaper fa-4x" style="color: #ddd; margin-bottom: 20px;"></i>
                    <h3>Новости пока отсутствуют</h3>
                    <p style="margin: 20px 0;">Следите за обновлениями</p>
                </div>
            <?php endif; ?>
            
            <!-- КАТЕГОРИИ НОВОСТЕЙ -->
            <div class="booking-form-container mt-4">
                <h3>Категории новостей</h3>
                <div style="display: flex; gap: 15px; margin-top: 20px; flex-wrap: wrap;">
                    <a href="/pages/news.php?category=news" class="btn btn-outline">
                        <i class="fas fa-bullhorn"></i> Новости
                    </a>
                    <a href="/pages/news.php?category=article" class="btn btn-outline">
                        <i class="fas fa-book"></i> Статьи
                    </a>
                    <a href="/pages/news.php?category=promo" class="btn btn-outline">
                        <i class="fas fa-percentage"></i> Акции
                    </a>
                    <a href="/pages/news.php?category=travel_tips" class="btn btn-outline">
                        <i class="fas fa-lightbulb"></i> Советы
                    </a>
                </div>
            </div>
            
            <!-- РАССЫЛКА -->
            <div class="booking-form-container mt-4">
                <h3>Подписка на новости</h3>
                <p>Получайте первыми новости о турах и акциях</p>
                <form action="/forms/process-subscribe.php" method="POST" style="display: flex; gap: 10px; margin-top: 15px;">
                    <input type="email" name="email" class="form-control" placeholder="Ваш email" required>
                    <button type="submit" class="btn btn-primary">Подписаться</button>
                </form>
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
                        <li><a href="/pages/news.php">Новости</a></li>
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