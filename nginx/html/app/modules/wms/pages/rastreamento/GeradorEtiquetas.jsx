import React, { useState, useEffect } from 'react';
import {
  Grid,
  Card,
  CardContent,
  Typography,
  Box,
  Button,
  TextField,
  FormControl,
  InputLabel,
  Select,
  MenuItem,
  Dialog,
  DialogTitle,
  DialogContent,
  DialogActions,
  Alert,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Paper,
  IconButton,
  Chip
} from '@mui/material';
import {
  QrCode as QrCodeIcon,
  Print as PrintIcon,
  Save as SaveIcon,
  Share as ShareIcon,
  Label as LabelIcon
} from '@mui/icons-material';
import QRCode from 'qrcode.react';
import Barcode from 'react-barcode';

const GeradorEtiquetas = () => {
  const [loading, setLoading] = useState(false);
  const [etiquetas, setEtiquetas] = useState([]);
  const [dialogOpen, setDialogOpen] = useState(false);
  const [previewOpen, setPreviewOpen] = useState(false);
  const [selectedEtiqueta, setSelectedEtiqueta] = useState(null);
  const [novaEtiqueta, setNovaEtiqueta] = useState({
    tipo: '',
    referencia: '',
    produto: '',
    quantidade: '',
    lote: '',
    dataValidade: '',
    localizacao: ''
  });
  const [erro, setErro] = useState(null);

  useEffect(() => {
    carregarEtiquetas();
  }, []);

  const carregarEtiquetas = async () => {
    try {
      const response = await fetch('/api/v1/rastreamento/etiquetas');
      const data = await response.json();
      setEtiquetas(data);
    } catch (error) {
      setErro('Erro ao carregar etiquetas');
      console.error(error);
    }
  };

  const handleDialogOpen = () => {
    setDialogOpen(true);
  };

  const handleDialogClose = () => {
    setDialogOpen(false);
    setNovaEtiqueta({
      tipo: '',
      referencia: '',
      produto: '',
      quantidade: '',
      lote: '',
      dataValidade: '',
      localizacao: ''
    });
  };

  const handlePreviewOpen = (etiqueta) => {
    setSelectedEtiqueta(etiqueta);
    setPreviewOpen(true);
  };

  const handlePreviewClose = () => {
    setPreviewOpen(false);
    setSelectedEtiqueta(null);
  };

  const gerarCodigo = async () => {
    try {
      const response = await fetch('/api/v1/rastreamento/etiquetas', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(novaEtiqueta)
      });

      const data = await response.json();
      await carregarEtiquetas();
      handleDialogClose();
    } catch (error) {
      setErro('Erro ao gerar etiqueta');
      console.error(error);
    }
  };

  const imprimirEtiqueta = async (etiqueta) => {
    try {
      await fetch(`/api/v1/rastreamento/etiquetas/${etiqueta.id}/imprimir`, {
        method: 'POST'
      });
    } catch (error) {
      setErro('Erro ao imprimir etiqueta');
      console.error(error);
    }
  };

  const getTipoEtiqueta = (tipo) => {
    switch (tipo.toLowerCase()) {
      case 'recebimento':
        return { label: 'Recebimento', color: 'success' };
      case 'armazenagem':
        return { label: 'Armazenagem', color: 'primary' };
      case 'separacao':
        return { label: 'Separação', color: 'warning' };
      case 'expedicao':
        return { label: 'Expedição', color: 'error' };
      default:
        return { label: tipo, color: 'default' };
    }
  };

  return (
    <Box p={3}>
      <Typography variant="h4" gutterBottom>
        Gerador de Etiquetas
      </Typography>

      {erro && (
        <Alert severity="error" sx={{ mb: 2 }}>
          {erro}
        </Alert>
      )}

      {/* Ações */}
      <Box display="flex" justifyContent="flex-end" mb={3}>
        <Button
          variant="contained"
          startIcon={<LabelIcon />}
          onClick={handleDialogOpen}
        >
          Nova Etiqueta
        </Button>
      </Box>

      {/* Lista de Etiquetas */}
      <TableContainer component={Paper}>
        <Table>
          <TableHead>
            <TableRow>
              <TableCell>ID</TableCell>
              <TableCell>Tipo</TableCell>
              <TableCell>Referência</TableCell>
              <TableCell>Produto</TableCell>
              <TableCell>Quantidade</TableCell>
              <TableCell>Lote</TableCell>
              <TableCell>Validade</TableCell>
              <TableCell>Localização</TableCell>
              <TableCell>Ações</TableCell>
            </TableRow>
          </TableHead>
          <TableBody>
            {etiquetas.map((etiqueta) => {
              const tipo = getTipoEtiqueta(etiqueta.tipo);
              return (
                <TableRow key={etiqueta.id}>
                  <TableCell>{etiqueta.id}</TableCell>
                  <TableCell>
                    <Chip
                      label={tipo.label}
                      color={tipo.color}
                      size="small"
                    />
                  </TableCell>
                  <TableCell>{etiqueta.referencia}</TableCell>
                  <TableCell>{etiqueta.produto}</TableCell>
                  <TableCell>{etiqueta.quantidade}</TableCell>
                  <TableCell>{etiqueta.lote}</TableCell>
                  <TableCell>{etiqueta.dataValidade}</TableCell>
                  <TableCell>{etiqueta.localizacao}</TableCell>
                  <TableCell>
                    <IconButton
                      size="small"
                      onClick={() => handlePreviewOpen(etiqueta)}
                    >
                      <QrCodeIcon />
                    </IconButton>
                    <IconButton
                      size="small"
                      onClick={() => imprimirEtiqueta(etiqueta)}
                    >
                      <PrintIcon />
                    </IconButton>
                    <IconButton size="small">
                      <ShareIcon />
                    </IconButton>
                  </TableCell>
                </TableRow>
              );
            })}
          </TableBody>
        </Table>
      </TableContainer>

      {/* Dialog Nova Etiqueta */}
      <Dialog
        open={dialogOpen}
        onClose={handleDialogClose}
        maxWidth="md"
        fullWidth
      >
        <DialogTitle>
          Nova Etiqueta
        </DialogTitle>
        <DialogContent>
          <Grid container spacing={2} sx={{ mt: 1 }}>
            <Grid item xs={12} md={6}>
              <FormControl fullWidth>
                <InputLabel>Tipo</InputLabel>
                <Select
                  value={novaEtiqueta.tipo}
                  onChange={(e) => setNovaEtiqueta({ ...novaEtiqueta, tipo: e.target.value })}
                >
                  <MenuItem value="recebimento">Recebimento</MenuItem>
                  <MenuItem value="armazenagem">Armazenagem</MenuItem>
                  <MenuItem value="separacao">Separação</MenuItem>
                  <MenuItem value="expedicao">Expedição</MenuItem>
                </Select>
              </FormControl>
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Referência"
                value={novaEtiqueta.referencia}
                onChange={(e) => setNovaEtiqueta({ ...novaEtiqueta, referencia: e.target.value })}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Produto"
                value={novaEtiqueta.produto}
                onChange={(e) => setNovaEtiqueta({ ...novaEtiqueta, produto: e.target.value })}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                type="number"
                label="Quantidade"
                value={novaEtiqueta.quantidade}
                onChange={(e) => setNovaEtiqueta({ ...novaEtiqueta, quantidade: e.target.value })}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Lote"
                value={novaEtiqueta.lote}
                onChange={(e) => setNovaEtiqueta({ ...novaEtiqueta, lote: e.target.value })}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                type="date"
                label="Data de Validade"
                InputLabelProps={{ shrink: true }}
                value={novaEtiqueta.dataValidade}
                onChange={(e) => setNovaEtiqueta({ ...novaEtiqueta, dataValidade: e.target.value })}
              />
            </Grid>
            <Grid item xs={12}>
              <TextField
                fullWidth
                label="Localização"
                value={novaEtiqueta.localizacao}
                onChange={(e) => setNovaEtiqueta({ ...novaEtiqueta, localizacao: e.target.value })}
              />
            </Grid>
          </Grid>
        </DialogContent>
        <DialogActions>
          <Button onClick={handleDialogClose}>Cancelar</Button>
          <Button
            variant="contained"
            onClick={gerarCodigo}
            startIcon={<SaveIcon />}
          >
            Gerar
          </Button>
        </DialogActions>
      </Dialog>

      {/* Dialog Preview */}
      <Dialog
        open={previewOpen}
        onClose={handlePreviewClose}
        maxWidth="sm"
        fullWidth
      >
        <DialogTitle>
          Preview da Etiqueta
        </DialogTitle>
        <DialogContent>
          {selectedEtiqueta && (
            <Box sx={{ p: 2 }}>
              <Card sx={{ mb: 2 }}>
                <CardContent>
                  <Box display="flex" justifyContent="center" mb={2}>
                    <QRCode
                      value={JSON.stringify(selectedEtiqueta)}
                      size={200}
                      level="H"
                    />
                  </Box>
                  <Box display="flex" justifyContent="center">
                    <Barcode
                      value={selectedEtiqueta.referencia}
                      width={1.5}
                      height={50}
                      fontSize={14}
                    />
                  </Box>
                </CardContent>
              </Card>
              <Typography variant="subtitle1" gutterBottom>
                Informações:
              </Typography>
              <Typography>ID: {selectedEtiqueta.id}</Typography>
              <Typography>Produto: {selectedEtiqueta.produto}</Typography>
              <Typography>Lote: {selectedEtiqueta.lote}</Typography>
              <Typography>Quantidade: {selectedEtiqueta.quantidade}</Typography>
              <Typography>Localização: {selectedEtiqueta.localizacao}</Typography>
            </Box>
          )}
        </DialogContent>
        <DialogActions>
          <Button onClick={handlePreviewClose}>Fechar</Button>
          <Button
            variant="contained"
            startIcon={<PrintIcon />}
            onClick={() => {
              imprimirEtiqueta(selectedEtiqueta);
              handlePreviewClose();
            }}
          >
            Imprimir
          </Button>
        </DialogActions>
      </Dialog>
    </Box>
  );
};

export default GeradorEtiquetas;
