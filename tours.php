<?php
session_start();
$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');

$country = $_GET['country'] ?? '';
$from_city = $_GET['from_city'] ?? 'Чита';
$nights = $_GET['nights'] ?? '';
$is_hot = isset($_GET['hot']) ? 1 : 0;

// Строим запрос
$where = "WHERE t.is_active = 1";
if ($country) {
    $country_id_query = "SELECT id FROM countries WHERE name = '$country'";
    $country_id_result = $sql->query($country_id_query);
    if ($country_data = $country_id_result->fetch_assoc()) {
        $where .= " AND t.country_id = " . $country_data['id'];
    }
}
if ($from_city) {
    $where .= " AND t.departure_city = '$from_city'";
}
if ($nights) {
    $where .= " AND t.nights = $nights";
}
if ($is_hot) {
    $where .= " AND t.is_hot = 1";
}

$query = "SELECT t.*, c.name as country_name FROM tours t 
          LEFT JOIN countries c ON t.country_id = c.id 
          $where ORDER BY t.price ASC";
$result = $sql->query($query);
$tours = [];
while ($row = $result->fetch_assoc()) {
    $tours[] = $row;
}

$countries = $sql->query("SELECT * FROM countries ORDER BY name");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Поиск туров - Anex Tour Чита</title>
<link rel="stylesheet" href="/style.css?v=<?php echo time(); ?>">    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                    <li><a href="/pages/tours.php" class="active"><i class="fas fa-globe-americas"></i> Туры</a></li>
                    <li><a href="/pages/services.php"><i class="fas fa-concierge-bell"></i> Услуги</a></li>
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
                <h1>Поиск туров</h1>
                <p>Найдите идеальный отдых из Читы</p>
            </div>
            
            <div style="display: flex; gap: 30px; margin-top: 30px;">
                <!-- Фильтры -->
                <div style="flex: 0 0 250px;">
                    <div class="filters-sidebar">
                        <h3><i class="fas fa-filter"></i> Фильтры поиска</h3>
                        
                        <form method="GET" action="">
                            <div class="filter-group">
                                <label>Страна</label>
                                <select name="country" class="form-control">
                                    <option value="">Все страны</option>
                                    <?php while ($country_item = $countries->fetch_assoc()): ?>
                                        <option value="<?php echo $country_item['name']; ?>" 
                                            <?php echo ($country == $country_item['name']) ? 'selected' : ''; ?>>
                                            <?php echo $country_item['name']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            
                            <div class="filter-group">
                                <label>Количество ночей</label>
                                <select name="nights" class="form-control">
                                    <option value="">Любое</option>
                                    <option value="7" <?php echo ($nights == '7') ? 'selected' : ''; ?>>7 ночей</option>
                                    <option value="10" <?php echo ($nights == '10') ? 'selected' : ''; ?>>10 ночей</option>
                                    <option value="14" <?php echo ($nights == '14') ? 'selected' : ''; ?>>14 ночей</option>
                                </select>
                            </div>
                            
                            <div class="filter-group">
                                <label>Только горящие туры</label>
                                <div style="margin-top: 5px;">
                                    <input type="checkbox" name="hot" id="hot" <?php echo $is_hot ? 'checked' : ''; ?>>
                                    <label for="hot">Показать горящие</label>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i> Применить фильтры
                            </button>
                            <a href="tours.php" class="btn btn-outline btn-block" style="margin-top: 10px;">
                                Сбросить фильтры
                            </a>
                        </form>
                    </div>
                </div>
                
                <!-- Результаты -->
                <div style="flex: 1;">
                    <div class="search-results">
                        <div class="results-header">
                            <h2 style="margin: 0;">Найдено туров: <?php echo count($tours); ?></h2>
                            <?php if ($country): ?>
                                <p style="color: #666; margin-top: 5px;">Страна: <?php echo htmlspecialchars($country); ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (!empty($tours)): ?>
                            <div class="tours-grid" style="margin-top: 20px;">
                                <?php foreach ($tours as $tour): ?>
                                    <div class="tour-card">
                                        <?php if ($tour['is_hot']): ?>
                                            <div class="tour-badge">Горящий тур</div>
                                        <?php endif; ?>
                                        <div class="tour-image">
                                            <img src="/images/tours/<?php echo $tour['image']; ?>" alt="<?php echo $tour['hotel_name']; ?>">
                                        </div>
                                        <div class="tour-info">
                                            <h3 class="tour-title"><?php echo $tour['country_name']; ?></h3>
                                            <p class="tour-hotel"><?php echo $tour['hotel_name']; ?></p>
                                            <div class="tour-details">
                                                <span><i class="far fa-calendar"></i> <?php echo $tour['departure_date']; ?></span>
                                                <span><i class="fas fa-moon"></i> <?php echo $tour['nights']; ?> ночей</span>
                                                <span><i class="fas fa-star" style="color: #f39c12;"></i> <?php echo $tour['stars']; ?>*</span>
                                            </div>
                                            <div class="tour-price">
                                                <?php if ($tour['old_price'] > $tour['price']): ?>
                                                    <div class="price-old"><?php echo number_format($tour['old_price'], 0, '', ' '); ?> ₽</div>
                                                <?php endif; ?>
                                                <div class="price-new"><?php echo number_format($tour['price'], 0, '', ' '); ?> ₽</div>
                                                <small>за человека</small>
                                            </div>
                                            <div class="tour-actions">
                                                <a href="/pages/booking.php?tour_id=<?php echo $tour['id']; ?>" class="btn btn-primary">
                                                    <i class="fas fa-shopping-cart"></i> Забронировать
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 10px; margin-top: 20px;">
                                <i class="fas fa-search fa-3x" style="color: #ddd; margin-bottom: 20px;"></i>
                                <h3>Туры по заданным критериям не найдены</h3>
                                <p style="margin: 20px 0; color: #666;">Попробуйте изменить параметры поиска или обратитесь к нашим менеджерам</p>
                                <a href="/pages/contacts.php" class="btn btn-primary">
                                    <i class="fas fa-phone-alt"></i> Получить консультацию
                                </a>
                            </div>
                        <?php endif; ?>
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