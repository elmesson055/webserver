import React, { useState, useEffect } from 'react';
import {
  Box,
  Card,
  CardContent,
  Grid,
  Typography,
  Button,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Paper,
  TextField,
  IconButton,
  Chip,
  Dialog,
  DialogTitle,
  DialogContent,
  DialogActions,
  FormControl,
  InputLabel,
  Select,
  MenuItem,
  LinearProgress,
  Tooltip,
  Badge,
  Divider,
  Tabs,
  Tab
} from '@mui/material';
import {
  Add as AddIcon,
  Edit as EditIcon,
  Delete as DeleteIcon,
  Print as PrintIcon,
  Email as EmailIcon,
  AttachFile as AttachFileIcon,
  MonetizationOn,
  Assessment,
  Receipt,
  LocalShipping,
  Warning,
  CheckCircle
} from '@mui/icons-material';
import { AdapterDateFns } from '@mui/x-date-pickers/AdapterDateFns';
import { LocalizationProvider, DatePicker } from '@mui/x-date-pickers';
import { ptBR } from 'date-fns/locale';

const Faturamento = () => {
  const [loading, setLoading] = useState(true);
  const [tabValue, setTabValue] = useState(0);
  const [faturas, setFaturas] = useState([]);
  const [selectedFatura, setSelectedFatura] = useState(null);
  const [openDialog, setOpenDialog] = useState(false);
  const [metricas, setMetricas] = useState({
    totalFaturado: 0,
    faturasEmitidas: 0,
    faturasVencidas: 0,
    faturasRecebidas: 0,
    ticketMedio: 0,
    previsaoFaturamento: 0
  });
  const [filtros, setFiltros] = useState({
    status: 'todos',
    periodo: '30',
    transportadora: 'todas'
  });

  useEffect(() => {
    carregarDados();
  }, [filtros]);

  const carregarDados = async () => {
    setLoading(true);
    try {
      const [metricasRes, faturasRes] = await Promise.all([
        fetch('/api/v1/tms/faturamento/metricas'),
        fetch('/api/v1/tms/faturamento/faturas')
      ]);

      const metricasData = await metricasRes.json();
      const faturasData = await faturasRes.json();

      setMetricas(metricasData);
      setFaturas(faturasData);
    } catch (error) {
      console.error('Erro ao carregar dados:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleTabChange = (event, newValue) => {
    setTabValue(newValue);
  };

  const handleOpenDialog = (fatura = null) => {
    setSelectedFatura(fatura);
    setOpenDialog(true);
  };

  const handleCloseDialog = () => {
    setSelectedFatura(null);
    setOpenDialog(false);
  };

  const getStatusColor = (status) => {
    switch (status.toLowerCase()) {
      case 'pendente':
        return 'warning';
      case 'pago':
        return 'success';
      case 'vencido':
        return 'error';
      case 'cancelado':
        return 'error';
      default:
        return 'default';
    }
  };

  const renderKPIs = () => (
    <Grid container spacing={3} mb={3}>
      <Grid item xs={12} md={2}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <MonetizationOn color="primary" />
              <Typography variant="subtitle2" ml={1}>
                Total Faturado
              </Typography>
            </Box>
            <Typography variant="h4">
              R$ {metricas.totalFaturado.toLocaleString()}
            </Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={2}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <Receipt color="primary" />
              <Typography variant="subtitle2" ml={1}>
                Faturas Emitidas
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.faturasEmitidas}</Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={2}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <Warning color="error" />
              <Typography variant="subtitle2" ml={1}>
                Faturas Vencidas
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.faturasVencidas}</Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={2}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <CheckCircle color="success" />
              <Typography variant="subtitle2" ml={1}>
                Faturas Recebidas
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.faturasRecebidas}</Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={2}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <Assessment color="info" />
              <Typography variant="subtitle2" ml={1}>
                Ticket Médio
              </Typography>
            </Box>
            <Typography variant="h4">
              R$ {metricas.ticketMedio.toLocaleString()}
            </Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={2}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <MonetizationOn color="warning" />
              <Typography variant="subtitle2" ml={1}>
                Previsão
              </Typography>
            </Box>
            <Typography variant="h4">
              R$ {metricas.previsaoFaturamento.toLocaleString()}
            </Typography>
          </CardContent>
        </Card>
      </Grid>
    </Grid>
  );

  const renderFiltros = () => (
    <Paper sx={{ p: 2, mb: 3 }}>
      <Grid container spacing={2} alignItems="center">
        <Grid item xs={12} md={3}>
          <FormControl fullWidth>
            <InputLabel>Status</InputLabel>
            <Select
              value={filtros.status}
              onChange={(e) => setFiltros({ ...filtros, status: e.target.value })}
            >
              <MenuItem value="todos">Todos</MenuItem>
              <MenuItem value="pendente">Pendente</MenuItem>
              <MenuItem value="pago">Pago</MenuItem>
              <MenuItem value="vencido">Vencido</MenuItem>
              <MenuItem value="cancelado">Cancelado</MenuItem>
            </Select>
          </FormControl>
        </Grid>
        <Grid item xs={12} md={3}>
          <FormControl fullWidth>
            <InputLabel>Período</InputLabel>
            <Select
              value={filtros.periodo}
              onChange={(e) => setFiltros({ ...filtros, periodo: e.target.value })}
            >
              <MenuItem value="7">Últimos 7 dias</MenuItem>
              <MenuItem value="15">Últimos 15 dias</MenuItem>
              <MenuItem value="30">Últimos 30 dias</MenuItem>
              <MenuItem value="90">Últimos 90 dias</MenuItem>
            </Select>
          </FormControl>
        </Grid>
        <Grid item xs={12} md={3}>
          <FormControl fullWidth>
            <InputLabel>Transportadora</InputLabel>
            <Select
              value={filtros.transportadora}
              onChange={(e) =>
                setFiltros({ ...filtros, transportadora: e.target.value })
              }
            >
              <MenuItem value="todas">Todas</MenuItem>
              <MenuItem value="transp1">Transportadora 1</MenuItem>
              <MenuItem value="transp2">Transportadora 2</MenuItem>
              <MenuItem value="transp3">Transportadora 3</MenuItem>
            </Select>
          </FormControl>
        </Grid>
        <Grid item xs={12} md={3}>
          <Button
            variant="contained"
            startIcon={<AddIcon />}
            onClick={() => handleOpenDialog()}
            fullWidth
          >
            Nova Fatura
          </Button>
        </Grid>
      </Grid>
    </Paper>
  );

  const renderFaturas = () => (
    <TableContainer component={Paper}>
      <Table>
        <TableHead>
          <TableRow>
            <TableCell>Número</TableCell>
            <TableCell>Transportadora</TableCell>
            <TableCell>Data Emissão</TableCell>
            <TableCell>Vencimento</TableCell>
            <TableCell>Valor</TableCell>
            <TableCell>Status</TableCell>
            <TableCell align="center">Ações</TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {faturas.map((fatura) => (
            <TableRow key={fatura.id}>
              <TableCell>{fatura.numero}</TableCell>
              <TableCell>{fatura.transportadora}</TableCell>
              <TableCell>{fatura.dataEmissao}</TableCell>
              <TableCell>{fatura.vencimento}</TableCell>
              <TableCell>R$ {fatura.valor.toLocaleString()}</TableCell>
              <TableCell>
                <Chip
                  label={fatura.status}
                  color={getStatusColor(fatura.status)}
                  size="small"
                />
              </TableCell>
              <TableCell align="center">
                <Tooltip title="Editar">
                  <IconButton
                    size="small"
                    onClick={() => handleOpenDialog(fatura)}
                  >
                    <EditIcon />
                  </IconButton>
                </Tooltip>
                <Tooltip title="Imprimir">
                  <IconButton size="small">
                    <PrintIcon />
                  </IconButton>
                </Tooltip>
                <Tooltip title="Enviar por E-mail">
                  <IconButton size="small">
                    <EmailIcon />
                  </IconButton>
                </Tooltip>
                <Tooltip title="Anexos">
                  <IconButton size="small">
                    <AttachFileIcon />
                  </IconButton>
                </Tooltip>
              </TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>
    </TableContainer>
  );

  const renderDialog = () => (
    <Dialog open={openDialog} onClose={handleCloseDialog} maxWidth="md" fullWidth>
      <DialogTitle>
        {selectedFatura ? 'Editar Fatura' : 'Nova Fatura'}
      </DialogTitle>
      <DialogContent>
        <Box component="form" sx={{ mt: 2 }}>
          <Grid container spacing={2}>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Número da Fatura"
                defaultValue={selectedFatura?.numero}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <FormControl fullWidth>
                <InputLabel>Transportadora</InputLabel>
                <Select defaultValue={selectedFatura?.transportadora || ''}>
                  <MenuItem value="transp1">Transportadora 1</MenuItem>
                  <MenuItem value="transp2">Transportadora 2</MenuItem>
                  <MenuItem value="transp3">Transportadora 3</MenuItem>
                </Select>
              </FormControl>
            </Grid>
            <Grid item xs={12} md={6}>
              <LocalizationProvider dateAdapter={AdapterDateFns} locale={ptBR}>
                <DatePicker
                  label="Data de Emissão"
                  renderInput={(params) => <TextField {...params} fullWidth />}
                  value={selectedFatura?.dataEmissao || null}
                />
              </LocalizationProvider>
            </Grid>
            <Grid item xs={12} md={6}>
              <LocalizationProvider dateAdapter={AdapterDateFns} locale={ptBR}>
                <DatePicker
                  label="Data de Vencimento"
                  renderInput={(params) => <TextField {...params} fullWidth />}
                  value={selectedFatura?.vencimento || null}
                />
              </LocalizationProvider>
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Valor"
                type="number"
                defaultValue={selectedFatura?.valor}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <FormControl fullWidth>
                <InputLabel>Status</InputLabel>
                <Select defaultValue={selectedFatura?.status || 'pendente'}>
                  <MenuItem value="pendente">Pendente</MenuItem>
                  <MenuItem value="pago">Pago</MenuItem>
                  <MenuItem value="vencido">Vencido</MenuItem>
                  <MenuItem value="cancelado">Cancelado</MenuItem>
                </Select>
              </FormControl>
            </Grid>
            <Grid item xs={12}>
              <TextField
                fullWidth
                multiline
                rows={4}
                label="Observações"
                defaultValue={selectedFatura?.observacoes}
              />
            </Grid>
          </Grid>
        </Box>
      </DialogContent>
      <DialogActions>
        <Button onClick={handleCloseDialog}>Cancelar</Button>
        <Button variant="contained" onClick={handleCloseDialog}>
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
      <Typography variant="h4" gutterBottom>
        Faturamento
      </Typography>

      {renderKPIs()}
      {renderFiltros()}

      <Paper sx={{ mb: 3 }}>
        <Tabs
          value={tabValue}
          onChange={handleTabChange}
          indicatorColor="primary"
          textColor="primary"
          variant="fullWidth"
        >
          <Tab label="Faturas" />
          <Tab label="Relatórios" />
          <Tab label="Configurações" />
        </Tabs>
        <Box p={3}>
          {tabValue === 0 && renderFaturas()}
          {tabValue === 1 && (
            <Typography>Área de relatórios em desenvolvimento</Typography>
          )}
          {tabValue === 2 && (
            <Typography>Área de configurações em desenvolvimento</Typography>
          )}
        </Box>
      </Paper>

      {renderDialog()}
    </Box>
  );
};

export default Faturamento;
