# Wistra Project

Проект включает в себя систему управления пользователями и просмотр товаров с дополнительными полями.

## Требования
- Docker
- Docker Compose

## Установка и запуск

1. Клонируйте репозиторий:
2. Создайте файл .env из примера:
3. Запустите контейнеры: (docker-compose up -d --build)
4. Выполните миграции:(docker-compose exec php php src/Commands/migrate.php)


## Доступные URL

После успешной установки и миграции, следующие URL будут доступны:

### Веб-интерфейс
- http://localhost/ - Управление пользователями (создание, редактирование, удаление)
- http://localhost/goods - Просмотр товаров с дополнительными полями

### API Endpoints
- GET http://localhost/api/users - Получить список пользователей
- POST http://localhost/api/users - Создать пользователя
- PUT http://localhost/api/users/{id} - Обновить пользователя
- DELETE http://localhost/api/users/{id} - Удалить пользователя
- GET http://localhost/api/goods - Получить список товаров с доп. полями

## Проверка работоспособности

1. Откройте http://localhost/ в браузере
2. Должна отобразиться форма управления пользователями
3. Создайте нового пользователя через форму
4. Перейдите на http://localhost/goods для просмотра товаров

## Устранение неполадок

Если что-то не работает:

1. Проверьте статус контейнеров:
   bash
   docker-compose down
   docker-compose up -d --build
   docker-compose exec php php src/Commands/migrate.php