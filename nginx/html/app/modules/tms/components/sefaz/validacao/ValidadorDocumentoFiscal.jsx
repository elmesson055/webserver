import React, { useState, useEffect } from 'react';
import {
  Box,
  Paper,
  Typography,
  List,
  ListItem,
  ListItemIcon,
  ListItemText,
  Chip,
  Button,
  CircularProgress,
  Alert,
  AlertTitle,
  Collapse,
  IconButton
} from '@mui/material';
import {
  CheckCircle as CheckCircleIcon,
  Error as ErrorIcon,
  Warning as WarningIcon,
  Info as InfoIcon,
  ExpandMore as ExpandMoreIcon,
  ExpandLess as ExpandLessIcon
} from '@mui/icons-material';

import ValidacoesSefaz from '../../../services/sefaz/ValidacoesSefaz';

const ValidadorDocumentoFiscal = ({ tipo, dados }) => {
  const [validando, setValidando] = useState(false);
  const [resultados, setResultados] = useState({
    status: 'pendente', // pendente, sucesso, erro
    validacoes: []
  });
  const [expandido, setExpandido] = useState({});

  useEffect(() => {
    validarDocumento();
  }, [dados]);

  const validarDocumento = async () => {
    setValidando(true);
    try {
      const validacoes = new ValidacoesSefaz();
      const resultadosValidacao = [];

      switch (tipo) {
        case 'cte':
          // Validar Emitente
          const validacaoEmitente = await validacoes.validarCTe(dados).emitente;
          resultadosValidacao.push({
            grupo: 'Emitente',
            itens: [
              {
                descricao: 'CNPJ',
                status: validacaoEmitente.validarCNPJ(dados.emitente.cnpj) ? 'sucesso' : 'erro',
                mensagem: 'CNPJ do emitente inválido'
              },
              {
                descricao: 'Inscrição Estadual',
                status: validacaoEmitente.validarIE(dados.emitente.ie) ? 'sucesso' : 'erro',
                mensagem: 'IE do emitente inválida'
              }
            ]
          });

          // Validar Destinatário
          const validacaoDestinatario = await validacoes.validarCTe(dados).destinatario;
          resultadosValidacao.push({
            grupo: 'Destinatário',
            itens: [
              {
                descricao: 'Documento',
                status: validacaoDestinatario.validarDocumento(dados.destinatario.documento) ? 'sucesso' : 'erro',
                mensagem: 'Documento do destinatário inválido'
              }
            ]
          });

          // Validar Transporte
          const validacaoTransporte = await validacoes.validarCTe(dados).transporte;
          resultadosValidacao.push({
            grupo: 'Transporte',
            itens: [
              {
                descricao: 'Placa do Veículo',
                status: validacaoTransporte.validarVeiculo(dados.transporte.veiculoPlaca) ? 'sucesso' : 'erro',
                mensagem: 'Placa do veículo inválida'
              },
              {
                descricao: 'RNTRC',
                status: validacaoTransporte.validarRNTRC(dados.transporte.veiculoRNTRC) ? 'sucesso' : 'erro',
                mensagem: 'RNTRC inválido'
              }
            ]
          });

          // Validar Valores
          const validacaoValores = await validacoes.validarCTe(dados).valores;
          resultadosValidacao.push({
            grupo: 'Valores',
            itens: [
              {
                descricao: 'Base de Cálculo',
                status: validacaoValores.validarBaseCalculo(dados.valores) ? 'sucesso' : 'erro',
                mensagem: 'Base de cálculo inválida'
              },
              {
                descricao: 'Alíquotas',
                status: validacaoValores.validarAliquotas(dados.valores) ? 'sucesso' : 'erro',
                mensagem: 'Alíquotas inválidas'
              }
            ]
          });
          break;

        case 'mdfe':
          // Validar Emitente
          const validacaoEmitenteMDFe = await validacoes.validarMDFe(dados).emitente;
          resultadosValidacao.push({
            grupo: 'Emitente',
            itens: [
              {
                descricao: 'CNPJ',
                status: validacaoEmitenteMDFe.validarCNPJ(dados.emitente.cnpj) ? 'sucesso' : 'erro',
                mensagem: 'CNPJ do emitente inválido'
              }
            ]
          });

          // Validar Veículo
          const validacaoVeiculoMDFe = await validacoes.validarMDFe(dados).veiculo;
          resultadosValidacao.push({
            grupo: 'Veículo',
            itens: [
              {
                descricao: 'Placa',
                status: validacaoVeiculoMDFe.validarPlaca(dados.veiculo.placa) ? 'sucesso' : 'erro',
                mensagem: 'Placa do veículo inválida'
              },
              {
                descricao: 'RENAVAM',
                status: validacaoVeiculoMDFe.validarRENAVAM(dados.veiculo.renavam) ? 'sucesso' : 'erro',
                mensagem: 'RENAVAM inválido'
              }
            ]
          });

          // Validar Condutores
          const validacaoCondutorMDFe = await validacoes.validarMDFe(dados).condutor;
          resultadosValidacao.push({
            grupo: 'Condutores',
            itens: dados.condutores.map((condutor, index) => ({
              descricao: `Condutor ${index + 1}`,
              status: validacaoCondutorMDFe.validarCPF(condutor.cpf) ? 'sucesso' : 'erro',
              mensagem: `CPF do condutor ${index + 1} inválido`
            }))
          });
          break;

        // Implementar validações para outros tipos de documentos
      }

      const status = resultadosValidacao.every(grupo =>
        grupo.itens.every(item => item.status === 'sucesso')
      ) ? 'sucesso' : 'erro';

      setResultados({
        status,
        validacoes: resultadosValidacao
      });
    } catch (error) {
      console.error('Erro ao validar documento:', error);
      setResultados({
        status: 'erro',
        validacoes: [{
          grupo: 'Erro',
          itens: [{
            descricao: 'Erro na validação',
            status: 'erro',
            mensagem: error.message
          }]
        }]
      });
    } finally {
      setValidando(false);
    }
  };

  const handleExpandGrupo = (grupo) => {
    setExpandido(prev => ({
      ...prev,
      [grupo]: !prev[grupo]
    }));
  };

  const renderIconeStatus = (status) => {
    switch (status) {
      case 'sucesso':
        return <CheckCircleIcon color="success" />;
      case 'erro':
        return <ErrorIcon color="error" />;
      case 'alerta':
        return <WarningIcon color="warning" />;
      default:
        return <InfoIcon color="info" />;
    }
  };

  const renderResumo = () => (
    <Alert
      severity={resultados.status === 'sucesso' ? 'success' : 'error'}
      sx={{ mb: 2 }}
    >
      <AlertTitle>
        {resultados.status === 'sucesso' ? 'Documento Válido' : 'Documento Inválido'}
      </AlertTitle>
      {resultados.status === 'sucesso' ?
        'Todas as validações foram concluídas com sucesso.' :
        'Foram encontrados erros durante a validação do documento.'
      }
    </Alert>
  );

  const renderGrupoValidacao = (grupo, index) => (
    <Box key={index} sx={{ mb: 2 }}>
      <ListItem
        button
        onClick={() => handleExpandGrupo(grupo.grupo)}
      >
        <ListItemIcon>
          {grupo.itens.some(item => item.status === 'erro') ?
            <ErrorIcon color="error" /> :
            <CheckCircleIcon color="success" />
          }
        </ListItemIcon>
        <ListItemText
          primary={grupo.grupo}
          secondary={`${grupo.itens.filter(item => item.status === 'sucesso').length}/${grupo.itens.length} validações`}
        />
        <IconButton>
          {expandido[grupo.grupo] ? <ExpandLessIcon /> : <ExpandMoreIcon />}
        </IconButton>
      </ListItem>

      <Collapse in={expandido[grupo.grupo]}>
        <List component="div" disablePadding>
          {grupo.itens.map((item, itemIndex) => (
            <ListItem
              key={itemIndex}
              sx={{ pl: 4 }}
            >
              <ListItemIcon>
                {renderIconeStatus(item.status)}
              </ListItemIcon>
              <ListItemText
                primary={item.descricao}
                secondary={item.status === 'erro' ? item.mensagem : null}
              />
              <Chip
                label={item.status}
                color={
                  item.status === 'sucesso' ? 'success' :
                  item.status === 'erro' ? 'error' :
                  item.status === 'alerta' ? 'warning' :
                  'info'
                }
                size="small"
              />
            </ListItem>
          ))}
        </List>
      </Collapse>
    </Box>
  );

  if (validando) {
    return (
      <Box display="flex" justifyContent="center" p={3}>
        <CircularProgress />
      </Box>
    );
  }

  return (
    <Paper sx={{ p: 3 }}>
      <Typography variant="h6" gutterBottom>
        Validação do Documento
      </Typography>

      {renderResumo()}

      <List>
        {resultados.validacoes.map((grupo, index) =>
          renderGrupoValidacao(grupo, index)
        )}
      </List>

      <Box display="flex" justifyContent="flex-end" mt={2}>
        <Button
          variant="contained"
          onClick={validarDocumento}
          disabled={validando}
        >
          Validar Novamente
        </Button>
      </Box>
    </Paper>
  );
};

export default ValidadorDocumentoFiscal;
