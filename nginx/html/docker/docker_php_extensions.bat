@echo off
setlocal

:: Script para instalar extensões PHP no container

:: Nome do container PHP
set PHP_CONTAINER_NAME=custos_web

:: Instalar dependências do sistema
echo Instalando dependências do sistema...
docker exec -it %PHP_CONTAINER_NAME% bash -c "apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    git \
    unzip"

:: Instalar extensões PHP
echo Instalando extensões PHP...
docker exec -it %PHP_CONTAINER_NAME% bash -c "docker-php-ext-install \
    pdo \
    pdo_mysql \
    zip \
    gd \
    mbstring \
    xml \
    opcache"

:: Instalar Composer
echo Instalando Composer...
docker exec -it %PHP_CONTAINER_NAME% bash -c "curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer"

:: Habilitar mod_rewrite do Apache
echo Habilitando mod_rewrite...
docker exec -it %PHP_CONTAINER_NAME% bash -c "a2enmod rewrite && service apache2 restart"

echo Instalação concluída!
pause
