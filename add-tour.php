<?php
require_once 'auth.php';

$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');

// Обработка добавления тура
if (isset($_POST['add_tour'])) {
    $hotel_name = $sql->real_escape_string($_POST['hotel_name']);
    $country_id = $_POST['country_id'];
    $stars = $_POST['stars'];
    $nights = $_POST['nights'];
    $departure_city = $sql->real_escape_string($_POST['departure_city']);
    $departure_date = $_POST['departure_date'];
    $price = $_POST['price'];
    $old_price = !empty($_POST['old_price']) ? $_POST['old_price'] : 'NULL';
    $is_hot = isset($_POST['is_hot']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $places_available = $_POST['places_available'];
    $description = $sql->real_escape_string($_POST['description'] ?? '');
    
    // Генерация кода тура
    $tour_code = 'ANEX-' . strtoupper(substr($hotel_name, 0, 3)) . '-' . date('ymd') . rand(100, 999);
    
    $query = "INSERT INTO tours (
                tour_code, hotel_name, country_id, stars, nights, 
                departure_city, departure_date, price, old_price, 
                is_hot, is_active, places_available, description
              ) VALUES (
                '$tour_code', '$hotel_name', '$country_id', '$stars', '$nights',
                '$departure_city', '$departure_date', '$price', $old_price,
                '$is_hot', '$is_active', '$places_available', '$description'
              )";
    
    if ($sql->query($query)) {
        header('Location: manage-tours.php?success=1');
        exit;
    } else {
        $error = "Ошибка: " . $sql->error;
    }
}

$countries = $sql->query("SELECT * FROM countries ORDER BY name");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить тур - Anex Tour Чита</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <h1 style="margin: 0; font-size: 20px;">Добавить новый тур</h1>
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
            <a href="manage-clients.php">Клиенты</a>
        </div>
    </nav>
    
    <main class="main-content">
        <div class="container">
            <div style="margin: 30px 0;">
                <?php if (isset($error)): ?>
                    <div style="background: #fee; color: #d00; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" style="background: white; padding: 20px; border-radius: 10px;">
                    <div class="form-row">
                        <div class="form-group" style="flex: 2;">
                            <label>Название отеля *</label>
                            <input type="text" name="hotel_name" class="form-control" required>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Страна *</label>
                            <select name="country_id" class="form-control" required>
                                <option value="">Выберите страну</option>
                                <?php while ($country = $countries->fetch_assoc()): ?>
                                    <option value="<?php echo $country['id']; ?>">
                                        <?php echo $country['name']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group" style="flex: 1;">
                            <label>Звездность *</label>
                            <select name="stars" class="form-control" required>
                                <option value="5">5 звезд</option>
                                <option value="4">4 звезды</option>
                                <option value="3">3 звезды</option>
                                <option value="2">2 звезды</option>
                                <option value="1">1 звезда</option>
                            </select>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Ночей *</label>
                            <input type="number" name="nights" class="form-control" min="1" required>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Мест доступно</label>
                            <input type="number" name="places_available" class="form-control" min="0" value="10">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group" style="flex: 1;">
                            <label>Город вылета *</label>
                            <input type="text" name="departure_city" class="form-control" value="Чита" required>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Дата вылета *</label>
                            <input type="date" name="departure_date" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group" style="flex: 1;">
                            <label>Цена * (руб.)</label>
                            <input type="number" name="price" class="form-control" min="0" step="1000" required>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Старая цена (руб.)</label>
                            <input type="number" name="old_price" class="form-control" min="0" step="1000">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Описание тура</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Подробное описание отеля и тура..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="is_hot"> Горящий тур
                        </label>
                        <label style="margin-left: 20px;">
                            <input type="checkbox" name="is_active" checked> Активный
                        </label>
                    </div>
                    
                    <button type="submit" name="add_tour" class="btn btn-primary">Добавить тур</button>
                    <a href="manage-tours.php" class="btn btn-secondary">Отмена</a>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
<?php $sql->close(); ?>