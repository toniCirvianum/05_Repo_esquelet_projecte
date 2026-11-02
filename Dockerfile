FROM php:8.3-apache-bookworm

# Composer gestiona les llibreries PHP del projecte.
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

RUN docker-php-ext-install pdo_mysql
RUN apt-get update && apt-get install -y git zip unzip \
    && rm -rf /var/lib/apt/lists/*
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug
RUN { \
  echo "zend_extension=xdebug"; \
  echo "xdebug.mode=develop,debug"; \
  echo "xdebug.start_with_request=yes"; \
  echo "xdebug.discover_client_host=false"; \
  echo "xdebug.client_host=host.docker.internal"; \
  echo "xdebug.client_port=9003"; \
  echo "xdebug.log_level=0"; \
} > /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /var/www/html
ENV COMPOSER_ALLOW_SUPERUSER=1

# Instal·lem les dependències en arrencar, després de muntar el projecte.
# Si no existeix composer.json, require el crea i genera el lock i vendor/.
# Si ja existeix, install utilitza les dependències declarades i el lock.
# Amb bash -e, un error de Composer impedeix l'arrencada d'Apache.
#
# Per afegir una llibreria, per exemple PHPMailer:
# 1. Atura el servei: docker compose stop php
# 2. Copia la línia d'exemple següent dins del CMD, just després de fi;
#    i abans d'a2enmod rewrite, sense el # inicial i conservant la barra final.
#    composer require phpmailer/phpmailer --no-interaction --prefer-dist; \
# 3. Reconstrueix i arrenca: docker compose up -d --build php
#    En arrencar, require actualitza composer.json, composer.lock i vendor/.
# 4. Un cop instal·lada, retira la línia afegida al CMD: composer install
#    ja instal·larà PHPMailer en les arrencades següents. Per aplicar-ho,
#    torna a aturar el servei i executa docker compose up -d --build php.
# No utilitzis RUN composer require: s'executaria durant la construcció,
# abans de muntar la carpeta del projecte.
# Conserva composer.json i composer.lock al repositori; vendor/ està exclòs.
# Afegir un paquet al require d'aquest CMD només afecta projectes que encara
# no tenen composer.json: no afegeix dependències a un projecte ja inicialitzat.
# Totes les llibreries es carreguen amb un únic vendor/autoload.php des de PHP.
CMD ["bash", "-ec", "\
    if [ ! -f composer.json ]; then \
        composer require vlucas/phpdotenv:^5.6 --no-interaction --prefer-dist; \
        composer require phpmailer/phpmailer --no-interaction --prefer-dist; \
        composer require google/apiclient --no-interaction --prefer-dist; \
    else \
        composer install --no-interaction --prefer-dist; \
    fi; \
    a2enmod rewrite; \
    exec apache2-foreground"]
