# Checklist para Criação de Novo Módulo

Use este checklist ao criar um novo módulo para garantir que todos os padrões e requisitos sejam atendidos.

## 1. Estrutura de Diretórios

- [ ] Criar diretório principal do módulo em `/app/modules/nome_modulo/`
- [ ] Criar subdiretórios:
  - [ ] `/views`
  - [ ] `/views/components`
  - [ ] `/models`
  - [ ] `/controllers`
  - [ ] `/config`
  - [ ] `/assets/js`
  - [ ] `/assets/css`
  - [ ] `/assets/img`

## 2. Arquivos Base

- [ ] Criar arquivo `.htaccess`
- [ ] Criar arquivo `index.php`
- [ ] Criar arquivo `functions.php`
- [ ] Criar arquivo `config/config.php`

## 3. Segurança

- [ ] Implementar verificação de sessão
- [ ] Adicionar proteção CSRF
- [ ] Validar inputs
- [ ] Configurar permissões de acesso

## 4. Banco de Dados

- [ ] Criar scripts SQL em `/app/modules/database/sql/`
- [ ] Usar prepared statements
- [ ] Documentar esquema do banco
- [ ] Implementar backup e rollback

## 5. Frontend

- [ ] Criar templates base
- [ ] Implementar validação JavaScript
- [ ] Otimizar assets
- [ ] Testar responsividade

## 6. Testes

- [ ] Criar testes unitários em `/app/modules/tests/nome_modulo/`
- [ ] Testar casos de erro
- [ ] Validar segurança
- [ ] Testar performance

## 7. Documentação

- [ ] Atualizar README principal
- [ ] Documentar APIs
- [ ] Documentar funções
- [ ] Criar exemplos de uso

## 8. Deploy

- [ ] Testar em ambiente de desenvolvimento
- [ ] Verificar dependências
- [ ] Otimizar para produção
- [ ] Fazer backup antes do deploy

## 9. Pós-Deploy

- [ ] Monitorar erros
- [ ] Verificar logs
- [ ] Validar funcionalidades
- [ ] Coletar feedback

Use este checklist como guia para garantir que todos os aspectos importantes sejam considerados ao criar um novo módulo.
