#!/bin/bash

# Script de monitoramento do sistema
# Monitora recursos e serviços críticos

# Configurações
LOG_DIR="/var/log/monitor"
ALERT_THRESHOLD_CPU=80
ALERT_THRESHOLD_MEM=80
ALERT_THRESHOLD_DISK=90

# Criar diretório de log se não existir
mkdir -p $LOG_DIR

# Função para log
log() {
    echo "[$(date +'%Y-%m-%d %H:%M:%S')] $1" >> "$LOG_DIR/monitor.log"
}

# Monitorar CPU
check_cpu() {
    CPU_USAGE=$(top -bn1 | grep "Cpu(s)" | awk '{print $2}' | cut -d'.' -f1)
    if [ "$CPU_USAGE" -gt $ALERT_THRESHOLD_CPU ]; then
        log "ALERTA: Uso de CPU alto ($CPU_USAGE%)"
        return 1
    fi
    return 0
}

# Monitorar Memória
check_memory() {
    MEM_USAGE=$(free | grep Mem | awk '{print $3/$2 * 100.0}' | cut -d'.' -f1)
    if [ "$MEM_USAGE" -gt $ALERT_THRESHOLD_MEM ]; then
        log "ALERTA: Uso de memória alto ($MEM_USAGE%)"
        return 1
    fi
    return 0
}

# Monitorar Disco
check_disk() {
    DISK_USAGE=$(df -h / | tail -1 | awk '{print $5}' | cut -d'%' -f1)
    if [ "$DISK_USAGE" -gt $ALERT_THRESHOLD_DISK ]; then
        log "ALERTA: Uso de disco alto ($DISK_USAGE%)"
        return 1
    fi
    return 0
}

# Verificar serviços
check_services() {
    # Verificar Apache
    if ! pgrep apache2 > /dev/null; then
        log "ERRO: Serviço Apache não está rodando"
        return 1
    fi

    # Verificar MySQL
    if ! mysqladmin ping -h db -u custos -pcusto#123 --silent; then
        log "ERRO: Serviço MySQL não está respondendo"
        return 1
    fi

    # Verificar Redis
    if ! redis-cli -h redis ping > /dev/null; then
        log "ERRO: Serviço Redis não está respondendo"
        return 1
    fi

    return 0
}

# Verificar conexões
check_connections() {
    # Conexões TCP
    CONN_COUNT=$(netstat -an | grep ESTABLISHED | wc -l)
    log "INFO: Conexões TCP ativas: $CONN_COUNT"

    # Conexões Apache
    APACHE_CONN=$(netstat -an | grep :80 | grep ESTABLISHED | wc -l)
    log "INFO: Conexões Apache: $APACHE_CONN"

    # Conexões MySQL
    MYSQL_CONN=$(netstat -an | grep :3306 | grep ESTABLISHED | wc -l)
    log "INFO: Conexões MySQL: $MYSQL_CONN"
}

# Verificar logs de erro
check_error_logs() {
    # Apache errors
    APACHE_ERRORS=$(tail -n 100 /var/log/apache2/error.log | grep -i error | wc -l)
    if [ "$APACHE_ERRORS" -gt 0 ]; then
        log "ALERTA: $APACHE_ERRORS erros encontrados no log do Apache"
    fi

    # PHP errors
    PHP_ERRORS=$(tail -n 100 /var/log/php/error.log | grep -i error | wc -l)
    if [ "$PHP_ERRORS" -gt 0 ]; then
        log "ALERTA: $PHP_ERRORS erros encontrados no log do PHP"
    fi

    # MySQL errors
    MYSQL_ERRORS=$(tail -n 100 /var/log/mysql/error.log | grep -i error | wc -l)
    if [ "$MYSQL_ERRORS" -gt 0 ]; then
        log "ALERTA: $MYSQL_ERRORS erros encontrados no log do MySQL"
    fi
}

# Verificar performance do banco
check_db_performance() {
    # Slow queries
    SLOW_QUERIES=$(mysql -h db -u custos -pcusto#123 -e "SHOW GLOBAL STATUS LIKE 'Slow_queries';" | tail -1 | awk '{print $2}')
    if [ "$SLOW_QUERIES" -gt 0 ]; then
        log "ALERTA: $SLOW_QUERIES queries lentas detectadas"
    fi

    # Conexões abertas
    OPEN_CONNECTIONS=$(mysql -h db -u custos -pcusto#123 -e "SHOW GLOBAL STATUS LIKE 'Threads_connected';" | tail -1 | awk '{print $2}')
    log "INFO: Conexões MySQL abertas: $OPEN_CONNECTIONS"
}

# Execução principal
main() {
    log "Iniciando verificação do sistema..."
    
    check_cpu
    check_memory
    check_disk
    check_services
    check_connections
    check_error_logs
    check_db_performance
    
    log "Verificação do sistema concluída"
}

# Executar script principal
main
