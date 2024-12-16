FROM php:8.1-apache

# Установка необходимых зависимостей и Composer
RUN apt-get update && apt-get install -y \
    unzip \
    && docker-php-ext-install pdo_mysql \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN a2enmod rewrite

# Установка конфигурации Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html
COPY . .

# Установка зависимостей проекта
RUN composer install

# Установка правильных прав доступа
RUN chmod -R 755 storage \
    && chmod -R 755 bootstrap \
    && chmod -R 777 public

EXPOSE 80
CMD ["apache2-foreground"]
