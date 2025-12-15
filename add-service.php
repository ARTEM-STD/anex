<?php
require_once 'auth.php';

$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');

if (isset($_POST['add_service'])) {
    $title = $sql->real_escape_string($_POST['title']);
    $description = $sql->real_escape_string($_POST['description']);
    $icon = $sql->real_escape_string($_POST['icon']);
    $price_from = !empty($_POST['price_from']) ? $_POST['price_from'] : 'NULL';
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $query = "INSERT INTO services (title, description, icon, price_from, is_active) 
              VALUES ('$title', '$description', '$icon', $price_from, '$is_active')";
    
    if ($sql->query($query)) {
        header('Location: manage-services.php?success=1');
        exit;
    }
}

// Популярные иконки Font Awesome
$popular_icons = [
    'fas fa-umbrella-beach',
    'fas fa-plane',
    'fas fa-hotel',
    'fas fa-passport',
    'fas fa-car',
    'fas fa-ship',
    'fas fa-bus',
    'fas fa-train',
    'fas fa-money-bill-wave',
    'fas fa-shield-alt',
    'fas fa-suitcase',
    'fas fa-globe-americas',
    'fas fa-concierge-bell',
    'fas fa-ring',
    'fas fa-gift'
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить услугу - Anex Tour Чита</title>
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <h1 style="margin: 0; font-size: 20px;">Добавить новую услугу</h1>
            <div>
                <a href="manage-services.php" style="color: white; text-decoration: none; margin-right: 10px;">Назад</a>
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
            <a href="manage-services.php"><i class="fas fa-concierge-bell"></i> Услуги</a>
        </div>
    </nav>
    
    <main class="main-content">
        <div class="container">
            <div style="margin: 30px 0;">
                <form method="POST" style="background: white; padding: 20px; border-radius: 10px;">
                    <div class="form-group">
                        <label>Название услуги *</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Описание услуги *</label>
                        <textarea name="description" class="form-control" rows="4" required></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group" style="flex: 1;">
                            <label>Иконка (Font Awesome)</label>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <input type="text" name="icon" class="form-control" placeholder="fas fa-icon-name" id="icon-input">
                                <div id="icon-preview" style="font-size: 24px; color: #3498db; min-width: 40px; text-align: center;"></div>
                            </div>
                            <small style="color: #666; margin-top: 5px; display: block;">
                                Используйте классы Font Awesome, например: fas fa-plane
                            </small>
                            
                            <div style="margin-top: 15px;">
                                <strong>Популярные иконки:</strong>
                                <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px;">
                                    <?php foreach ($popular_icons as $icon_class): ?>
                                        <button type="button" class="icon-selector" data-icon="<?php echo $icon_class; ?>" 
                                                style="background: #f8f9fa; border: 1px solid #ddd; padding: 8px; border-radius: 5px; cursor: pointer;">
                                            <i class="<?php echo $icon_class; ?>"></i>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group" style="flex: 1;">
                            <label>Цена от (руб.)</label>
                            <input type="number" name="price_from" class="form-control" min="0" step="100">
                            <small style="color: #666; margin-top: 5px; display: block;">
                                Оставьте пустым, если услуга бесплатная или цена договорная
                            </small>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="is_active" checked> Активная услуга
                        </label>
                    </div>
                    
                    <button type="submit" name="add_service" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Добавить услугу
                    </button>
                    <a href="manage-services.php" class="btn btn-secondary">Отмена</a>
                </form>
            </div>
        </div>
        
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const iconInput = document.getElementById('icon-input');
            const iconPreview = document.getElementById('icon-preview');
            
            // Обновление превью иконки
            function updateIconPreview() {
                const iconClass = iconInput.value.trim();
                if (iconClass) {
                    iconPreview.innerHTML = `<i class="${iconClass}"></i>`;
                } else {
                    iconPreview.innerHTML = '';
                }
            }
            
            iconInput.addEventListener('input', updateIconPreview);
            
            // Выбор иконки из списка
            document.querySelectorAll('.icon-selector').forEach(button => {
                button.addEventListener('click', function() {
                    const iconClass = this.getAttribute('data-icon');
                    iconInput.value = iconClass;
                    updateIconPreview();
                });
            });
            
            // Инициализация превью
            updateIconPreview();
        });
        </script>
    </main>
</body>
</html>
<?php $sql->close(); ?>