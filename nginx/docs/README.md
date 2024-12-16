# Sistema de Gestão de Custos Extras

## Visão Geral
Sistema desenvolvido para gerenciamento de custos extras, com interface moderna e funcionalidades de controle e análise.

## Documentação por Componente

### 1. [Configuração do Sistema](config_sistema.md)
- Configurações globais
- Constantes do sistema
- Variáveis de ambiente

### 2. [Componente de Login](login_component.md)
- Estrutura da página de login
- Estilos e responsividade
- Personalização

### 3. [Sistema de Autenticação](autenticacao.md)
- Fluxo de autenticação
- Gerenciamento de sessões
- Segurança

### 4. [Componente de Rodapé](add_rodape.md)
- Implementação do rodapé
- Estilos e temas
- Configurações

## Estrutura de Diretórios

```
/
├── assets/
│   ├── css/
│   │   ├── login.css
│   │   └── footer.css
│   ├── js/
│   └── img/
├── config/
│   └── config.php
├── includes/
│   ├── footer.php
│   └── session.php
├── docs/
│   ├── README.md
│   ├── config_sistema.md
│   ├── login_component.md
│   ├── autenticacao.md
│   └── add_rodape.md
└── login.php
```

## Requisitos do Sistema

### Servidor
- PHP 7.4+
- MySQL 5.7+
- Apache 2.4+

### Cliente
- Navegadores modernos (Chrome, Firefox, Safari, Edge)
- JavaScript habilitado
- Resolução mínima: 320px

## Instalação

1. Clone o repositório
2. Configure o arquivo `config.php`
3. Importe o banco de dados
4. Configure o servidor web

## Desenvolvimento

### Padrões de Código
- PSR-12 para PHP
- BEM para CSS
- ESLint para JavaScript

### Fluxo de Trabalho
1. Criar branch feature/bugfix
2. Desenvolver/testar localmente
3. Criar pull request
4. Code review
5. Merge para main

## Deploy

### Ambiente de Desenvolvimento
1. Atualizar repositório local
2. Configurar variáveis de ambiente
3. Testar funcionalidades

### Ambiente de Produção
1. Backup do sistema atual
2. Deploy via pipeline automatizado
3. Verificar logs e monitoramento

## Manutenção

### Rotinas Diárias
- Backup do banco de dados
- Verificação de logs
- Monitoramento de performance

### Atualizações
- Seguir changelog
- Testar em staging
- Deploy em horários de baixo uso

## Suporte

### Contato
- Email: elmesson@outlook.com
- Tel: (38) 98824-9631

### Reportando Problemas
1. Descrever o problema
2. Fornecer logs relevantes
3. Detalhar passos para reproduzir
4. Informar ambiente/versão

## Licença
Todos os direitos reservados - Elmesson Analytics
