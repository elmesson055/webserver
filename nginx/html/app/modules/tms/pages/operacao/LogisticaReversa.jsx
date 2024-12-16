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
  LinearProgress
} from '@mui/material';
import {
  Add as AddIcon,
  Edit as EditIcon,
  Delete as DeleteIcon,
  LocalShipping,
  Assignment,
  Timeline,
  Print as PrintIcon
} from '@mui/icons-material';
import {
  BarChart,
  Bar,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  Legend,
  PieChart,
  Pie,
  Cell
} from 'recharts';

const LogisticaReversa = () => {
  const [loading, setLoading] = useState(true);
  const [solicitacoes, setSolicitacoes] = useState([]);
  const [metricas, setMetricas] = useState({
    totalSolicitacoes: 0,
    emAndamento: 0,
    finalizadas: 0,
    custoTotal: 0,
    tempoMedio: 0
  });
  const [openDialog, setOpenDialog] = useState(false);
  const [selectedSolicitacao, setSelectedSolicitacao] = useState(null);
  const [filtros, setFiltros] = useState({
    status: 'todos',
    tipo: 'todos',
    periodo: '30'
  });

  useEffect(() => {
    carregarDados();
  }, [filtros]);

  const carregarDados = async () => {
    try {
      setLoading(true);
      const [metricasRes, solicitacoesRes] = await Promise.all([
        fetch('/api/v1/tms/logistica-reversa/metricas'),
        fetch('/api/v1/tms/logistica-reversa/solicitacoes')
      ]);

      const metricasData = await metricasRes.json();
      const solicitacoesData = await solicitacoesRes.json();

      setMetricas(metricasData);
      setSolicitacoes(solicitacoesData);
    } catch (error) {
      console.error('Erro ao carregar dados:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleOpenDialog = (solicitacao = null) => {
    setSelectedSolicitacao(solicitacao);
    setOpenDialog(true);
  };

  const handleCloseDialog = () => {
    setSelectedSolicitacao(null);
    setOpenDialog(false);
  };

  const handleSalvarSolicitacao = async (event) => {
    event.preventDefault();
    // Implementar lógica de salvamento
    handleCloseDialog();
  };

  const getStatusColor = (status) => {
    switch (status.toLowerCase()) {
      case 'pendente':
        return 'warning';
      case 'em andamento':
        return 'info';
      case 'finalizado':
        return 'success';
      case 'cancelado':
        return 'error';
      default:
        return 'default';
    }
  };

  if (loading) {
    return <LinearProgress />;
  }

  return (
    <Box p={3}>
      <Box display="flex" justifyContent="space-between" alignItems="center" mb={3}>
        <Typography variant="h4">Logística Reversa</Typography>
        <Button
          variant="contained"
          color="primary"
          startIcon={<AddIcon />}
          onClick={() => handleOpenDialog()}
        >
          Nova Solicitação
        </Button>
      </Box>

      {/* KPIs */}
      <Grid container spacing={3} mb={3}>
        <Grid item xs={12} md={2.4}>
          <Card>
            <CardContent>
              <Box display="flex" alignItems="center" mb={1}>
                <Assignment color="primary" />
                <Typography variant="subtitle2" ml={1}>
                  Total Solicitações
                </Typography>
              </Box>
              <Typography variant="h4">{metricas.totalSolicitacoes}</Typography>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={2.4}>
          <Card>
            <CardContent>
              <Box display="flex" alignItems="center" mb={1}>
                <Timeline color="warning" />
                <Typography variant="subtitle2" ml={1}>
                  Em Andamento
                </Typography>
              </Box>
              <Typography variant="h4">{metricas.emAndamento}</Typography>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={2.4}>
          <Card>
            <CardContent>
              <Box display="flex" alignItems="center" mb={1}>
                <LocalShipping color="success" />
                <Typography variant="subtitle2" ml={1}>
                  Finalizadas
                </Typography>
              </Box>
              <Typography variant="h4">{metricas.finalizadas}</Typography>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={2.4}>
          <Card>
            <CardContent>
              <Box display="flex" alignItems="center" mb={1}>
                <Assignment color="primary" />
                <Typography variant="subtitle2" ml={1}>
                  Custo Total
                </Typography>
              </Box>
              <Typography variant="h4">R$ {metricas.custoTotal}</Typography>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={2.4}>
          <Card>
            <CardContent>
              <Box display="flex" alignItems="center" mb={1}>
                <Timeline color="primary" />
                <Typography variant="subtitle2" ml={1}>
                  Tempo Médio
                </Typography>
              </Box>
              <Typography variant="h4">{metricas.tempoMedio}h</Typography>
            </CardContent>
          </Card>
        </Grid>
      </Grid>

      {/* Filtros */}
      <Paper sx={{ p: 2, mb: 3 }}>
        <Grid container spacing={2} alignItems="center">
          <Grid item xs={12} md={3}>
            <FormControl fullWidth>
              <InputLabel>Status</InputLabel>
              <Select
                value={filtros.status}
                label="Status"
                onChange={(e) => setFiltros({ ...filtros, status: e.target.value })}
              >
                <MenuItem value="todos">Todos</MenuItem>
                <MenuItem value="pendente">Pendente</MenuItem>
                <MenuItem value="em_andamento">Em Andamento</MenuItem>
                <MenuItem value="finalizado">Finalizado</MenuItem>
                <MenuItem value="cancelado">Cancelado</MenuItem>
              </Select>
            </FormControl>
          </Grid>
          <Grid item xs={12} md={3}>
            <FormControl fullWidth>
              <InputLabel>Tipo</InputLabel>
              <Select
                value={filtros.tipo}
                label="Tipo"
                onChange={(e) => setFiltros({ ...filtros, tipo: e.target.value })}
              >
                <MenuItem value="todos">Todos</MenuItem>
                <MenuItem value="devolucao">Devolução</MenuItem>
                <MenuItem value="troca">Troca</MenuItem>
                <MenuItem value="garantia">Garantia</MenuItem>
              </Select>
            </FormControl>
          </Grid>
          <Grid item xs={12} md={3}>
            <FormControl fullWidth>
              <InputLabel>Período</InputLabel>
              <Select
                value={filtros.periodo}
                label="Período"
                onChange={(e) => setFiltros({ ...filtros, periodo: e.target.value })}
              >
                <MenuItem value="7">Últimos 7 dias</MenuItem>
                <MenuItem value="15">Últimos 15 dias</MenuItem>
                <MenuItem value="30">Últimos 30 dias</MenuItem>
                <MenuItem value="90">Últimos 90 dias</MenuItem>
              </Select>
            </FormControl>
          </Grid>
        </Grid>
      </Paper>

      {/* Tabela de Solicitações */}
      <TableContainer component={Paper}>
        <Table>
          <TableHead>
            <TableRow>
              <TableCell>ID</TableCell>
              <TableCell>Data</TableCell>
              <TableCell>Cliente</TableCell>
              <TableCell>Tipo</TableCell>
              <TableCell>Status</TableCell>
              <TableCell>Origem</TableCell>
              <TableCell>Destino</TableCell>
              <TableCell>Valor</TableCell>
              <TableCell align="center">Ações</TableCell>
            </TableRow>
          </TableHead>
          <TableBody>
            {solicitacoes.map((solicitacao) => (
              <TableRow key={solicitacao.id}>
                <TableCell>{solicitacao.id}</TableCell>
                <TableCell>{solicitacao.data}</TableCell>
                <TableCell>{solicitacao.cliente}</TableCell>
                <TableCell>{solicitacao.tipo}</TableCell>
                <TableCell>
                  <Chip
                    label={solicitacao.status}
                    color={getStatusColor(solicitacao.status)}
                    size="small"
                  />
                </TableCell>
                <TableCell>{solicitacao.origem}</TableCell>
                <TableCell>{solicitacao.destino}</TableCell>
                <TableCell>R$ {solicitacao.valor}</TableCell>
                <TableCell align="center">
                  <IconButton
                    size="small"
                    onClick={() => handleOpenDialog(solicitacao)}
                    color="primary"
                  >
                    <EditIcon />
                  </IconButton>
                  <IconButton size="small" color="primary">
                    <PrintIcon />
                  </IconButton>
                </TableCell>
              </TableRow>
            ))}
          </TableBody>
        </Table>
      </TableContainer>

      {/* Modal de Nova Solicitação/Edição */}
      <Dialog open={openDialog} onClose={handleCloseDialog} maxWidth="md" fullWidth>
        <DialogTitle>
          {selectedSolicitacao ? 'Editar Solicitação' : 'Nova Solicitação'}
        </DialogTitle>
        <DialogContent>
          <Box component="form" onSubmit={handleSalvarSolicitacao} sx={{ mt: 2 }}>
            <Grid container spacing={2}>
              <Grid item xs={12} md={6}>
                <TextField
                  fullWidth
                  label="Cliente"
                  defaultValue={selectedSolicitacao?.cliente || ''}
                />
              </Grid>
              <Grid item xs={12} md={6}>
                <FormControl fullWidth>
                  <InputLabel>Tipo</InputLabel>
                  <Select
                    defaultValue={selectedSolicitacao?.tipo || 'devolucao'}
                    label="Tipo"
                  >
                    <MenuItem value="devolucao">Devolução</MenuItem>
                    <MenuItem value="troca">Troca</MenuItem>
                    <MenuItem value="garantia">Garantia</MenuItem>
                  </Select>
                </FormControl>
              </Grid>
              <Grid item xs={12} md={6}>
                <TextField
                  fullWidth
                  label="Origem"
                  defaultValue={selectedSolicitacao?.origem || ''}
                />
              </Grid>
              <Grid item xs={12} md={6}>
                <TextField
                  fullWidth
                  label="Destino"
                  defaultValue={selectedSolicitacao?.destino || ''}
                />
              </Grid>
              <Grid item xs={12} md={6}>
                <TextField
                  fullWidth
                  label="Valor"
                  type="number"
                  defaultValue={selectedSolicitacao?.valor || ''}
                />
              </Grid>
              <Grid item xs={12} md={6}>
                <FormControl fullWidth>
                  <InputLabel>Status</InputLabel>
                  <Select
                    defaultValue={selectedSolicitacao?.status || 'pendente'}
                    label="Status"
                  >
                    <MenuItem value="pendente">Pendente</MenuItem>
                    <MenuItem value="em_andamento">Em Andamento</MenuItem>
                    <MenuItem value="finalizado">Finalizado</MenuItem>
                    <MenuItem value="cancelado">Cancelado</MenuItem>
                  </Select>
                </FormControl>
              </Grid>
              <Grid item xs={12}>
                <TextField
                  fullWidth
                  label="Observações"
                  multiline
                  rows={4}
                  defaultValue={selectedSolicitacao?.observacoes || ''}
                />
              </Grid>
            </Grid>
          </Box>
        </DialogContent>
        <DialogActions>
          <Button onClick={handleCloseDialog}>Cancelar</Button>
          <Button variant="contained" onClick={handleSalvarSolicitacao}>
            Salvar
          </Button>
        </DialogActions>
      </Dialog>
    </Box>
  );
};

export default LogisticaReversa;
