<?php
require_once 'auth.php';

$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');

// Определяем фильтр по статусу
$status_filter = $_GET['status'] ?? '';
$where = "WHERE 1=1";

if ($status_filter) {
    $where .= " AND b.status = '$status_filter'";
}

// Обновление статуса
if (isset($_POST['update_status'])) {
    $booking_id = $_POST['booking_id'];
    $status = $_POST['status'];
    
    $query = "UPDATE bookings SET status = '$status' WHERE id = $booking_id";
    $sql->query($query);
    header('Location: manage-bookings.php?updated=1&status=' . $status_filter);
    exit;
}

// Получение всех бронирований с учетом фильтра
$query = "SELECT b.*, c.name as client_name, t.hotel_name 
          FROM bookings b 
          LEFT JOIN clients c ON b.client_id = c.id 
          LEFT JOIN tours t ON b.tour_id = t.id 
          $where 
          ORDER BY b.created_at DESC";
$result = $sql->query($query);

// Подсчет бронирований по статусам
$counts = [
    'all' => $sql->query("SELECT COUNT(*) as count FROM bookings")->fetch_assoc()['count'],
    'pending' => $sql->query("SELECT COUNT(*) as count FROM bookings WHERE status = 'pending'")->fetch_assoc()['count'],
    'confirmed' => $sql->query("SELECT COUNT(*) as count FROM bookings WHERE status = 'confirmed'")->fetch_assoc()['count'],
    'paid' => $sql->query("SELECT COUNT(*) as count FROM bookings WHERE status = 'paid'")->fetch_assoc()['count'],
    'cancelled' => $sql->query("SELECT COUNT(*) as count FROM bookings WHERE status = 'cancelled'")->fetch_assoc()['count']
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление бронированиями - Anex Tour Чита</title>
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <h1 style="margin: 0; font-size: 20px;">Управление бронированиями</h1>
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
            <a href="manage-bookings.php" class="active"><i class="fas fa-shopping-cart"></i> Бронирования</a>
            <a href="manage-requests.php"><i class="fas fa-phone-alt"></i> Заявки</a>
            <a href="manage-clients.php"><i class="fas fa-users"></i> Клиенты</a>
        </div>
    </nav>
    
    <main class="main-content">
        <div class="container">
            <h2 style="margin-top: 30px;">Управление бронированиями</h2>
            
            <?php if (isset($_GET['updated'])): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i> Статус бронирования обновлен
                </div>
            <?php endif; ?>
            
            <!-- Фильтры по статусу -->
            <div style="background: white; padding: 15px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                <h4 style="margin-bottom: 10px;">Фильтры по статусу:</h4>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="manage-bookings.php" class="btn <?php echo $status_filter == '' ? 'btn-primary' : 'btn-outline'; ?> btn-sm">
                        Все (<?php echo $counts['all']; ?>)
                    </a>
                    <a href="manage-bookings.php?status=pending" class="btn <?php echo $status_filter == 'pending' ? 'btn-primary' : 'btn-outline'; ?> btn-sm">
                        <i class="fas fa-clock"></i> Ожидающие (<?php echo $counts['pending']; ?>)
                    </a>
                    <a href="manage-bookings.php?status=confirmed" class="btn <?php echo $status_filter == 'confirmed' ? 'btn-primary' : 'btn-outline'; ?> btn-sm">
                        <i class="fas fa-check-circle"></i> Подтвержденные (<?php echo $counts['confirmed']; ?>)
                    </a>
                    <a href="manage-bookings.php?status=paid" class="btn <?php echo $status_filter == 'paid' ? 'btn-primary' : 'btn-outline'; ?> btn-sm">
                        <i class="fas fa-credit-card"></i> Оплаченные (<?php echo $counts['paid']; ?>)
                    </a>
                    <a href="manage-bookings.php?status=cancelled" class="btn <?php echo $status_filter == 'cancelled' ? 'btn-primary' : 'btn-outline'; ?> btn-sm">
                        <i class="fas fa-times-circle"></i> Отмененные (<?php echo $counts['cancelled']; ?>)
                    </a>
                </div>
            </div>
            
            <!-- Список бронирований -->
            <div class="table-container">
                <table>
                    <tr>
                        <th>Код</th>
                        <th>Клиент</th>
                        <th>Отель</th>
                        <th>Телефон</th>
                        <th>Сумма</th>
                        <th>Статус</th>
                        <th>Дата</th>
                        <th>Действия</th>
                    </tr>
                    <?php while ($booking = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $booking['booking_code']; ?></td>
                        <td><?php echo htmlspecialchars($booking['client_name']); ?></td>
                        <td><?php echo htmlspecialchars($booking['hotel_name']); ?></td>
                        <td><?php echo htmlspecialchars($booking['client_phone']); ?></td>
                        <td><?php echo number_format($booking['total_price'], 0, '', ' '); ?> ₽</td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                <select name="status" onchange="this.form.submit()" style="padding: 5px; border: 1px solid #ddd; border-radius: 5px; font-size: 12px;">
                                    <option value="pending" <?php echo $booking['status'] == 'pending' ? 'selected' : ''; ?>>Ожидает</option>
                                    <option value="confirmed" <?php echo $booking['status'] == 'confirmed' ? 'selected' : ''; ?>>Подтверждено</option>
                                    <option value="paid" <?php echo $booking['status'] == 'paid' ? 'selected' : ''; ?>>Оплачено</option>
                                    <option value="cancelled" <?php echo $booking['status'] == 'cancelled' ? 'selected' : ''; ?>>Отменено</option>
                                </select>
                                <button type="submit" name="update_status" style="display: none;">Сохранить</button>
                            </form>
                        </td>
                        <td><?php echo date('d.m.Y', strtotime($booking['created_at'])); ?></td>
                        <td>
                            <a href="view-booking.php?id=<?php echo $booking['id']; ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i> Просмотр
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
<?php $sql->close(); ?>