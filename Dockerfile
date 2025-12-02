FROM php:8.2-apache

# 1. Instalar dependencias del sistema y Node.js (para Vite)
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git libonig-dev libxml2-dev \
    curl gnupg \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo_mysql zip bcmath opcache

# 2. Configurar Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

RUN sed -ri -e 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf

RUN a2enmod rewrite
# 3. Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Copiar archivos del proyecto
WORKDIR /var/www/html
COPY . .

# 5. Instalar dependencias de PHP y Frontend
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# 6. Ajustar permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 7. Crear script de arranque para la Base de Datos y el Server
RUN echo "#!/bin/bash\n\
    touch database/database.sqlite\n\
    chown www-data:www-data database/database.sqlite\n\
    php artisan migrate:fresh --seed --force\n\
    apache2-foreground" > /start.sh && chmod +x /start.sh

# 8. Ejecutar
CMD ["/start.sh"]