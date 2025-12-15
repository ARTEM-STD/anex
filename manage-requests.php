<?php
require_once 'auth.php';

$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');

// Определяем фильтры
$status_filter = $_GET['status'] ?? '';
$type_filter = $_GET['type'] ?? '';
$where = "WHERE 1=1";

if ($status_filter) {
    $where .= " AND status = '$status_filter'";
}
if ($type_filter) {
    $where .= " AND type = '$type_filter'";
}

// Обновление статуса
if (isset($_POST['update_status'])) {
    $request_id = $_POST['request_id'];
    $status = $_POST['status'];
    
    $query = "UPDATE requests SET status = '$status' WHERE id = $request_id";
    $sql->query($query);
    header('Location: manage-requests.php?updated=1');
    exit;
}

// Удаление заявки
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $sql->query("DELETE FROM requests WHERE id = $id");
    header('Location: manage-requests.php?deleted=1');
    exit;
}

// Получение всех заявок с учетом фильтров
$query = "SELECT * FROM requests $where ORDER BY created_at DESC";
$result = $sql->query($query);

// Подсчет статистики
$new_count = $sql->query("SELECT COUNT(*) as count FROM requests WHERE status = 'new'")->fetch_assoc()['count'];
$callback_count = $sql->query("SELECT COUNT(*) as count FROM requests WHERE type = 'callback'")->fetch_assoc()['count'];
$total_count = $sql->query("SELECT COUNT(*) as count FROM requests")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление заявками - Anex Tour Чита</title>
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <h1 style="margin: 0; font-size: 20px;">Управление заявками</h1>
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
            <a href="manage-requests.php" class="active"><i class="fas fa-phone-alt"></i> Заявки</a>
            <a href="manage-clients.php"><i class="fas fa-users"></i> Клиенты</a>
        </div>
    </nav>
    
    <main class="main-content">
        <div class="container">
            <h2 style="margin-top: 30px;">Управление заявками</h2>
            
            <?php if (isset($_GET['updated'])): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i> Статус заявки обновлен
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['deleted'])): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i> Заявка удалена
                </div>
            <?php endif; ?>
            
            <!-- Фильтры -->
            <div style="background: white; padding: 15px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                <h4 style="margin-bottom: 10px;">Фильтры:</h4>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="manage-requests.php" class="btn <?php echo !$status_filter && !$type_filter ? 'btn-primary' : 'btn-outline'; ?> btn-sm">
                        Все заявки (<?php echo $total_count; ?>)
                    </a>
                    <a href="manage-requests.php?status=new" class="btn <?php echo $status_filter == 'new' ? 'btn-primary' : 'btn-outline'; ?> btn-sm">
                        <i class="fas fa-star"></i> Новые (<?php echo $new_count; ?>)
                    </a>
                    <a href="manage-requests.php?type=callback" class="btn <?php echo $type_filter == 'callback' ? 'btn-primary' : 'btn-outline'; ?> btn-sm">
                        <i class="fas fa-phone"></i> Обратные звонки (<?php echo $callback_count; ?>)
                    </a>
                    <a href="manage-requests.php?type=consultation" class="btn <?php echo $type_filter == 'consultation' ? 'btn-primary' : 'btn-outline'; ?> btn-sm">
                        <i class="fas fa-comments"></i> Консультации
                    </a>
                </div>
            </div>
            
            <!-- Список заявок -->
            <div class="table-container">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Телефон</th>
                        <th>Тип</th>
                        <th>Сообщение</th>
                        <th>Статус</th>
                        <th>Дата</th>
                        <th>Действия</th>
                    </tr>
                    <?php while ($request = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $request['id']; ?></td>
                        <td><?php echo htmlspecialchars($request['name']); ?></td>
                        <td><?php echo htmlspecialchars($request['phone']); ?></td>
                        <td>
                            <?php 
                            $types = [
                                'callback' => 'Обратный звонок',
                                'consultation' => 'Консультация',
                                'contact' => 'Контактная форма',
                                'subscription' => 'Подписка'
                            ];
                            echo $types[$request['type']] ?? $request['type'];
                            ?>
                        </td>
                        <td style="max-width: 200px;"><?php echo htmlspecialchars(substr($request['message'] ?? '', 0, 50)) . '...'; ?></td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                <select name="status" onchange="this.form.submit()" style="padding: 5px; border: 1px solid #ddd; border-radius: 5px; font-size: 12px;">
                                    <option value="new" <?php echo $request['status'] == 'new' ? 'selected' : ''; ?>>Новая</option>
                                    <option value="processed" <?php echo $request['status'] == 'processed' ? 'selected' : ''; ?>>Обработана</option>
                                    <option value="spam" <?php echo $request['status'] == 'spam' ? 'selected' : ''; ?>>Спам</option>
                                </select>
                                <button type="submit" name="update_status" style="display: none;">Сохранить</button>
                            </form>
                        </td>
                        <td><?php echo date('d.m.Y H:i', strtotime($request['created_at'])); ?></td>
                        <td>
                            <a href="?delete_id=<?php echo $request['id']; ?>" class="btn btn-secondary btn-sm" onclick="return confirm('Удалить заявку?')">
                                <i class="fas fa-trash"></i> Удалить
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