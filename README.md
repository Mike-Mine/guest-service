# Проект по управлению гостями

RESTful API для управления информацией о гостях. Проект разработан с использованием Laravel 11 и предоставляет возможности для добавления, обновления, удаления и получения данных о гостях. Документация API сгенерирована с использованием Swagger.

## Требования
- Docker
- Docker Compose

## Установка
1. **Клонируйте репозиторий:**
    ```bash
    git clone https://github.com/Mike-Mine/guest-service.git
    cd guest-service
    ```
2. **Настройте переменные окружения:**
    Скопируйте файл `.env.example` в `.env`:
    ```bash
    cp .env.example .env
    ```
    В `.env` необходимо добавить API_TOKEN, который будет использоваться для авторизации.

3. **Построение Docker-контейнеров (при первой установке):**
    Выполните следующие команды для сборки необходимых контейнеров:
    ```bash
    docker compose up -d --build
    docker compose exec php bash
    chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
    chmod -R 775 /var/www/storage /var/www/bootstrap/cache
    composer setup
    ```
4. **Запуск Docker-контейнеров (для последуующих запусков):**
    ```bash
    docker compose up -d
    ```
    Это запустит приложение в Docker-контейнере, доступное по адресу `http://localhost`.

## Документация API
API следует RESTful-конвенции и включает следующие эндпоинты:
- **GET /api/guests** – Получить список всех гостей.
- **POST /api/guests** – Создать нового гостя.
- **GET /api/guests/{id}** – Получить конкретного гостя по ID.
- **PUT /api/guests/{id}** – Обновить информацию о существующем госте.
- **DELETE /api/guests/{id}** – Удалить гостя.

В ответе возвращаются заголовки X-Debug-Time и X-Debug-Memory, которые указывают, сколько миллисекунд выполнялся запрос и сколько Кб памяти потребовалось соответственно.

Подробные примеры запросов и ответов, включая правила валидации и возможные ошибки, можно найти здесь:
```bash
http://localhost/api/documentation
```
