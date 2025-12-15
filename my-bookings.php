<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /pages/login.php');
    exit;
}

$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');
$user_id = $_SESSION['user_id'];

$bookings = $sql->query("SELECT b.*, t.hotel_name, t.country_id, t.departure_date, t.nights, t.image,
                         c.name as country_name
                         FROM bookings b 
                         LEFT JOIN tours t ON b.tour_id = t.id 
                         LEFT JOIN countries c ON t.country_id = c.id 
                         WHERE b.client_id = $user_id 
                         ORDER BY b.created_at DESC");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мои бронирования - Anex Tour Чита</title>
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="site-header">
        <div class="header-top">
            <div class="container">
                <div class="logo">
                    <h1><a href="/">Anex Tour <span class="city-label">Чита</span></a></h1>
                    <p class="tagline">Личный кабинет клиента</p>
                </div>
            </div>
        </div>
        
        <nav class="main-nav">
            <div class="container">
                <ul class="nav-menu">
                    <li><a href="/"><i class="fas fa-home"></i> На сайт</a></li>
                    <li><a href="/cabinet/index.php"><i class="fas fa-tachometer-alt"></i> Обзор</a></li>
                    <li><a href="/cabinet/my-bookings.php" class="active"><i class="fas fa-suitcase"></i> Мои бронирования</a></li>
                    <li><a href="/cabinet/my-profile.php"><i class="fas fa-user-cog"></i> Мой профиль</a></li>
                    <li><a href="/cabinet/my-documents.php"><i class="fas fa-file-alt"></i> Мои документы</a></li>
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
                <h1>Мои бронирования</h1>
                <p>История и статусы ваших заказов</p>
            </div>
            
            <?php if ($bookings->num_rows > 0): ?>
                <div class="tours-grid">
                    <?php while ($booking = $bookings->fetch_assoc()): ?>
                        <div class="tour-card">
                            <div class="tour-badge status-<?php echo $booking['status']; ?>">
                                <?php 
                                $status_names = [
                                    'pending' => 'Ожидает',
                                    'confirmed' => 'Подтверждено',
                                    'paid' => 'Оплачено',
                                    'cancelled' => 'Отменено'
                                ];
                                echo $status_names[$booking['status']] ?? $booking['status'];
                                ?>
                            </div>
                            <div class="tour-image">
                                <img src="/images/tours/<?php echo $booking['image']; ?>" alt="<?php echo $booking['hotel_name']; ?>">
                            </div>
                            <div class="tour-info">
                                <h3 class="tour-title"><?php echo $booking['country_name']; ?></h3>
                                <p class="tour-hotel"><?php echo $booking['hotel_name']; ?></p>
                                <div class="tour-details">
                                    <span><i class="far fa-calendar"></i> <?php echo $booking['departure_date']; ?></span>
                                    <span><i class="fas fa-moon"></i> <?php echo $booking['nights']; ?> ночей</span>
                                    <span><i class="fas fa-users"></i> <?php echo $booking['adults']; ?> взр. + <?php echo $booking['children']; ?> дет.</span>
                                </div>
                                <div class="tour-price">
                                    <div class="price-new"><?php echo number_format($booking['total_price'], 0, '', ' '); ?> ₽</div>
                                    <small>Код: <?php echo $booking['booking_code']; ?></small>
                                </div>
                                <div class="tour-actions">
                                    <a href="/cabinet/my-bookings.php?id=<?php echo $booking['id']; ?>" class="btn btn-outline">
                                        <i class="fas fa-info-circle"></i> Подробнее
                                    </a>
                                    <?php if ($booking['status'] == 'pending'): ?>
                                        <a href="#" class="btn btn-primary" onclick="return confirm('Отменить бронирование?')">
                                            <i class="fas fa-times"></i> Отменить
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 60px 20px;">
                    <i class="fas fa-suitcase fa-4x"></i>
                    <h3>У вас пока нет бронирований</h3>
                    <p style="margin: 20px 0;">Начните планировать свой отпуск прямо сейчас</p>
                    <a href="/pages/tours.php" class="btn btn-primary">
                        <i class="fas fa-search"></i> Найти тур
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
<?php $sql->close(); ?>