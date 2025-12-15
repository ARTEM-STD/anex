<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /pages/login.php');
    exit;
}

$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');
$user_id = $_SESSION['user_id'];

// Получаем документы пользователя
$documents_query = "SELECT d.*, b.booking_code 
                   FROM documents d 
                   LEFT JOIN bookings b ON d.booking_id = b.id 
                   WHERE b.client_id = $user_id 
                   ORDER BY d.created_at DESC";
$documents_result = $sql->query($documents_query);
$documents = [];
while ($row = $documents_result->fetch_assoc()) {
    $documents[] = $row;
}

// Если таблицы документов нет, показываем информацию о бронированиях
$bookings_query = "SELECT b.*, t.hotel_name FROM bookings b 
                   LEFT JOIN tours t ON b.tour_id = t.id 
                   WHERE b.client_id = $user_id AND b.status IN ('confirmed', 'paid') 
                   ORDER BY b.created_at DESC";
$bookings_result = $sql->query($bookings_query);
$bookings = [];
while ($row = $bookings_result->fetch_assoc()) {
    $bookings[] = $row;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мои документы - Anex Tour Чита</title>
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
                    <li><a href="/cabinet/my-profile.php"><i class="fas fa-user-cog"></i> Мой профиль</a></li>
                    <li><a href="/cabinet/my-documents.php" class="active"><i class="fas fa-file-alt"></i> Мои документы</a></li>
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
            <div class="page-header" style="padding: 40px 0 20px;">
                <h1>Мои документы</h1>
                <p>Ваши туристические документы и ваучеры</p>
            </div>
            
            <?php if (!empty($documents)): ?>
                <div class="table-container">
                    <table>
                        <tr>
                            <th>Название документа</th>
                            <th>Тип</th>
                            <th>Бронирование</th>
                            <th>Дата добавления</th>
                            <th>Действия</th>
                        </tr>
                        <?php foreach ($documents as $doc): ?>
                        <tr>
                            <td>
                                <i class="fas fa-file-pdf" style="color: #e74c3c; margin-right: 10px;"></i>
                                <?php echo $doc['title']; ?>
                            </td>
                            <td>
                                <?php 
                                $types = [
                                    'voucher' => 'Ваучер',
                                    'contract' => 'Договор',
                                    'ticket' => 'Билет',
                                    'insurance' => 'Страховка',
                                    'other' => 'Другое'
                                ];
                                echo $types[$doc['type']] ?? $doc['type'];
                                ?>
                            </td>
                            <td><?php echo $doc['booking_code']; ?></td>
                            <td><?php echo date('d.m.Y', strtotime($doc['created_at'])); ?></td>
                            <td>
                                <a href="/uploads/documents/<?php echo $doc['file_name']; ?>" class="btn btn-outline btn-sm" target="_blank" download>
                                    <i class="fas fa-download"></i> Скачать
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php else: ?>
                <!-- Если документов нет, показываем информацию о подтвержденных бронированиях -->
                <?php if (!empty($bookings)): ?>
                    <div class="table-container">
                        <h3>Подтвержденные бронирования</h3>
                        <p>Документы по этим бронированиям появятся здесь после полной оплаты</p>
                        <table>
                            <tr>
                                <th>Код бронирования</th>
                                <th>Отель</th>
                                <th>Статус</th>
                                <th>Дата вылета</th>
                            </tr>
                            <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?php echo $booking['booking_code']; ?></td>
                                <td><?php echo $booking['hotel_name']; ?></td>
                                <td class="status-<?php echo $booking['status']; ?>">
                                    <?php echo $booking['status'] == 'confirmed' ? 'Подтверждено' : 'Оплачено'; ?>
                                </td>
                                <td><?php echo $booking['departure_date']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                <?php else: ?>
                    <div style="text-align: center; padding: 60px 20px;">
                        <i class="fas fa-file-alt fa-4x" style="color: #ddd; margin-bottom: 20px;"></i>
                        <h3>Документы пока отсутствуют</h3>
                        <p style="margin: 20px 0;">Документы по вашим бронированиям появятся здесь после подтверждения заказа</p>
                        <a href="/cabinet/my-bookings.php" class="btn btn-primary">
                            <i class="fas fa-suitcase"></i> Мои бронирования
                        </a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            
            <!-- ИНФОРМАЦИЯ О ДОКУМЕНТАХ -->
            <div class="booking-form-container mt-4">
                <h3>Информация о документах</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
                    <div>
                        <h4><i class="fas fa-file-contract" style="color: #3498db;"></i> Договор</h4>
                        <p>Договор на оказание туристических услуг. Обязателен для подписания.</p>
                    </div>
                    <div>
                        <h4><i class="fas fa-hotel" style="color: #27ae60;"></i> Ваучер</h4>
                        <p>Документ для заселения в отель. Распечатайте и возьмите с собой.</p>
                    </div>
                    <div>
                        <h4><i class="fas fa-shield-alt" style="color: #f39c12;"></i> Страховка</h4>
                        <p>Полис медицинского страхования для выезда за рубеж.</p>
                    </div>
                    <div>
                        <h4><i class="fas fa-plane" style="color: #e74c3c;"></i> Билеты</h4>
                        <p>Авиабилеты и документы на трансфер.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
<?php $sql->close(); ?>