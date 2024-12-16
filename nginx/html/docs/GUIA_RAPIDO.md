# Guia Rápido de Referência

## Comandos Principais

### Docker
```bash
# Iniciar ambiente de produção
docker-compose -f docker-compose.prod.yml up -d

# Verificar status dos containers
docker-compose -f docker-compose.prod.yml ps

# Visualizar logs
docker-compose -f docker-compose.prod.yml logs -f

# Reiniciar serviços
docker-compose -f docker-compose.prod.yml restart
```

### Backup
```bash
# Backup manual
./docker/scripts/backup.sh

# Verificar logs de backup
tail -f /backup/output/backup.log
```

### Monitoramento
```bash
# Verificar status do sistema
curl http://localhost/healthcheck.php

# Monitoramento manual
./docker/scripts/monitor.sh

# Verificar logs de monitoramento
tail -f /var/log/monitor/monitor.log
```

## URLs Importantes

- **Aplicação**: http://localhost
- **Healthcheck**: http://localhost/healthcheck.php
- **PHPMyAdmin**: http://localhost:8080

## Portas

- **80**: HTTP
- **443**: HTTPS
- **3306**: MySQL
- **6379**: Redis

## Diretórios Importantes

- **/var/www/html**: Código fonte
- **/backup/output**: Backups
- **/var/log**: Logs do sistema

## Credenciais Padrão

### MySQL
- **Database**: custo_extras
- **User**: custos
- **Password**: custo#123

### Redis
- **Password**: Definida em REDIS_PASSWORD no .env

### Admin
- **User**: admin
- **Password**: Admin#123

## Contatos

- **Responsável**: Elmesson
- **Email**: elmesson@outlook.com
- **Tel**: (38) 98824-9631

## Documentação Completa

Consulte os seguintes arquivos para mais detalhes:
- `docs/IMPLEMENTACOES_SISTEMA.md`: Documentação detalhada
- `docs/DIRETRIZES_DESENVOLVIMENTO.md`: Diretrizes de desenvolvimento
- `docs/README.md`: Visão geral do sistema
