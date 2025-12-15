<?php
require_once 'auth.php';

$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');

// Добавление страны
if (isset($_POST['add_country'])) {
    $name = $sql->real_escape_string($_POST['name']);
    $is_popular = isset($_POST['is_popular']) ? 1 : 0;
    
    $query = "INSERT INTO countries (name, is_popular) VALUES ('$name', '$is_popular')";
    if ($sql->query($query)) {
        header('Location: manage-countries.php?success=1');
        exit;
    }
}

// Удаление страны
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $sql->query("DELETE FROM countries WHERE id = $id");
    header('Location: manage-countries.php?deleted=1');
    exit;
}

// Получение всех стран
$countries = $sql->query("SELECT * FROM countries ORDER BY name");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление странами - Anex Tour Чита</title>
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <h1 style="margin: 0; font-size: 20px;">Управление странами</h1>
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
            <a href="manage-countries.php" class="active"><i class="fas fa-flag"></i> Страны</a>
        </div>
    </nav>
    
    <main class="main-content">
        <div class="container">
            <h2 style="margin-top: 30px;">Список стран</h2>
            
            <?php if (isset($_GET['success'])): ?>
                <div class="success-message">Страна успешно добавлена</div>
            <?php endif; ?>
            
            <?php if (isset($_GET['deleted'])): ?>
                <div class="success-message">Страна удалена</div>
            <?php endif; ?>
            
            <!-- Форма добавления страны -->
            <div style="background: white; padding: 20px; border-radius: 10px; margin-bottom: 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                <h3 style="margin-bottom: 15px;">Добавить новую страну</h3>
                <form method="POST" style="display: flex; gap: 15px; align-items: flex-end;">
                    <div style="flex: 1;">
                        <label>Название страны *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div style="flex: 0 0 150px;">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="is_popular" style="margin: 0;">
                            Популярная страна
                        </label>
                    </div>
                    <div>
                        <button type="submit" name="add_country" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Добавить
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Список стран -->
            <div class="table-container">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Популярная</th>
                        <th>Кол-во туров</th>
                        <th>Действия</th>
                    </tr>
                    <?php while ($country = $countries->fetch_assoc()): 
                        $tours_count = $sql->query("SELECT COUNT(*) as count FROM tours WHERE country_id = " . $country['id'])->fetch_assoc()['count'];
                    ?>
                    <tr>
                        <td><?php echo $country['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($country['name']); ?></strong></td>
                        <td>
                            <?php if ($country['is_popular']): ?>
                                <span style="color: #2ecc71; font-size: 12px; padding: 3px 8px; background: #d4edda; border-radius: 3px;">
                                    <i class="fas fa-star"></i> Да
                                </span>
                            <?php else: ?>
                                <span style="color: #666; font-size: 12px;">Нет</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $tours_count; ?></td>
                        <td>
                            <a href="edit-country.php?id=<?php echo $country['id']; ?>" class="btn btn-primary btn-sm">Редактировать</a>
                            <a href="?delete_id=<?php echo $country['id']; ?>" class="btn btn-secondary btn-sm" onclick="return confirm('Удалить страну? Это также удалит все связанные туры.')">Удалить</a>
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