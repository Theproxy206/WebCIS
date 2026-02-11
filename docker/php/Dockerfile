FROM rockylinux:9

ENV TZ=America/Mexico_City
ENV APP_DIR=/var/www/html

# Sistema base + repos
RUN dnf -y update && \
    dnf -y install epel-release && \
    dnf -y install https://rpms.remirepo.net/enterprise/remi-release-9.rpm && \
    dnf module reset php -y && \
    dnf module enable php:remi-8.3 -y

# Apache + PHP (libphp)
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

# Forzar MPM prefork (CRÍTICO)
RUN rm -f /etc/httpd/conf.modules.d/00-mpm.conf && \
    echo "LoadModule mpm_prefork_module modules/mod_mpm_prefork.so" \
    > /etc/httpd/conf.modules.d/00-mpm.conf

# Eliminar cualquier rastro de PHP-FPM
RUN rm -f /etc/httpd/conf.d/php-fpm.conf \
          /etc/httpd/conf.modules.d/10-proxy_fcgi.conf

# Composer
RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

# Apache config correcta para Laravel
RUN sed -i 's|DocumentRoot "/var/www/html"|DocumentRoot "/var/www/html/public"|' \
        /etc/httpd/conf/httpd.conf && \
    printf '\n<Directory "/var/www/html/public">\n\
AllowOverride All\n\
Require all granted\n\
</Directory>\n' >> /etc/httpd/conf/httpd.conf

# Proyecto
COPY ../.. ${APP_DIR}

# Permisos Laravel
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

CMD ["/usr/sbin/httpd", "-D", "FOREGROUND"]
