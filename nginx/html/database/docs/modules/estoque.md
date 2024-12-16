# Módulo de Estoque - WMS e Controle de Lotes

## 1. Estrutura do WMS

### 1.1 Hierarquia de Localização
- **Armazém**: Unidade física principal
- **Área**: Subdivisão do armazém (Recebimento, Armazenagem, Picking, Expedição)
- **Endereço**: Localização específica (Rua > Prateleira > Nível > Posição)

### 1.2 Controle de Lotes
- Rastreabilidade completa
- Gestão de validade
- Movimentações
- Status do lote (Quarentena, Disponível, Bloqueado, Vencido)

## 2. Funcionalidades Principais

### 2.1 Gestão de Armazéns
- Cadastro de armazéns e áreas
- Definição de capacidades
- Mapeamento de endereços
- Monitoramento de ocupação

### 2.2 Controle de Lotes
- Entrada de lotes
- Rastreamento de validade
- Movimentações entre endereços
- Bloqueio/desbloqueio de lotes

### 2.3 Inventário
- Inventário geral
- Inventário parcial
- Inventário cíclico
- Gestão de contagens
- Ajustes de estoque

## 3. Procedures Principais

### 3.1 Entrada de Lote
```sql
CALL sp_entrada_lote(
    p_id_produto,
    p_numero_lote,
    p_data_fabricacao,
    p_data_validade,
    p_quantidade,
    p_id_endereco,
    p_usuario_id
);
```

### 3.2 Transferência de Lote
```sql
CALL sp_transferir_lote(
    p_id_lote,
    p_quantidade,
    p_id_endereco_origem,
    p_id_endereco_destino,
    p_usuario_id
);
```

### 3.3 Iniciar Inventário
```sql
CALL sp_iniciar_inventario(
    p_tipo,
    p_usuario_responsavel,
    p_id_area
);
```

## 4. Views de Controle

### 4.1 Ocupação de Endereços
```sql
SELECT * FROM vw_ocupacao_enderecos
WHERE armazem = 'ARMAZEM_01'
AND ocupado = true;
```

### 4.2 Status dos Lotes
```sql
SELECT * FROM vw_status_lotes
WHERE status = 'QUARENTENA'
ORDER BY data_validade;
```

## 5. Regras de Negócio

### 5.1 Validações de Lote
- Data de validade deve ser posterior à data de fabricação
- Quantidade não pode ser negativa
- Lote em quarentena não pode ser movimentado
- Endereço de destino deve ter capacidade disponível

### 5.2 Status de Lote
- **Quarentena**: Aguardando liberação de qualidade
- **Disponível**: Liberado para uso
- **Bloqueado**: Impedido temporariamente
- **Vencido**: Fora da validade

### 5.3 Inventário
- Contagem deve ser feita por dois usuários diferentes
- Divergências maiores que 5% exigem recontagem
- Ajustes devem ser aprovados por supervisor

## 6. Índices e Performance

### 6.1 Índices Principais
- Status do lote
- Data de validade
- Ocupação de endereço
- Data de movimentação

### 6.2 Otimizações
- Particionamento por data em movimentações
- Índices compostos para consultas frequentes
- Cache de consultas de ocupação

## 7. Integrações

### 7.1 Módulo de Compras
- Recebimento de mercadorias
- Entrada de lotes
- Conferência física

### 7.2 Módulo de Vendas
- Reserva de lotes
- Picking
- Expedição

### 7.3 Módulo de Qualidade
- Liberação de lotes
- Bloqueio/desbloqueio
- Laudos técnicos

## 8. Relatórios

### 8.1 Operacionais
- Ocupação por endereço
- Lotes próximos ao vencimento
- Movimentações diárias
- Divergências de inventário

### 8.2 Gerenciais
- Taxa de ocupação
- Giro por endereço
- Acuracidade de inventário
- Tempo médio de permanência

## 9. Próximas Implementações

### 9.1 Curto Prazo
- [ ] App mobile para movimentações
- [ ] Impressão de etiquetas
- [ ] Dashboard operacional
- [ ] Alertas de vencimento

### 9.2 Médio Prazo
- [ ] Otimização de endereçamento
- [ ] Gestão de doca
- [ ] Cross-docking
- [ ] Integração com coletores

### 9.3 Longo Prazo
- [ ] Algoritmos de picking
- [ ] Gestão de transportadoras
- [ ] Roteirização
- [ ] Integração com balanças
