FROM php:8.4-fpm

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
	git zip unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev \
	&& docker-php-ext-install pdo pdo_mysql zip

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Cria usuário com UID 1000
RUN useradd -u 1000 -ms /bin/bash devuser
USER devuser

WORKDIR /var/www

COPY --chown=devuser:devuser . /var/www

