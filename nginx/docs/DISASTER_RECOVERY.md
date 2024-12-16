# Manual de Scripts Anti-Desastre - Custo Extras

## 📜 Scripts Disponíveis

### 1. auto_deploy.bat
**Localização**: `C:\custo-extras\docker\auto_deploy.bat`

**Quando Usar**:
- Para fazer deploy da aplicação
- Para atualizar a aplicação
- Para reiniciar todos os serviços

**Como Executar**:
```batch
cd C:\custo-extras\docker
auto_deploy.bat
```

**O que ele faz**:
1. Cria backup automático antes do deploy
2. Verifica espaço em disco
3. Para containers existentes
4. Atualiza imagens Docker
5. Inicia novos containers
6. Verifica integridade do sistema

### 2. realtime_backup.bat
**Localização**: `C:\custo-extras\docker\realtime_backup.bat`

**Quando Usar**:
- Antes de fazer alterações críticas
- Para criar backup manual
- Para garantir ponto de restauração

**Como Executar**:
```batch
cd C:\custo-extras\docker
realtime_backup.bat
```

**O que ele faz**:
1. Cria snapshot do banco de dados
2. Backup dos volumes Docker
3. Backup das configurações
4. Gera índice do backup

### 3. restore_snapshot.bat
**Localização**: `C:\custo-extras\docker\restore_snapshot.bat`

**Quando Usar**:
- Após falha no deploy
- Quando banco de dados corrompido
- Para reverter alterações indesejadas
- Em caso de perda de dados

**Como Executar**:
```batch
cd C:\custo-extras\docker
restore_snapshot.bat
```

**O que ele faz**:
1. Lista snapshots disponíveis
2. Restaura banco de dados selecionado
3. Restaura volumes Docker
4. Restaura configurações
5. Reinicia o sistema

## 🚨 Cenários de Recuperação

### Cenário 1: Falha Durante Deploy
1. Execute:
   ```batch
   cd C:\custo-extras\docker
   restore_snapshot.bat
   ```
2. Selecione o snapshot mais recente antes da falha
3. Aguarde restauração completa

### Cenário 2: Banco de Dados Corrompido
1. Execute:
   ```batch
   cd C:\custo-extras\docker
   restore_snapshot.bat
   ```
2. Selecione último snapshot com banco íntegro
3. Aguarde restauração completa

### Cenário 3: Perda de Dados
1. Execute backup manual primeiro:
   ```batch
   cd C:\custo-extras\docker
   realtime_backup.bat
   ```
2. Execute restauração:
   ```batch
   restore_snapshot.bat
   ```
3. Selecione snapshot desejado

### Cenário 4: Atualização do Sistema
1. Crie backup preventivo:
   ```batch
   cd C:\custo-extras\docker
   realtime_backup.bat
   ```
2. Execute deploy:
   ```batch
   auto_deploy.bat
   ```

## 📁 Estrutura de Arquivos

```
C:\custo-extras\
├── docker\
│   ├── auto_deploy.bat     # Script de deploy
│   ├── realtime_backup.bat # Script de backup
│   └── restore_snapshot.bat # Script de restauração
├── backup_mysql_custo-extras\
│   ├── emergency\          # Backups de emergência
│   └── snapshots\          # Snapshots completos
└── Logs\                   # Logs de operações
```

## 🌐 Links de Acesso ao Sistema

### Ambiente Local
- **Sistema Web**: http://localhost
  - Interface principal do Custo Extras
  - Login administrativo

### Serviços
- **phpMyAdmin**: http://localhost:8080
  - Gerenciamento do banco de dados
  - Usuário: custos
  - Senha: custo#123

### Portas de Serviço
- Web (Apache): 80
- MySQL: 3307
- Redis: 6379
- phpMyAdmin: 8080

### Verificação de Status
Para verificar se todos os serviços estão rodando:
```batch
cd C:\custo-extras\docker
docker-compose ps
```

## ⚠️ Observações Importantes

1. **Antes de Qualquer Restauração**:
   - Verifique se há espaço em disco suficiente
   - Não interrompa o processo de restauração
   - Mantenha os logs para referência

2. **Manutenção dos Backups**:
   - Mantenha pelo menos 5 snapshots recentes
   - Verifique periodicamente a integridade dos backups
   - Limpe backups mais antigos que 30 dias

3. **Em Caso de Falha nos Scripts**:
   - Verifique os logs em `C:\custo-extras\Logs\`
   - Não tente executar o mesmo script novamente sem verificar os logs
   - Em caso de dúvida, contate o suporte técnico

## 🔐 Informações de Acesso

### Banco de Dados
- Database: custo_extras
- Usuário: custos
- Senha: custo#123

### Containers
- Web: custos_web
- DB: custos_db
- Redis: custos_redis
- Backup: custos_backup

## 📞 Suporte

Em caso de falha nos scripts ou necessidade de suporte:
1. Verifique os logs
2. Documente o erro encontrado
3. Contate o suporte técnico com as informações coletadas
