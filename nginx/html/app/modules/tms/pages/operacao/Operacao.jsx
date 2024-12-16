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
  Tabs,
  Tab,
  Tooltip,
  Badge,
  Divider
} from '@mui/material';
import {
  LocalShipping,
  Assignment,
  Warning,
  CheckCircle,
  Schedule,
  Print as PrintIcon,
  Edit as EditIcon,
  Delete as DeleteIcon,
  Search as SearchIcon,
  Refresh as RefreshIcon,
  FilterList as FilterIcon,
  Map as MapIcon,
  Timeline as TimelineIcon,
  History as HistoryIcon,
  AttachFile as AttachFileIcon
} from '@mui/icons-material';
import { AdapterDateFns } from '@mui/x-date-pickers/AdapterDateFns';
import { LocalizationProvider, DatePicker } from '@mui/x-date-pickers';
import { ptBR } from 'date-fns/locale';

const Operacao = () => {
  const [loading, setLoading] = useState(true);
  const [tabValue, setTabValue] = useState(0);
  const [pedidos, setPedidos] = useState([]);
  const [entregas, setEntregas] = useState([]);
  const [ocorrencias, setOcorrencias] = useState([]);
  const [metricas, setMetricas] = useState({
    totalPedidos: 0,
    pedidosHoje: 0,
    emTransito: 0,
    atrasados: 0,
    entregues: 0,
    ocorrencias: 0
  });
  const [filtros, setFiltros] = useState({
    status: 'todos',
    periodo: '7',
    transportadora: 'todas'
  });
  const [openDialog, setOpenDialog] = useState(false);
  const [selectedItem, setSelectedItem] = useState(null);
  const [dialogType, setDialogType] = useState('');

  useEffect(() => {
    carregarDados();
  }, [filtros]);

  const carregarDados = async () => {
    setLoading(true);
    try {
      const [metricasRes, pedidosRes, entregasRes, ocorrenciasRes] = await Promise.all([
        fetch('/api/v1/tms/operacao/metricas'),
        fetch('/api/v1/tms/operacao/pedidos'),
        fetch('/api/v1/tms/operacao/entregas'),
        fetch('/api/v1/tms/operacao/ocorrencias')
      ]);

      const metricasData = await metricasRes.json();
      const pedidosData = await pedidosRes.json();
      const entregasData = await entregasRes.json();
      const ocorrenciasData = await ocorrenciasRes.json();

      setMetricas(metricasData);
      setPedidos(pedidosData);
      setEntregas(entregasData);
      setOcorrencias(ocorrenciasData);
    } catch (error) {
      console.error('Erro ao carregar dados:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleTabChange = (event, newValue) => {
    setTabValue(newValue);
  };

  const handleOpenDialog = (type, item = null) => {
    setDialogType(type);
    setSelectedItem(item);
    setOpenDialog(true);
  };

  const handleCloseDialog = () => {
    setDialogType('');
    setSelectedItem(null);
    setOpenDialog(false);
  };

  const getStatusColor = (status) => {
    switch (status.toLowerCase()) {
      case 'pendente':
        return 'warning';
      case 'em transito':
        return 'info';
      case 'entregue':
        return 'success';
      case 'atrasado':
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
              <Assignment color="primary" />
              <Typography variant="subtitle2" ml={1}>
                Total de Pedidos
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.totalPedidos}</Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={2}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <Schedule color="primary" />
              <Typography variant="subtitle2" ml={1}>
                Pedidos Hoje
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.pedidosHoje}</Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={2}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <LocalShipping color="info" />
              <Typography variant="subtitle2" ml={1}>
                Em Trânsito
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.emTransito}</Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={2}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <Warning color="error" />
              <Typography variant="subtitle2" ml={1}>
                Atrasados
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.atrasados}</Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={2}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <CheckCircle color="success" />
              <Typography variant="subtitle2" ml={1}>
                Entregues
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.entregues}</Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={2}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <Assignment color="warning" />
              <Typography variant="subtitle2" ml={1}>
                Ocorrências
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.ocorrencias}</Typography>
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
              <MenuItem value="em_transito">Em Trânsito</MenuItem>
              <MenuItem value="entregue">Entregue</MenuItem>
              <MenuItem value="atrasado">Atrasado</MenuItem>
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
              onChange={(e) => setFiltros({ ...filtros, transportadora: e.target.value })}
            >
              <MenuItem value="todas">Todas</MenuItem>
              <MenuItem value="transp1">Transportadora 1</MenuItem>
              <MenuItem value="transp2">Transportadora 2</MenuItem>
              <MenuItem value="transp3">Transportadora 3</MenuItem>
            </Select>
          </FormControl>
        </Grid>
        <Grid item xs={12} md={3}>
          <Box display="flex" gap={1}>
            <Button
              variant="contained"
              startIcon={<SearchIcon />}
              onClick={carregarDados}
              fullWidth
            >
              Buscar
            </Button>
            <Button
              variant="outlined"
              startIcon={<RefreshIcon />}
              onClick={() => {
                setFiltros({
                  status: 'todos',
                  periodo: '7',
                  transportadora: 'todas'
                });
              }}
            >
              Limpar
            </Button>
          </Box>
        </Grid>
      </Grid>
    </Paper>
  );

  const renderPedidos = () => (
    <TableContainer component={Paper}>
      <Table>
        <TableHead>
          <TableRow>
            <TableCell>Pedido</TableCell>
            <TableCell>Data</TableCell>
            <TableCell>Cliente</TableCell>
            <TableCell>Origem</TableCell>
            <TableCell>Destino</TableCell>
            <TableCell>Transportadora</TableCell>
            <TableCell>Status</TableCell>
            <TableCell align="center">Ações</TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {pedidos.map((pedido) => (
            <TableRow key={pedido.id}>
              <TableCell>{pedido.numero}</TableCell>
              <TableCell>{pedido.data}</TableCell>
              <TableCell>{pedido.cliente}</TableCell>
              <TableCell>{pedido.origem}</TableCell>
              <TableCell>{pedido.destino}</TableCell>
              <TableCell>{pedido.transportadora}</TableCell>
              <TableCell>
                <Chip
                  label={pedido.status}
                  color={getStatusColor(pedido.status)}
                  size="small"
                />
              </TableCell>
              <TableCell align="center">
                <Tooltip title="Editar">
                  <IconButton
                    size="small"
                    onClick={() => handleOpenDialog('pedido', pedido)}
                  >
                    <EditIcon />
                  </IconButton>
                </Tooltip>
                <Tooltip title="Rastrear">
                  <IconButton size="small">
                    <MapIcon />
                  </IconButton>
                </Tooltip>
                <Tooltip title="Documentos">
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

  const renderEntregas = () => (
    <TableContainer component={Paper}>
      <Table>
        <TableHead>
          <TableRow>
            <TableCell>Entrega</TableCell>
            <TableCell>Pedido</TableCell>
            <TableCell>Data Prevista</TableCell>
            <TableCell>Motorista</TableCell>
            <TableCell>Veículo</TableCell>
            <TableCell>Status</TableCell>
            <TableCell>Ocorrências</TableCell>
            <TableCell align="center">Ações</TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {entregas.map((entrega) => (
            <TableRow key={entrega.id}>
              <TableCell>{entrega.numero}</TableCell>
              <TableCell>{entrega.pedido}</TableCell>
              <TableCell>{entrega.dataPrevista}</TableCell>
              <TableCell>{entrega.motorista}</TableCell>
              <TableCell>{entrega.veiculo}</TableCell>
              <TableCell>
                <Chip
                  label={entrega.status}
                  color={getStatusColor(entrega.status)}
                  size="small"
                />
              </TableCell>
              <TableCell>
                <Badge badgeContent={entrega.ocorrencias} color="error">
                  <Warning />
                </Badge>
              </TableCell>
              <TableCell align="center">
                <Tooltip title="Editar">
                  <IconButton
                    size="small"
                    onClick={() => handleOpenDialog('entrega', entrega)}
                  >
                    <EditIcon />
                  </IconButton>
                </Tooltip>
                <Tooltip title="Timeline">
                  <IconButton size="small">
                    <TimelineIcon />
                  </IconButton>
                </Tooltip>
                <Tooltip title="Imprimir">
                  <IconButton size="small">
                    <PrintIcon />
                  </IconButton>
                </Tooltip>
              </TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>
    </TableContainer>
  );

  const renderOcorrencias = () => (
    <TableContainer component={Paper}>
      <Table>
        <TableHead>
          <TableRow>
            <TableCell>Data/Hora</TableCell>
            <TableCell>Entrega</TableCell>
            <TableCell>Tipo</TableCell>
            <TableCell>Descrição</TableCell>
            <TableCell>Responsável</TableCell>
            <TableCell>Status</TableCell>
            <TableCell align="center">Ações</TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {ocorrencias.map((ocorrencia) => (
            <TableRow key={ocorrencia.id}>
              <TableCell>{ocorrencia.dataHora}</TableCell>
              <TableCell>{ocorrencia.entrega}</TableCell>
              <TableCell>{ocorrencia.tipo}</TableCell>
              <TableCell>{ocorrencia.descricao}</TableCell>
              <TableCell>{ocorrencia.responsavel}</TableCell>
              <TableCell>
                <Chip
                  label={ocorrencia.status}
                  color={getStatusColor(ocorrencia.status)}
                  size="small"
                />
              </TableCell>
              <TableCell align="center">
                <Tooltip title="Editar">
                  <IconButton
                    size="small"
                    onClick={() => handleOpenDialog('ocorrencia', ocorrencia)}
                  >
                    <EditIcon />
                  </IconButton>
                </Tooltip>
                <Tooltip title="Histórico">
                  <IconButton size="small">
                    <HistoryIcon />
                  </IconButton>
                </Tooltip>
              </TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>
    </TableContainer>
  );

  if (loading) {
    return <LinearProgress />;
  }

  return (
    <Box p={3}>
      <Typography variant="h4" gutterBottom>
        Operação
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
          <Tab label="Pedidos" />
          <Tab label="Entregas" />
          <Tab label="Ocorrências" />
        </Tabs>
        <Box p={3}>
          {tabValue === 0 && renderPedidos()}
          {tabValue === 1 && renderEntregas()}
          {tabValue === 2 && renderOcorrencias()}
        </Box>
      </Paper>

      {/* Dialog de Edição */}
      <Dialog open={openDialog} onClose={handleCloseDialog} maxWidth="md" fullWidth>
        <DialogTitle>
          {dialogType === 'pedido' && 'Editar Pedido'}
          {dialogType === 'entrega' && 'Editar Entrega'}
          {dialogType === 'ocorrencia' && 'Editar Ocorrência'}
        </DialogTitle>
        <DialogContent>
          <Box component="form" sx={{ mt: 2 }}>
            <Grid container spacing={2}>
              {/* Campos específicos para cada tipo de edição */}
              {dialogType === 'pedido' && (
                <>
                  <Grid item xs={12} md={6}>
                    <TextField
                      fullWidth
                      label="Número do Pedido"
                      defaultValue={selectedItem?.numero}
                    />
                  </Grid>
                  <Grid item xs={12} md={6}>
                    <TextField
                      fullWidth
                      label="Cliente"
                      defaultValue={selectedItem?.cliente}
                    />
                  </Grid>
                </>
              )}
              {dialogType === 'entrega' && (
                <>
                  <Grid item xs={12} md={6}>
                    <TextField
                      fullWidth
                      label="Número da Entrega"
                      defaultValue={selectedItem?.numero}
                    />
                  </Grid>
                  <Grid item xs={12} md={6}>
                    <TextField
                      fullWidth
                      label="Motorista"
                      defaultValue={selectedItem?.motorista}
                    />
                  </Grid>
                </>
              )}
              {dialogType === 'ocorrencia' && (
                <>
                  <Grid item xs={12} md={6}>
                    <TextField
                      fullWidth
                      label="Tipo"
                      defaultValue={selectedItem?.tipo}
                    />
                  </Grid>
                  <Grid item xs={12} md={6}>
                    <TextField
                      fullWidth
                      label="Responsável"
                      defaultValue={selectedItem?.responsavel}
                    />
                  </Grid>
                  <Grid item xs={12}>
                    <TextField
                      fullWidth
                      multiline
                      rows={4}
                      label="Descrição"
                      defaultValue={selectedItem?.descricao}
                    />
                  </Grid>
                </>
              )}
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
    </Box>
  );
};

export default Operacao;
