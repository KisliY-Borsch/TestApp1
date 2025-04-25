FROM php:8.3-fpm

# Устанавливаем системные пакеты
RUN apt-get update --allow-insecure-repositories --allow-releaseinfo-change && \
    apt-get install -y --allow-unauthenticated \
    git \
    curl \
    unzip \
    supervisor \
    libpng-dev \
    libzip-dev \
    libonig-dev \
    libsqlite3-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Устанавливаем PHP-расширения
RUN docker-php-ext-install pdo_mysql

# Устанавливаем ласт композер
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Указываем рабочую директорию
WORKDIR /var/www/html

# Копируем проект в контейнер
COPY . .

# Настраиваем права
RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# Копируем конфиг supervisor
COPY docker/supervisor/supervisord.conf /etc/supervisor/supervisord.conf

# Запускаем supervisor
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]
