<?php
require_once 'auth.php';

$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');

$client_id = intval($_GET['id']);

// Получение данных клиента
$client_query = "SELECT * FROM clients WHERE id = $client_id";
$client_result = $sql->query($client_query);
$client = $client_result->fetch_assoc();

if (!$client) {
    header('Location: manage-clients.php');
    exit;
}

// Обновление клиента
if (isset($_POST['update_client'])) {
    $name = $sql->real_escape_string($_POST['name']);
    $email = $sql->real_escape_string($_POST['email']);
    $phone = $sql->real_escape_string($_POST['phone']);
    $passport = $sql->real_escape_string($_POST['passport'] ?? '');
    
    $query = "UPDATE clients SET 
              name = '$name',
              email = '$email',
              phone = '$phone',
              passport = '$passport'
              WHERE id = $client_id";
    
    if ($sql->query($query)) {
        header('Location: manage-clients.php?updated=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование клиента - Anex Tour Чита</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <h1 style="margin: 0; font-size: 20px;">Редактирование клиента #<?php echo $client_id; ?></h1>
            <div>
                <a href="manage-clients.php" style="color: white; text-decoration: none; margin-right: 10px;">Назад</a>
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
                <form method="POST" style="background: white; padding: 20px; border-radius: 10px;">
                    <div class="form-row">
                        <div class="form-group" style="flex: 1;">
                            <label>Имя *</label>
                            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($client['name']); ?>" required>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Email *</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($client['email']); ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group" style="flex: 1;">
                            <label>Телефон *</label>
                            <input type="tel" name="phone" class="form-control" value="<?php echo htmlspecialchars($client['phone']); ?>" required>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Паспортные данные</label>
                            <input type="text" name="passport" class="form-control" value="<?php echo htmlspecialchars($client['passport'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Информация:</label>
                        <p style="margin-top: 5px;">
                            <small>
                                Дата регистрации: <?php echo date('d.m.Y H:i', strtotime($client['created_at'])); ?><br>
                                ID клиента: <?php echo $client['id']; ?>
                            </small>
                        </p>
                    </div>
                    
                    <button type="submit" name="update_client" class="btn btn-primary">Сохранить</button>
                    <a href="manage-clients.php" class="btn btn-secondary">Отмена</a>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
<?php $sql->close(); ?>