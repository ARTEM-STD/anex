<?php
require_once 'auth.php';

$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');

if (isset($_POST['add_news'])) {
    $title = $sql->real_escape_string($_POST['title']);
    $slug = $sql->real_escape_string(strtolower(str_replace(' ', '-', $title)) . '-' . time());
    $content = $sql->real_escape_string($_POST['content']);
    $excerpt = $sql->real_escape_string($_POST['excerpt']);
    $category = $_POST['category'];
    $is_published = isset($_POST['is_published']) ? 1 : 0;
    
    $query = "INSERT INTO news (title, slug, content, excerpt, category, is_published, author_id) 
              VALUES ('$title', '$slug', '$content', '$excerpt', '$category', '$is_published', " . $_SESSION['admin_id'] . ")";
    
    if ($sql->query($query)) {
        header('Location: manage-news.php?success=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить новость - Anex Tour Чита</title>
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <h1 style="margin: 0; font-size: 20px;">Добавить новую новость</h1>
            <div>
                <a href="manage-news.php" style="color: white; text-decoration: none; margin-right: 10px;">Назад</a>
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
            <a href="manage-news.php"><i class="fas fa-newspaper"></i> Новости</a>
        </div>
    </nav>
    
    <main class="main-content">
        <div class="container">
            <div style="margin: 30px 0;">
                <form method="POST" style="background: white; padding: 20px; border-radius: 10px;">
                    <div class="form-group">
                        <label>Заголовок новости *</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group" style="flex: 1;">
                            <label>Категория *</label>
                            <select name="category" class="form-control" required>
                                <option value="news">Новость</option>
                                <option value="article">Статья</option>
                                <option value="promo">Акция</option>
                                <option value="travel_tips">Советы путешественникам</option>
                            </select>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Краткое описание (анонс)</label>
                            <input type="text" name="excerpt" class="form-control">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Содержимое новости *</label>
                        <textarea name="content" class="form-control" rows="10" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="is_published" checked> Опубликовать сразу
                        </label>
                    </div>
                    
                    <button type="submit" name="add_news" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Добавить новость
                    </button>
                    <a href="manage-news.php" class="btn btn-secondary">Отмена</a>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
<?php $sql->close(); ?>