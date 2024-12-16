# API Mobile WMS

## 1. Endpoints de Recebimento

### 1.1 Conferência de Nota Fiscal
```http
POST /api/v1/recebimento/conferencia
{
    "chave_nfe": "string",
    "temperatura": number,
    "fotos": [base64],
    "itens": [
        {
            "id_produto": number,
            "quantidade": number,
            "lote": "string",
            "validade": "date"
        }
    ]
}
```

### 1.2 Captura de Fotos
```http
POST /api/v1/recebimento/fotos
{
    "id_nota": number,
    "tipo_foto": "CARGA|AVARIA|DOCUMENTO",
    "foto": base64
}
```

## 2. Endpoints de Armazenagem

### 2.1 Endereçamento
```http
POST /api/v1/armazenagem/endereco
{
    "id_produto": number,
    "id_lote": number,
    "quantidade": number,
    "id_endereco": number
}
```

### 2.2 Consulta Sugestão
```http
GET /api/v1/armazenagem/sugestao?id_produto={id}&quantidade={qtd}
```

## 3. Endpoints de Separação

### 3.1 Lista de Pedidos
```http
GET /api/v1/separacao/pedidos?status={status}
```

### 3.2 Iniciar Separação
```http
POST /api/v1/separacao/iniciar
{
    "id_pedido": number
}
```

### 3.3 Confirmar Item
```http
POST /api/v1/separacao/confirmar
{
    "id_item": number,
    "quantidade": number,
    "id_lote": number
}
```

## 4. Endpoints de Expedição

### 4.1 Conferência
```http
POST /api/v1/expedicao/conferencia
{
    "id_pedido": number,
    "itens": [
        {
            "id_item": number,
            "quantidade": number,
            "id_lote": number
        }
    ]
}
```

### 4.2 Carregar Veículo
```http
POST /api/v1/expedicao/carregamento
{
    "id_pedido": number,
    "placa_veiculo": "string",
    "temperatura": number
}
```

## 5. Endpoints de Inventário

### 5.1 Lista de Contagens
```http
GET /api/v1/inventario/contagens?status={status}
```

### 5.2 Registrar Contagem
```http
POST /api/v1/inventario/contagem
{
    "id_contagem": number,
    "id_endereco": number,
    "id_produto": number,
    "quantidade": number
}
```

## 6. Endpoints de Consulta

### 6.1 Produto
```http
GET /api/v1/consulta/produto?codigo={codigo}
```

### 6.2 Endereço
```http
GET /api/v1/consulta/endereco?codigo={codigo}
```

### 6.3 Lote
```http
GET /api/v1/consulta/lote?numero={numero}
```

## 7. Segurança

### 7.1 Autenticação
```http
POST /api/v1/auth/login
{
    "usuario": "string",
    "senha": "string"
}
```

### 7.2 Refresh Token
```http
POST /api/v1/auth/refresh
{
    "refresh_token": "string"
}
```

## 8. Respostas Padrão

### 8.1 Sucesso
```json
{
    "success": true,
    "data": {},
    "message": "string"
}
```

### 8.2 Erro
```json
{
    "success": false,
    "error": {
        "code": "string",
        "message": "string",
        "details": {}
    }
}
```

## 9. Códigos de Erro

- `WMS001`: Nota fiscal não encontrada
- `WMS002`: Produto não encontrado
- `WMS003`: Endereço inválido
- `WMS004`: Quantidade insuficiente
- `WMS005`: Temperatura fora do limite
- `WMS006`: Pedido não encontrado
- `WMS007`: Operação não permitida
- `WMS008`: Lote vencido
- `WMS009`: Credenciais inválidas
- `WMS010`: Token expirado

## 10. Boas Práticas

1. **Cache**
   - Cache de produtos por 1 hora
   - Cache de endereços por 24 horas
   - Cache de configurações por 1 hora

2. **Rate Limiting**
   - 100 requisições por minuto por usuário
   - 1000 requisições por hora por dispositivo

3. **Timeout**
   - 30 segundos para operações normais
   - 60 segundos para upload de fotos
   - 120 segundos para relatórios

4. **Compressão**
   - GZIP para todas as respostas
   - Compressão de imagens antes do upload

5. **Versionamento**
   - Prefix /api/v1 para todas as rotas
   - Header X-API-Version para verificação

## 11. Monitoramento

1. **Métricas**
   - Tempo de resposta
   - Taxa de erro
   - Uso de CPU/Memória
   - Requisições por segundo

2. **Logs**
   - Nível INFO para operações normais
   - Nível ERROR para falhas
   - Nível DEBUG para desenvolvimento

3. **Alertas**
   - Taxa de erro > 5%
   - Tempo de resposta > 5s
   - Espaço em disco < 20%
   - CPU > 80%
