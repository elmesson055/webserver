# Changelog

## [2023-12-15]

### Alterações
- Corrigido problema de rota na página de login
- Atualizado nginx.conf para melhor tratamento de arquivos PHP
- Adicionada documentação da estrutura do projeto
- Removido arquivo login.php duplicado da raiz
- Corrigido caminhos de inclusão no dashboard/index.php usando dirname(__FILE__)
- Simplificado o layout do dashboard
- Corrigido configurações de sessão para serem definidas antes de session_start()
- Adicionado diretório personalizado para armazenamento de sessões
- Criado guia completo de diretrizes de desenvolvimento
- Corrigido caminhos de inclusão em todos os arquivos usando dirname(__FILE__) corretamente
- Melhorado a sidebar com todos os módulos disponíveis e categorias
- Adicionado ícones para todos os módulos
- Implementado estilo visual melhorado para a sidebar
- Criado tabela de módulos e permissões no banco de dados
- Adicionado tratamento de erros na sidebar
- Implementado fallback para quando não há módulos disponíveis
- Atualizado estrutura da tabela modules com novas colunas
- Reescrito script de inserção de módulos para evitar erros de sintaxe

### Arquivos Afetados
- `/app/modules/auth/login.php` - Atualizado para usar caminhos relativos e corrigido includes
- `/conf/nginx.conf` - Melhorada configuração PHP e rotas
- `/docs/project_structure.md` - Criada documentação da estrutura
- `/docs/CHANGELOG.md` - Criado arquivo de changelog
- `/app/Views/dashboard/index.php` - Corrigido caminhos de inclusão e atualizado layout
- `/config/session.php` - Corrigido ordem das configurações de sessão
- Criado diretório `/storage/sessions` para armazenamento seguro das sessões
- `/docs/development_guidelines.md` - Criado guia completo de desenvolvimento
- `/app/modules/common/sidebar.php` - Atualizado com novos módulos e melhor organização
- `/sql/create_modules.sql` - Criado script para tabelas de módulos e permissões
- `/sql/update_modules_table.sql` - Criado script para atualizar estrutura das tabelas

### Próximos Passos
1. Validar todas as rotas do sistema
2. Implementar sistema de logs mais robusto
3. Criar documentação de API (se aplicável)
4. Implementar testes automatizados
5. Revisar e expandir diretrizes conforme necessário
6. Implementar verificação automatizada de caminhos de inclusão
7. Criar páginas index.php para todos os módulos listados na sidebar
8. Implementar controle de permissões por módulo
9. Adicionar breadcrumbs para navegação
10. Executar script de atualização das tabelas de módulos
11. Popular tabela de permissões para outros papéis além do administrador
12. Verificar e corrigir possíveis problemas de codificação UTF-8 nos nomes dos módulos

### Notas de Manutenção
- Consultar `/docs/development_guidelines.md` antes de qualquer desenvolvimento
- Sempre definir configurações de sessão antes de session_start()
- Sempre usar dirname(__FILE__) para caminhos relativos e contar corretamente os níveis de diretório
- Sempre consultar `/docs/project_structure.md` antes de fazer alterações
- Manter o CHANGELOG.md atualizado com todas as mudanças
- Seguir a estrutura de diretórios documentada
- Evitar duplicação de arquivos
- Documentar todas as alterações nas configurações
- Ao adicionar novos módulos, incluir na estrutura de categorias da sidebar
- Manter consistência nos ícones e estilos visuais
- Sempre verificar permissões ao adicionar novos módulos
- Manter backup do banco de dados antes de alterações estruturais
- Verificar codificação UTF-8 ao trabalhar com caracteres especiais
