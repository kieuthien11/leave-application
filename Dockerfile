# Sử dụng PHP 8.2 với FPM
FROM php:8.2-fpm

# Cài đặt các thư viện cần thiết
RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd \
    && docker-php-ext-install pdo pdo_mysql gd

# Cài Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Thiết lập thư mục làm việc
WORKDIR /var/www

# Copy toàn bộ source vào container
COPY . /var/www

# Cài đặt các package Laravel
RUN composer install --no-dev --optimize-autoloader

# Cấp quyền cho storage và bootstrap
RUN chmod -R 777 storage bootstrap/cache

CMD ["php-fpm"]
