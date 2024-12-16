import React, { useState, useEffect } from 'react';
import {
  Box,
  Grid,
  Card,
  CardContent,
  Typography,
  IconButton,
  Button,
  Chip,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Paper,
  Dialog,
  DialogTitle,
  DialogContent,
  DialogActions,
  TextField,
  MenuItem
} from '@mui/material';
import {
  Refresh,
  Warning,
  LocalShipping,
  Timeline,
  LocationOn,
  Speed,
  Message,
  Phone
} from '@mui/icons-material';
import { MapContainer, TileLayer, Marker, Popup } from 'react-leaflet';

const TorreControle = () => {
  const [entregas, setEntregas] = useState([]);
  const [alertas, setAlertas] = useState([]);
  const [ocorrencias, setOcorrencias] = useState([]);
  const [dialogOpen, setDialogOpen] = useState(false);
  const [selectedEntrega, setSelectedEntrega] = useState(null);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    carregarDados();
    const interval = setInterval(carregarDados, 30000); // Atualiza a cada 30 segundos
    return () => clearInterval(interval);
  }, []);

  const carregarDados = async () => {
    setLoading(true);
    try {
      const [entregasRes, alertasRes, ocorrenciasRes] = await Promise.all([
        fetch('/api/v1/tms/torre-controle/entregas'),
        fetch('/api/v1/tms/torre-controle/alertas'),
        fetch('/api/v1/tms/torre-controle/ocorrencias')
      ]);

      const [entregasData, alertasData, ocorrenciasData] = await Promise.all([
        entregasRes.json(),
        alertasRes.json(),
        ocorrenciasRes.json()
      ]);

      setEntregas(entregasData);
      setAlertas(alertasData);
      setOcorrencias(ocorrenciasData);
    } catch (error) {
      console.error('Erro ao carregar dados:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleDialogOpen = (entrega) => {
    setSelectedEntrega(entrega);
    setDialogOpen(true);
  };

  const handleDialogClose = () => {
    setSelectedEntrega(null);
    setDialogOpen(false);
  };

  const getStatusColor = (status) => {
    switch (status.toLowerCase()) {
      case 'atrasado':
        return 'error';
      case 'risco':
        return 'warning';
      case 'normal':
        return 'success';
      default:
        return 'default';
    }
  };

  return (
    <Box p={3}>
      {/* Cabeçalho */}
      <Box display="flex" justifyContent="space-between" alignItems="center" mb={3}>
        <Typography variant="h4">Torre de Controle</Typography>
        <Button
          startIcon={<Refresh />}
          variant="outlined"
          onClick={carregarDados}
          disabled={loading}
        >
          Atualizar
        </Button>
      </Box>

      {/* KPIs */}
      <Grid container spacing={2} mb={3}>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Box display="flex" alignItems="center" mb={1}>
                <Warning color="error" />
                <Typography variant="subtitle1" ml={1}>
                  Alertas Críticos
                </Typography>
              </Box>
              <Typography variant="h4">{alertas.filter(a => a.nivel === 'critico').length}</Typography>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Box display="flex" alignItems="center" mb={1}>
                <LocalShipping color="primary" />
                <Typography variant="subtitle1" ml={1}>
                  Entregas em Rota
                </Typography>
              </Box>
              <Typography variant="h4">{entregas.filter(e => e.status === 'em_rota').length}</Typography>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Box display="flex" alignItems="center" mb={1}>
                <Speed color="warning" />
                <Typography variant="subtitle1" ml={1}>
                  Atrasos
                </Typography>
              </Box>
              <Typography variant="h4">{entregas.filter(e => e.status === 'atrasado').length}</Typography>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Box display="flex" alignItems="center" mb={1}>
                <Timeline color="success" />
                <Typography variant="subtitle1" ml={1}>
                  SLA Geral
                </Typography>
              </Box>
              <Typography variant="h4">95%</Typography>
            </CardContent>
          </Card>
        </Grid>
      </Grid>

      {/* Mapa e Alertas */}
      <Grid container spacing={2} mb={3}>
        <Grid item xs={12} md={8}>
          <Card sx={{ height: '400px' }}>
            <CardContent>
              <Typography variant="h6" gutterBottom>
                Monitoramento em Tempo Real
              </Typography>
              <Box sx={{ height: '350px' }}>
                <MapContainer
                  center={[-23.5505, -46.6333]}
                  zoom={13}
                  style={{ height: '100%', width: '100%' }}
                >
                  <TileLayer
                    url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                    attribution='&copy; OpenStreetMap contributors'
                  />
                  {entregas.map((entrega) => (
                    <Marker
                      key={entrega.id}
                      position={[entrega.lat, entrega.lng]}
                    >
                      <Popup>
                        <Typography variant="subtitle2">
                          {entrega.codigo} - {entrega.cliente}
                        </Typography>
                        <Typography variant="body2">
                          Status: {entrega.status}
                        </Typography>
                      </Popup>
                    </Marker>
                  ))}
                </MapContainer>
              </Box>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={4}>
          <Card sx={{ height: '400px', overflow: 'auto' }}>
            <CardContent>
              <Typography variant="h6" gutterBottom>
                Alertas
              </Typography>
              {alertas.map((alerta) => (
                <Card key={alerta.id} sx={{ mb: 1 }}>
                  <CardContent>
                    <Box display="flex" justifyContent="space-between" alignItems="center">
                      <Typography variant="subtitle2">
                        {alerta.titulo}
                      </Typography>
                      <Chip
                        label={alerta.nivel}
                        color={getStatusColor(alerta.nivel)}
                        size="small"
                      />
                    </Box>
                    <Typography variant="body2" color="text.secondary">
                      {alerta.descricao}
                    </Typography>
                    <Box display="flex" justifyContent="flex-end" mt={1}>
                      <IconButton size="small">
                        <Message />
                      </IconButton>
                      <IconButton size="small">
                        <Phone />
                      </IconButton>
                    </Box>
                  </CardContent>
                </Card>
              ))}
            </CardContent>
          </Card>
        </Grid>
      </Grid>

      {/* Lista de Entregas em Monitoramento */}
      <Card>
        <CardContent>
          <Typography variant="h6" gutterBottom>
            Entregas em Monitoramento
          </Typography>
          <TableContainer>
            <Table>
              <TableHead>
                <TableRow>
                  <TableCell>Código</TableCell>
                  <TableCell>Cliente</TableCell>
                  <TableCell>Transportadora</TableCell>
                  <TableCell>Status</TableCell>
                  <TableCell>Previsão</TableCell>
                  <TableCell>Ações</TableCell>
                </TableRow>
              </TableHead>
              <TableBody>
                {entregas.map((entrega) => (
                  <TableRow key={entrega.id}>
                    <TableCell>{entrega.codigo}</TableCell>
                    <TableCell>{entrega.cliente}</TableCell>
                    <TableCell>{entrega.transportadora}</TableCell>
                    <TableCell>
                      <Chip
                        label={entrega.status}
                        color={getStatusColor(entrega.status)}
                        size="small"
                      />
                    </TableCell>
                    <TableCell>{entrega.previsao}</TableCell>
                    <TableCell>
                      <IconButton
                        size="small"
                        onClick={() => handleDialogOpen(entrega)}
                      >
                        <Timeline />
                      </IconButton>
                      <IconButton size="small">
                        <LocationOn />
                      </IconButton>
                      <IconButton size="small">
                        <Message />
                      </IconButton>
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          </TableContainer>
        </CardContent>
      </Card>

      {/* Dialog de Detalhes */}
      <Dialog open={dialogOpen} onClose={handleDialogClose} maxWidth="md" fullWidth>
        <DialogTitle>Detalhes da Entrega</DialogTitle>
        <DialogContent>
          {selectedEntrega && (
            <Grid container spacing={2}>
              <Grid item xs={12} md={6}>
                <TextField
                  fullWidth
                  label="Código"
                  value={selectedEntrega.codigo}
                  InputProps={{ readOnly: true }}
                />
              </Grid>
              <Grid item xs={12} md={6}>
                <TextField
                  fullWidth
                  label="Cliente"
                  value={selectedEntrega.cliente}
                  InputProps={{ readOnly: true }}
                />
              </Grid>
              <Grid item xs={12}>
                <TextField
                  fullWidth
                  label="Status"
                  value={selectedEntrega.status}
                  InputProps={{ readOnly: true }}
                />
              </Grid>
              <Grid item xs={12}>
                <Typography variant="subtitle2" gutterBottom>
                  Timeline de Eventos
                </Typography>
                {/* Timeline de eventos aqui */}
              </Grid>
            </Grid>
          )}
        </DialogContent>
        <DialogActions>
          <Button onClick={handleDialogClose}>Fechar</Button>
        </DialogActions>
      </Dialog>
    </Box>
  );
};

export default TorreControle;
