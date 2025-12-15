<?php
session_start();
$sql = new mysqli('127.0.0.1', 'root', '', 'anex_chit', '3306');

$tour_id = $_POST['tour_id'];
$client_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$client_name = $_POST['client_name'];
$client_phone = $_POST['client_phone'];
$client_email = $_POST['client_email'];
$client_passport = $_POST['client_passport'] ?? '';
$adults = $_POST['adults'];
$children = $_POST['children'] ?? 0;
$notes = $_POST['notes'] ?? '';

// Если пользователь не авторизован, создаем запись клиента
if ($client_id == 0) {
    // Проверяем, есть ли уже клиент с таким email
    $check_client = $sql->query("SELECT id FROM clients WHERE email = '$client_email'");
    if ($check_client->num_rows > 0) {
        $client = $check_client->fetch_assoc();
        $client_id = $client['id'];
    } else {
        // Создаем нового клиента
        $create_client = "INSERT INTO clients (name, email, phone, passport, password, created_at) 
                         VALUES ('$client_name', '$client_email', '$client_phone', '$client_passport', 'temp123', NOW())";
        $sql->query($create_client);
        $client_id = $sql->insert_id;
    }
}

// Получаем цену тура
$tour_query = "SELECT price FROM tours WHERE id = $tour_id";
$tour_result = $sql->query($tour_query);
$tour_data = $tour_result->fetch_assoc();
$price_per_person = $tour_data['price'];

// Расчет стоимости
$total_price = $price_per_person * $adults + ($price_per_person * $children * 0.7);

// Генерация кода бронирования
$booking_code = 'ANEX-' . date('ymd') . '-' . rand(1000, 9999);

// Сохраняем бронирование
$query = "INSERT INTO bookings (booking_code, tour_id, client_id, client_name, client_phone, client_email, 
          client_passport, adults, children, total_price, notes, status, created_at) 
          VALUES ('$booking_code', '$tour_id', '$client_id', '$client_name', '$client_phone', '$client_email', 
          '$client_passport', '$adults', '$children', '$total_price', '$notes', 'pending', NOW())";

if ($sql->query($query)) {
    // Если пользователь был не авторизован, логиним его
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['user_id'] = $client_id;
        $_SESSION['user_name'] = $client_name;
        $_SESSION['user_email'] = $client_email;
    }
    
    header('Location: /pages/thank-you.php?type=booking&code=' . $booking_code);
} else {
    header('Location: /pages/booking.php?error=1');
}

$sql->close();
?>