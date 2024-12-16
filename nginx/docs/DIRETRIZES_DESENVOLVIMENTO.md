# Diretrizes de Desenvolvimento - Sistema Custo Extras

## 1. Processo de Aprovação e Controle de Mudanças

### 1.1 Fluxo de Aprovação
1. **Proposta de Mudança**
   - Documentar a mudança proposta
   - Identificar impactos potenciais
   - Listar documentos relacionados
   - Estimar tempo e recursos necessários

2. **Revisão da Documentação**
   - Consultar documentos existentes:
     - DESENVOLVIMENTO.md
     - IMPLEMENTACAO.md
     - TESTES.md
     - [Documento específico do módulo]

3. **Aprovação Explícita**
   - Submeter proposta para aprovação
   - Aguardar consentimento explícito
   - Documentar aprovação recebida

### 1.2 Controle de Versão
- Criar branch específica para cada mudança
- Seguir padrão de nomenclatura: `feature/[modulo]-[descricao]`
- Manter histórico de alterações documentado

## 2. Implementação Gradual e Controlada

### 2.1 Fases de Implementação
1. **Fase de Planejamento**
   - Análise de requisitos
   - Revisão da documentação existente
   - Definição de etapas

2. **Fase de Desenvolvimento**
   - Implementação por módulos
   - Revisão de código
   - Testes unitários

3. **Fase de Testes**
   - Testes integrados
   - Testes de aceitação
   - Validação de requisitos

4. **Fase de Implantação**
   - Deploy em ambiente de teste
   - Validação final
   - Deploy em produção

### 2.2 Checkpoints Obrigatórios
- Revisão de código antes do merge
- Validação de testes automatizados
- Aprovação do responsável pelo módulo
- Documentação atualizada

## 3. Processo de Testes

### 3.1 Tipos de Testes Obrigatórios
- Testes Unitários
- Testes de Integração
- Testes de Interface
- Testes de Performance
- Testes de Segurança

### 3.2 Critérios de Aceitação
- Cobertura mínima de testes: 80%
- Todos os testes passando
- Sem vulnerabilidades críticas
- Performance dentro dos padrões

## 4. Documentação

### 4.1 Documentação Obrigatória
- Descrição da mudança
- Impactos no sistema
- Alterações no banco de dados
- Novos endpoints ou alterações
- Mudanças na interface
- Resultados dos testes

### 4.2 Atualização da Documentação
- Atualizar README.md
- Atualizar documentação técnica
- Atualizar documentação de API
- Registrar mudanças no CHANGELOG

## 5. Consulta de Documentação

### 5.1 Documentos Base (Sempre Consultar)
1. **Arquitetura e Desenvolvimento**
   - DESENVOLVIMENTO.md
   - IMPLEMENTACAO.md
   - DISASTER_RECOVERY.md

2. **Interface e Design**
   - TEMA.md
   - design_system.md
   - responsive_design_documentation.md

3. **Segurança e Autenticação**
   - LOGIN.md
   - autenticacao.md
   - USERS_MODULE.md

4. **Performance e Otimização**
   - performance_optimization.md
   - config_sistema.md

### 5.2 Documentos Específicos por Módulo
- Consultar documentação específica do módulo afetado
- Verificar dependências com outros módulos
- Revisar interfaces e integrações

## 6. Checklist de Implementação

### 6.1 Antes da Implementação
- [ ] Documentação atual revisada
- [ ] Impactos mapeados
- [ ] Aprovação obtida
- [ ] Plano de testes definido
- [ ] Branch criada

### 6.2 Durante a Implementação
- [ ] Código seguindo padrões
- [ ] Testes sendo escritos
- [ ] Documentação sendo atualizada
- [ ] Revisões periódicas

### 6.3 Após a Implementação
- [ ] Todos os testes passando
- [ ] Documentação atualizada
- [ ] Code review realizado
- [ ] Aprovação final obtida
- [ ] Merge request criado

## 7. Contatos e Suporte

### Responsável pelo Desenvolvimento
- Nome: Elmesson
- Email: elmesson@outlook.com
- Tel: (38) 98824-9631

### Ambiente de Desenvolvimento
- URL: http://localhost
- Banco: custo_extras
- Usuário: custos
- Senha: custo#123

## 8. Padrões de Desenvolvimento

### 8.1 Idioma e Fuso Horário
- **Idioma Oficial**: Português do Brasil (pt-BR)
  - Toda documentação
  - Comentários no código
  - Mensagens de interface
  - Mensagens de erro
  - Logs do sistema
  - Nomes de variáveis e funções
  - Commits e pull requests

- **Fuso Horário**: GMT-3 (Brasília)
  - Todos os registros de data e hora
  - Logs do sistema
  - Agendamentos
  - Relatórios
  - Backups
  - Processos automatizados
  - Timestamps do banco de dados

### 8.2 Formatação
- Datas: DD/MM/YYYY
- Horários: HH:mm:ss
- Valores monetários: R$ #.###,##
- Números decimais: #.###,##

## 9. Organização de Testes

### 9.1 Estrutura de Diretórios de Testes
```
/tests
├── auth/                    # Testes de autenticação e autorização
│   ├── LoginTest.php
│   ├── PermissionsTest.php
│   └── SecurityTest.php
├── database/               # Testes de banco de dados
│   ├── ConnectionTest.php
│   ├── MigrationTest.php
│   └── QueryTest.php
├── integration/            # Testes de integração
│   ├── ApiTest.php
│   └── ServicesTest.php
├── unit/                   # Testes unitários
│   ├── ModelsTest.php
│   └── HelpersTest.php
├── performance/            # Testes de performance
│   ├── LoadTest.php
│   └── StressTest.php
└── ui/                     # Testes de interface
    ├── ComponentsTest.php
    └── LayoutTest.php
```

### 9.2 Nomenclatura
- Arquivos de teste devem terminar com sufixo `Test.php`
- Classes de teste devem estender `PHPUnit\Framework\TestCase`
- Métodos de teste devem começar com prefixo `test`
- Nomes devem ser descritivos e em português do Brasil

### 9.3 Estrutura dos Arquivos de Teste
```php
<?php
namespace Tests\[Categoria];

use PHPUnit\Framework\TestCase;

class [Nome]Test extends TestCase
{
    protected function setUp(): void
    {
        // Configuração inicial
    }

    public function testFuncionalidade(): void
    {
        // Teste específico
    }

    protected function tearDown(): void
    {
        // Limpeza após teste
    }
}
```

### 9.4 Categorias de Teste
1. **Testes Unitários** (`/tests/unit/`)
   - Testar funções/métodos isoladamente
   - Usar mocks para dependências
   - Foco em lógica de negócio

2. **Testes de Integração** (`/tests/integration/`)
   - Testar interação entre componentes
   - Verificar fluxos completos
   - Testar integrações com serviços

3. **Testes de Banco de Dados** (`/tests/database/`)
   - Testar conexões
   - Verificar queries
   - Testar migrações

4. **Testes de Autenticação** (`/tests/auth/`)
   - Login/Logout
   - Permissões
   - Segurança

5. **Testes de Performance** (`/tests/performance/`)
   - Testes de carga
   - Testes de stress
   - Métricas de tempo

6. **Testes de Interface** (`/tests/ui/`)
   - Componentes visuais
   - Layout responsivo
   - Interações do usuário

### 9.5 Boas Práticas
1. **Isolamento**
   - Cada teste deve ser independente
   - Usar setUp() para preparar ambiente
   - Usar tearDown() para limpeza

2. **Nomenclatura**
   - Nomes claros e descritivos
   - Indicar o que está sendo testado
   - Usar português do Brasil

3. **Documentação**
   - Documentar propósito do teste
   - Documentar pré-condições
   - Documentar resultados esperados

4. **Organização**
   - Um arquivo por classe de teste
   - Agrupar testes relacionados
   - Manter hierarquia de diretórios

### 9.6 Execução de Testes
```bash
# Executar todos os testes
./vendor/bin/phpunit

# Executar categoria específica
./vendor/bin/phpunit tests/[categoria]

# Executar arquivo específico
./vendor/bin/phpunit tests/[categoria]/[Arquivo]Test.php
```

### 9.7 Relatórios e Cobertura
- Gerar relatórios de cobertura
- Manter cobertura mínima de 80%
- Documentar partes não testadas
