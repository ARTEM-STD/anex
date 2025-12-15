<?php

require_once 'auth.php'; // Добавьте эту строку

$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');

$tour_id = intval($_GET['id']);
require_once 'auth.php'; 
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}   

$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');

$tour_id = $_GET['id'];

// Получение данных тура
$tour_query = "SELECT * FROM tours WHERE id = $tour_id";
$tour_result = $sql->query($tour_query);
$tour = $tour_result->fetch_assoc();

// Обновление тура
if (isset($_POST['update_tour'])) {
    $hotel_name = $_POST['hotel_name'];
    $country_id = $_POST['country_id'];
    $stars = $_POST['stars'];
    $nights = $_POST['nights'];
    $departure_city = $_POST['departure_city'];
    $departure_date = $_POST['departure_date'];
    $price = $_POST['price'];
    $old_price = $_POST['old_price'] ?: 'NULL';
    $is_hot = isset($_POST['is_hot']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $query = "UPDATE tours SET 
              hotel_name = '$hotel_name',
              country_id = '$country_id',
              stars = '$stars',
              nights = '$nights',
              departure_city = '$departure_city',
              departure_date = '$departure_date',
              price = '$price',
              old_price = $old_price,
              is_hot = '$is_hot',
              is_active = '$is_active'
              WHERE id = $tour_id";
    
    if ($sql->query($query)) {
        header('Location: manage-tours.php');
        exit;
    }
}

$countries = $sql->query("SELECT * FROM countries ORDER BY name");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование тура - Anex Tour Чита</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <h1 style="margin: 0; font-size: 20px;">Редактирование тура #<?php echo $tour_id; ?></h1>
            <div>
                <a href="manage-tours.php" style="color: white; text-decoration: none; margin-right: 10px;">Назад</a>
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
        </div>
    </nav>
    
    <main class="main-content">
        <div class="container">
            <div style="margin: 30px 0;">
                <form method="POST" style="background: white; padding: 20px; border-radius: 10px;">
                    <div class="form-row">
                        <div class="form-group" style="flex: 1;">
                            <label>Название отеля</label>
                            <input type="text" name="hotel_name" class="form-control" value="<?php echo $tour['hotel_name']; ?>" required>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Страна</label>
                            <select name="country_id" class="form-control" required>
                                <?php while ($country = $countries->fetch_assoc()): ?>
                                    <option value="<?php echo $country['id']; ?>" <?php echo $country['id'] == $tour['country_id'] ? 'selected' : ''; ?>>
                                        <?php echo $country['name']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group" style="flex: 1;">
                            <label>Звездность</label>
                            <select name="stars" class="form-control" required>
                                <option value="5" <?php echo $tour['stars'] == 5 ? 'selected' : ''; ?>>5</option>
                                <option value="4" <?php echo $tour['stars'] == 4 ? 'selected' : ''; ?>>4</option>
                                <option value="3" <?php echo $tour['stars'] == 3 ? 'selected' : ''; ?>>3</option>
                            </select>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Цена</label>
                            <input type="number" name="price" class="form-control" value="<?php echo $tour['price']; ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group" style="flex: 1;">
                            <label>Старая цена</label>
                            <input type="number" name="old_price" class="form-control" value="<?php echo $tour['old_price']; ?>">
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Ночей</label>
                            <input type="number" name="nights" class="form-control" value="<?php echo $tour['nights']; ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group" style="flex: 1;">
                            <label>Город вылета</label>
                            <input type="text" name="departure_city" class="form-control" value="<?php echo $tour['departure_city']; ?>" required>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Дата вылета</label>
                            <input type="date" name="departure_date" class="form-control" value="<?php echo $tour['departure_date']; ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="is_hot" <?php echo $tour['is_hot'] ? 'checked' : ''; ?>> Горящий тур
                        </label>
                        <label style="margin-left: 20px;">
                            <input type="checkbox" name="is_active" <?php echo $tour['is_active'] ? 'checked' : ''; ?>> Активный
                        </label>
                    </div>
                    
                    <button type="submit" name="update_tour" class="btn btn-primary">Сохранить</button>
                    <a href="manage-tours.php" class="btn btn-secondary">Отмена</a>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
<?php $sql->close(); ?>