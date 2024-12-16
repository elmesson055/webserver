# Integração SEFAZ

## Documentos Fiscais Eletrônicos

### CT-e (Conhecimento de Transporte Eletrônico)

#### Endpoints
```
/cte/emissao
/cte/consulta/{chave}
/cte/cancelamento
/cte/correcao
/cte/inutilizacao
/cte/eventos
```

#### Schemas
- `cte-v4.00.xsd`: Schema base do CT-e
- `eventoCTe-v4.00.xsd`: Schema de eventos
- `retEventoCTe-v4.00.xsd`: Schema de retorno de eventos

#### Exemplos
```xml
<!-- Emissão CT-e -->
<CTe xmlns="http://www.portalfiscal.inf.br/cte">
  <infCte versao="4.00">
    <!-- Dados do CT-e -->
  </infCte>
  <Signature>
    <!-- Assinatura Digital -->
  </Signature>
</CTe>

<!-- Evento CT-e -->
<eventoCTe xmlns="http://www.portalfiscal.inf.br/cte">
  <infEvento>
    <!-- Dados do Evento -->
  </infEvento>
</eventoCTe>
```

### MDF-e (Manifesto Eletrônico de Documentos Fiscais)

#### Endpoints
```
/mdfe/emissao
/mdfe/consulta/{chave}
/mdfe/encerramento
/mdfe/cancelamento
/mdfe/condutor
/mdfe/dfe
```

#### Schemas
- `mdfe-v3.00.xsd`: Schema base do MDF-e
- `eventoMDFe-v3.00.xsd`: Schema de eventos
- `retEventoMDFe-v3.00.xsd`: Schema de retorno de eventos

#### Exemplos
```xml
<!-- Emissão MDF-e -->
<MDFe xmlns="http://www.portalfiscal.inf.br/mdfe">
  <infMDFe versao="3.00">
    <!-- Dados do MDF-e -->
  </infMDFe>
  <Signature>
    <!-- Assinatura Digital -->
  </Signature>
</MDFe>

<!-- Evento MDF-e -->
<eventoMDFe xmlns="http://www.portalfiscal.inf.br/mdfe">
  <infEvento>
    <!-- Dados do Evento -->
  </infEvento>
</eventoMDFe>
```

### NF-e (Nota Fiscal Eletrônica)

#### Endpoints
```
/nfe/consulta/{chave}
/nfe/manifestacao
/nfe/download
/nfe/eventos
```

#### Schemas
- `nfe-v4.00.xsd`: Schema base da NF-e
- `eventoNFe-v4.00.xsd`: Schema de eventos
- `retEventoNFe-v4.00.xsd`: Schema de retorno de eventos

#### Exemplos
```xml
<!-- Manifestação do Destinatário -->
<eventoNFe xmlns="http://www.portalfiscal.inf.br/nfe">
  <infEvento>
    <detEvento>
      <!-- Dados da Manifestação -->
    </detEvento>
  </infEvento>
</eventoNFe>
```

### EFD (Escrituração Fiscal Digital)

#### Endpoints
```
/efd/geracao
/efd/validacao
/efd/transmissao
/efd/consulta
```

#### Layouts
- `Registro0000`: Abertura do Arquivo Digital e Identificação da entidade
- `RegistroC100`: Documento - Nota Fiscal
- `RegistroD100`: Documento - Conhecimento de Transporte
- `RegistroE100`: Período de Apuração do ICMS

#### Exemplos
```
|0000|123|01012024|31012024|EMPRESA TESTE|12345678000199|ES|123456789|
|C100|0|1|FOR123|55|00|1|123|12345678901234567890123456789012345678901234|01012024|01012024|1000.00|
|D100|0|1|FOR123|57|00|1|123|12345678901234567890123456789012345678901234|01012024|01012024|1000.00|
|E100|01012024|31012024|
```

## Requisitos Técnicos

### Certificação Digital
- Tipo A1 ou A3
- ICP-Brasil
- Validade mínima de 12 meses
- Configuração no Webservice

### Ambiente
- Produção
- Homologação
- Contingência

### Webservices
- SOAP 1.2
- REST (alguns serviços)
- HTTPS
- Autenticação mútua

### Validações
- Schema XML
- Assinatura Digital
- Dados Cadastrais
- Regras de Negócio

## Contingência

### Tipos
1. SVCAN (Serviço Virtual de Contingência do Ambiente Nacional)
2. SVCRS (Serviço Virtual de Contingência do Rio Grande do Sul)
3. EPEC (Evento Prévio de Emissão em Contingência)
4. FS-DA (Formulário de Segurança para Impressão de Documento Auxiliar)

### Procedimentos
1. Detecção da indisponibilidade
2. Ativação do modo contingência
3. Emissão em contingência
4. Transmissão posterior

### Monitoramento
- Status dos serviços
- Tempo de resposta
- Erros
- Logs

## Segurança

### Certificado Digital
- Proteção da chave privada
- Renovação automática
- Backup
- Recuperação

### Comunicação
- SSL/TLS
- Autenticação mútua
- Criptografia
- Firewall

### Dados
- Backup
- Versionamento
- Auditoria
- Logs

## Testes

### Homologação
1. Credenciamento
2. Testes de emissão
3. Testes de eventos
4. Validação de schemas

### Casos de Teste
- Emissão normal
- Contingência
- Cancelamento
- Correção
- Inutilização
- Eventos

### Validações
- Schema XML
- Assinatura
- Regras de negócio
- Performance

## Próximos Passos

### Fase 1 - Documentos
1. CT-e
2. MDF-e
3. NF-e
4. Eventos

### Fase 2 - EFD
1. Geração
2. Validação
3. Transmissão
4. Consulta

### Fase 3 - Integrações
1. SINTEGRA
2. SPED
3. GNRE
4. DI/DSE

### Fase 4 - Melhorias
1. Performance
2. Monitoramento
3. Relatórios
4. Análises
