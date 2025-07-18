# Utilisation d'une image de base avec Apache et PHP
FROM php:8.0-apache
# Mise à jour des paquets et installation des dépendances
RUN apt-get update && \
    apt-get install -y zip unzip git \
    lame \
    libmp3lame-dev \
    && rm -rf /var/lib/apt/lists/*


WORKDIR /var/www/html

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Activer le module Apache mod_rewrite
RUN a2enmod rewrite

COPY docker-virtualhost.conf /etc/apache2/sites-available/000-default.conf
COPY www/ .
RUN chmod 777 tmp/ public/download/

RUN service apache2 restart


# composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer update

EXPOSE 80