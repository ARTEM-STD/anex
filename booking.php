<?php
session_start();
$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');

$tour_id = $_GET['tour_id'] ?? '';

// Получаем данные тура если передан ID
$tour = null;
if ($tour_id) {
    $query = "SELECT t.*, c.name as country_name FROM tours t 
              LEFT JOIN countries c ON t.country_id = c.id 
              WHERE t.id = $tour_id";
    $result = $sql->query($query);
    $tour = $result->fetch_assoc();
}

// Если пользователь авторизован, получаем его данные
$user_data = [];
if (isset($_SESSION['user_id'])) {
    $user_query = "SELECT * FROM clients WHERE id = " . $_SESSION['user_id'];
    $user_result = $sql->query($user_query);
    $user_data = $user_result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Бронирование тура - Anex Tour Чита</title>
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
                        <a href="/cabinet/index.php" class="btn btn-outline">
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
                <h1>Бронирование тура</h1>
                <p>Заполните форму для бронирования</p>
            </div>
            
            <div class="booking-form-container">
                <?php if ($tour): ?>
                    <div class="tour-summary">
                        <h3>Выбранный тур</h3>
                        <div style="display: flex; gap: 20px; margin-top: 15px; align-items: center;">
                            <div style="flex: 0 0 150px;">
                                <img src="/images/tours/<?php echo $tour['image']; ?>" alt="<?php echo $tour['hotel_name']; ?>" style="width: 100%; border-radius: 5px;">
                            </div>
                            <div style="flex: 1;">
                                <h4><?php echo $tour['hotel_name']; ?></h4>
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin-top: 10px;">
                                    <div>
                                        <strong>Страна:</strong> <?php echo $tour['country_name']; ?>
                                    </div>
                                    <div>
                                        <strong>Дата вылета:</strong> <?php echo $tour['departure_date']; ?>
                                    </div>
                                    <div>
                                        <strong>Ночей:</strong> <?php echo $tour['nights']; ?>
                                    </div>
                                    <div>
                                        <strong>Цена:</strong> <?php echo number_format($tour['price'], 0, '', ' '); ?> ₽
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <h3 style="margin-top: 30px;">Данные для бронирования</h3>
                
                <form action="/forms/process-booking.php" method="POST">
                    <?php if ($tour_id): ?>
                        <input type="hidden" name="tour_id" value="<?php echo $tour_id; ?>">
                    <?php else: ?>
                        <div class="form-group">
                            <label>Выберите тур *</label>
                            <select name="tour_id" class="form-control" required>
                                <option value="">Выберите тур...</option>
                                <?php
                                $tours_list = $sql->query("SELECT t.*, c.name as country_name FROM tours t 
                                                         LEFT JOIN countries c ON t.country_id = c.id 
                                                         WHERE t.is_active = 1 
                                                         ORDER BY t.departure_date DESC");
                                while ($t = $tours_list->fetch_assoc()): ?>
                                    <option value="<?php echo $t['id']; ?>">
                                        <?php echo $t['country_name']; ?> - <?php echo $t['hotel_name']; ?> 
                                        (<?php echo $t['departure_date']; ?>, <?php echo $t['nights']; ?> ночей)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    
                    <div class="form-row">
                        <div class="form-group" style="flex: 1;">
                            <label>Ваше имя *</label>
                            <input type="text" name="client_name" class="form-control" 
                                   value="<?php echo $user_data['name'] ?? ''; ?>" required>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Телефон *</label>
                            <input type="tel" name="client_phone" class="form-control" 
                                   value="<?php echo $user_data['phone'] ?? ''; ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group" style="flex: 1;">
                            <label>Email *</label>
                            <input type="email" name="client_email" class="form-control" 
                                   value="<?php echo $user_data['email'] ?? ''; ?>" required>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Паспортные данные</label>
                            <input type="text" name="client_passport" class="form-control" 
                                   value="<?php echo $user_data['passport'] ?? ''; ?>" 
                                   placeholder="Серия и номер паспорта">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group" style="flex: 1;">
                            <label>Количество взрослых *</label>
                            <select name="adults" class="form-control" required>
                                <option value="1">1 взрослый</option>
                                <option value="2" selected>2 взрослых</option>
                                <option value="3">3 взрослых</option>
                                <option value="4">4 взрослых</option>
                                <option value="5">5 взрослых</option>
                            </select>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Количество детей</label>
                            <select name="children" class="form-control">
                                <option value="0" selected>0 детей</option>
                                <option value="1">1 ребенок</option>
                                <option value="2">2 ребенка</option>
                                <option value="3">3 ребенка</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Возраст детей (через запятую)</label>
                        <input type="text" name="children_ages" class="form-control" 
                               placeholder="Например: 5, 8 (укажите если есть дети)">
                    </div>
                    
                    <div class="form-group">
                        <label>Дополнительные пожелания и комментарии</label>
                        <textarea name="notes" class="form-control" rows="4" 
                                  placeholder="Укажите особые пожелания, если есть"></textarea>
                    </div>
                    
                    <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0;">
                        <h4><i class="fas fa-info-circle"></i> Важная информация</h4>
                        <ul style="margin: 10px 0 0 20px;">
                            <li>После отправки формы наш менеджер свяжется с вами для подтверждения бронирования</li>
                            <li>Для подтверждения бронирования потребуется предоплата 30% от стоимости тура</li>
                            <li>Оставшиеся 70% оплачиваются не позднее, чем за 14 дней до вылета</li>
                            <li>Все документы будут доступны в вашем личном кабинете после оплаты</li>
                        </ul>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block" style="padding: 15px; font-size: 18px;">
                        <i class="fas fa-paper-plane"></i> Отправить заявку на бронирование
                    </button>
                </form>
                
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <div style="margin-top: 30px; padding: 20px; background: #e8f4fc; border-radius: 10px;">
                        <h4><i class="fas fa-exclamation-circle"></i> Рекомендация</h4>
                        <p style="margin: 10px 0;">
                            <a href="/pages/register.php">Зарегистрируйтесь</a> или 
                            <a href="/pages/login.php">войдите в личный кабинет</a>, чтобы:
                        </p>
                        <ul style="margin: 10px 0 0 20px;">
                            <li>Отслеживать статус бронирования</li>
                            <li>Получать электронные документы</li>
                            <li>Видеть историю всех заказов</li>
                            <li>Получать персональные предложения</li>
                        </ul>
                    </div>
                <?php endif; ?>
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