import React, { useState, useEffect } from 'react';
import {
  Box,
  Paper,
  Typography,
  Grid,
  Divider,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Chip,
  Button,
  IconButton,
  Dialog,
  DialogTitle,
  DialogContent,
  DialogActions,
  Tooltip
} from '@mui/material';
import {
  Print as PrintIcon,
  Save as SaveIcon,
  Email as EmailIcon,
  QrCode as QrCodeIcon,
  LocalShipping as ShippingIcon,
  Assignment as AssignmentIcon,
  Receipt as ReceiptIcon,
  Description as DescriptionIcon
} from '@mui/icons-material';

const VisualizadorDocumentoFiscal = ({ tipo, dados, onPrint, onDownload, onEmail }) => {
  const [qrCodeDialog, setQrCodeDialog] = useState(false);

  const renderIconeTipo = () => {
    switch (tipo) {
      case 'cte':
        return <ShippingIcon />;
      case 'mdfe':
        return <AssignmentIcon />;
      case 'nfe':
        return <ReceiptIcon />;
      default:
        return <DescriptionIcon />;
    }
  };

  const formatarValor = (valor) => {
    return new Intl.NumberFormat('pt-BR', {
      style: 'currency',
      currency: 'BRL'
    }).format(valor);
  };

  const renderDadosEmitente = () => (
    <Grid container spacing={2}>
      <Grid item xs={12}>
        <Typography variant="subtitle1" gutterBottom>
          Dados do Emitente
        </Typography>
      </Grid>
      <Grid item xs={12} md={6}>
        <Typography variant="body2" color="textSecondary">
          CNPJ: {dados.emitente.cnpj}
        </Typography>
        <Typography variant="body2" color="textSecondary">
          IE: {dados.emitente.ie}
        </Typography>
        <Typography variant="body2" color="textSecondary">
          Razão Social: {dados.emitente.razaoSocial}
        </Typography>
      </Grid>
      <Grid item xs={12} md={6}>
        <Typography variant="body2" color="textSecondary">
          Endereço: {dados.emitente.endereco.logradouro}, {dados.emitente.endereco.numero}
        </Typography>
        <Typography variant="body2" color="textSecondary">
          Bairro: {dados.emitente.endereco.bairro}
        </Typography>
        <Typography variant="body2" color="textSecondary">
          Município: {dados.emitente.endereco.municipio} - {dados.emitente.endereco.uf}
        </Typography>
      </Grid>
    </Grid>
  );

  const renderDadosDestinatario = () => (
    <Grid container spacing={2}>
      <Grid item xs={12}>
        <Typography variant="subtitle1" gutterBottom>
          Dados do Destinatário
        </Typography>
      </Grid>
      <Grid item xs={12} md={6}>
        <Typography variant="body2" color="textSecondary">
          Documento: {dados.destinatario.documento}
        </Typography>
        <Typography variant="body2" color="textSecondary">
          IE: {dados.destinatario.ie}
        </Typography>
        <Typography variant="body2" color="textSecondary">
          Nome: {dados.destinatario.nome}
        </Typography>
      </Grid>
      <Grid item xs={12} md={6}>
        <Typography variant="body2" color="textSecondary">
          Endereço: {dados.destinatario.endereco.logradouro}, {dados.destinatario.endereco.numero}
        </Typography>
        <Typography variant="body2" color="textSecondary">
          Bairro: {dados.destinatario.endereco.bairro}
        </Typography>
        <Typography variant="body2" color="textSecondary">
          Município: {dados.destinatario.endereco.municipio} - {dados.destinatario.endereco.uf}
        </Typography>
      </Grid>
    </Grid>
  );

  const renderDadosTransporte = () => (
    <Grid container spacing={2}>
      <Grid item xs={12}>
        <Typography variant="subtitle1" gutterBottom>
          Dados do Transporte
        </Typography>
      </Grid>
      <Grid item xs={12} md={6}>
        <Typography variant="body2" color="textSecondary">
          Modalidade: {dados.transporte.modalidade}
        </Typography>
        <Typography variant="body2" color="textSecondary">
          Placa: {dados.transporte.veiculoPlaca}
        </Typography>
        <Typography variant="body2" color="textSecondary">
          RNTRC: {dados.transporte.veiculoRNTRC}
        </Typography>
      </Grid>
      <Grid item xs={12} md={6}>
        <Typography variant="body2" color="textSecondary">
          Motorista: {dados.transporte.motoristaNome}
        </Typography>
        <Typography variant="body2" color="textSecondary">
          CPF: {dados.transporte.motoristaCPF}
        </Typography>
      </Grid>
    </Grid>
  );

  const renderDadosCarga = () => (
    <Grid container spacing={2}>
      <Grid item xs={12}>
        <Typography variant="subtitle1" gutterBottom>
          Dados da Carga
        </Typography>
      </Grid>
      <Grid item xs={12} md={6}>
        <Typography variant="body2" color="textSecondary">
          Tipo: {dados.carga.tipo}
        </Typography>
        <Typography variant="body2" color="textSecondary">
          Unidade: {dados.carga.unidade}
        </Typography>
        <Typography variant="body2" color="textSecondary">
          Quantidade: {dados.carga.quantidade}
        </Typography>
      </Grid>
      <Grid item xs={12} md={6}>
        <Typography variant="body2" color="textSecondary">
          Peso Bruto: {dados.carga.pesoBruto} kg
        </Typography>
        <Typography variant="body2" color="textSecondary">
          Peso Líquido: {dados.carga.pesoLiquido} kg
        </Typography>
        <Typography variant="body2" color="textSecondary">
          Valor: {formatarValor(dados.carga.valor)}
        </Typography>
      </Grid>
    </Grid>
  );

  const renderDadosValores = () => (
    <Grid container spacing={2}>
      <Grid item xs={12}>
        <Typography variant="subtitle1" gutterBottom>
          Valores e Impostos
        </Typography>
      </Grid>
      <Grid item xs={12}>
        <TableContainer>
          <Table size="small">
            <TableHead>
              <TableRow>
                <TableCell>Descrição</TableCell>
                <TableCell align="right">Valor</TableCell>
              </TableRow>
            </TableHead>
            <TableBody>
              <TableRow>
                <TableCell>Valor do Serviço</TableCell>
                <TableCell align="right">{formatarValor(dados.valores.servico)}</TableCell>
              </TableRow>
              <TableRow>
                <TableCell>Pedágio</TableCell>
                <TableCell align="right">{formatarValor(dados.valores.pedagio)}</TableCell>
              </TableRow>
              <TableRow>
                <TableCell>Outras Despesas</TableCell>
                <TableCell align="right">{formatarValor(dados.valores.outrasDepesas)}</TableCell>
              </TableRow>
              <TableRow>
                <TableCell>Base de Cálculo ICMS</TableCell>
                <TableCell align="right">{formatarValor(dados.valores.baseCalculoICMS)}</TableCell>
              </TableRow>
              <TableRow>
                <TableCell>ICMS ({dados.valores.aliquotaICMS}%)</TableCell>
                <TableCell align="right">{formatarValor(dados.valores.valorICMS)}</TableCell>
              </TableRow>
              <TableRow>
                <TableCell>ISS ({dados.valores.aliquotaISS}%)</TableCell>
                <TableCell align="right">{formatarValor(dados.valores.valorISS)}</TableCell>
              </TableRow>
              <TableRow>
                <TableCell><strong>Valor Total</strong></TableCell>
                <TableCell align="right">
                  <strong>
                    {formatarValor(
                      dados.valores.servico +
                      dados.valores.pedagio +
                      dados.valores.outrasDepesas
                    )}
                  </strong>
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </TableContainer>
      </Grid>
    </Grid>
  );

  const renderQRCodeDialog = () => (
    <Dialog
      open={qrCodeDialog}
      onClose={() => setQrCodeDialog(false)}
      maxWidth="sm"
      fullWidth
    >
      <DialogTitle>QR Code</DialogTitle>
      <DialogContent>
        {/* Implementar visualização do QR Code */}
      </DialogContent>
      <DialogActions>
        <Button onClick={() => setQrCodeDialog(false)}>
          Fechar
        </Button>
      </DialogActions>
    </Dialog>
  );

  return (
    <Box>
      <Paper sx={{ p: 3 }}>
        {/* Cabeçalho */}
        <Box display="flex" alignItems="center" mb={3}>
          <Box mr={2}>
            {renderIconeTipo()}
          </Box>
          <Box flexGrow={1}>
            <Typography variant="h5">
              {tipo.toUpperCase()} - {dados.chave}
            </Typography>
            <Typography variant="subtitle2" color="textSecondary">
              Emitido em: {new Date(dados.dataEmissao).toLocaleString()}
            </Typography>
          </Box>
          <Box>
            <Chip
              label={dados.status}
              color={
                dados.status === 'Autorizado' ? 'success' :
                dados.status === 'Cancelado' ? 'error' :
                dados.status === 'Pendente' ? 'warning' :
                'default'
              }
            />
          </Box>
        </Box>

        {/* Ações */}
        <Box display="flex" gap={1} mb={3}>
          <Tooltip title="Imprimir">
            <IconButton onClick={onPrint}>
              <PrintIcon />
            </IconButton>
          </Tooltip>
          <Tooltip title="Download XML">
            <IconButton onClick={onDownload}>
              <SaveIcon />
            </IconButton>
          </Tooltip>
          <Tooltip title="Enviar por E-mail">
            <IconButton onClick={onEmail}>
              <EmailIcon />
            </IconButton>
          </Tooltip>
          <Tooltip title="QR Code">
            <IconButton onClick={() => setQrCodeDialog(true)}>
              <QrCodeIcon />
            </IconButton>
          </Tooltip>
        </Box>

        {/* Conteúdo */}
        <Grid container spacing={3}>
          <Grid item xs={12}>
            {renderDadosEmitente()}
          </Grid>
          
          <Grid item xs={12}>
            <Divider />
          </Grid>
          
          <Grid item xs={12}>
            {renderDadosDestinatario()}
          </Grid>
          
          <Grid item xs={12}>
            <Divider />
          </Grid>
          
          <Grid item xs={12}>
            {renderDadosTransporte()}
          </Grid>
          
          <Grid item xs={12}>
            <Divider />
          </Grid>
          
          <Grid item xs={12}>
            {renderDadosCarga()}
          </Grid>
          
          <Grid item xs={12}>
            <Divider />
          </Grid>
          
          <Grid item xs={12}>
            {renderDadosValores()}
          </Grid>
        </Grid>
      </Paper>

      {renderQRCodeDialog()}
    </Box>
  );
};

export default VisualizadorDocumentoFiscal;
