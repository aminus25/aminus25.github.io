<?php
require_once 'config.php';

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 1;
$product = getProductById($conn, $product_id);

// Если товар не найден, берём первый из списка
if (!$product) {
    $products = getProducts($conn);
    $product = $products[0] ?? null;
}

// Если совсем нет товаров, показываем ошибку
if (!$product) {
    die("Товары не найдены в базе данных");
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Зеленый дом - <?= htmlspecialchars($product['name']) ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Шрифт -->
    <link href="https://fonts.googleapis.com/css2?family=Alegreya+Sans:wght@300;400;500;700;800&display=swap" rel="stylesheet">

    <!-- Твой CSS -->
    <link rel="stylesheet" href="css/style.css">

    <link rel="icon" href="favicon.png" type="images/png">

    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-XXXXXXXXXX');
    </script>
    
    <style>
        .btn-green {
            background-color: #74A322;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 4px;
            cursor: pointer;
            transition: 0.3s;
            font-size: 16px;
        }
        .btn-green:hover {
            background-color: #5a821a;
            color: white;
        }
    </style>
</head>
<body class="d-flex flex-column">

<!-- ===== ШАПКА ===== -->
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <!-- Логотип на белом фоне -->
        <div class="logo-container">
            <img src="images/logo-house.png" alt="Зеленый дом" height="35">
            <span class="brand-text">Зеленый дом</span>
        </div>
        
        <!-- Кнопка для мобильного меню -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Меню навигации -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="index.html">Главная</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="catalog.php">Каталог</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="form.php">Заказ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom active" href="product.php">Товар</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- ===== ОСНОВНОЙ КОНТЕНТ ===== -->
<main class="mt-4">

<!-- ===== ТОВАР ===== -->
<div class="container">
    <div class="row">
        <!-- Левая колонка - фото -->
        <div class="col-md-6">
            <div class="produc1t-image-container">
                <img src="images/<?= $product['image'] ?>" class="produc1t-image" alt="<?= htmlspecialchars($product['name']) ?>">
            </div>
        </div>
        
        <!-- Правая колонка - информация -->
        <div class="col-md-6">
            <h2 class="fw-bold product-name"><?= htmlspecialchars($product['name']) ?></h2>
            <p class="fw-bold text-green product-price"><?= number_format($product['price'], 0, '', ' ') ?> ₽</p>
            
            <!-- Характеристики -->
            <div class="product-details">
                <p><span class="fw-medium">Диаметр горшка:</span> <span class="product-diametr"><?= $product['diametr'] ?></span></p>
                <p><span class="fw-medium">Высота от пола:</span> <span class="product-height"><?= $product['height'] ?></span></p>
                <p><span class="fw-medium">Куда ставим:</span> <span class="product-placement"><?= $product['placement'] ?></span></p>
                <p><span class="fw-medium">Освещенность:</span> <span class="product-light"><?= $product['light'] ?></span></p>
                <p><span class="fw-medium">Сложность ухода:</span> <span class="product-care"><?= $product['care'] ?></span></p>
                <p><span class="fw-medium">В наличии:</span> <span class="product-stock"><?= $product['stock'] ?></span> шт.</p>
            </div>
            
            <!-- Доставка -->
            <h5 class="fw-bold delivery-title">Доставка:</h5>
            <ul class="list-unstyled delivery-list">
                <li><i class="bi bi-truck me-2 text-green"></i> Курьерская доставка — от 300 ₽</li>
                <li><i class="bi bi-envelope-paper me-2 text-green"></i> Почта России — от 200 ₽</li>
                <li><i class="bi bi-shop me-2 text-green"></i> Самовывоз (Москва, ул. Зеленая, д. 10) — бесплатно</li>
            </ul>
            
            <!-- Бейджи -->
            <div class="d-flex gap-3 my-3">
                <span class="badge bg-light text-dark badge-item"><i class="bi bi-flower1 me-1 text-green"></i>Горшок</span>
                <span class="badge bg-light text-dark badge-item"><i class="bi bi-thermometer-half me-1 text-green"></i>22°C</span>
                <span class="badge bg-light text-dark badge-item"><i class="bi bi-rulers me-1 text-green"></i><?= $product['diametr'] ?></span>
            </div>
            
            <!-- КНОПКА - теперь добавляет в корзину и переходит в form.php -->
            <button class="btn-green mt-2" onclick="addToCartAndGo()">🛒 Заказать</button>
        </div>
    </div>
</div>

</main>

<!-- ===== ПОДВАЛ ===== -->
<footer>
    <div class="container">
        <span><i class="bi bi-clock me-1"></i>Обработка заказов: 9:00-20:00</span>
        <span><i class="bi bi-envelope me-1"></i>greenhome@mail.ru</span>
        <span><i class="bi bi-telephone me-1"></i>+7 (929) 796-06-97</span>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Функция добавления товара в корзину
function addToCart(id, name, price, quantity = 1) {
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    
    // Проверяем, есть ли уже такой товар
    let existing = cart.find(item => item.id === id);
    if (existing) {
        existing.quantity += quantity;
    } else {
        cart.push({ id: id, name: name, price: price, quantity: quantity });
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
    return true;
}

// Добавляем текущий товар и переходим в form.php
function addToCartAndGo() {
    // Получаем данные товара из PHP
    const productId = <?= $product['id'] ?>;
    const productName = '<?= htmlspecialchars($product['name']) ?>';
    const productPrice = <?= $product['price'] ?>;
    
    // Добавляем товар в корзину
    addToCart(productId, productName, productPrice, 1);
    
    // Показываем сообщение
    alert('✅ Товар "' + productName + '" добавлен в корзину!');
    
    // Переходим на страницу оформления заказа
    window.location.href = 'form.php';
}
</script>

</body>
</html>