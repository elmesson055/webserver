import React, { useState, useEffect } from 'react';
import {
  Box,
  Grid,
  Card,
  CardContent,
  Typography,
  Button,
  TextField,
  IconButton,
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
  Chip,
  MenuItem,
  LinearProgress
} from '@mui/material';
import {
  Refresh,
  Upload,
  Download,
  CheckCircle,
  Warning,
  Error,
  AttachMoney,
  Search,
  Assignment
} from '@mui/icons-material';
import { AdapterDateFns } from '@mui/x-date-pickers/AdapterDateFns';
import { LocalizationProvider, DatePicker } from '@mui/x-date-pickers';
import ptBR from 'date-fns/locale/pt-BR';

const ConciliacaoFrete = () => {
  const [faturas, setFaturas] = useState([]);
  const [dialogOpen, setDialogOpen] = useState(false);
  const [selectedFatura, setSelectedFatura] = useState(null);
  const [loading, setLoading] = useState(false);
  const [filtros, setFiltros] = useState({
    dataInicio: null,
    dataFim: null,
    transportadora: '',
    status: ''
  });
  const [metricas, setMetricas] = useState({
    totalFaturas: 0,
    valorTotal: 0,
    divergencias: 0,
    percentualConciliado: 0
  });

  useEffect(() => {
    carregarDados();
  }, [filtros]);

  const carregarDados = async () => {
    setLoading(true);
    try {
      const [faturasRes, metricasRes] = await Promise.all([
        fetch('/api/v1/tms/conciliacao/faturas', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(filtros)
        }),
        fetch('/api/v1/tms/conciliacao/metricas')
      ]);

      const [faturasData, metricasData] = await Promise.all([
        faturasRes.json(),
        metricasRes.json()
      ]);

      setFaturas(faturasData);
      setMetricas(metricasData);
    } catch (error) {
      console.error('Erro ao carregar dados:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleDialogOpen = (fatura = null) => {
    setSelectedFatura(fatura);
    setDialogOpen(true);
  };

  const handleDialogClose = () => {
    setSelectedFatura(null);
    setDialogOpen(false);
  };

  const handleUploadFatura = async (event) => {
    const file = event.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('file', file);

    try {
      await fetch('/api/v1/tms/conciliacao/upload', {
        method: 'POST',
        body: formData
      });
      carregarDados();
    } catch (error) {
      console.error('Erro ao fazer upload:', error);
    }
  };

  const getStatusColor = (status) => {
    switch (status.toLowerCase()) {
      case 'conciliado':
        return 'success';
      case 'divergente':
        return 'error';
      case 'pendente':
        return 'warning';
      default:
        return 'default';
    }
  };

  return (
    <Box p={3}>
      {/* Cabeçalho */}
      <Box display="flex" justifyContent="space-between" alignItems="center" mb={3}>
        <Typography variant="h4">Conciliação de Fretes</Typography>
        <Box>
          <input
            type="file"
            id="upload-fatura"
            style={{ display: 'none' }}
            onChange={handleUploadFatura}
          />
          <label htmlFor="upload-fatura">
            <Button
              variant="contained"
              color="primary"
              component="span"
              startIcon={<Upload />}
              sx={{ mr: 1 }}
            >
              Importar Fatura
            </Button>
          </label>
          <Button
            variant="outlined"
            startIcon={<Refresh />}
            onClick={carregarDados}
          >
            Atualizar
          </Button>
        </Box>
      </Box>

      {/* Métricas */}
      <Grid container spacing={2} mb={3}>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Box display="flex" alignItems="center" mb={1}>
                <Assignment color="primary" />
                <Typography variant="subtitle1" ml={1}>
                  Total de Faturas
                </Typography>
              </Box>
              <Typography variant="h4">{metricas.totalFaturas}</Typography>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Box display="flex" alignItems="center" mb={1}>
                <AttachMoney color="primary" />
                <Typography variant="subtitle1" ml={1}>
                  Valor Total
                </Typography>
              </Box>
              <Typography variant="h4">
                R$ {metricas.valorTotal.toLocaleString()}
              </Typography>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Box display="flex" alignItems="center" mb={1}>
                <Warning color="error" />
                <Typography variant="subtitle1" ml={1}>
                  Divergências
                </Typography>
              </Box>
              <Typography variant="h4">{metricas.divergencias}</Typography>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Box display="flex" alignItems="center" mb={1}>
                <CheckCircle color="success" />
                <Typography variant="subtitle1" ml={1}>
                  Conciliado
                </Typography>
              </Box>
              <Typography variant="h4">{metricas.percentualConciliado}%</Typography>
              <LinearProgress
                variant="determinate"
                value={metricas.percentualConciliado}
                color="success"
                sx={{ mt: 1 }}
              />
            </CardContent>
          </Card>
        </Grid>
      </Grid>

      {/* Filtros */}
      <Card sx={{ mb: 3 }}>
        <CardContent>
          <Grid container spacing={2} alignItems="center">
            <Grid item xs={12} md={3}>
              <LocalizationProvider dateAdapter={AdapterDateFns} locale={ptBR}>
                <DatePicker
                  label="Data Início"
                  value={filtros.dataInicio}
                  onChange={(date) =>
                    setFiltros({ ...filtros, dataInicio: date })
                  }
                  renderInput={(params) => <TextField {...params} fullWidth />}
                />
              </LocalizationProvider>
            </Grid>
            <Grid item xs={12} md={3}>
              <LocalizationProvider dateAdapter={AdapterDateFns} locale={ptBR}>
                <DatePicker
                  label="Data Fim"
                  value={filtros.dataFim}
                  onChange={(date) => setFiltros({ ...filtros, dataFim: date })}
                  renderInput={(params) => <TextField {...params} fullWidth />}
                />
              </LocalizationProvider>
            </Grid>
            <Grid item xs={12} md={3}>
              <TextField
                select
                fullWidth
                label="Transportadora"
                value={filtros.transportadora}
                onChange={(e) =>
                  setFiltros({ ...filtros, transportadora: e.target.value })
                }
              >
                <MenuItem value="">Todas</MenuItem>
                {/* Lista de transportadoras */}
              </TextField>
            </Grid>
            <Grid item xs={12} md={3}>
              <TextField
                select
                fullWidth
                label="Status"
                value={filtros.status}
                onChange={(e) =>
                  setFiltros({ ...filtros, status: e.target.value })
                }
              >
                <MenuItem value="">Todos</MenuItem>
                <MenuItem value="conciliado">Conciliado</MenuItem>
                <MenuItem value="divergente">Divergente</MenuItem>
                <MenuItem value="pendente">Pendente</MenuItem>
              </TextField>
            </Grid>
          </Grid>
        </CardContent>
      </Card>

      {/* Lista de Faturas */}
      <TableContainer component={Paper}>
        <Table>
          <TableHead>
            <TableRow>
              <TableCell>Número</TableCell>
              <TableCell>Transportadora</TableCell>
              <TableCell>Data</TableCell>
              <TableCell>Valor Fatura</TableCell>
              <TableCell>Valor Sistema</TableCell>
              <TableCell>Divergência</TableCell>
              <TableCell>Status</TableCell>
              <TableCell align="center">Ações</TableCell>
            </TableRow>
          </TableHead>
          <TableBody>
            {faturas.map((fatura) => (
              <TableRow key={fatura.id}>
                <TableCell>{fatura.numero}</TableCell>
                <TableCell>{fatura.transportadora}</TableCell>
                <TableCell>{fatura.data}</TableCell>
                <TableCell>R$ {fatura.valorFatura}</TableCell>
                <TableCell>R$ {fatura.valorSistema}</TableCell>
                <TableCell>
                  {fatura.divergencia > 0 && (
                    <Typography color="error">
                      R$ {fatura.divergencia}
                    </Typography>
                  )}
                </TableCell>
                <TableCell>
                  <Chip
                    label={fatura.status}
                    color={getStatusColor(fatura.status)}
                  />
                </TableCell>
                <TableCell align="center">
                  <IconButton
                    color="primary"
                    onClick={() => handleDialogOpen(fatura)}
                  >
                    <Search />
                  </IconButton>
                  <IconButton color="primary">
                    <Download />
                  </IconButton>
                </TableCell>
              </TableRow>
            ))}
          </TableBody>
        </Table>
      </TableContainer>

      {/* Dialog de Detalhes */}
      <Dialog open={dialogOpen} onClose={handleDialogClose} maxWidth="md" fullWidth>
        <DialogTitle>Detalhes da Fatura</DialogTitle>
        <DialogContent>
          {selectedFatura && (
            <Grid container spacing={2}>
              <Grid item xs={12} md={6}>
                <TextField
                  fullWidth
                  label="Número da Fatura"
                  value={selectedFatura.numero}
                  InputProps={{ readOnly: true }}
                />
              </Grid>
              <Grid item xs={12} md={6}>
                <TextField
                  fullWidth
                  label="Transportadora"
                  value={selectedFatura.transportadora}
                  InputProps={{ readOnly: true }}
                />
              </Grid>
              <Grid item xs={12}>
                <TableContainer component={Paper}>
                  <Table size="small">
                    <TableHead>
                      <TableRow>
                        <TableCell>CTe</TableCell>
                        <TableCell>Data</TableCell>
                        <TableCell>Valor Fatura</TableCell>
                        <TableCell>Valor Sistema</TableCell>
                        <TableCell>Divergência</TableCell>
                      </TableRow>
                    </TableHead>
                    <TableBody>
                      {selectedFatura.itens?.map((item) => (
                        <TableRow key={item.id}>
                          <TableCell>{item.cte}</TableCell>
                          <TableCell>{item.data}</TableCell>
                          <TableCell>R$ {item.valorFatura}</TableCell>
                          <TableCell>R$ {item.valorSistema}</TableCell>
                          <TableCell>
                            {item.divergencia > 0 && (
                              <Typography color="error">
                                R$ {item.divergencia}
                              </Typography>
                            )}
                          </TableCell>
                        </TableRow>
                      ))}
                    </TableBody>
                  </Table>
                </TableContainer>
              </Grid>
            </Grid>
          )}
        </DialogContent>
        <DialogActions>
          <Button onClick={handleDialogClose}>Fechar</Button>
          <Button variant="contained" color="primary">
            Exportar
          </Button>
        </DialogActions>
      </Dialog>
    </Box>
  );
};

export default ConciliacaoFrete;
