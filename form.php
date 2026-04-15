<?php
require_once 'config.php';

$success = false;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderData = [
        'name' => $_POST['name'] ?? '',
        'surname' => $_POST['surname'] ?? '',
        'email' => $_POST['email'] ?? '',
        'address' => $_POST['address'] ?? '',
        'city' => $_POST['city'] ?? '',
        'delivery' => $_POST['delivery'] ?? '',
        'payment' => $_POST['payment'] ?? '',
        'order_details' => $_POST['order_details'] ?? '',
        'total' => $_POST['total'] ?? '0'
    ];
    
    if (saveOrder($conn, $orderData)) {
        $success = true;
        // Очищаем корзину после успешного заказа
        echo "<script>localStorage.removeItem('cart');</script>";
    } else {
        $error = "Ошибка сохранения заказа: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Зеленый дом - Оформление заказа</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Alegreya+Sans:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="favicon.png" type="images/png">
</head>
<body class="d-flex flex-column">

<nav class="navbar navbar-expand-lg navbar-custom" style="padding: 0.2rem 0;">
    <div class="container">
        <div class="logo-container">
            <img src="images/logo-house.png" alt="Зеленый дом" height="35">
            <span class="brand-text">Зеленый дом</span>
        </div>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link nav-link-custom" href="index.html">Главная</a></li>
                <li class="nav-item"><a class="nav-link nav-link-custom" href="catalog.php">Каталог</a></li>
                <li class="nav-item"><a class="nav-link nav-link-custom active" href="form.php">Заказ</a></li>
                <li class="nav-item"><a class="nav-link nav-link-custom" href="product.php">Товар</a></li>
            </ul>
        </div>
    </div>
</nav>

<main>

<div class="container mt-2">
    <h1 class="fw-bold text-dark" style="font-size: 1.8rem; margin-bottom: 0.5rem;">Оформить заказ</h1>
</div>

<div class="container mt-2">
    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ✅ Ваш заказ успешно оформлен! Спасибо за покупку.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            ❌ <?= $error ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
</div>

<div class="container">
    <form method="POST" action="" id="orderForm">
        <div class="row g-2">
            
            <!-- ЛЕВАЯ КОЛОНКА - ПОЛЯ -->
            <div class="col-lg-7">
                
                <div class="form-section">
                    <h3 class="mb-2">Данные получателя</h3>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label">Имя</label>
                            <input type="text" class="form-control" name="name" placeholder="Имя" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Фамилия</label>
                            <input type="text" class="form-control" name="surname" placeholder="Фамилия">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="email@mail.ru" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Адрес</label>
                            <input type="text" class="form-control" name="address" placeholder="Улица, дом" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Страна</label>
                            <select class="form-select" name="country">
                                <option>Россия</option>
                                <option>Беларусь</option>
                                <option>Казахстан</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Город</label>
                            <input type="text" class="form-control" name="city" placeholder="Город">
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3 class="mb-2">Доставка</h3>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="delivery" value="Почта России" checked>
                        <label class="form-check-label">РФ Почта России</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="delivery" value="Курьер">
                        <label class="form-check-label">Курьер</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="delivery" value="Самовывоз">
                        <label class="form-check-label">Самовывоз</label>
                    </div>
                </div>
            </div>
            
            <!-- ПРАВАЯ КОЛОНКА - КОРЗИНА (динамическая) -->
            <div class="col-lg-5">
                
                <div class="form-section">
                    <h3 class="mb-2">Корзина</h3>
                    
                    <div id="cart-items">
                        <!-- Сюда будет подгружаться корзина через JS -->
                        <div class="text-center text-muted">Загрузка корзины...</div>
                    </div>
                    
                    <!-- Скрытое поле для суммы - ОБЯЗАТЕЛЬНО -->
                    <input type="hidden" name="total" id="total-input" value="0">
                </div>
                
                <div class="form-section">
                    <h3 class="mb-2">Оплата</h3>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment" value="Кредитная карта" checked>
                        <label class="form-check-label">Кредитная карта</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="payment" value="Дебетовая карта">
                        <label class="form-check-label">Дебетовая карта</label>
                    </div>
                    
                    <div class="row g-2">
                        <div class="col-12">
                            <label class="form-label">Имя на карте</label>
                            <input type="text" class="form-control" placeholder="IVAN PETROV">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Номер карты</label>
                            <input type="text" class="form-control" placeholder="1234 5678 9012 3456">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Срок</label>
                            <input type="text" class="form-control" placeholder="ММ/ГГ">
                        </div>
                        <div class="col-6">
                            <label class="form-label">CVV</label>
                            <input type="text" class="form-control" placeholder="123">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn w-100 mt-2" style="background-color: #74A322; color: white;">
                        Оформить заказ
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

</main>

<footer>
    <div class="container">
        <span><i class="bi bi-clock me-1"></i>Обработка заказов: 9:00-20:00</span>
        <span><i class="bi bi-envelope me-1"></i>greenhome@mail.ru</span>
        <span><i class="bi bi-telephone me-1"></i>+7 (929) 796-06-97</span>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Отображение корзины на странице form.php
function displayCart() {
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    const container = document.getElementById('cart-items');
    const totalInput = document.getElementById('total-input');
    
    if (cart.length === 0) {
        container.innerHTML = '<div class="text-center text-muted">Корзина пуста. Перейдите в <a href="catalog.php">каталог</a></div>';
        if (totalInput) totalInput.value = '0';
        return;
    }
    
    let total = 0;
    let html = '<table class="table table-cart table-sm"><thead><tr><th>Товар</th><th>Кол-во</th><th>Цена</th><th></th></tr></thead><tbody>';
    
    cart.forEach((item, index) => {
        const sum = item.price * item.quantity;
        total += sum;
        html += `
            <tr>
                <td>${item.name}</td>
                <td>
                    <button class="btn btn-sm btn-outline-secondary" onclick="changeQuantity(${index}, -1)">-</button>
                    <span class="mx-1">${item.quantity}</span>
                    <button class="btn btn-sm btn-outline-secondary" onclick="changeQuantity(${index}, 1)">+</button>
                </td>
                <td>${sum.toLocaleString()} ₽</td>
                <td><button class="btn btn-sm btn-danger" onclick="removeItem(${index})">×</button></td>
            </tr>
        `;
    });
    
    html += `<tr class="total-row"><td colspan="2" class="text-end"><strong>ИТОГО:</strong></td><td colspan="2"><strong>${total.toLocaleString()} ₽</strong></td></tr>`;
    html += '</tbody></table>';
    
    container.innerHTML = html;
    // ВАЖНО: обновляем скрытое поле с суммой
    if (totalInput) {
        totalInput.value = total;
        console.log('Сумма обновлена: ' + total);
    }
}

function changeQuantity(index, delta) {
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    const newQuantity = cart[index].quantity + delta;
    if (newQuantity <= 0) {
        cart.splice(index, 1);
    } else {
        cart[index].quantity = newQuantity;
    }
    localStorage.setItem('cart', JSON.stringify(cart));
    displayCart();
}

function removeItem(index) {
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    displayCart();
}

// Загружаем корзину при загрузке страницы
document.addEventListener('DOMContentLoaded', displayCart);
</script>

</body>
</html>