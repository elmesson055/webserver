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
  Avatar,
  Badge,
  Rating,
  List,
  ListItem,
  ListItemText,
  ListItemIcon,
  Tabs,
  Tab
} from '@mui/material';
import {
  Person,
  Add as AddIcon,
  Edit as EditIcon,
  Delete as DeleteIcon,
  DirectionsCar,
  Assignment,
  Warning,
  CheckCircle,
  Star,
  Timeline as TimelineIcon,
  Assessment,
  LocalShipping,
  Speed as SpeedIcon,
  LocationOn,
  AttachFile,
  Email,
  Phone
} from '@mui/icons-material';
import { AdapterDateFns } from '@mui/x-date-pickers/AdapterDateFns';
import { LocalizationProvider, DatePicker } from '@mui/x-date-pickers';
import { ptBR } from 'date-fns/locale';

const GestaoMotoristas = () => {
  const [loading, setLoading] = useState(true);
  const [tabValue, setTabValue] = useState(0);
  const [motoristas, setMotoristas] = useState([]);
  const [selectedMotorista, setSelectedMotorista] = useState(null);
  const [openDialog, setOpenDialog] = useState(false);
  const [dialogType, setDialogType] = useState('');
  const [metricas, setMetricas] = useState({
    totalMotoristas: 0,
    emServico: 0,
    pontosCarteira: 0,
    avaliacaoMedia: 0
  });
  const [filtros, setFiltros] = useState({
    status: 'todos',
    transportadora: 'todas',
    regiao: 'todas'
  });

  useEffect(() => {
    carregarDados();
  }, [filtros]);

  const carregarDados = async () => {
    setLoading(true);
    try {
      const [metricasRes, motoristasRes] = await Promise.all([
        fetch('/api/v1/tms/motoristas/metricas'),
        fetch('/api/v1/tms/motoristas/lista')
      ]);

      const metricasData = await metricasRes.json();
      const motoristasData = await motoristasRes.json();

      setMetricas(metricasData);
      setMotoristas(motoristasData);
    } catch (error) {
      console.error('Erro ao carregar dados:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleTabChange = (event, newValue) => {
    setTabValue(newValue);
  };

  const handleOpenDialog = (type, motorista = null) => {
    setDialogType(type);
    setSelectedMotorista(motorista);
    setOpenDialog(true);
  };

  const handleCloseDialog = () => {
    setDialogType('');
    setSelectedMotorista(null);
    setOpenDialog(false);
  };

  const getStatusColor = (status) => {
    switch (status.toLowerCase()) {
      case 'ativo':
        return 'success';
      case 'inativo':
        return 'error';
      case 'em rota':
        return 'info';
      case 'suspenso':
        return 'warning';
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
              <Person color="primary" />
              <Typography variant="subtitle2" ml={1}>
                Total de Motoristas
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.totalMotoristas}</Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={3}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <LocalShipping color="info" />
              <Typography variant="subtitle2" ml={1}>
                Em Serviço
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.emServico}</Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={3}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <Warning color="error" />
              <Typography variant="subtitle2" ml={1}>
                Pontos na Carteira
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.pontosCarteira}</Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={3}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <Star color="warning" />
              <Typography variant="subtitle2" ml={1}>
                Avaliação Média
              </Typography>
            </Box>
            <Box display="flex" alignItems="center">
              <Rating value={metricas.avaliacaoMedia} readOnly precision={0.5} />
              <Typography variant="h6" ml={1}>
                ({metricas.avaliacaoMedia})
              </Typography>
            </Box>
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
              <MenuItem value="em_rota">Em Rota</MenuItem>
              <MenuItem value="suspenso">Suspenso</MenuItem>
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
          <FormControl fullWidth>
            <InputLabel>Região</InputLabel>
            <Select
              value={filtros.regiao}
              onChange={(e) => setFiltros({ ...filtros, regiao: e.target.value })}
            >
              <MenuItem value="todas">Todas</MenuItem>
              <MenuItem value="norte">Norte</MenuItem>
              <MenuItem value="nordeste">Nordeste</MenuItem>
              <MenuItem value="centro_oeste">Centro-Oeste</MenuItem>
              <MenuItem value="sudeste">Sudeste</MenuItem>
              <MenuItem value="sul">Sul</MenuItem>
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
            Novo Motorista
          </Button>
        </Grid>
      </Grid>
    </Paper>
  );

  const renderMotoristas = () => (
    <TableContainer component={Paper}>
      <Table>
        <TableHead>
          <TableRow>
            <TableCell>Motorista</TableCell>
            <TableCell>CNH</TableCell>
            <TableCell>Transportadora</TableCell>
            <TableCell>Status</TableCell>
            <TableCell>Pontos</TableCell>
            <TableCell>Avaliação</TableCell>
            <TableCell>Entregas</TableCell>
            <TableCell align="center">Ações</TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {motoristas.map((motorista) => (
            <TableRow key={motorista.id}>
              <TableCell>
                <Box display="flex" alignItems="center">
                  <Avatar src={motorista.foto} alt={motorista.nome} />
                  <Box ml={2}>
                    <Typography variant="subtitle2">{motorista.nome}</Typography>
                    <Typography variant="caption" color="textSecondary">
                      {motorista.telefone}
                    </Typography>
                  </Box>
                </Box>
              </TableCell>
              <TableCell>{motorista.cnh}</TableCell>
              <TableCell>{motorista.transportadora}</TableCell>
              <TableCell>
                <Chip
                  label={motorista.status}
                  color={getStatusColor(motorista.status)}
                  size="small"
                />
              </TableCell>
              <TableCell>
                <Badge
                  badgeContent={motorista.pontos}
                  color={motorista.pontos > 20 ? 'error' : 'warning'}
                >
                  <DirectionsCar />
                </Badge>
              </TableCell>
              <TableCell>
                <Rating value={motorista.avaliacao} readOnly size="small" />
              </TableCell>
              <TableCell>{motorista.entregas}</TableCell>
              <TableCell align="center">
                <Tooltip title="Editar">
                  <IconButton
                    size="small"
                    onClick={() => handleOpenDialog('editar', motorista)}
                  >
                    <EditIcon />
                  </IconButton>
                </Tooltip>
                <Tooltip title="Histórico">
                  <IconButton size="small">
                    <TimelineIcon />
                  </IconButton>
                </Tooltip>
                <Tooltip title="Documentos">
                  <IconButton size="small">
                    <AttachFile />
                  </IconButton>
                </Tooltip>
                <Tooltip title="Localização">
                  <IconButton size="small">
                    <LocationOn />
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
        {selectedMotorista ? 'Editar Motorista' : 'Novo Motorista'}
      </DialogTitle>
      <DialogContent>
        <Box component="form" sx={{ mt: 2 }}>
          <Grid container spacing={2}>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Nome Completo"
                defaultValue={selectedMotorista?.nome}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="CNH"
                defaultValue={selectedMotorista?.cnh}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Telefone"
                defaultValue={selectedMotorista?.telefone}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="E-mail"
                defaultValue={selectedMotorista?.email}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <FormControl fullWidth>
                <InputLabel>Transportadora</InputLabel>
                <Select defaultValue={selectedMotorista?.transportadora || ''}>
                  <MenuItem value="transp1">Transportadora 1</MenuItem>
                  <MenuItem value="transp2">Transportadora 2</MenuItem>
                  <MenuItem value="transp3">Transportadora 3</MenuItem>
                </Select>
              </FormControl>
            </Grid>
            <Grid item xs={12} md={6}>
              <FormControl fullWidth>
                <InputLabel>Status</InputLabel>
                <Select defaultValue={selectedMotorista?.status || 'ativo'}>
                  <MenuItem value="ativo">Ativo</MenuItem>
                  <MenuItem value="inativo">Inativo</MenuItem>
                  <MenuItem value="suspenso">Suspenso</MenuItem>
                </Select>
              </FormControl>
            </Grid>
            <Grid item xs={12} md={6}>
              <LocalizationProvider dateAdapter={AdapterDateFns} locale={ptBR}>
                <DatePicker
                  label="Validade CNH"
                  renderInput={(params) => <TextField {...params} fullWidth />}
                  value={selectedMotorista?.validadeCnh || null}
                />
              </LocalizationProvider>
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Pontos na Carteira"
                type="number"
                defaultValue={selectedMotorista?.pontos}
              />
            </Grid>
            <Grid item xs={12}>
              <TextField
                fullWidth
                multiline
                rows={4}
                label="Observações"
                defaultValue={selectedMotorista?.observacoes}
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
        Gestão de Motoristas
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
          <Tab icon={<Person />} label="Motoristas" />
          <Tab icon={<Assessment />} label="Desempenho" />
          <Tab icon={<DirectionsCar />} label="Infrações" />
          <Tab icon={<Assignment />} label="Documentos" />
        </Tabs>
        <Box p={3}>
          {tabValue === 0 && renderMotoristas()}
          {tabValue === 1 && (
            <Typography>Área de desempenho em desenvolvimento</Typography>
          )}
          {tabValue === 2 && (
            <Typography>Área de infrações em desenvolvimento</Typography>
          )}
          {tabValue === 3 && (
            <Typography>Área de documentos em desenvolvimento</Typography>
          )}
        </Box>
      </Paper>

      {renderDialog()}
    </Box>
  );
};

export default GestaoMotoristas;
