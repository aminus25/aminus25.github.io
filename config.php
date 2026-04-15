<?php
// ============================================
// ПОДКЛЮЧЕНИЕ К БАЗЕ ДАННЫХ
// ============================================

$host = '127.0.1.27';
$user = 'root';
$password = '';
$database = 'house_plants';  // ВАЖНО: ваша база данных называется house_plants

// Создаём подключение
$conn = new mysqli($host, $user, $password, $database);

// Проверяем подключение
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Устанавливаем кодировку
$conn->set_charset("utf8");

// ============================================
// ФУНКЦИИ ДЛЯ РАБОТЫ С БАЗОЙ ДАННЫХ
// ============================================

// Получить все товары
function getProducts($conn) {
    $sql = "SELECT * FROM products ORDER BY id";
    $result = $conn->query($sql);
    $products = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    return $products;
}

// Получить товар по ID
function getProductById($conn, $id) {
    $sql = "SELECT * FROM products WHERE id = " . intval($id);
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

// Сохранить заказ
function saveOrder($conn, $orderData) {
    // Создаём таблицу orders, если её нет
    $conn->query("CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        surname VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        address TEXT,
        city VARCHAR(100),
        delivery VARCHAR(50),
        payment VARCHAR(50),
        order_details TEXT,
        total VARCHAR(50),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    $sql = "INSERT INTO orders (name, surname, email, address, city, delivery, payment, order_details, total) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", 
        $orderData['name'],
        $orderData['surname'],
        $orderData['email'],
        $orderData['address'],
        $orderData['city'],
        $orderData['delivery'],
        $orderData['payment'],
        $orderData['order_details'],
        $orderData['total']
    );
    
    return $stmt->execute();
}
?>