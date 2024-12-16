import React, { useState, useEffect } from 'react';
import {
  Box,
  Card,
  CardContent,
  Grid,
  Typography,
  Button,
  Paper,
  TextField,
  FormControl,
  InputLabel,
  Select,
  MenuItem,
  LinearProgress,
  Tabs,
  Tab,
  IconButton,
  Tooltip,
  Dialog,
  DialogTitle,
  DialogContent,
  DialogActions,
  Checkbox,
  FormControlLabel,
  Radio,
  RadioGroup,
  Divider
} from '@mui/material';
import {
  QrCode2 as QrCodeIcon,
  Print as PrintIcon,
  Preview as PreviewIcon,
  Save as SaveIcon,
  Settings as SettingsIcon,
  History as HistoryIcon,
  LocalShipping,
  Inventory,
  Assignment,
  Edit as EditIcon,
  Delete as DeleteIcon,
  Add as AddIcon,
  Download as DownloadIcon
} from '@mui/icons-material';
import QRCode from 'qrcode.react';
import Barcode from 'react-barcode';

const GeradorEtiquetas = () => {
  const [loading, setLoading] = useState(false);
  const [tabValue, setTabValue] = useState(0);
  const [modeloSelecionado, setModeloSelecionado] = useState('');
  const [openPreview, setOpenPreview] = useState(false);
  const [openConfig, setOpenConfig] = useState(false);
  const [etiquetaData, setEtiquetaData] = useState({
    transportadora: '',
    destinatario: '',
    endereco: '',
    cidade: '',
    estado: '',
    cep: '',
    numeroPedido: '',
    codigoRastreio: '',
    peso: '',
    volume: '',
    observacoes: ''
  });
  const [configImpressao, setConfigImpressao] = useState({
    tamanho: 'a4',
    orientacao: 'retrato',
    margens: '10',
    copias: 1,
    incluirQRCode: true,
    incluirCodigoBarras: true,
    incluirLogo: true
  });

  const modelosEtiquetas = [
    { id: 'correios', nome: 'Correios', logo: '/logos/correios.png' },
    { id: 'mercadolivre', nome: 'Mercado Livre', logo: '/logos/mercadolivre.png' },
    { id: 'ups', nome: 'UPS', logo: '/logos/ups.png' },
    { id: 'fedex', nome: 'FedEx', logo: '/logos/fedex.png' },
    { id: 'personalizado', nome: 'Personalizado', logo: null }
  ];

  const handleTabChange = (event, newValue) => {
    setTabValue(newValue);
  };

  const handleModeloChange = (event) => {
    setModeloSelecionado(event.target.value);
    // Carregar template do modelo selecionado
    carregarTemplateModelo(event.target.value);
  };

  const carregarTemplateModelo = async (modeloId) => {
    setLoading(true);
    try {
      const response = await fetch(`/api/v1/tms/etiquetas/template/${modeloId}`);
      const template = await response.json();
      setEtiquetaData(template);
    } catch (error) {
      console.error('Erro ao carregar template:', error);
    } finally {
      setLoading(false);
    }
  };

  const handlePreview = () => {
    setOpenPreview(true);
  };

  const handlePrint = async () => {
    try {
      const response = await fetch('/api/v1/tms/etiquetas/imprimir', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          etiquetaData,
          configImpressao
        }),
      });
      
      if (response.ok) {
        // Sucesso na impressão
        console.log('Etiqueta enviada para impressão');
      }
    } catch (error) {
      console.error('Erro ao imprimir:', error);
    }
  };

  const renderFormulario = () => (
    <Paper sx={{ p: 3, mb: 3 }}>
      <Grid container spacing={2}>
        <Grid item xs={12} md={6}>
          <FormControl fullWidth>
            <InputLabel>Modelo de Etiqueta</InputLabel>
            <Select
              value={modeloSelecionado}
              onChange={handleModeloChange}
            >
              {modelosEtiquetas.map((modelo) => (
                <MenuItem key={modelo.id} value={modelo.id}>
                  {modelo.nome}
                </MenuItem>
              ))}
            </Select>
          </FormControl>
        </Grid>
        <Grid item xs={12} md={6}>
          <TextField
            fullWidth
            label="Número do Pedido"
            value={etiquetaData.numeroPedido}
            onChange={(e) => setEtiquetaData({
              ...etiquetaData,
              numeroPedido: e.target.value
            })}
          />
        </Grid>
        <Grid item xs={12} md={6}>
          <TextField
            fullWidth
            label="Transportadora"
            value={etiquetaData.transportadora}
            onChange={(e) => setEtiquetaData({
              ...etiquetaData,
              transportadora: e.target.value
            })}
          />
        </Grid>
        <Grid item xs={12} md={6}>
          <TextField
            fullWidth
            label="Código de Rastreio"
            value={etiquetaData.codigoRastreio}
            onChange={(e) => setEtiquetaData({
              ...etiquetaData,
              codigoRastreio: e.target.value
            })}
          />
        </Grid>
        <Grid item xs={12}>
          <TextField
            fullWidth
            label="Destinatário"
            value={etiquetaData.destinatario}
            onChange={(e) => setEtiquetaData({
              ...etiquetaData,
              destinatario: e.target.value
            })}
          />
        </Grid>
        <Grid item xs={12}>
          <TextField
            fullWidth
            label="Endereço"
            value={etiquetaData.endereco}
            onChange={(e) => setEtiquetaData({
              ...etiquetaData,
              endereco: e.target.value
            })}
          />
        </Grid>
        <Grid item xs={12} md={4}>
          <TextField
            fullWidth
            label="Cidade"
            value={etiquetaData.cidade}
            onChange={(e) => setEtiquetaData({
              ...etiquetaData,
              cidade: e.target.value
            })}
          />
        </Grid>
        <Grid item xs={12} md={4}>
          <TextField
            fullWidth
            label="Estado"
            value={etiquetaData.estado}
            onChange={(e) => setEtiquetaData({
              ...etiquetaData,
              estado: e.target.value
            })}
          />
        </Grid>
        <Grid item xs={12} md={4}>
          <TextField
            fullWidth
            label="CEP"
            value={etiquetaData.cep}
            onChange={(e) => setEtiquetaData({
              ...etiquetaData,
              cep: e.target.value
            })}
          />
        </Grid>
        <Grid item xs={12} md={6}>
          <TextField
            fullWidth
            label="Peso (kg)"
            type="number"
            value={etiquetaData.peso}
            onChange={(e) => setEtiquetaData({
              ...etiquetaData,
              peso: e.target.value
            })}
          />
        </Grid>
        <Grid item xs={12} md={6}>
          <TextField
            fullWidth
            label="Volume"
            value={etiquetaData.volume}
            onChange={(e) => setEtiquetaData({
              ...etiquetaData,
              volume: e.target.value
            })}
          />
        </Grid>
        <Grid item xs={12}>
          <TextField
            fullWidth
            multiline
            rows={3}
            label="Observações"
            value={etiquetaData.observacoes}
            onChange={(e) => setEtiquetaData({
              ...etiquetaData,
              observacoes: e.target.value
            })}
          />
        </Grid>
      </Grid>
    </Paper>
  );

  const renderPreview = () => (
    <Dialog
      open={openPreview}
      onClose={() => setOpenPreview(false)}
      maxWidth="md"
      fullWidth
    >
      <DialogTitle>
        Prévia da Etiqueta
        <IconButton
          onClick={() => setOpenPreview(false)}
          sx={{ position: 'absolute', right: 8, top: 8 }}
        >
          <EditIcon />
        </IconButton>
      </DialogTitle>
      <DialogContent>
        <Box sx={{ p: 2, border: '1px dashed #ccc' }}>
          {/* Área de prévia da etiqueta */}
          <Grid container spacing={2}>
            {configImpressao.incluirLogo && (
              <Grid item xs={12}>
                <Box sx={{ textAlign: 'center', mb: 2 }}>
                  <img
                    src={modelosEtiquetas.find(m => m.id === modeloSelecionado)?.logo}
                    alt="Logo"
                    style={{ height: 50 }}
                  />
                </Box>
              </Grid>
            )}
            <Grid item xs={12}>
              <Typography variant="h6">{etiquetaData.destinatario}</Typography>
              <Typography>{etiquetaData.endereco}</Typography>
              <Typography>
                {etiquetaData.cidade} - {etiquetaData.estado}
              </Typography>
              <Typography>CEP: {etiquetaData.cep}</Typography>
            </Grid>
            <Grid item xs={12}>
              <Divider sx={{ my: 2 }} />
            </Grid>
            <Grid item xs={6}>
              <Typography variant="subtitle2">Pedido:</Typography>
              <Typography>{etiquetaData.numeroPedido}</Typography>
            </Grid>
            <Grid item xs={6}>
              <Typography variant="subtitle2">Rastreio:</Typography>
              <Typography>{etiquetaData.codigoRastreio}</Typography>
            </Grid>
            {configImpressao.incluirCodigoBarras && (
              <Grid item xs={12}>
                <Box sx={{ textAlign: 'center', my: 2 }}>
                  <Barcode value={etiquetaData.codigoRastreio} />
                </Box>
              </Grid>
            )}
            {configImpressao.incluirQRCode && (
              <Grid item xs={12}>
                <Box sx={{ textAlign: 'center', my: 2 }}>
                  <QRCode value={JSON.stringify(etiquetaData)} size={128} />
                </Box>
              </Grid>
            )}
          </Grid>
        </Box>
      </DialogContent>
      <DialogActions>
        <Button onClick={() => setOpenPreview(false)}>Fechar</Button>
        <Button
          variant="contained"
          startIcon={<PrintIcon />}
          onClick={handlePrint}
        >
          Imprimir
        </Button>
      </DialogActions>
    </Dialog>
  );

  const renderConfigDialog = () => (
    <Dialog
      open={openConfig}
      onClose={() => setOpenConfig(false)}
      maxWidth="sm"
      fullWidth
    >
      <DialogTitle>Configurações de Impressão</DialogTitle>
      <DialogContent>
        <Grid container spacing={2} sx={{ mt: 1 }}>
          <Grid item xs={12}>
            <FormControl component="fieldset">
              <Typography variant="subtitle2" gutterBottom>
                Tamanho do Papel
              </Typography>
              <RadioGroup
                value={configImpressao.tamanho}
                onChange={(e) => setConfigImpressao({
                  ...configImpressao,
                  tamanho: e.target.value
                })}
              >
                <FormControlLabel value="a4" control={<Radio />} label="A4" />
                <FormControlLabel value="a6" control={<Radio />} label="A6" />
                <FormControlLabel value="termica" control={<Radio />} label="Térmica" />
              </RadioGroup>
            </FormControl>
          </Grid>
          <Grid item xs={12}>
            <FormControl component="fieldset">
              <Typography variant="subtitle2" gutterBottom>
                Orientação
              </Typography>
              <RadioGroup
                value={configImpressao.orientacao}
                onChange={(e) => setConfigImpressao({
                  ...configImpressao,
                  orientacao: e.target.value
                })}
              >
                <FormControlLabel value="retrato" control={<Radio />} label="Retrato" />
                <FormControlLabel value="paisagem" control={<Radio />} label="Paisagem" />
              </RadioGroup>
            </FormControl>
          </Grid>
          <Grid item xs={12}>
            <TextField
              fullWidth
              type="number"
              label="Número de Cópias"
              value={configImpressao.copias}
              onChange={(e) => setConfigImpressao({
                ...configImpressao,
                copias: e.target.value
              })}
            />
          </Grid>
          <Grid item xs={12}>
            <FormControlLabel
              control={
                <Checkbox
                  checked={configImpressao.incluirQRCode}
                  onChange={(e) => setConfigImpressao({
                    ...configImpressao,
                    incluirQRCode: e.target.checked
                  })}
                />
              }
              label="Incluir QR Code"
            />
          </Grid>
          <Grid item xs={12}>
            <FormControlLabel
              control={
                <Checkbox
                  checked={configImpressao.incluirCodigoBarras}
                  onChange={(e) => setConfigImpressao({
                    ...configImpressao,
                    incluirCodigoBarras: e.target.checked
                  })}
                />
              }
              label="Incluir Código de Barras"
            />
          </Grid>
          <Grid item xs={12}>
            <FormControlLabel
              control={
                <Checkbox
                  checked={configImpressao.incluirLogo}
                  onChange={(e) => setConfigImpressao({
                    ...configImpressao,
                    incluirLogo: e.target.checked
                  })}
                />
              }
              label="Incluir Logo"
            />
          </Grid>
        </Grid>
      </DialogContent>
      <DialogActions>
        <Button onClick={() => setOpenConfig(false)}>Cancelar</Button>
        <Button variant="contained" onClick={() => setOpenConfig(false)}>
          Salvar
        </Button>
      </DialogActions>
    </Dialog>
  );

  if (loading) {
    return <LinearProgress />;
  }

  return (
    <Box p={3}>
      <Box display="flex" justifyContent="space-between" alignItems="center" mb={3}>
        <Typography variant="h4">Gerador de Etiquetas</Typography>
        <Box>
          <Tooltip title="Configurações">
            <IconButton onClick={() => setOpenConfig(true)}>
              <SettingsIcon />
            </IconButton>
          </Tooltip>
          <Tooltip title="Histórico">
            <IconButton>
              <HistoryIcon />
            </IconButton>
          </Tooltip>
        </Box>
      </Box>

      <Paper sx={{ mb: 3 }}>
        <Tabs
          value={tabValue}
          onChange={handleTabChange}
          indicatorColor="primary"
          textColor="primary"
          variant="fullWidth"
        >
          <Tab icon={<QrCodeIcon />} label="Etiquetas" />
          <Tab icon={<LocalShipping />} label="Transportadoras" />
          <Tab icon={<Assignment />} label="Templates" />
          <Tab icon={<Inventory />} label="Histórico" />
        </Tabs>
      </Paper>

      {tabValue === 0 && (
        <>
          {renderFormulario()}
          <Box display="flex" justifyContent="flex-end" gap={2}>
            <Button
              variant="outlined"
              startIcon={<PreviewIcon />}
              onClick={handlePreview}
            >
              Visualizar
            </Button>
            <Button
              variant="contained"
              startIcon={<PrintIcon />}
              onClick={handlePrint}
            >
              Imprimir
            </Button>
          </Box>
        </>
      )}

      {tabValue === 1 && (
        <Typography>Área de configuração de transportadoras em desenvolvimento</Typography>
      )}

      {tabValue === 2 && (
        <Typography>Área de templates em desenvolvimento</Typography>
      )}

      {tabValue === 3 && (
        <Typography>Histórico de etiquetas em desenvolvimento</Typography>
      )}

      {renderPreview()}
      {renderConfigDialog()}
    </Box>
  );
};

export default GeradorEtiquetas;
