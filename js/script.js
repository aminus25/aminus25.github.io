// ============================================
// ЛАБОРАТОРНАЯ РАБОТА №3
// СКРИПТ ДЛЯ САЙТА "ЗЕЛЕНЫЙ ДОМ"
// ============================================

// Ждём полной загрузки DOM
document.addEventListener('DOMContentLoaded', function() {
    
    // ========================================
    // 0. ОЧИСТКА УСТАРЕВШИХ ДАННЫХ (ОПЦИОНАЛЬНО)
    // ========================================
    
    // Если мы на странице товара, но нет данных в currentProduct,
    // можно загрузить данные по умолчанию или ничего не делать
    if (window.location.pathname.includes('product.html')) {
        const savedProduct = localStorage.getItem('currentProduct');
        if (!savedProduct) {
            console.log('Нет данных о товаре, используем данные по умолчанию');
            // Можно либо ничего не делать, либо загрузить монстеру по умолчанию
        }
    }
    
    // ========================================
    // 1. ОБРАБОТКА ФОРМЫ ЗАКАЗА (страница form.html)
    // ========================================
    
    const orderForm = document.getElementById('orderForm');
    
    if (orderForm) {
        // Добавляем обработчик события отправки формы
        orderForm.addEventListener('submit', function(event) {
            // Отключаем переход на другую страницу
            event.preventDefault();
            
            // Получаем значения полей
            const name = document.getElementById('name')?.value.trim() || '';
            const surname = document.getElementById('surname')?.value.trim() || '';
            const email = document.getElementById('email')?.value.trim() || '';
            const address = document.getElementById('address')?.value.trim() || '';
            const country = document.getElementById('country')?.value || 'Россия';
            const city = document.getElementById('city')?.value.trim() || '';
            
            // Получаем выбранный способ доставки
            const deliveryElement = document.querySelector('input[name="delivery"]:checked');
            const delivery = deliveryElement ? deliveryElement.value : 'Не выбран';
            
            // Получаем выбранный способ оплаты
            const paymentElement = document.querySelector('input[name="payment"]:checked');
            const payment = paymentElement ? paymentElement.value : 'Не выбран';
            
            // Получаем данные карты (обязательные для заполнения)
            const cardName = document.getElementById('cardName')?.value.trim() || '';
            const cardNumber = document.getElementById('cardNumber')?.value.trim() || '';
            const cardExpiry = document.getElementById('cardExpiry')?.value.trim() || '';
            const cardCvv = document.getElementById('cardCvv')?.value.trim() || '';
            
            // ПОЛУЧАЕМ ТОВАРЫ ИЗ КОРЗИНЫ
            const cartItems = getCartItems();
            const totalAmount = calculateTotal();
            
            // Данные для консоли
            const formData = {
               личные_данные: {
                    имя: name || 'не указано',
                    фамилия: surname || 'не указано',
                    email: email || 'не указано',
                    адрес: address || 'не указано',
                    страна: country,
                    город: city || 'не указано'
                },
                доставка: delivery,
                оплата: payment,
                данные_карты: {
                    имя_на_карте: cardName || 'не указано',
                    номер_карты: cardNumber || 'не указано',
                    срок: cardExpiry || 'не указано',
                    cvv: cardCvv  ||  'не указано'
                },
                заказ: {
                    товары: cartItems,
                    количество_позиций: cartItems.length,
                    общее_количество_товаров: cartItems.reduce((sum, item) => sum + item.количество, 0),
                    итоговая_сумма: totalAmount
                }
            };
            
            // ========================================
            // ВЫВОД В КОНСОЛЬ ВСЕХ ДАННЫХ О ЗАКАЗЕ
            // ========================================
            
            console.log('%c       НОВЫЙ ЗАКАЗ', 'color: #74A322; font-size: 16px; font-weight: bold');
            
            
            // Информация о клиенте
            console.log('%c ИНФОРМАЦИЯ О КЛИЕНТЕ:', 'color: #0066cc; font-weight: bold');
            console.log('   Имя:', name || 'не указано');
            console.log('   Фамилия:', surname || 'не указано');
            console.log('   Email:', email || 'не указано');
            console.log('   Адрес:', address || 'не указано');
            console.log('   Страна:', country);
            console.log('   Город:', city || 'не указано');
            
            // Доставка и оплата
            console.log('%c ДОСТАВКА И ОПЛАТА:', 'color: #cc6600; font-weight: bold');
            console.log('   Способ доставки:', delivery);
            console.log('   Способ оплаты:', payment);
            
            if (payment.includes('карта') || payment.includes('Card') || payment.includes('Кредитная') || payment.includes('Дебетовая')) {
                console.log('    Данные карты:');
                console.log('      Имя на карте:', cardName || 'не указано');
                console.log('      Номер карты:', cardNumber || 'не указано');
                console.log('      Срок:', cardExpiry || 'не указано');
                console.log('      CVV:', cardCvv ||'не указано');
            }
            
            // Товары в заказе
            console.log('%c СОСТАВ ЗАКАЗА:', 'color: #74A322; font-weight: bold');
            
            if (cartItems.length === 0) {
                console.log('    Корзина пуста!');
            } else {
                console.table(cartItems.map(item => ({
                    'Наименование': item.название,
                    'Количество': item.количество,
                    'Цена за шт.': item.цена + ' ₽',
                    'Сумма': (item.количество * item.цена) + ' ₽'
                })));
                
                // Детальный построчный вывод
                console.log('%c   Детали заказа:', 'font-weight: bold');
                cartItems.forEach((item, index) => {
                    const itemTotal = item.количество * item.цена;
                    console.log(`   ${index + 1}. ${item.название} — ${item.количество} шт. × ${item.цена} ₽ = ${itemTotal} ₽`);
                });
            }
            
            // Итог
            console.log('%c ИТОГО:', 'color: #74A322; font-size: 14px; font-weight: bold');
            console.log(`   Количество позиций: ${cartItems.length}`);
            console.log(`   Всего товаров: ${cartItems.reduce((sum, item) => sum + item.количество, 0)} шт.`);
            console.log(`   Сумма заказа: ${totalAmount}`);
            
          
            console.log('%c       ЗАКАЗ ОФОРМЛЕН', 'color: #74A322; font-size: 14px; font-weight: bold');
           
            
            // Также выводим объект для просмотра в консоли
            console.log('📋 Полные данные заказа (объект):', formData);
            
            // ========================================
            // 2. ПРОВЕРКА ВВОДИМЫХ ДАННЫХ
            // ========================================
            
            // Проверка email
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const isEmailValid = emailPattern.test(email);
            
            // Проверка обязательных полей
            const errors = [];
            
            if (!name) errors.push('Имя обязательно для заполнения');
            if (!email) errors.push('Email обязателен для заполнения');
            if (!address) errors.push('Адрес обязателен для заполнения');
            if (email && !isEmailValid) errors.push('Введите корректный email (пример: name@domain.ru)');
            
            // Проверка данных карты (обязательно, если выбрана оплата картой)
            if (payment.includes('карта') || payment.includes('Card') || payment.includes('Кредитная') || payment.includes('Дебетовая')) {
                if (!cardName) errors.push('Имя на карте обязательно для заполнения');
                if (!cardNumber) errors.push('Номер карты обязателен для заполнения');
                if (!cardExpiry) errors.push('Срок действия карты обязателен для заполнения');
                if (!cardCvv) errors.push('CVV код обязателен для заполнения');
                
                // Простейшая проверка номера карты (только цифры и пробелы)
                if (cardNumber && !/^[\d\s]{13,19}$/.test(cardNumber.replace(/\s/g, ''))) {
                    errors.push('Номер карты должен содержать 13-19 цифр');
                }
                
                // Проверка CVV (3-4 цифры)
                if (cardCvv && !/^\d{3,4}$/.test(cardCvv)) {
                    errors.push('CVV должен содержать 3 или 4 цифры');
                }
            }
            
            // Проверка, что корзина не пуста
            if (cartItems.length === 0) {
                errors.push('Корзина пуста! Добавьте товары перед оформлением заказа');
            }
            
            // ========================================
            // 3. ОТОБРАЖЕНИЕ МОДАЛЬНОГО ОКНА И ОЧИСТКА КОРЗИНЫ
            // ========================================
            
            const modalMessage = document.getElementById('modalMessage');
            
            // Проверяем, существует ли модальное окно на странице
            const modalElement = document.getElementById('resultModal');
            if (!modalElement) {
                alert('Ошибка: модальное окно не найдено');
                return;
            }
            
            const modal = new bootstrap.Modal(modalElement);
            
            if (errors.length > 0) {
                // Если есть ошибки
                modalMessage.innerHTML = `
                    <div class="alert alert-danger">
                        <strong>Ошибка!</strong><br>
                        ${errors.join('<br>')}
                    </div>
                `;
            } else {
                // Если всё хорошо
                modalMessage.innerHTML = `
                    <div class="alert alert-success">
                        <strong> Заказ успешно оформлен!</strong><br>
                        Спасибо за покупку!<br>
                        <hr>
                    </div>
                `;
                
                // ОЧИЩАЕМ КОРЗИНУ ПОСЛЕ УСПЕШНОГО ЗАКАЗА
                localStorage.removeItem('greenHomeCart');
                console.log('%c Корзина очищена после оформления заказа', 'color: #ff6600; font-weight: bold');
                
                // Обновляем отображение корзины
                updateCartDisplay();
                
                // Очищаем форму (опционально)
                // orderForm.reset();
            }
            
            // Показываем модальное окно
            modal.show();
        });
    }
    
    // ========================================
    // 4. ФУНКЦИИ ДЛЯ РАБОТЫ С КОРЗИНОЙ
    // ========================================
    
    // Получение товаров из корзины (из localStorage)
    function getCartItems() {
        const savedCart = localStorage.getItem('greenHomeCart');
        if (savedCart) {
            return JSON.parse(savedCart);
        }
        
        // Если в корзине ничего нет, возвращаем пустой массив
        return [];
    }
    
    // Сохранение корзины в localStorage
    function saveCart(cart) {
        localStorage.setItem('greenHomeCart', JSON.stringify(cart));
    }
    
    // Добавление товара в корзину
    function addToCart(product) {
        let cart = getCartItems();
        
        // Проверяем, есть ли уже такой товар в корзине
        const existingProduct = cart.find(item => item.название === product.название);
        if (existingProduct) {
            existingProduct.количество += product.количество;
        } else {
            cart.push(product);
        }
        
        saveCart(cart);
        console.log('✅ Товар добавлен в корзину:', product);
    }
    
    // Подсчёт итоговой суммы
    function calculateTotal() {
        const items = getCartItems();
        let total = 0;
        items.forEach(item => {
            total += item.количество * item.цена;
        });
        return total + ' ₽';
    }
    
    // Обновление отображения корзины на странице form.html
    function updateCartDisplay() {
        const cartTable = document.querySelector('.table-cart tbody');
        if (!cartTable) return;
        
        const items = getCartItems();
        
        // Очищаем таблицу
        cartTable.innerHTML = '';
        
        if (items.length === 0) {
            // Если корзина пуста
            const emptyRow = document.createElement('tr');
            emptyRow.innerHTML = '<td colspan="3" class="text-center">Корзина пуста</td>';
            cartTable.appendChild(emptyRow);
            return;
        }
        
        // Добавляем товары
        items.forEach(item => {
            const row = document.createElement('tr');
            const itemTotal = item.количество * item.цена;
            row.innerHTML = `
                <td>${item.название}</td>
                <td>${item.количество}</td>
                <td>${itemTotal} ₽</td>
            `;
            cartTable.appendChild(row);
        });
        
        // Добавляем строку с итогом
        const totalRow = document.createElement('tr');
        totalRow.className = 'total-row';
        totalRow.innerHTML = `
            <td colspan="2" class="text-end">ИТОГО:</td>
            <td><strong>${calculateTotal()}</strong></td>
        `;
        cartTable.appendChild(totalRow);
    }
    
    // Вызываем обновление корзины на странице form.html
    if (window.location.pathname.includes('form.html')) {
        updateCartDisplay();
    }
    
    // ========================================
    // 5. ОБРАБОТКА КНОПОК "В КОРЗИНУ" (страница catalog.html)
    // ========================================
    
    const cartButtons = document.querySelectorAll('.btn-square');
    
    cartButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            
            // Находим карточку товара
            const card = this.closest('.product-card');
            if (!card) return;
            
            // Получаем данные из data-атрибутов
            const product = {
                id: card.dataset.productId,
                название: card.dataset.productName,
                цена: parseInt(card.dataset.productPrice) || 0,
                изображение: card.dataset.productImage,
                диаметр: card.dataset.productDiametr || '12 см',
                высота: card.dataset.productHeight || '30 см',
                освещение: card.dataset.productLight || 'рассеянный свет',
                уход: card.dataset.productCare || 'новичок',
                наличие: card.dataset.productStock || '5',
                размещение: card.dataset.productPlacement || 'гостиная, спальня',
                количество: 1
            };
            
            // Сохраняем данные о текущем товаре для страницы product.html
            const productForPage = {
                name: product.название,
                price: product.цена,
                image: product.изображение,
                diametr: product.диаметр,
                height: product.высота,
                light: product.освещение,
                care: product.уход,
                stock: product.наличие,
                placement: product.размещение,
                delivery: [
                    `В наличии ${product.наличие} штук`,
                    'Самовывоз',
                    'Доставка курьером по Москве',
                    'Доставка по РФ (СДЭК)'
                ]
            };
            
            localStorage.setItem('currentProduct', JSON.stringify(productForPage));
            
            // Добавляем товар в корзину
            addToCart({
                название: product.название,
                количество: 1,
                цена: product.цена
            });
            
            // Перенаправляем на страницу карточки товара
            window.location.href = 'product.html';
        });
    });
    
    // ========================================
    // 6. ЗАГРУЗКА ДАННЫХ НА СТРАНИЦУ ТОВАРА (product.html)
    // ========================================
    
    if (window.location.pathname.includes('product.html')) {
        // Загружаем данные о текущем товаре
        const savedProduct = localStorage.getItem('currentProduct');
        if (savedProduct) {
            const product = JSON.parse(savedProduct);
            
            // Заполняем основные поля
            const nameEl = document.querySelector('.product-name');
            if (nameEl) nameEl.textContent = product.name;
            
            const priceEl = document.querySelector('.product-price');
            if (priceEl) priceEl.textContent = product.price + ' ₽';
            
            const imageEl = document.querySelector('.produc1t-image');
            if (imageEl) imageEl.src = 'images/' + product.image;
            
            // Заполняем характеристики
            const detailsEl = document.querySelectorAll('.product-details p');
            if (detailsEl.length >= 5) {
                // Диаметр горшка
                let diametrSpan = detailsEl[0].querySelector('span:last-child');
                if (!diametrSpan) {
                    diametrSpan = document.createElement('span');
                    diametrSpan.className = 'product-diametr';
                    detailsEl[0].innerHTML = '<span class="fw-medium">Диаметр горшка:</span> ';
                    detailsEl[0].appendChild(diametrSpan);
                }
                diametrSpan.textContent = product.diametr;
                
                // Высота от пола
                let heightSpan = detailsEl[1].querySelector('span:last-child');
                if (!heightSpan) {
                    heightSpan = document.createElement('span');
                    heightSpan.className = 'product-height';
                    detailsEl[1].innerHTML = '<span class="fw-medium">Высота от пола:</span> ';
                    detailsEl[1].appendChild(heightSpan);
                }
                heightSpan.textContent = product.height;
                
                // Куда ставим
                let placementSpan = detailsEl[2].querySelector('span:last-child');
                if (!placementSpan) {
                    placementSpan = document.createElement('span');
                    placementSpan.className = 'product-placement';
                    detailsEl[2].innerHTML = '<span class="fw-medium">Куда ставим:</span> ';
                    detailsEl[2].appendChild(placementSpan);
                }
                placementSpan.textContent = product.placement;
                
                // Освещенность
                let lightSpan = detailsEl[3].querySelector('span:last-child');
                if (!lightSpan) {
                    lightSpan = document.createElement('span');
                    lightSpan.className = 'product-light';
                    detailsEl[3].innerHTML = '<span class="fw-medium">Освещенность:</span> ';
                    detailsEl[3].appendChild(lightSpan);
                }
                lightSpan.textContent = product.light;
                
                // Сложность ухода
                let careSpan = detailsEl[4].querySelector('span:last-child');
                if (!careSpan) {
                    careSpan = document.createElement('span');
                    careSpan.className = 'product-care';
                    detailsEl[4].innerHTML = '<span class="fw-medium">Сложность ухода:</span> ';
                    detailsEl[4].appendChild(careSpan);
                }
                careSpan.textContent = product.care;
            }
            
            // Заполняем список доставки
            const deliveryList = document.querySelector('.delivery-list');
            if (deliveryList && product.delivery) {
                deliveryList.innerHTML = '';
                product.delivery.forEach(item => {
                    const li = document.createElement('li');
                    li.innerHTML = `<span class="text-green me-2">•</span>${item}`;
                    deliveryList.appendChild(li);
                });
            }
            
            // Обновляем бейдж с размером
            const sizeBadge = document.querySelector('.badge-item:last-child');
            if (sizeBadge) {
                sizeBadge.innerHTML = `<i class="bi bi-rulers me-1 text-green"></i>${product.diametr}`;
            }
            
            // Обновляем бейдж с температурой (можно оставить как есть или тоже сделать динамическим)
        } else {
            console.log('Нет данных о товаре, используем значения по умолчанию');
            // Можно оставить как есть или загрузить данные по умолчанию
        }
    }
    
    // ========================================
    // 7. ПОИСК ПО КАТАЛОГУ (страница catalog.html)
    // ========================================
    
    const catalogPage = document.querySelector('.product-card');
    
    if (catalogPage) {
        // Создаём поле поиска, если его ещё нет
        const searchContainer = document.querySelector('.container.mt-4');
        
        if (searchContainer && !document.getElementById('searchInput')) {
            // Создаём HTML для поиска
            const searchHTML = `
                <div class="row mb-4">
                    <div class="col-md-6 mx-auto">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control" id="searchInput" placeholder="Поиск растений...">
                        </div>
                    </div>
                </div>
            `;
            
            // Вставляем поиск перед карточками
            searchContainer.insertAdjacentHTML('afterbegin', searchHTML);
        }
        
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            const cards = document.querySelectorAll('.product-card');
            
            // Функция фильтрации
            function filterCards(searchText) {
                cards.forEach(card => {
                    // Ищем название растения
                    const title = card.querySelector('.card-title')?.textContent.toLowerCase() || '';
                    const matches = title.includes(searchText.toLowerCase());
                    
                    // Показываем или скрываем родительский элемент (колонку)
                    const parentCol = card.closest('.col-auto');
                    if (parentCol) {
                        parentCol.style.display = matches ? 'block' : 'none';
                    }
                });
            }
            
            // Обработчик ввода
            searchInput.addEventListener('input', function() {
                filterCards(this.value);
            });
            
            // Обработчик очистки (если поле очистили)
            searchInput.addEventListener('search', function() {
                filterCards('');
            });
            
            console.log('🔍 Поиск по каталогу активирован');
        }
    }
    
    // ========================================
    // 8. ПОДСВЕТКА АКТИВНОГО МЕНЮ
    // ========================================
    
    const currentPage = window.location.pathname.split('/').pop();
    const navLinks = document.querySelectorAll('.nav-link-custom');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPage || (currentPage === '' && href === 'index14.html')) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
    
    console.log('📦 Скрипт загружен. Текущая страница:', currentPage || 'index14.html');
});