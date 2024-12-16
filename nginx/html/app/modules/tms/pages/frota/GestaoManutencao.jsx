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
  List,
  ListItem,
  ListItemText,
  ListItemIcon,
  Autocomplete
} from '@mui/material';
import {
  Build as BuildIcon,
  DirectionsCar,
  Warning,
  CheckCircle,
  Timeline as TimelineIcon,
  Assessment,
  Inventory as InventoryIcon,
  Add as AddIcon,
  Edit as EditIcon,
  Delete as DeleteIcon,
  Assignment,
  AttachMoney,
  Settings,
  Person,
  LocalShipping,
  Engineering
} from '@mui/icons-material';
import { AdapterDateFns } from '@mui/x-date-pickers/AdapterDateFns';
import { LocalizationProvider, DatePicker } from '@mui/x-date-pickers';
import { ptBR } from 'date-fns/locale';

const GestaoManutencao = () => {
  const [loading, setLoading] = useState(true);
  const [tabValue, setTabValue] = useState(0);
  const [manutencoes, setManutencoes] = useState([]);
  const [selectedManutencao, setSelectedManutencao] = useState(null);
  const [openDialog, setOpenDialog] = useState(false);
  const [dialogType, setDialogType] = useState('');
  const [metricas, setMetricas] = useState({
    totalManutencoes: 0,
    emAndamento: 0,
    preventivas: 0,
    custoTotal: 0
  });
  const [filtros, setFiltros] = useState({
    status: 'todos',
    tipo: 'todos',
    veiculo: 'todos'
  });

  useEffect(() => {
    carregarDados();
  }, [filtros]);

  const carregarDados = async () => {
    setLoading(true);
    try {
      const [metricasRes, manutencoesRes] = await Promise.all([
        fetch('/api/v1/tms/manutencao/metricas'),
        fetch('/api/v1/tms/manutencao/lista')
      ]);

      const metricasData = await metricasRes.json();
      const manutencoesData = await manutencoesRes.json();

      setMetricas(metricasData);
      setManutencoes(manutencoesData);
    } catch (error) {
      console.error('Erro ao carregar dados:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleTabChange = (event, newValue) => {
    setTabValue(newValue);
  };

  const handleOpenDialog = (type, manutencao = null) => {
    setDialogType(type);
    setSelectedManutencao(manutencao);
    setOpenDialog(true);
  };

  const handleCloseDialog = () => {
    setDialogType('');
    setSelectedManutencao(null);
    setOpenDialog(false);
  };

  const getStatusColor = (status) => {
    switch (status.toLowerCase()) {
      case 'concluída':
        return 'success';
      case 'pendente':
        return 'warning';
      case 'em andamento':
        return 'info';
      case 'atrasada':
        return 'error';
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
              <BuildIcon color="primary" />
              <Typography variant="subtitle2" ml={1}>
                Total de Manutenções
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.totalManutencoes}</Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={3}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <Settings color="info" />
              <Typography variant="subtitle2" ml={1}>
                Em Andamento
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.emAndamento}</Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={3}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <CheckCircle color="success" />
              <Typography variant="subtitle2" ml={1}>
                Preventivas
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.preventivas}</Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={3}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <AttachMoney color="warning" />
              <Typography variant="subtitle2" ml={1}>
                Custo Total
              </Typography>
            </Box>
            <Typography variant="h4">R$ {metricas.custoTotal}</Typography>
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
              <MenuItem value="em_andamento">Em Andamento</MenuItem>
              <MenuItem value="concluida">Concluída</MenuItem>
              <MenuItem value="atrasada">Atrasada</MenuItem>
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
              <MenuItem value="preventiva">Preventiva</MenuItem>
              <MenuItem value="corretiva">Corretiva</MenuItem>
              <MenuItem value="revisao">Revisão</MenuItem>
            </Select>
          </FormControl>
        </Grid>
        <Grid item xs={12} md={3}>
          <FormControl fullWidth>
            <InputLabel>Veículo</InputLabel>
            <Select
              value={filtros.veiculo}
              onChange={(e) => setFiltros({ ...filtros, veiculo: e.target.value })}
            >
              <MenuItem value="todos">Todos</MenuItem>
              <MenuItem value="veiculo1">Veículo 1</MenuItem>
              <MenuItem value="veiculo2">Veículo 2</MenuItem>
              <MenuItem value="veiculo3">Veículo 3</MenuItem>
            </Select>
          </FormControl>
        </Grid>
        <Grid item xs={12} md={3}>
          <Button
            variant="contained"
            startIcon={<AddIcon />}
            onClick={() => handleOpenDialog('nova')}
            fullWidth
          >
            Nova Manutenção
          </Button>
        </Grid>
      </Grid>
    </Paper>
  );

  const renderManutencoes = () => (
    <TableContainer component={Paper}>
      <Table>
        <TableHead>
          <TableRow>
            <TableCell>OS</TableCell>
            <TableCell>Veículo</TableCell>
            <TableCell>Tipo</TableCell>
            <TableCell>Responsável</TableCell>
            <TableCell>Data Prevista</TableCell>
            <TableCell>Status</TableCell>
            <TableCell>Custo</TableCell>
            <TableCell align="center">Ações</TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {manutencoes.map((manutencao) => (
            <TableRow key={manutencao.id}>
              <TableCell>
                <Box display="flex" alignItems="center">
                  <BuildIcon />
                  <Box ml={2}>
                    <Typography variant="subtitle2">#{manutencao.os}</Typography>
                    <Typography variant="caption" color="textSecondary">
                      {manutencao.descricao}
                    </Typography>
                  </Box>
                </Box>
              </TableCell>
              <TableCell>{manutencao.veiculo}</TableCell>
              <TableCell>{manutencao.tipo}</TableCell>
              <TableCell>{manutencao.responsavel}</TableCell>
              <TableCell>{manutencao.dataPrevista}</TableCell>
              <TableCell>
                <Chip
                  label={manutencao.status}
                  color={getStatusColor(manutencao.status)}
                  size="small"
                />
              </TableCell>
              <TableCell>R$ {manutencao.custo}</TableCell>
              <TableCell align="center">
                <Tooltip title="Editar">
                  <IconButton
                    size="small"
                    onClick={() => handleOpenDialog('editar', manutencao)}
                  >
                    <EditIcon />
                  </IconButton>
                </Tooltip>
                <Tooltip title="Peças">
                  <IconButton size="small">
                    <InventoryIcon />
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
        {selectedManutencao ? 'Editar Manutenção' : 'Nova Manutenção'}
      </DialogTitle>
      <DialogContent>
        <Box component="form" sx={{ mt: 2 }}>
          <Grid container spacing={2}>
            <Grid item xs={12} md={6}>
              <Autocomplete
                options={[]}
                renderInput={(params) => (
                  <TextField {...params} label="Veículo" fullWidth />
                )}
                defaultValue={selectedManutencao?.veiculo || null}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <FormControl fullWidth>
                <InputLabel>Tipo</InputLabel>
                <Select defaultValue={selectedManutencao?.tipo || ''}>
                  <MenuItem value="preventiva">Preventiva</MenuItem>
                  <MenuItem value="corretiva">Corretiva</MenuItem>
                  <MenuItem value="revisao">Revisão</MenuItem>
                </Select>
              </FormControl>
            </Grid>
            <Grid item xs={12} md={6}>
              <LocalizationProvider dateAdapter={AdapterDateFns} locale={ptBR}>
                <DatePicker
                  label="Data Prevista"
                  renderInput={(params) => <TextField {...params} fullWidth />}
                  value={selectedManutencao?.dataPrevista || null}
                />
              </LocalizationProvider>
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Quilometragem"
                type="number"
                defaultValue={selectedManutencao?.quilometragem}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <Autocomplete
                options={[]}
                renderInput={(params) => (
                  <TextField {...params} label="Responsável" fullWidth />
                )}
                defaultValue={selectedManutencao?.responsavel || null}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <FormControl fullWidth>
                <InputLabel>Status</InputLabel>
                <Select defaultValue={selectedManutencao?.status || 'pendente'}>
                  <MenuItem value="pendente">Pendente</MenuItem>
                  <MenuItem value="em_andamento">Em Andamento</MenuItem>
                  <MenuItem value="concluida">Concluída</MenuItem>
                </Select>
              </FormControl>
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Custo Estimado"
                type="number"
                defaultValue={selectedManutencao?.custoEstimado}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Tempo Estimado (horas)"
                type="number"
                defaultValue={selectedManutencao?.tempoEstimado}
              />
            </Grid>
            <Grid item xs={12}>
              <TextField
                fullWidth
                multiline
                rows={4}
                label="Descrição do Serviço"
                defaultValue={selectedManutencao?.descricao}
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
        Gestão de Manutenção
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
          <Tab icon={<BuildIcon />} label="Manutenções" />
          <Tab icon={<DirectionsCar />} label="Veículos" />
          <Tab icon={<Engineering />} label="Mecânicos" />
          <Tab icon={<Assessment />} label="Relatórios" />
        </Tabs>
        <Box p={3}>
          {tabValue === 0 && renderManutencoes()}
          {tabValue === 1 && (
            <Typography>Área de veículos em desenvolvimento</Typography>
          )}
          {tabValue === 2 && (
            <Typography>Área de mecânicos em desenvolvimento</Typography>
          )}
          {tabValue === 3 && (
            <Typography>Área de relatórios em desenvolvimento</Typography>
          )}
        </Box>
      </Paper>

      {renderDialog()}
    </Box>
  );
};

export default GestaoManutencao;
