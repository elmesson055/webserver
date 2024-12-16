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
  Tabs,
  Tab,
  Badge,
  List,
  ListItem,
  ListItemText,
  ListItemIcon
} from '@mui/material';
import {
  DirectionsCar,
  LocalShipping,
  Build as BuildIcon,
  Warning,
  CheckCircle,
  Timeline as TimelineIcon,
  Assessment,
  Inventory as InventoryIcon,
  LocalGasStation,
  Speed as SpeedIcon,
  Add as AddIcon,
  Edit as EditIcon,
  Delete as DeleteIcon,
  Assignment,
  AttachMoney,
  Settings,
  Tire as TireIcon
} from '@mui/icons-material';
import { AdapterDateFns } from '@mui/x-date-pickers/AdapterDateFns';
import { LocalizationProvider, DatePicker } from '@mui/x-date-pickers';
import { ptBR } from 'date-fns/locale';

const GestaoFrota = () => {
  const [loading, setLoading] = useState(true);
  const [tabValue, setTabValue] = useState(0);
  const [veiculos, setVeiculos] = useState([]);
  const [selectedVeiculo, setSelectedVeiculo] = useState(null);
  const [openDialog, setOpenDialog] = useState(false);
  const [dialogType, setDialogType] = useState('');
  const [metricas, setMetricas] = useState({
    totalVeiculos: 0,
    emOperacao: 0,
    emManutencao: 0,
    custoMedioKm: 0
  });
  const [filtros, setFiltros] = useState({
    status: 'todos',
    tipo: 'todos',
    base: 'todas'
  });

  useEffect(() => {
    carregarDados();
  }, [filtros]);

  const carregarDados = async () => {
    setLoading(true);
    try {
      const [metricasRes, veiculosRes] = await Promise.all([
        fetch('/api/v1/tms/frota/metricas'),
        fetch('/api/v1/tms/frota/veiculos')
      ]);

      const metricasData = await metricasRes.json();
      const veiculosData = await veiculosRes.json();

      setMetricas(metricasData);
      setVeiculos(veiculosData);
    } catch (error) {
      console.error('Erro ao carregar dados:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleTabChange = (event, newValue) => {
    setTabValue(newValue);
  };

  const handleOpenDialog = (type, veiculo = null) => {
    setDialogType(type);
    setSelectedVeiculo(veiculo);
    setOpenDialog(true);
  };

  const handleCloseDialog = () => {
    setDialogType('');
    setSelectedVeiculo(null);
    setOpenDialog(false);
  };

  const getStatusColor = (status) => {
    switch (status.toLowerCase()) {
      case 'ativo':
        return 'success';
      case 'inativo':
        return 'error';
      case 'em manutenção':
        return 'warning';
      case 'em viagem':
        return 'info';
      default:
        return 'default';
    }
  };

  const renderKPIs = () => (
    <Grid container spacing={3} mb={3}>
      <Grid item xs={12} md={3}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <LocalShipping color="primary" />
              <Typography variant="subtitle2" ml={1}>
                Total de Veículos
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.totalVeiculos}</Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={3}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <DirectionsCar color="success" />
              <Typography variant="subtitle2" ml={1}>
                Em Operação
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.emOperacao}</Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={3}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <BuildIcon color="warning" />
              <Typography variant="subtitle2" ml={1}>
                Em Manutenção
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.emManutencao}</Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={3}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <AttachMoney color="info" />
              <Typography variant="subtitle2" ml={1}>
                Custo Médio/Km
              </Typography>
            </Box>
            <Typography variant="h4">R$ {metricas.custoMedioKm}</Typography>
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
              <MenuItem value="ativo">Ativo</MenuItem>
              <MenuItem value="inativo">Inativo</MenuItem>
              <MenuItem value="manutencao">Em Manutenção</MenuItem>
              <MenuItem value="viagem">Em Viagem</MenuItem>
            </Select>
          </FormControl>
        </Grid>
        <Grid item xs={12} md={3}>
          <FormControl fullWidth>
            <InputLabel>Tipo</InputLabel>
            <Select
              value={filtros.tipo}
              onChange={(e) => setFiltros({ ...filtros, tipo: e.target.value })}
            >
              <MenuItem value="todos">Todos</MenuItem>
              <MenuItem value="truck">Truck</MenuItem>
              <MenuItem value="carreta">Carreta</MenuItem>
              <MenuItem value="bitrem">Bi-trem</MenuItem>
              <MenuItem value="van">Van</MenuItem>
            </Select>
          </FormControl>
        </Grid>
        <Grid item xs={12} md={3}>
          <FormControl fullWidth>
            <InputLabel>Base</InputLabel>
            <Select
              value={filtros.base}
              onChange={(e) => setFiltros({ ...filtros, base: e.target.value })}
            >
              <MenuItem value="todas">Todas</MenuItem>
              <MenuItem value="sp">São Paulo</MenuItem>
              <MenuItem value="rj">Rio de Janeiro</MenuItem>
              <MenuItem value="mg">Minas Gerais</MenuItem>
            </Select>
          </FormControl>
        </Grid>
        <Grid item xs={12} md={3}>
          <Button
            variant="contained"
            startIcon={<AddIcon />}
            onClick={() => handleOpenDialog('novo')}
            fullWidth
          >
            Novo Veículo
          </Button>
        </Grid>
      </Grid>
    </Paper>
  );

  const renderVeiculos = () => (
    <TableContainer component={Paper}>
      <Table>
        <TableHead>
          <TableRow>
            <TableCell>Veículo</TableCell>
            <TableCell>Placa</TableCell>
            <TableCell>Tipo</TableCell>
            <TableCell>Base</TableCell>
            <TableCell>Status</TableCell>
            <TableCell>Km Rodados</TableCell>
            <TableCell>Próx. Manutenção</TableCell>
            <TableCell align="center">Ações</TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {veiculos.map((veiculo) => (
            <TableRow key={veiculo.id}>
              <TableCell>
                <Box display="flex" alignItems="center">
                  <LocalShipping />
                  <Box ml={2}>
                    <Typography variant="subtitle2">{veiculo.modelo}</Typography>
                    <Typography variant="caption" color="textSecondary">
                      {veiculo.marca}
                    </Typography>
                  </Box>
                </Box>
              </TableCell>
              <TableCell>{veiculo.placa}</TableCell>
              <TableCell>{veiculo.tipo}</TableCell>
              <TableCell>{veiculo.base}</TableCell>
              <TableCell>
                <Chip
                  label={veiculo.status}
                  color={getStatusColor(veiculo.status)}
                  size="small"
                />
              </TableCell>
              <TableCell>{veiculo.kmRodados} km</TableCell>
              <TableCell>
                <Box display="flex" alignItems="center">
                  {veiculo.diasProxManutencao <= 7 ? (
                    <Warning color="error" sx={{ mr: 1 }} />
                  ) : (
                    <CheckCircle color="success" sx={{ mr: 1 }} />
                  )}
                  {veiculo.proxManutencao}
                </Box>
              </TableCell>
              <TableCell align="center">
                <Tooltip title="Editar">
                  <IconButton
                    size="small"
                    onClick={() => handleOpenDialog('editar', veiculo)}
                  >
                    <EditIcon />
                  </IconButton>
                </Tooltip>
                <Tooltip title="Manutenção">
                  <IconButton size="small">
                    <BuildIcon />
                  </IconButton>
                </Tooltip>
                <Tooltip title="Histórico">
                  <IconButton size="small">
                    <TimelineIcon />
                  </IconButton>
                </Tooltip>
                <Tooltip title="Documentos">
                  <IconButton size="small">
                    <Assignment />
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
        {selectedVeiculo ? 'Editar Veículo' : 'Novo Veículo'}
      </DialogTitle>
      <DialogContent>
        <Box component="form" sx={{ mt: 2 }}>
          <Grid container spacing={2}>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Modelo"
                defaultValue={selectedVeiculo?.modelo}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Marca"
                defaultValue={selectedVeiculo?.marca}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Placa"
                defaultValue={selectedVeiculo?.placa}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Renavam"
                defaultValue={selectedVeiculo?.renavam}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <FormControl fullWidth>
                <InputLabel>Tipo</InputLabel>
                <Select defaultValue={selectedVeiculo?.tipo || ''}>
                  <MenuItem value="truck">Truck</MenuItem>
                  <MenuItem value="carreta">Carreta</MenuItem>
                  <MenuItem value="bitrem">Bi-trem</MenuItem>
                  <MenuItem value="van">Van</MenuItem>
                </Select>
              </FormControl>
            </Grid>
            <Grid item xs={12} md={6}>
              <FormControl fullWidth>
                <InputLabel>Base</InputLabel>
                <Select defaultValue={selectedVeiculo?.base || ''}>
                  <MenuItem value="sp">São Paulo</MenuItem>
                  <MenuItem value="rj">Rio de Janeiro</MenuItem>
                  <MenuItem value="mg">Minas Gerais</MenuItem>
                </Select>
              </FormControl>
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Capacidade (kg)"
                type="number"
                defaultValue={selectedVeiculo?.capacidade}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Consumo Médio (km/l)"
                type="number"
                defaultValue={selectedVeiculo?.consumoMedio}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <LocalizationProvider dateAdapter={AdapterDateFns} locale={ptBR}>
                <DatePicker
                  label="Próxima Manutenção"
                  renderInput={(params) => <TextField {...params} fullWidth />}
                  value={selectedVeiculo?.proxManutencao || null}
                />
              </LocalizationProvider>
            </Grid>
            <Grid item xs={12} md={6}>
              <FormControl fullWidth>
                <InputLabel>Status</InputLabel>
                <Select defaultValue={selectedVeiculo?.status || 'ativo'}>
                  <MenuItem value="ativo">Ativo</MenuItem>
                  <MenuItem value="inativo">Inativo</MenuItem>
                  <MenuItem value="manutencao">Em Manutenção</MenuItem>
                </Select>
              </FormControl>
            </Grid>
            <Grid item xs={12}>
              <TextField
                fullWidth
                multiline
                rows={4}
                label="Observações"
                defaultValue={selectedVeiculo?.observacoes}
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
        Gestão de Frota
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
          <Tab icon={<DirectionsCar />} label="Veículos" />
          <Tab icon={<BuildIcon />} label="Manutenções" />
          <Tab icon={<InventoryIcon />} label="Estoque" />
          <Tab icon={<TireIcon />} label="Pneus" />
        </Tabs>
        <Box p={3}>
          {tabValue === 0 && renderVeiculos()}
          {tabValue === 1 && (
            <Typography>Área de manutenções em desenvolvimento</Typography>
          )}
          {tabValue === 2 && (
            <Typography>Área de estoque em desenvolvimento</Typography>
          )}
          {tabValue === 3 && (
            <Typography>Área de pneus em desenvolvimento</Typography>
          )}
        </Box>
      </Paper>

      {renderDialog()}
    </Box>
  );
};

export default GestaoFrota;
