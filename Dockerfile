FROM rockylinux:9

# Variables básicas
ENV TZ=America/Mexico_City
ENV APP_DIR=/var/www/html

# Actualizar sistema e instalar repositorios necesarios
RUN dnf -y update && \
    dnf -y install epel-release && \
    dnf -y install \
        https://rpms.remirepo.net/enterprise/remi-release-9.rpm && \
    dnf module reset php -y && \
    dnf module enable php:remi-8.3 -y

# Instalar Apache, PHP 8.3 y extensiones necesarias para Laravel
RUN dnf -y install \
    httpd \
    php \
    php-cli \
    php-common \
    php-mbstring \
    php-pdo \
    php-mysqlnd \
    php-bcmath \
    php-xml \
    php-zip \
    php-opcache \
    php-gd \
    php-json \
    git \
    unzip \
    && dnf clean all

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

# Configuración de Apache
RUN sed -i 's|DocumentRoot "/var/www/html"|DocumentRoot "/var/www/html/public"|' /etc/httpd/conf/httpd.conf && \
    sed -i 's|<Directory "/var/www/html">|<Directory "/var/www/html/public">|' /etc/httpd/conf/httpd.conf && \
    sed -i 's|AllowOverride None|AllowOverride All|' /etc/httpd/conf/httpd.conf

# Copiar el proyecto Laravel
COPY . ${APP_DIR}

# Permisos para Laravel
RUN chown -R apache:apache ${APP_DIR} && \
    chmod -R 775 ${APP_DIR}/storage ${APP_DIR}/bootstrap/cache

WORKDIR ${APP_DIR}

RUN git config --global --add safe.directory /var/www/html

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

EXPOSE 80

# Apache en primer plano (requerido por Docker)
CMD ["/usr/sbin/httpd", "-D", "FOREGROUND"]
