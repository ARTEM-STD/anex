<?php
require_once 'auth.php';

$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');

$news_id = intval($_GET['id']);

// Получение данных новости
$news_query = "SELECT * FROM news WHERE id = $news_id";
$news_result = $sql->query($news_query);
$news = $news_result->fetch_assoc();

if (!$news) {
    header('Location: manage-news.php');
    exit;
}

if (isset($_POST['update_news'])) {
    $title = $sql->real_escape_string($_POST['title']);
    $content = $sql->real_escape_string($_POST['content']);
    $excerpt = $sql->real_escape_string($_POST['excerpt']);
    $category = $_POST['category'];
    $is_published = isset($_POST['is_published']) ? 1 : 0;
    
    $query = "UPDATE news SET 
              title = '$title',
              content = '$content',
              excerpt = '$excerpt',
              category = '$category',
              is_published = '$is_published'
              WHERE id = $news_id";
    
    if ($sql->query($query)) {
        header('Location: manage-news.php?updated=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать новость - Anex Tour Чита</title>
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <h1 style="margin: 0; font-size: 20px;">Редактирование новости #<?php echo $news_id; ?></h1>
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
                        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($news['title']); ?>" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group" style="flex: 1;">
                            <label>Категория *</label>
                            <select name="category" class="form-control" required>
                                <option value="news" <?php echo $news['category'] == 'news' ? 'selected' : ''; ?>>Новость</option>
                                <option value="article" <?php echo $news['category'] == 'article' ? 'selected' : ''; ?>>Статья</option>
                                <option value="promo" <?php echo $news['category'] == 'promo' ? 'selected' : ''; ?>>Акция</option>
                                <option value="travel_tips" <?php echo $news['category'] == 'travel_tips' ? 'selected' : ''; ?>>Советы путешественникам</option>
                            </select>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Краткое описание (анонс)</label>
                            <input type="text" name="excerpt" class="form-control" value="<?php echo htmlspecialchars($news['excerpt'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Содержимое новости *</label>
                        <textarea name="content" class="form-control" rows="10" required><?php echo htmlspecialchars($news['content']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="is_published" <?php echo $news['is_published'] ? 'checked' : ''; ?>> Опубликовано
                        </label>
                    </div>
                    
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                        <small>
                            <strong>Информация:</strong><br>
                            Дата создания: <?php echo date('d.m.Y H:i', strtotime($news['created_at'])); ?><br>
                            Просмотров: <?php echo $news['views']; ?><br>
                            ID: <?php echo $news['id']; ?>
                        </small>
                    </div>
                    
                    <button type="submit" name="update_news" class="btn btn-primary">
                        <i class="fas fa-save"></i> Сохранить изменения
                    </button>
                    <a href="manage-news.php" class="btn btn-secondary">Отмена</a>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
<?php $sql->close(); ?>