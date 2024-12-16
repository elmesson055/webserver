FROM php:8.1-cli-alpine

# Instalar extensões PHP necessárias
RUN docker-php-ext-install sockets pcntl

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Criar diretório da aplicação
WORKDIR /app

# Copiar arquivos do WebSocket
COPY app/modules/notificacoes/websocket /app/websocket

# Instalar dependências
RUN composer require cboden/ratchet

# Expor porta do WebSocket
EXPOSE 8080

# Comando para iniciar o servidor WebSocket
CMD ["php", "/app/websocket/server.php"]
