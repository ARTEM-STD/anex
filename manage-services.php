<?php
require_once 'auth.php';

$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');

// Обновление порядка
if (isset($_POST['update_order'])) {
    foreach ($_POST['order'] as $id => $order) {
        $id = intval($id);
        $order = intval($order);
        $sql->query("UPDATE services SET sort_order = $order WHERE id = $id");
    }
    header('Location: manage-services.php?updated=1');
    exit;
}

// Удаление услуги
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $sql->query("DELETE FROM services WHERE id = $id");
    header('Location: manage-services.php?deleted=1');
    exit;
}

// Получение всех услуг
$services = $sql->query("SELECT * FROM services ORDER BY sort_order, title");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление услугами - Anex Tour Чита</title>
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <h1 style="margin: 0; font-size: 20px;">Управление услугами</h1>
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
            <a href="manage-bookings.php"><i class="fas fa-shopping-cart"></i> Бронирования</a>
            <a href="manage-requests.php"><i class="fas fa-phone-alt"></i> Заявки</a>
            <a href="manage-clients.php"><i class="fas fa-users"></i> Клиенты</a>
            <a href="manage-services.php" class="active"><i class="fas fa-concierge-bell"></i> Услуги</a>
        </div>
    </nav>
    
    <main class="main-content">
        <div class="container">
            <h2 style="margin-top: 30px;">Услуги турагентства</h2>
            
            <?php if (isset($_GET['updated'])): ?>
                <div class="success-message">Порядок услуг обновлен</div>
            <?php endif; ?>
            
            <?php if (isset($_GET['deleted'])): ?>
                <div class="success-message">Услуга удалена</div>
            <?php endif; ?>
            
            <!-- Кнопка добавления -->
            <div style="margin-bottom: 20px;">
                <a href="add-service.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Добавить услугу
                </a>
            </div>
            
            <!-- Список услуг -->
            <form method="POST">
                <div class="table-container">
                    <table>
                        <tr>
                            <th>Порядок</th>
                            <th>Иконка</th>
                            <th>Название</th>
                            <th>Описание</th>
                            <th>Цена от</th>
                            <th>Статус</th>
                            <th>Действия</th>
                        </tr>
                        <?php while ($service = $services->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <input type="number" name="order[<?php echo $service['id']; ?>]" 
                                       value="<?php echo $service['sort_order']; ?>" 
                                       style="width: 60px; padding: 5px; text-align: center;">
                            </td>
                            <td>
                                <?php if ($service['icon']): ?>
                                    <i class="<?php echo $service['icon']; ?> fa-lg" style="color: #3498db;"></i>
                                <?php endif; ?>
                            </td>
                            <td><strong><?php echo htmlspecialchars($service['title']); ?></strong></td>
                            <td style="max-width: 300px;"><?php echo htmlspecialchars($service['description']); ?></td>
                            <td>
                                <?php if ($service['price_from']): ?>
                                    от <?php echo number_format($service['price_from'], 0, '', ' '); ?> ₽
                                <?php else: ?>
                                    <span style="color: #999;">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($service['is_active']): ?>
                                    <span style="color: #2ecc71; font-size: 12px; padding: 3px 8px; background: #d4edda; border-radius: 3px;">
                                        Активна
                                    </span>
                                <?php else: ?>
                                    <span style="color: #e74c3c; font-size: 12px; padding: 3px 8px; background: #f8d7da; border-radius: 3px;">
                                        Неактивна
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit-service.php?id=<?php echo $service['id']; ?>" class="btn btn-primary btn-sm">Редактировать</a>
                                <a href="?delete_id=<?php echo $service['id']; ?>" class="btn btn-secondary btn-sm" onclick="return confirm('Удалить услугу?')">Удалить</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </table>
                </div>
                
                <div style="margin-top: 20px;">
                    <button type="submit" name="update_order" class="btn btn-primary">
                        <i class="fas fa-save"></i> Сохранить порядок
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
<?php $sql->close(); ?>