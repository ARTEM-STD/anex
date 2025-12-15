<?php
require_once 'auth.php';

$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');

$service_id = intval($_GET['id']);

// Получение данных услуги
$service_query = "SELECT * FROM services WHERE id = $service_id";
$service_result = $sql->query($service_query);
$service = $service_result->fetch_assoc();

if (!$service) {
    header('Location: manage-services.php');
    exit;
}

if (isset($_POST['update_service'])) {
    $title = $sql->real_escape_string($_POST['title']);
    $description = $sql->real_escape_string($_POST['description']);
    $icon = $sql->real_escape_string($_POST['icon']);
    $price_from = !empty($_POST['price_from']) ? $_POST['price_from'] : 'NULL';
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $query = "UPDATE services SET 
              title = '$title',
              description = '$description',
              icon = '$icon',
              price_from = $price_from,
              is_active = '$is_active'
              WHERE id = $service_id";
    
    if ($sql->query($query)) {
        header('Location: manage-services.php?updated=1');
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
    <title>Редактировать услугу - Anex Tour Чита</title>
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <h1 style="margin: 0; font-size: 20px;">Редактирование услуги #<?php echo $service_id; ?></h1>
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
                        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($service['title']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Описание услуги *</label>
                        <textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($service['description']); ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group" style="flex: 1;">
                            <label>Иконка (Font Awesome)</label>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <input type="text" name="icon" class="form-control" 
                                       value="<?php echo htmlspecialchars($service['icon'] ?? ''); ?>" 
                                       placeholder="fas fa-icon-name" id="icon-input">
                                <div id="icon-preview" style="font-size: 24px; color: #3498db; min-width: 40px; text-align: center;">
                                    <?php if ($service['icon']): ?>
                                        <i class="<?php echo htmlspecialchars($service['icon']); ?>"></i>
                                    <?php endif; ?>
                                </div>
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
                            <input type="number" name="price_from" class="form-control" 
                                   value="<?php echo $service['price_from'] ?? ''; ?>" min="0" step="100">
                            <small style="color: #666; margin-top: 5px; display: block;">
                                Оставьте пустым, если услуга бесплатная или цена договорная
                            </small>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="is_active" <?php echo $service['is_active'] ? 'checked' : ''; ?>> Активная услуга
                        </label>
                    </div>
                    
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                        <small>
                            <strong>Информация:</strong><br>
                            ID услуги: <?php echo $service['id']; ?><br>
                            Порядок сортировки: <?php echo $service['sort_order']; ?>
                        </small>
                    </div>
                    
                    <button type="submit" name="update_service" class="btn btn-primary">
                        <i class="fas fa-save"></i> Сохранить изменения
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
        });
        </script>
    </main>
</body>
</html>
<?php $sql->close(); ?>