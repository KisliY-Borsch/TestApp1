# 🚀 Telegram Bot API для управления подписками

Проект на **Laravel 12**, который обрабатывает команды Telegram (`/start`, `/stop`) и отправляет уведомления подписчикам через очереди.

---

## 📦 Стек технологий

- PHP 8.3 + Laravel 12
- Docker + Docker Compose
- MySQL
- Supervisor для очередей
- Laravel Queues
- Swagger (L5-Swagger / OpenAPI)
- PHPUnit для тестов

---

## ⚙️ Быстрый старт

### Запусти проект:

```bash
make start
```

## Что делает make start:
- Проверяет наличие .env
- Собирает и запускает контейнеры Docker
- Выполняет composer install
- Прогоняет миграции базы данных с сидированием
- Ставит Webhook для Telegram

---

## 📜 Документация API

### Swagger UI доступен здесь:
```bash
http://localhost/api/documentation
```

---

## ❤️ Запуск команды для нотификации

```bash
docker compose exec app php artisan notify:tasks
```

---

## 🧪 Тестирование

### Тесты написаны на PHPUnit:
```bash
php artisan test
```

### Что покрыто:
- Обработка команды /start
- Обработка команды /stop
- Вызов команды notify:tasks
