    <?php
    session_start();
    $sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');

    $hotTours = $sql->query("SELECT * FROM tours WHERE is_hot = 1 AND is_active = 1 ORDER BY price ASC LIMIT 6");
    $popularCountries = $sql->query("SELECT * FROM countries WHERE is_popular = 1 LIMIT 6");
    ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anex Tour - Официальное турагентство в Чите</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Стили для логотипа -->
    <style>
        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
        }
        
        .logo-svg {
            height: 50px;
            width: auto;
            transition: transform 0.3s ease;
        }
        
        .logo-svg:hover {
            transform: scale(1.05);
        }
        
        .logo-text {
            display: flex;
            flex-direction: column;
        }
        
        .logo-main {
            color: var(--anex-blue);
            font-size: 1.8rem;
            font-weight: 700;
            line-height: 1;
            margin: 0;
        }
        
        .logo-sub {
            color: var(--anex-red);
            font-size: 0.9rem;
            font-weight: 600;
            line-height: 1;
            margin: 3px 0 0 0;
        }
        
        .logo-city {
            background: var(--anex-blue);
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
            margin-top: 3px;
        }
        
        @media (max-width: 768px) {
            .logo-container {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
            
            .logo-svg {
                height: 40px;
            }
            
            .logo-main {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="header-top">
            <div class="container">
        <a href="/" class="logo-container">
            <!-- Используем оригинальный SVG логотип Anex Tour -->
            <img src="https://files1.anextour.ru/user-files/elfinder/com/logo/logo-anex.svg" 
                 alt="Anex Tour" 
                 class="logo-svg">
            
            
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
        <section class="hero-section">
            <div class="hero-overlay">
                <div class="container">
                    <h2 class="hero-title">Туры от Anex Tour. Вылеты из Читы</h2>
                    <p class="hero-subtitle">Подберите идеальный отдых за 2 минуты</p>
                    
                    <form action="/pages/tours.php" method="GET" class="search-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label><i class="fas fa-plane-departure"></i> Откуда</label>
                                <select name="from_city" class="form-control">
                                    <option value="Чита" selected>Чита</option>
                                    <option value="Иркутск">Иркутск</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label><i class="fas fa-globe-americas"></i> Страна</label>
                                <select name="country" class="form-control">
                                    <option value="">Все страны</option>
                                    <?php
                                    $all_countries = $sql->query("SELECT * FROM countries ORDER BY name");
                                    while ($country = $all_countries->fetch_assoc()): ?>
                                        <option value="<?php echo $country['name']; ?>"><?php echo $country['name']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-search"></i> Найти туры
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <section class="hot-tours-section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title"><i class="fas fa-fire"></i> Горящие туры из Читы</h2>
                    <a href="/pages/tours.php?hot=1" class="btn btn-link">Смотреть все →</a>
                </div>
                
                <?php if ($hotTours->num_rows > 0): ?>
                    <div class="tours-grid">
                        <?php while ($tour = $hotTours->fetch_assoc()): 
                            $country_name = $sql->query("SELECT name FROM countries WHERE id = " . $tour['country_id'])->fetch_assoc()['name'];
                        ?>
                            <div class="tour-card">
                                 <div class="tour-image">
                                    <img src="/images/tours/<?php echo $tour['image']; ?>" alt="<?php echo $tour['hotel_name']; ?>">
                                </div>
                                <div class="tour-info">
                                    <h3 class="tour-title"><?php echo $country_name; ?></h3>
                                    <p class="tour-hotel"><?php echo $tour['hotel_name']; ?></p>
                                    <div class="tour-details">
                                        <span><i class="far fa-calendar"></i> <?php echo $tour['departure_date']; ?></span>
                                        <span><i class="fas fa-moon"></i> <?php echo $tour['nights']; ?> ночей</span>
                                    </div>
                                    <div class="tour-price">
                                        <?php if ($tour['old_price']): ?>
                                            <div class="price-old"><?php echo number_format($tour['old_price'], 0, '', ' '); ?> ₽</div>
                                        <?php endif; ?>
                                        <div class="price-new"><?php echo number_format($tour['price'], 0, '', ' '); ?> ₽</div>
                                        <small>за человека</small>
                                    </div>
                                    <div class="tour-actions">
                                        <a href="/pages/booking.php?tour_id=<?php echo $tour['id']; ?>" class="btn btn-primary">Забронировать</a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p class="no-tours">Нет горящих туров в данный момент</p>
                <?php endif; ?>
            </div>
        </section>

        <section class="how-it-works-section">
            <div class="container">
                <h2 class="section-title">Онлайн-бронирование — 3 простых шага</h2>
                <div class="steps-grid">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <div class="step-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3>Выберите тур</h3>
                        <p>Найдите подходящий тур в нашем каталоге</p>
                    </div>
                    
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <div class="step-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <h3>Забронируйте онлайн</h3>
                        <p>Заполните форму бронирования</p>
                    </div>
                    
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <div class="step-icon">
                            <i class="fas fa-plane"></i>
                        </div>
                        <h3>Получите документы</h3>
                        <p>Документы поступят в личный кабинет</p>
                    </div>
                </div>
            </div>
        </section>
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
    </footer><div style="position: fixed; bottom: 10px; right: 10px; opacity: 0.3; z-index: 10000;">
    <a href="/pages/login.php?admin=1" 
       style="color: #ff0000ff; font-size: 10px; text-decoration: none;"
       title="Вход для администратора">
        <i class="fas fa-user-cog"></i>
    </a>
</div>
</body>
</html>
<?php $sql->close(); ?>