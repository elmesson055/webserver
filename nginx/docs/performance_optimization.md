# Guia de Otimização de Performance - Custo Extras

## 1. Análise de Consultas SQL

### Problemas Comuns
- Consultas sem índices
- Consultas sem preparação
- Consultas com muitos JOINs
- Consultas que retornam dados desnecessários

### Boas Práticas

#### Criação de Índices
```sql
-- Exemplo de criação de índice
CREATE INDEX idx_usuarios_email ON usuarios(email);
CREATE INDEX idx_custos_data ON custos(data_criacao);
```

#### Consultas Preparadas
```php
// Ruim
$result = $db->query("SELECT * FROM usuarios WHERE email = '$email'");

// Bom
$stmt = $db->prepare("SELECT * FROM usuarios WHERE email = :email");
$stmt->execute(['email' => $email]);
```

#### Limitação de Resultados
```php
// Sempre use LIMIT
$stmt = $db->prepare("SELECT * FROM custos WHERE usuario_id = :id LIMIT 50");
```

## 2. Otimização de Código PHP

### Problemas Comuns
- Loops desnecessários
- Processamento síncrono
- Carregamento de dados em massa
- Falta de cache

### Boas Práticas

#### Carregamento Lazy
```php
// Ruim: Carrega tudo de uma vez
$todosUsuarios = Usuario::all();

// Bom: Carrega sob demanda
$usuarios = Usuario::paginate(50);
```

#### Uso de Cache
```php
// Exemplo de cache de permissões
$permissoes = Redis::remember('user_permissions_'.$userId, 3600, function() use ($userId) {
    return PermissaoService::getPermissoes($userId);
});
```

## 3. Otimização de Infraestrutura

### Configurações Recomendadas

#### PHP-FPM
```ini
; php-fpm.conf
pm = dynamic
pm.max_children = 50
pm.start_servers = 20
pm.min_spare_servers = 10
pm.max_spare_servers = 30
```

#### MySQL
```ini
; my.cnf
innodb_buffer_pool_size = 2G
query_cache_size = 256M
max_connections = 200
```

## 4. Monitoramento

### Ferramentas
- Xdebug
- New Relic
- Blackfire.io
- MySQL Slow Query Log

### Comandos de Diagnóstico
```bash
# Verificar consultas lentas
SHOW FULL PROCESSLIST;
EXPLAIN SELECT ...;
```

## 5. Estratégias Adicionais

- Implementar microserviços
- Usar filas para processamento assíncrono
- Considerar cache distribuído
- Otimizar consultas complexas

## 6. Implementação de Analytics de Custos Logísticos

### Visão Geral do Sistema de Analytics

#### Objetivos
- Análise detalhada de custos por transportadora
- Geração de relatórios de performance
- Identificação de oportunidades de otimização
- Suporte à tomada de decisão estratégica

### Componentes Principais

#### 1. Repositórios de Dados
- `TransportadoraRepository`
  - Recupera informações de transportadoras
  - Métodos:
    - `obterTodasTransportadoras()`
    - `obterDetalhesTransportadora()`

- `CustosRepository`
  - Gerencia dados de custos
  - Métodos:
    - `obterHistoricoCustos()`
    - `obterTiposCusto()`
    - `registrarCustoExtra()`

- `EmbarcadorRepository`
  - Recupera informações de embarcadores
  - Método: `obterTodosEmbarcadores()`

#### 2. Serviço de Analytics

##### Funcionalidades Principais
- Análise de custos por transportadora
- Cálculo de métricas de performance
- Geração de relatórios detalhados
- Recomendações de otimização

##### Métricas Calculadas
- Total de custos
- Número de registros
- Média de custo
- Variação de custo
- Agrupamento por tipo de custo

#### 3. Estratégias de Análise

##### Cálculo de Variação de Custo
- Utiliza coeficiente de variação
- Identifica instabilidade nos custos
- Threshold de 30% para recomendações

##### Agrupamento de Tipos de Custo
- Agrupa custos por categoria
- Identifica categorias com maior impacto financeiro
- Destaca custos acima de R$ 50.000

#### 4. Geração de Recomendações

##### Critérios de Recomendação
- Variação de custo superior a 30%
- Custos totais acima de R$ 100.000
- Baixo volume de registros
- Análise de tipos de custo específicos

#### 5. Relatórios Gerados

##### Estrutura do Relatório
- Data de geração
- Resumo geral
- Detalhes por transportadora
  - Nome
  - Métricas de custo
  - Tipos de custo

##### Recomendações
- Ações sugeridas por transportadora
- Estratégias de otimização
- Potenciais economias

### Benefícios da Implementação

#### Tomada de Decisão
- Dados precisos e contextualizados
- Insights estratégicos
- Suporte à negociação com transportadoras

#### Otimização de Custos
- Identificação de ineficiências
- Recomendações personalizadas
- Potencial de redução de gastos

#### Transparência
- Relatórios detalhados
- Rastreabilidade de custos
- Auditoria simplificada

### Próximos Passos

#### Desenvolvimento
- Implementar interface de visualização
- Adicionar mais métricas de análise
- Desenvolver dashboard interativo

#### Melhorias Futuras
- Integração com machine learning
- Previsão de custos
- Análise preditiva de tendências

### Considerações Técnicas

#### Tecnologias
- Linguagem: PHP 8.1+
- Banco de Dados: MySQL 8.0
- Arquitetura: Repositórios e Serviços

#### Segurança
- Tratamento de erros
- Conexão segura com banco de dados
- Logging de erros

### Exemplo de Uso

```php
$analyticsService = new AnalyticsService();
$relatorio = $analyticsService->gerarAnaliseCompletaCustos();
$recomendacoes = $analyticsService->gerarRecomendacoesOtimizacao($relatorio);
```

### Métricas de Sucesso

- Redução de custos
- Precisão das recomendações
- Tempo de análise
- Facilidade de interpretação

## Próximos Passos

1. Realizar auditoria de performance
2. Identificar gargalos específicos
3. Implementar otimizações incrementalmente
4. Medir impacto das melhorias
