# Build stage
FROM php:8.1-apache as builder

# Argumentos de build
ARG USER=www-data
ARG GROUP=www-data
ARG CACHE_DIR=/var/cache

# Configurar timezone e locale
ENV TZ=America/Sao_Paulo
ENV LANG=pt_BR.UTF-8
ENV LANGUAGE=pt_BR:pt
ENV LC_ALL=pt_BR.UTF-8

# Criar diretórios de cache
RUN mkdir -p ${CACHE_DIR}/composer ${CACHE_DIR}/npm \
    && chown -R ${USER}:${GROUP} ${CACHE_DIR}

# Instalar Node.js e npm com cache
RUN --mount=type=cache,target=/var/cache/apt \
    curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Instalar dependências e extensões PHP
RUN apt-get update && apt-get install -y \
    locales \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    git \
    unzip \
    curl \
    chromium \
    chromium-driver \
    && echo "pt_BR.UTF-8 UTF-8" >> /etc/locale.gen \
    && locale-gen pt_BR.UTF-8 \
    && ln -snf /usr/share/zoneinfo/$TZ /etc/localtime \
    && echo $TZ > /etc/timezone \
    && docker-php-ext-install \
       pdo \
       pdo_mysql \
       zip \
       gd \
       mbstring \
       xml \
       opcache \
       intl \
    && docker-php-ext-enable \
       pdo_mysql \
       zip \
       gd \
       intl \
    && rm -rf /var/lib/apt/lists/*

# Configurar OPCache
RUN { \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=4000'; \
    echo 'opcache.revalidate_freq=60'; \
    echo 'opcache.fast_shutdown=1'; \
    echo 'opcache.enable_cli=1'; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini

# Copiar composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install TCPDF library
RUN composer require tecnickcom/tcpdf

# Configurar diretório de trabalho
WORKDIR /var/www/html

# Copiar arquivos do projeto
COPY composer.json composer.lock ./
COPY package.json ./

# Instalar dependências do Composer e NPM
RUN --mount=type=cache,target=/var/cache/composer \
    COMPOSER_CACHE_DIR=/var/cache/composer composer install \
    --no-interaction --no-plugins --no-scripts --prefer-dist --no-autoloader

RUN --mount=type=cache,target=/var/cache/npm \
    npm install --cache /var/cache/npm --prefer-offline

# Stage final
FROM php:8.1-apache

# Configurar timezone e locale
ENV TZ=America/Sao_Paulo
ENV LANG=pt_BR.UTF-8
ENV LANGUAGE=pt_BR:pt
ENV LC_ALL=pt_BR.UTF-8
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Copiar arquivos e configurações do stage anterior
COPY --from=builder /var/www/html/vendor /var/www/html/vendor
COPY --from=builder /var/www/html/node_modules /var/www/html/node_modules
COPY --from=builder /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d
COPY --from=builder /usr/bin/composer /usr/bin/composer
COPY --from=builder /usr/bin/node /usr/bin/node
COPY --from=builder /usr/bin/npm /usr/bin/npm

# Instalar extensões PHP no stage final
RUN apt-get update && apt-get install -y \
    locales \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    curl \
    && docker-php-ext-install \
       pdo \
       pdo_mysql \
       zip \
       gd \
       mbstring \
       xml \
       opcache \
       intl \
    && docker-php-ext-enable \
       pdo_mysql \
       zip \
       gd \
       intl \
    && rm -rf /var/lib/apt/lists/*

# Copiar configuração do Apache
COPY docker/php/apache.conf /etc/apache2/sites-available/000-default.conf

# Copiar script de configuração dos assets
COPY docker/php/setup-assets.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/setup-assets.sh

# Copiar o código
COPY . .

# Configurar Apache
RUN a2enmod rewrite

# Criar diretórios necessários e definir permissões
RUN mkdir -p storage/app/public \
       storage/framework/cache \
       storage/framework/sessions \
       storage/framework/views \
       storage/logs \
       bootstrap/cache \
       public/views/errors.bak \
    && cp -r public/views/errors/* public/views/errors.bak/ \
    && chown -R www-data:www-data . \
    && chmod -R 755 . \
    && chmod -R 775 storage bootstrap/cache

# Configurar assets
RUN /usr/local/bin/setup-assets.sh

# Copiar script de entrada
COPY docker/php/docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Definir ponto de entrada
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
