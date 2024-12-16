#!/bin/bash

# Script de backup automatizado
# Realiza backup do banco de dados MySQL e arquivos do Redis

# Configurações
BACKUP_DIR="/backup/output"
MYSQL_USER="custos"
MYSQL_PASSWORD="custo#123"
MYSQL_DATABASE="custo_extras"
DATE=$(date +%Y%m%d-%H%M%S)
RETENTION_DAYS=7

# Criar diretório de backup se não existir
mkdir -p $BACKUP_DIR

# Função para log
log() {
    echo "[$(date +'%Y-%m-%d %H:%M:%S')] $1" >> "$BACKUP_DIR/backup.log"
}

# Função para limpeza de backups antigos
cleanup_old_backups() {
    log "Iniciando limpeza de backups antigos..."
    find $BACKUP_DIR -name "*.gz" -type f -mtime +$RETENTION_DAYS -delete
    find $BACKUP_DIR -name "*.sql" -type f -mtime +$RETENTION_DAYS -delete
    log "Limpeza de backups antigos concluída"
}

# Backup do MySQL
backup_mysql() {
    log "Iniciando backup do MySQL..."
    mysqldump -h db -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE > "$BACKUP_DIR/mysql-$DATE.sql"
    if [ $? -eq 0 ]; then
        gzip "$BACKUP_DIR/mysql-$DATE.sql"
        log "Backup do MySQL concluído com sucesso"
    else
        log "ERRO: Falha no backup do MySQL"
        exit 1
    fi
}

# Backup do Redis
backup_redis() {
    log "Iniciando backup do Redis..."
    redis-cli -h redis save
    if [ $? -eq 0 ]; then
        cp /backup/redis/dump.rdb "$BACKUP_DIR/redis-$DATE.rdb"
        gzip "$BACKUP_DIR/redis-$DATE.rdb"
        log "Backup do Redis concluído com sucesso"
    else
        log "ERRO: Falha no backup do Redis"
        exit 1
    fi
}

# Verificar espaço em disco
check_disk_space() {
    DISK_USAGE=$(df -h "$BACKUP_DIR" | tail -1 | awk '{print $5}' | cut -d'%' -f1)
    if [ "$DISK_USAGE" -gt 90 ]; then
        log "ALERTA: Espaço em disco crítico ($DISK_USAGE%)"
        # Enviar notificação (implementar conforme necessidade)
    fi
}

# Execução principal
main() {
    log "Iniciando processo de backup..."
    
    # Verificar espaço em disco
    check_disk_space
    
    # Realizar backups
    backup_mysql
    backup_redis
    
    # Limpar backups antigos
    cleanup_old_backups
    
    log "Processo de backup finalizado com sucesso"
}

# Executar script principal
main
