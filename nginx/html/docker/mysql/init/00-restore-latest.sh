#!/bin/bash

# Aguardar o MySQL iniciar
while ! mysqladmin ping -h"localhost" --silent; do
    sleep 1
done

# Procurar pelo backup SQL mais recente
LATEST_BACKUP=$(ls -t /docker-entrypoint-initdb.d/*.sql 2>/dev/null | head -n1)

if [ ! -z "$LATEST_BACKUP" ]; then
    echo "Restaurando backup mais recente: $LATEST_BACKUP"
    mysql -u root -p"$MYSQL_ROOT_PASSWORD" "$MYSQL_DATABASE" < "$LATEST_BACKUP"
    echo "Restauração concluída!"
else
    echo "Nenhum backup SQL encontrado para restaurar."
fi
