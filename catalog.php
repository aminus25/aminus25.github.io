<?php
require_once 'config.php';
$products = getProducts($conn);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Зеленый дом - Каталог</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Alegreya+Sans:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="favicon.png" type="images/png">

    <style>
        .btn-cart {
            background-color: #74A322;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            transition: 0.3s;
            font-size: 14px;
        }
        .btn-cart:hover {
            background-color: #5a821a;
            color: white;
        }
        .btn-square-small {
            background-color: transparent;
            color: #74A322;
            border: 1px solid #74A322;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 4px;
            transition: 0.3s;
            font-size: 14px;
            display: inline-block;
        }
        .btn-square-small:hover {
            background-color: #74A322;
            color: white;
        }
        .product-card {
            width: 280px;
            transition: transform 0.3s;
        }
        .product-image {
            width: 180px;
            height: 180px;
            object-fit: cover;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-custom">
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
                <li class="nav-item"><a class="nav-link nav-link-custom active" href="catalog.php">Каталог</a></li>
                <li class="nav-item"><a class="nav-link nav-link-custom" href="form.php">Заказ</a></li>
                <li class="nav-item"><a class="nav-link nav-link-custom" href="product.php">Товар</a></li>
            </ul>
        </div>
    </div>
</nav>

<main class="flex-grow-1">

<div class="container mt-5">
    <h1 class="fw-bold text-dark" style="font-size: 2.2rem;">Наш Каталог</h1>
</div>

<div class="container mt-4">
    <div class="row justify-content-center" style="gap: 53px;">
        
        <?php foreach ($products as $product): ?>
        <div class="col-auto">
            <div class="card border-0 shadow-sm product-card">
                
                <div class="d-flex justify-content-center pt-4">
                    <img src="images/<?= $product['image'] ?>" class="product-image" alt="<?= htmlspecialchars($product['name']) ?>">
                </div>
                <div class="card-body d-flex flex-column text-center">
                    <h3 class="card-title h5 fw-normal"><?= htmlspecialchars($product['name']) ?></h3>
                    <p class="card-text fs-3 fw-bold mb-2" style="color: #74A322;"><?= number_format($product['price'], 0, '', ' ') ?> ₽</p>
                    <p class="card-text text-secondary">
                        Диаметр горшка: <?= $product['diametr'] ?><br>
                        Высота от пола: <?= $product['height'] ?>
                    </p>
                    <div class="d-flex gap-2 justify-content-center mt-2">
                        <a href="product.php?id=<?= $product['id'] ?>" class="btn-square-small">Подробнее</a>
                        <button class="btn-cart" onclick="addToCart(<?= $product['id'] ?>, '<?= htmlspecialchars($product['name']) ?>', <?= $product['price'] ?>)">В корзину</button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        
    </div>
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
// Функция добавления товара в корзину
function addToCart(id, name, price) {
    console.log("Добавляем: " + name);
    
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    
    let existing = cart.find(item => item.id === id);
    if (existing) {
        existing.quantity++;
    } else {
        cart.push({ id: id, name: name, price: price, quantity: 1 });
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
    alert('✅ Товар "' + name + '" добавлен в корзину!');
}

// Проверка, что скрипт работает
console.log("Скрипт каталога загружен");
</script>

</body>
</html>