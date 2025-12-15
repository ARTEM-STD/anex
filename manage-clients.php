<?php
require_once 'auth.php';

$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');

// Удаление клиента
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $sql->query("DELETE FROM clients WHERE id = $id");
    header('Location: manage-clients.php?deleted=1');
    exit;
}

// Получение всех клиентов
$query = "SELECT * FROM clients ORDER BY created_at DESC";
$result = $sql->query($query);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление клиентами - Anex Tour Чита</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <h1 style="margin: 0; font-size: 20px;">Управление клиентами</h1>
            <div>
                <a href="index.php" style="color: white; text-decoration: none; margin-right: 10px;">Назад</a>
                <a href="logout.php" style="color: white; text-decoration: none; background: #e74c3c; padding: 5px 10px; border-radius: 5px;">Выйти</a>
            </div>
        </div>
    </header>
    
    <nav class="admin-nav">
        <div class="container">
            <a href="index.php">Главная</a>
            <a href="manage-tours.php">Туры</a>
            <a href="manage-bookings.php">Бронирования</a>
            <a href="manage-requests.php">Заявки</a>
            <a href="manage-clients.php" class="active">Клиенты</a>
        </div>
    </nav>
    
    <main class="main-content">
        <div class="container">
            <h2 style="margin-top: 30px;">Все клиенты</h2>
            
            <?php if (isset($_GET['deleted'])): ?>
                <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    Клиент успешно удален
                </div>
            <?php endif; ?>
            
            <div class="table-container">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Телефон</th>
                        <th>Паспорт</th>
                        <th>Дата регистрации</th>
                        <th>Действия</th>
                    </tr>
                    <?php while ($client = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $client['id']; ?></td>
                        <td><?php echo htmlspecialchars($client['name']); ?></td>
                        <td><?php echo htmlspecialchars($client['email']); ?></td>
                        <td><?php echo htmlspecialchars($client['phone']); ?></td>
                        <td><?php echo htmlspecialchars($client['passport'] ?? '-'); ?></td>
                        <td><?php echo date('d.m.Y H:i', strtotime($client['created_at'])); ?></td>
                        <td>
                            <a href="edit-client.php?id=<?php echo $client['id']; ?>" class="btn btn-primary btn-sm">Редактировать</a>
                            <a href="?delete_id=<?php echo $client['id']; ?>" class="btn btn-secondary btn-sm" onclick="return confirm('Удалить клиента?')">Удалить</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
            
            <div style="margin-top: 30px;">
                <h3>Статистика</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin-top: 15px;">
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; text-align: center;">
                        <strong>Всего клиентов:</strong><br>
                        <?php echo $result->num_rows; ?>
                    </div>
                    <?php
                    $today = date('Y-m-d');
                    $today_clients = $sql->query("SELECT COUNT(*) as count FROM clients WHERE DATE(created_at) = '$today'")->fetch_assoc()['count'];
                    ?>
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; text-align: center;">
                        <strong>За сегодня:</strong><br>
                        <?php echo $today_clients; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
<?php $sql->close(); ?>