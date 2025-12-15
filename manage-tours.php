<?php
require_once 'auth.php';

$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');

// Определяем фильтр
$filter = $_GET['filter'] ?? '';
$where = "WHERE 1=1";

if ($filter == 'hot') {
    $where .= " AND t.is_hot = 1";
} elseif ($filter == 'active') {
    $where .= " AND t.is_active = 1";
} elseif ($filter == 'inactive') {
    $where .= " AND t.is_active = 0";
}

// Удаление тура
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $sql->query("DELETE FROM tours WHERE id = $id");
    header('Location: manage-tours.php?deleted=1');
    exit;
}

// Получение туров с учетом фильтра
$query = "SELECT t.*, c.name as country_name 
          FROM tours t 
          LEFT JOIN countries c ON t.country_id = c.id 
          $where 
          ORDER BY t.departure_date DESC, t.created_at DESC";
$result = $sql->query($query);

// Подсчет статистики для отображения
$total_tours = $result->num_rows;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление турами - Anex Tour Чита</title>
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <h1 style="margin: 0; font-size: 20px;">Управление турами</h1>
            <div>
                <a href="index.php" style="color: white; text-decoration: none; margin-right: 10px;">Назад</a>
                <a href="logout.php" style="color: white; text-decoration: none; background: #e74c3c; padding: 5px 10px; border-radius: 5px;">Выйти</a>
            </div>
        </div>
    </header>
    
    <nav class="admin-nav">
        <div class="container">
            <a href="index.php"><i class="fas fa-tachometer-alt"></i> Главная</a>
            <a href="manage-tours.php" class="active"><i class="fas fa-globe-americas"></i> Туры</a>
            <a href="manage-bookings.php"><i class="fas fa-shopping-cart"></i> Бронирования</a>
            <a href="manage-requests.php"><i class="fas fa-phone-alt"></i> Заявки</a>
            <a href="manage-clients.php"><i class="fas fa-users"></i> Клиенты</a>
        </div>
    </nav>
    
    <main class="main-content">
        <div class="container">
            <h2 style="margin-top: 30px;">Управление турами</h2>
            
            <?php if (isset($_GET['deleted'])): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i> Тур успешно удален
                </div>
            <?php endif; ?>
            
            <!-- Фильтры -->
            <div style="background: white; padding: 15px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                <h4 style="margin-bottom: 10px;">Фильтры:</h4>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="manage-tours.php" class="btn <?php echo $filter == '' ? 'btn-primary' : 'btn-outline'; ?> btn-sm">
                        Все туры (<?php echo $tours_count; ?>)
                    </a>
                    <a href="manage-tours.php?filter=hot" class="btn <?php echo $filter == 'hot' ? 'btn-primary' : 'btn-outline'; ?> btn-sm">
                        <i class="fas fa-fire"></i> Горящие (<?php echo $hot_tours; ?>)
                    </a>
                    <a href="manage-tours.php?filter=active" class="btn <?php echo $filter == 'active' ? 'btn-primary' : 'btn-outline'; ?> btn-sm">
                        <i class="fas fa-eye"></i> Активные (<?php echo $active_tours; ?>)
                    </a>
                    <a href="manage-tours.php?filter=inactive" class="btn <?php echo $filter == 'inactive' ? 'btn-primary' : 'btn-outline'; ?> btn-sm">
                        <i class="fas fa-eye-slash"></i> Неактивные
                    </a>
                </div>
            </div>
            
            <!-- Кнопка добавления -->
            <div style="margin-bottom: 20px;">
                <a href="add-tour.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Добавить новый тур
                </a>
            </div>
            
            <!-- Список туров -->
            <div class="table-container">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Отель</th>
                        <th>Страна</th>
                        <th>Дата вылета</th>
                        <th>Ночей</th>
                        <th>Цена</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                    <?php while ($tour = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $tour['id']; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($tour['hotel_name']); ?></strong>
                            <?php if ($tour['is_hot']): ?>
                                <span style="color: #e74c3c; font-size: 12px; margin-left: 5px;">
                                    <i class="fas fa-fire"></i>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($tour['country_name']); ?></td>
                        <td><?php echo date('d.m.Y', strtotime($tour['departure_date'])); ?></td>
                        <td><?php echo $tour['nights']; ?></td>
                        <td><?php echo number_format($tour['price'], 0, '', ' '); ?> ₽</td>
                        <td>
                            <?php if ($tour['is_active']): ?>
                                <span style="color: #2ecc71; font-size: 12px; padding: 3px 8px; background: #d4edda; border-radius: 3px;">
                                    Активен
                                </span>
                            <?php else: ?>
                                <span style="color: #e74c3c; font-size: 12px; padding: 3px 8px; background: #f8d7da; border-radius: 3px;">
                                    Неактивен
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit-tour.php?id=<?php echo $tour['id']; ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Редактировать
                            </a>
                            <a href="?delete_id=<?php echo $tour['id']; ?>" class="btn btn-secondary btn-sm" onclick="return confirm('Удалить тур?')">
                                <i class="fas fa-trash"></i> Удалить
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
                
                <?php if ($total_tours == 0): ?>
                    <div style="text-align: center; padding: 40px 20px; color: #666;">
                        <i class="fas fa-plane fa-3x" style="color: #ddd; margin-bottom: 15px;"></i>
                        <h3>Туры не найдены</h3>
                        <p>По выбранному фильтру туров не найдено</p>
                        <a href="manage-tours.php" class="btn btn-primary">
                            <i class="fas fa-list"></i> Показать все туры
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>
<?php $sql->close(); ?>