# Sistema de Custos Extras

## Estrutura do Sistema

O sistema está organizado em módulos independentes, cada um com sua própria responsabilidade:

### Módulos

1. **Painel**
   - Dashboard principal
   - Visão geral do sistema

2. **Custos Extras**
   - Registro Inicial
   - Aprovações
   - Análise

3. **Cadastros**
   - Fornecedores
   - Status Gerais
   - Usuários
   - Perfis

4. **Relatórios**
   - Relatório de Custos
   - Relatório de Aprovações

5. **Notificações**
   - Lista de Notificações
   - Regras de Notificação
   - Histórico

6. **Auditoria**
   - Logs de Auditoria
   - Versões
   - Alterações

## Tecnologias Utilizadas

- PHP 8.1
- MySQL 8.0
- Redis
- Memcached
- WebSocket para notificações em tempo real
- Docker para containerização

## Configuração do Ambiente

### Requisitos

- Docker
- Docker Compose

### Instalação

1. Clone o repositório
2. Execute o comando:
```bash
docker-compose up -d
```

### Serviços

- **App**: http://localhost
- **WebSocket**: ws://localhost:8080
- **MySQL**: localhost:3306
- **Redis**: localhost:6379
- **Memcached**: localhost:11211

## Estrutura de Arquivos

```
app/
├── core/
│   ├── Router.php
│   └── ...
├── modules/
│   ├── auditoria/
│   ├── cadastros/
│   ├── custos_extras/
│   ├── layouts/
│   ├── notificacoes/
│   ├── painel/
│   └── relatorios/
├── config/
│   ├── modules.php
│   └── routes.php
└── ...

docker/
├── mysql/
│   └── init.sql
├── websocket/
│   └── Dockerfile
└── ...

public/
├── assets/
│   ├── css/
│   ├── js/
│   └── img/
└── ...
```

## Funcionalidades Principais

1. **Gestão de Custos Extras**
   - Registro de novos custos
   - Fluxo de aprovação
   - Análise e relatórios

2. **Sistema de Notificações**
   - Notificações em tempo real via WebSocket
   - Regras configuráveis
   - Histórico de notificações

3. **Auditoria**
   - Registro de todas as alterações
   - Versionamento de documentos
   - Logs detalhados

4. **Relatórios**
   - Exportação em CSV
   - Filtros avançados
   - Gráficos e dashboards

## Segurança

- Autenticação de usuários
- Controle de acesso baseado em perfis
- Registro de todas as ações
- Proteção contra SQL Injection
- Validação de dados
- Sanitização de entradas

## Manutenção

### Banco de Dados
- Backups automáticos
- Migrations para controle de versão
- Índices otimizados

### Cache
- Redis para cache de sessão
- Memcached para cache de dados
- Estratégia de invalidação de cache

### Logs
- Rotação de logs
- Níveis de log configuráveis
- Monitoramento de erros

## Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature
3. Commit suas mudanças
4. Push para a branch
5. Abra um Pull Request

## Suporte

Para suporte, entre em contato com a equipe de desenvolvimento.
