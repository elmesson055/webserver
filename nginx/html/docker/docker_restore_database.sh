#!/bin/bash

# Configurações
BACKUP_DIR="/backup"
MYSQL_USER="custos"
MYSQL_PASSWORD="custo#123"

# Função de uso
usage() {
    echo "Uso: $0 [opções]"
    echo "Opções:"
    echo "  -d, --database NOME_DO_BANCO   Especificar banco de dados para restaurar"
    echo "  -f, --file CAMINHO_BACKUP      Caminho completo para o arquivo de backup"
    echo "  -l, --list                     Listar backups disponíveis"
    echo "  -h, --help                     Mostrar esta mensagem de ajuda"
    exit 1
}

# Listar backups
list_backups() {
    echo "Backups disponíveis:"
    find $BACKUP_DIR -name "*.tar.gz" | sort -r
}

# Restaurar banco de dados
restore_database() {
    local DATABASE=$1
    local BACKUP_FILE=$2

    # Verificar se o arquivo de backup existe
    if [ ! -f "$BACKUP_FILE" ]; then
        echo "Erro: Arquivo de backup não encontrado."
        exit 1
    }

    # Descompactar backup
    tar -xzvf "$BACKUP_FILE"

    # Encontrar arquivo SQL
    SQL_FILE=$(find /backup -name "*.sql")

    if [ -z "$SQL_FILE" ]; then
        echo "Erro: Nenhum arquivo SQL encontrado no backup."
        exit 1
    fi

    # Restaurar banco de dados
    echo "Restaurando banco de dados $DATABASE..."
    mysql -u $MYSQL_USER -p$MYSQL_PASSWORD $DATABASE < "$SQL_FILE"

    if [ $? -eq 0 ]; then
        echo "Restauração concluída com sucesso."
        # Limpar arquivos temporários
        rm "$SQL_FILE"
    else
        echo "Erro na restauração do banco de dados."
        exit 1
    fi
}

# Processar argumentos
while [[ $# -gt 0 ]]; do
    key="$1"
    case $key in
        -d|--database)
        DATABASE="$2"
        shift
        shift
        ;;
        -f|--file)
        BACKUP_FILE="$2"
        shift
        shift
        ;;
        -l|--list)
        list_backups
        exit 0
        ;;
        -h|--help)
        usage
        ;;
        *)
        echo "Argumento inválido: $1"
        usage
        ;;
    esac
done

# Verificar argumentos obrigatórios
if [ -z "$DATABASE" ] || [ -z "$BACKUP_FILE" ]; then
    usage
fi

# Executar restauração
restore_database "$DATABASE" "$BACKUP_FILE"
