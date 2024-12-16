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
  List,
  ListItem,
  ListItemText,
  ListItemIcon,
  ListItemSecondaryAction,
  Stepper,
  Step,
  StepLabel,
  StepContent
} from '@mui/material';
import {
  LocalShipping,
  Assignment,
  Warning,
  CheckCircle,
  Schedule,
  Print as PrintIcon,
  Edit as EditIcon,
  Map as MapIcon,
  Timeline as TimelineIcon,
  History as HistoryIcon,
  AttachFile as AttachFileIcon,
  LocationOn,
  Phone,
  Email,
  Person,
  CalendarToday,
  AccessTime,
  PhotoCamera,
  Comment,
  Star
} from '@mui/icons-material';
import { AdapterDateFns } from '@mui/x-date-pickers/AdapterDateFns';
import { LocalizationProvider, DatePicker, TimePicker } from '@mui/x-date-pickers';
import { ptBR } from 'date-fns/locale';
import { MapContainer, TileLayer, Marker, Popup } from 'react-leaflet';
import 'leaflet/dist/leaflet.css';

const Entregas = () => {
  const [loading, setLoading] = useState(true);
  const [entregas, setEntregas] = useState([]);
  const [selectedEntrega, setSelectedEntrega] = useState(null);
  const [openDialog, setOpenDialog] = useState(false);
  const [dialogType, setDialogType] = useState('');
  const [metricas, setMetricas] = useState({
    totalEntregas: 0,
    entregasHoje: 0,
    emTransito: 0,
    atrasadas: 0,
    concluidas: 0,
    ocorrencias: 0
  });
  const [filtros, setFiltros] = useState({
    status: 'todos',
    periodo: '7',
    transportadora: 'todas',
    regiao: 'todas'
  });
  const [openMap, setOpenMap] = useState(false);
  const [openTimeline, setOpenTimeline] = useState(false);
  const [openFotos, setOpenFotos] = useState(false);

  useEffect(() => {
    carregarDados();
  }, [filtros]);

  const carregarDados = async () => {
    setLoading(true);
    try {
      const [metricasRes, entregasRes] = await Promise.all([
        fetch('/api/v1/tms/entregas/metricas'),
        fetch('/api/v1/tms/entregas/lista')
      ]);

      const metricasData = await metricasRes.json();
      const entregasData = await entregasRes.json();

      setMetricas(metricasData);
      setEntregas(entregasData);
    } catch (error) {
      console.error('Erro ao carregar dados:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleOpenDialog = (type, entrega = null) => {
    setDialogType(type);
    setSelectedEntrega(entrega);
    setOpenDialog(true);
  };

  const handleCloseDialog = () => {
    setDialogType('');
    setSelectedEntrega(null);
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
              <Assignment color="primary" />
              <Typography variant="subtitle2" ml={1}>
                Total de Entregas
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.totalEntregas}</Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={2}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <CalendarToday color="primary" />
              <Typography variant="subtitle2" ml={1}>
                Entregas Hoje
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.entregasHoje}</Typography>
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
                Atrasadas
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.atrasadas}</Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={2}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <CheckCircle color="success" />
              <Typography variant="subtitle2" ml={1}>
                Concluídas
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.concluidas}</Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={2}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <Warning color="warning" />
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
      </Grid>
    </Paper>
  );

  const renderEntregas = () => (
    <TableContainer component={Paper}>
      <Table>
        <TableHead>
          <TableRow>
            <TableCell>ID</TableCell>
            <TableCell>Data/Hora</TableCell>
            <TableCell>Cliente</TableCell>
            <TableCell>Endereço</TableCell>
            <TableCell>Transportadora</TableCell>
            <TableCell>Status</TableCell>
            <TableCell>Ocorrências</TableCell>
            <TableCell align="center">Ações</TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {entregas.map((entrega) => (
            <TableRow key={entrega.id}>
              <TableCell>{entrega.id}</TableCell>
              <TableCell>{entrega.dataHora}</TableCell>
              <TableCell>{entrega.cliente}</TableCell>
              <TableCell>{entrega.endereco}</TableCell>
              <TableCell>{entrega.transportadora}</TableCell>
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
                    onClick={() => handleOpenDialog('editar', entrega)}
                  >
                    <EditIcon />
                  </IconButton>
                </Tooltip>
                <Tooltip title="Rastrear">
                  <IconButton
                    size="small"
                    onClick={() => {
                      setSelectedEntrega(entrega);
                      setOpenMap(true);
                    }}
                  >
                    <MapIcon />
                  </IconButton>
                </Tooltip>
                <Tooltip title="Timeline">
                  <IconButton
                    size="small"
                    onClick={() => {
                      setSelectedEntrega(entrega);
                      setOpenTimeline(true);
                    }}
                  >
                    <TimelineIcon />
                  </IconButton>
                </Tooltip>
                <Tooltip title="Fotos">
                  <IconButton
                    size="small"
                    onClick={() => {
                      setSelectedEntrega(entrega);
                      setOpenFotos(true);
                    }}
                  >
                    <PhotoCamera />
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

  // Modal de Edição de Entrega
  const renderEditDialog = () => (
    <Dialog open={openDialog} onClose={handleCloseDialog} maxWidth="md" fullWidth>
      <DialogTitle>Editar Entrega</DialogTitle>
      <DialogContent>
        <Box component="form" sx={{ mt: 2 }}>
          <Grid container spacing={2}>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Cliente"
                defaultValue={selectedEntrega?.cliente}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Transportadora"
                defaultValue={selectedEntrega?.transportadora}
              />
            </Grid>
            <Grid item xs={12}>
              <TextField
                fullWidth
                label="Endereço"
                defaultValue={selectedEntrega?.endereco}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <LocalizationProvider dateAdapter={AdapterDateFns} locale={ptBR}>
                <DatePicker
                  label="Data"
                  renderInput={(params) => <TextField {...params} fullWidth />}
                  value={selectedEntrega?.data || null}
                />
              </LocalizationProvider>
            </Grid>
            <Grid item xs={12} md={6}>
              <LocalizationProvider dateAdapter={AdapterDateFns} locale={ptBR}>
                <TimePicker
                  label="Hora"
                  renderInput={(params) => <TextField {...params} fullWidth />}
                  value={selectedEntrega?.hora || null}
                />
              </LocalizationProvider>
            </Grid>
            <Grid item xs={12}>
              <FormControl fullWidth>
                <InputLabel>Status</InputLabel>
                <Select defaultValue={selectedEntrega?.status || ''}>
                  <MenuItem value="pendente">Pendente</MenuItem>
                  <MenuItem value="em_transito">Em Trânsito</MenuItem>
                  <MenuItem value="entregue">Entregue</MenuItem>
                  <MenuItem value="atrasado">Atrasado</MenuItem>
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
                defaultValue={selectedEntrega?.observacoes}
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

  // Modal de Mapa
  const renderMapDialog = () => (
    <Dialog open={openMap} onClose={() => setOpenMap(false)} maxWidth="md" fullWidth>
      <DialogTitle>Rastreamento da Entrega</DialogTitle>
      <DialogContent>
        <Box sx={{ height: 400, width: '100%', position: 'relative' }}>
          <MapContainer
            center={[-23.5505, -46.6333]}
            zoom={13}
            style={{ height: '100%', width: '100%' }}
          >
            <TileLayer
              url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
              attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            />
            {selectedEntrega && (
              <Marker position={[selectedEntrega.lat, selectedEntrega.lng]}>
                <Popup>
                  <Typography variant="subtitle2">{selectedEntrega.cliente}</Typography>
                  <Typography variant="body2">{selectedEntrega.endereco}</Typography>
                </Popup>
              </Marker>
            )}
          </MapContainer>
        </Box>
      </DialogContent>
      <DialogActions>
        <Button onClick={() => setOpenMap(false)}>Fechar</Button>
      </DialogActions>
    </Dialog>
  );

  // Modal de Timeline
  const renderTimelineDialog = () => (
    <Dialog open={openTimeline} onClose={() => setOpenTimeline(false)} maxWidth="md" fullWidth>
      <DialogTitle>Timeline da Entrega</DialogTitle>
      <DialogContent>
        <Stepper orientation="vertical">
          {selectedEntrega?.timeline?.map((step, index) => (
            <Step key={index} active={true}>
              <StepLabel
                StepIconComponent={() => (
                  <Box
                    sx={{
                      width: 24,
                      height: 24,
                      borderRadius: '50%',
                      bgcolor: step.completed ? 'success.main' : 'grey.400',
                      display: 'flex',
                      alignItems: 'center',
                      justifyContent: 'center',
                    }}
                  >
                    <CheckCircle sx={{ color: 'white', fontSize: 16 }} />
                  </Box>
                )}
              >
                <Typography variant="subtitle2">{step.titulo}</Typography>
                <Typography variant="caption" color="text.secondary">
                  {step.dataHora}
                </Typography>
              </StepLabel>
              <StepContent>
                <Typography>{step.descricao}</Typography>
                {step.fotos && (
                  <Box sx={{ mt: 1, display: 'flex', gap: 1 }}>
                    {step.fotos.map((foto, idx) => (
                      <img
                        key={idx}
                        src={foto}
                        alt={`Foto ${idx + 1}`}
                        style={{ width: 100, height: 100, objectFit: 'cover' }}
                      />
                    ))}
                  </Box>
                )}
              </StepContent>
            </Step>
          ))}
        </Stepper>
      </DialogContent>
      <DialogActions>
        <Button onClick={() => setOpenTimeline(false)}>Fechar</Button>
      </DialogActions>
    </Dialog>
  );

  // Modal de Fotos
  const renderFotosDialog = () => (
    <Dialog open={openFotos} onClose={() => setOpenFotos(false)} maxWidth="md" fullWidth>
      <DialogTitle>Fotos da Entrega</DialogTitle>
      <DialogContent>
        <Grid container spacing={2}>
          {selectedEntrega?.fotos?.map((foto, index) => (
            <Grid item xs={12} sm={6} md={4} key={index}>
              <Card>
                <img
                  src={foto.url}
                  alt={foto.descricao}
                  style={{ width: '100%', height: 200, objectFit: 'cover' }}
                />
                <CardContent>
                  <Typography variant="subtitle2">{foto.descricao}</Typography>
                  <Typography variant="caption" color="text.secondary">
                    {foto.dataHora}
                  </Typography>
                </CardContent>
              </Card>
            </Grid>
          ))}
        </Grid>
      </DialogContent>
      <DialogActions>
        <Button onClick={() => setOpenFotos(false)}>Fechar</Button>
      </DialogActions>
    </Dialog>
  );

  if (loading) {
    return <LinearProgress />;
  }

  return (
    <Box p={3}>
      <Typography variant="h4" gutterBottom>
        Entregas
      </Typography>

      {renderKPIs()}
      {renderFiltros()}
      {renderEntregas()}
      {renderEditDialog()}
      {renderMapDialog()}
      {renderTimelineDialog()}
      {renderFotosDialog()}
    </Box>
  );
};

export default Entregas;
