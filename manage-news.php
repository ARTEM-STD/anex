<?php
require_once 'auth.php';

$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');

// Удаление новости
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $sql->query("DELETE FROM news WHERE id = $id");
    header('Location: manage-news.php?deleted=1');
    exit;
}

// Получение всех новостей
$news = $sql->query("SELECT * FROM news ORDER BY published_at DESC");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление новостями - Anex Tour Чита</title>
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <h1 style="margin: 0; font-size: 20px;">Управление новостями</h1>
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
            <a href="manage-news.php" class="active"><i class="fas fa-newspaper"></i> Новости</a>
        </div>
    </nav>
    
    <main class="main-content">
        <div class="container">
            <h2 style="margin-top: 30px;">Новости и статьи</h2>
            
            <?php if (isset($_GET['deleted'])): ?>
                <div class="success-message">Новость удалена</div>
            <?php endif; ?>
            
            <!-- Кнопка добавления -->
            <div style="margin-bottom: 20px;">
                <a href="add-news.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Добавить новость
                </a>
                <a href="/pages/news.php" target="_blank" class="btn btn-outline">
                    <i class="fas fa-external-link-alt"></i> Посмотреть на сайте
                </a>
            </div>
            
            <!-- Список новостей -->
            <div class="table-container">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Заголовок</th>
                        <th>Категория</th>
                        <th>Дата публикации</th>
                        <th>Просмотры</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                    <?php while ($item = $news->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $item['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($item['title']); ?></strong></td>
                        <td>
                            <?php 
                            $categories = [
                                'news' => 'Новость',
                                'article' => 'Статья',
                                'promo' => 'Акция',
                                'travel_tips' => 'Советы'
                            ];
                            echo $categories[$item['category']] ?? $item['category'];
                            ?>
                        </td>
                        <td><?php echo date('d.m.Y H:i', strtotime($item['published_at'])); ?></td>
                        <td><?php echo $item['views']; ?></td>
                        <td>
                            <?php if ($item['is_published']): ?>
                                <span style="color: #2ecc71; font-size: 12px; padding: 3px 8px; background: #d4edda; border-radius: 3px;">
                                    <i class="fas fa-eye"></i> Опубликована
                                </span>
                            <?php else: ?>
                                <span style="color: #f39c12; font-size: 12px; padding: 3px 8px; background: #fff3cd; border-radius: 3px;">
                                    <i class="fas fa-eye-slash"></i> Черновик
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit-news.php?id=<?php echo $item['id']; ?>" class="btn btn-primary btn-sm">Редактировать</a>
                            <a href="?delete_id=<?php echo $item['id']; ?>" class="btn btn-secondary btn-sm" onclick="return confirm('Удалить новость?')">Удалить</a>
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