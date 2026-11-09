FROM php:8.2-apache

# Instalar extensiones necesarias de MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Configurar Apache para que escuche en el puerto 10000 (exigido por Render)
RUN sed -i 's/80/10000/g' /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

# Copiar los archivos del proyecto
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html