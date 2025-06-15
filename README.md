Реалізовано просту SPA-систему керування задачами (todo-list) з можливістю створення, редагування, зміни статусу та видалення задач. Фронтенд побудовано на базових Web Components із використанням jQuery, без сторонніх фреймворків. Бекенд написано на чистому PHP 7.2 із власною мінімальною архітектурою: DI-контейнер, роутер, обробка помилок, консоль для запуску міграцій.

AI-інструменти частково використовувались для прискорення розробки: допомога у побудові базових структур DI та роутера, уточнення технічних деталей (наприклад, парсинг вхідних запитів), а також рев’ю підходів. Уся логіка, реалізація та структура — власноруч.

До production-рівня необхідно:

- реалізувати базову авторизацію та захист від CSRF;

- додати системне логування та обробку помилок через UI;

- покрити ключову логіку тестами;

- покращити безпеку запитів (prepared statements, валідація);

- розширити фронтенд-фреймворк — зараз він має лише базову реалізацію без реактивності, шаблонів, менеджменту стану чи роботи з формами.

Проєкт свідомо зібраний максимально просто — з акцентом на ясність структури, ручну реалізацію логіки та зрозумілу архітектуру.

---

##  Як запустити проєкт локально

1. Клонуй репозиторій та перейди в нього:

   ```bash
   git clone https://github.com/Zangeore/worksection-test-task.git project && cd project
   ```

2. Створи локальний конфіг `App/Config/config-local.php`:

   ```php
   <?php

   use Core\Database\DatabaseInterface;
   use Core\Database\PdoDatabase;

   return [
       'definitions' => [
           DatabaseInterface::class => [
               'class' => PdoDatabase::class,
               'arguments' => [
                   'dsn' => 'mysql:host=db;dbname=wst_db;charset=utf8mb4',
                   'username' => 'wst',
                   'password' => 'pwd',
               ],
               'shared' => true,
           ],
       ]
   ];
   ```

3. Запусти сервіси:

   ```bash
   docker compose up -d --build
   ```

4. Встанови PHP-залежності через Composer:

   ```bash
   docker compose exec -u www-data application composer install
   ```

5. Встанови frontend-залежності:

   ```bash
   docker compose exec -u www-data application yarn install
   ```

6. Збери фронтенд:

   ```bash
   docker compose exec -u www-data application yarn build
   ```

7. Запусти міграції:

   ```bash
   docker compose exec -u www-data application php public/index.php migrate:up
   ```

8. Відкрий у браузері:

   ```
   http://localhost
   ```

---
