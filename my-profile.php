<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /pages/login.php');
    exit;
}

$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');
$user_id = $_SESSION['user_id'];

// Получаем данные пользователя
$user_query = "SELECT * FROM clients WHERE id = $user_id";
$user_result = $sql->query($user_query);
$user = $user_result->fetch_assoc();

// Обновление профиля
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $passport = $_POST['passport'];
    
    $update_query = "UPDATE clients SET 
                    name = '$name',
                    phone = '$phone',
                    email = '$email',
                    passport = '$passport'
                    WHERE id = $user_id";
    
    if ($sql->query($update_query)) {
        $success = "Профиль успешно обновлен";
        // Обновляем данные в переменной
        $user['name'] = $name;
        $user['phone'] = $phone;
        $user['email'] = $email;
        $user['passport'] = $passport;
    } else {
        $error = "Ошибка обновления профиля";
    }
}

// Смена пароля
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($user['password'] == $current_password) {
        if ($new_password == $confirm_password) {
            $update_pass_query = "UPDATE clients SET password = '$new_password' WHERE id = $user_id";
            if ($sql->query($update_pass_query)) {
                $password_success = "Пароль успешно изменен";
            }
        } else {
            $password_error = "Новые пароли не совпадают";
        }
    } else {
        $password_error = "Текущий пароль неверен";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мой профиль - Anex Tour Чита</title>
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="site-header">
        <div class="header-top">
            <div class="container">
                <div class="logo">
                    <h1><a href="/">Anex Tour <span class="city-label">Чита</span></a></h1>
                    <p class="tagline">Личный кабинет клиента</p>
                </div>
            </div>
        </div>
        
        <nav class="main-nav">
            <div class="container">
                <ul class="nav-menu">
                    <li><a href="/"><i class="fas fa-home"></i> На сайт</a></li>
                    <li><a href="/cabinet/index.php"><i class="fas fa-tachometer-alt"></i> Обзор</a></li>
                    <li><a href="/cabinet/my-bookings.php"><i class="fas fa-suitcase"></i> Мои бронирования</a></li>
                    <li><a href="/cabinet/my-profile.php" class="active"><i class="fas fa-user-cog"></i> Мой профиль</a></li>
                    <li><a href="/cabinet/my-documents.php"><i class="fas fa-file-alt"></i> Мои документы</a></li>
                </ul>
                
                <div class="header-actions">
                    <a href="/forms/logout.php" class="btn btn-secondary">
                        <i class="fas fa-sign-out-alt"></i> Выйти
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <main class="main-content">
        <div class="container">
            <div class="page-header">
                <h1>Мой профиль</h1>
                <p>Управление личными данными</p>
            </div>
            
            <div class="booking-form-container">
                <!-- ОСНОВНАЯ ИНФОРМАЦИЯ -->
                <h3>Основная информация</h3>
                
                <?php if (isset($success)): ?>
                    <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 5px; margin-bottom: 20px;">
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                    <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 5px; margin-bottom: 20px;">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-row">
                        <div class="form-group" style="flex: 1;">
                            <label>Имя</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $user['name']; ?>" required>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Телефон</label>
                            <input type="tel" name="phone" class="form-control" value="<?php echo $user['phone']; ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group" style="flex: 1;">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Паспортные данные</label>
                            <input type="text" name="passport" class="form-control" value="<?php echo $user['passport'] ?? ''; ?>">
                        </div>
                    </div>
                    
                    <button type="submit" name="update_profile" class="btn btn-primary">
                        <i class="fas fa-save"></i> Сохранить изменения
                    </button>
                </form>
                
                <!-- СМЕНА ПАРОЛЯ -->
                <h3 style="margin-top: 40px;">Смена пароля</h3>
                
                <?php if (isset($password_success)): ?>
                    <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 5px; margin-bottom: 20px;">
                        <?php echo $password_success; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($password_error)): ?>
                    <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 5px; margin-bottom: 20px;">
                        <?php echo $password_error; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-row">
                        <div class="form-group" style="flex: 1;">
                            <label>Текущий пароль</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group" style="flex: 1;">
                            <label>Новый пароль</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Повторите новый пароль</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                    </div>
                    
                    <button type="submit" name="change_password" class="btn btn-primary">
                        <i class="fas fa-key"></i> Сменить пароль
                    </button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
<?php $sql->close(); ?>