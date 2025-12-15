<?php
require_once 'auth.php';

$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');

$country_id = intval($_GET['id']);

// Получение данных страны
$country_query = "SELECT * FROM countries WHERE id = $country_id";
$country_result = $sql->query($country_query);
$country = $country_result->fetch_assoc();

if (!$country) {
    header('Location: manage-countries.php');
    exit;
}

if (isset($_POST['update_country'])) {
    $name = $sql->real_escape_string($_POST['name']);
    $is_popular = isset($_POST['is_popular']) ? 1 : 0;
    
    $query = "UPDATE countries SET 
              name = '$name',
              is_popular = '$is_popular'
              WHERE id = $country_id";
    
    if ($sql->query($query)) {
        header('Location: manage-countries.php?updated=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать страну - Anex Tour Чита</title>
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <h1 style="margin: 0; font-size: 20px;">Редактирование страны #<?php echo $country_id; ?></h1>
            <div>
                <a href="manage-countries.php" style="color: white; text-decoration: none; margin-right: 10px;">Назад</a>
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
            <a href="manage-countries.php"><i class="fas fa-flag"></i> Страны</a>
        </div>
    </nav>
    
    <main class="main-content">
        <div class="container">
            <div style="margin: 30px 0;">
                <form method="POST" style="background: white; padding: 20px; border-radius: 10px;">
                    <div class="form-group">
                        <label>Название страны *</label>
                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($country['name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="is_popular" <?php echo $country['is_popular'] ? 'checked' : ''; ?>> Популярная страна
                        </label>
                        <small style="color: #666; display: block; margin-top: 5px;">
                            Популярные страны отображаются на главной странице сайта
                        </small>
                    </div>
                    
                    <?php
                    // Получаем количество туров для этой страны
                    $tours_count = $sql->query("SELECT COUNT(*) as count FROM tours WHERE country_id = $country_id")->fetch_assoc()['count'];
                    ?>
                    
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                        <small>
                            <strong>Информация:</strong><br>
                            ID страны: <?php echo $country['id']; ?><br>
                            Дата добавления: <?php echo date('d.m.Y', strtotime($country['created_at'])); ?><br>
                            Туров в этой стране: <?php echo $tours_count; ?><br>
                            <?php if ($country['image'] && $country['image'] != 'default.jpg'): ?>
                                Изображение: <?php echo $country['image']; ?>
                            <?php endif; ?>
                        </small>
                    </div>
                    
                    <button type="submit" name="update_country" class="btn btn-primary">
                        <i class="fas fa-save"></i> Сохранить изменения
                    </button>
                    <a href="manage-countries.php" class="btn btn-secondary">Отмена</a>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
<?php $sql->close(); ?>