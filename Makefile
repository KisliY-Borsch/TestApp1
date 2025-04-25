ENV_FILE := .env
ENV_EXAMPLE_FILE := .env.example

-include $(ENV_FILE)
export

start: env build webhook

build:
	@echo "🔧 Собираем и запускаем проект..."
	docker compose up -d --build --force-recreate
	docker compose exec app composer install
	docker compose exec app php artisan migrate:fresh --seed
	@echo "🚀 Проект запущен!"

env:
	@echo "🛠️ Проверка .env файла..."
	@if [ ! -f $(ENV_FILE) ]; then \
		echo "➕ .env не найден, копируем из .env.example..."; \
		cp $(ENV_EXAMPLE_FILE) $(ENV_FILE); \
		echo "⚠️ Нужно указать TELEGRAM_BOT_TOKEN в .env!"; \
	fi

	@if [ -z "$(TELEGRAM_BOT_TOKEN)" ]; then \
		echo "❌ Ошибка: переменная TELEGRAM_BOT_TOKEN должна быть указана в .env"; \
		exit 1; \
	else \
		echo "✅ Переменная TELEGRAM_BOT_TOKEN найдена"; \
	fi

webhook:
	@echo "📡 Устанавливаем Webhook..."
	@response=$$(curl -s -X POST "https://api.telegram.org/bot${TELEGRAM_BOT_TOKEN}/setWebhook?url=https://${APP_URL}/api/telegram/webhook"); \
	if command -v jq > /dev/null; then \
		ok=$$(echo "$$response" | jq -r '.ok'); \
		if [ "$$ok" = "true" ]; then \
			echo "✅ Webhook успешно установлен!"; \
		else \
			echo "❌ Ошибка при установке Webhook. Ответ от Telegram:"; \
			echo "$$response" | jq .; \
		fi \
	else \
		echo "⚠️ Ответ от Telegram:"; \
		echo "$$response"; \
	fi
