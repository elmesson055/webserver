import React, { useState, useEffect } from 'react';
import {
  Box,
  Card,
  CardContent,
  Grid,
  Typography,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Paper,
  FormControl,
  InputLabel,
  Select,
  MenuItem,
  LinearProgress,
  Tooltip,
  IconButton,
  Tabs,
  Tab,
  CircularProgress,
  Rating
} from '@mui/material';
import {
  Speed as SpeedIcon,
  LocalShipping,
  Warning,
  CheckCircle,
  Timeline as TimelineIcon,
  Assessment,
  Person,
  DirectionsCar,
  LocalGasStation,
  AttachMoney,
  Star
} from '@mui/icons-material';
import {
  LineChart,
  Line,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip as ChartTooltip,
  Legend,
  ResponsiveContainer,
  PieChart,
  Pie,
  Cell
} from 'recharts';

const MonitoramentoDesempenho = () => {
  const [loading, setLoading] = useState(true);
  const [tabValue, setTabValue] = useState(0);
  const [metricas, setMetricas] = useState({
    pontualidade: 0,
    satisfacaoCliente: 0,
    ocorrencias: 0,
    consumoCombustivel: 0,
    custoKm: 0,
    tempoMedioEntrega: 0
  });
  const [transportadoras, setTransportadoras] = useState([]);
  const [veiculos, setVeiculos] = useState([]);
  const [motoristas, setMotoristas] = useState([]);
  const [filtros, setFiltros] = useState({
    periodo: '30',
    transportadora: 'todas',
    regiao: 'todas'
  });

  useEffect(() => {
    carregarDados();
  }, [filtros]);

  const carregarDados = async () => {
    setLoading(true);
    try {
      const [
        metricasRes,
        transportadorasRes,
        veiculosRes,
        motoristasRes
      ] = await Promise.all([
        fetch('/api/v1/tms/desempenho/metricas'),
        fetch('/api/v1/tms/desempenho/transportadoras'),
        fetch('/api/v1/tms/desempenho/veiculos'),
        fetch('/api/v1/tms/desempenho/motoristas')
      ]);

      const metricasData = await metricasRes.json();
      const transportadorasData = await transportadorasRes.json();
      const veiculosData = await veiculosRes.json();
      const motoristasData = await motoristasRes.json();

      setMetricas(metricasData);
      setTransportadoras(transportadorasData);
      setVeiculos(veiculosData);
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

  const renderKPIs = () => (
    <Grid container spacing={3} mb={3}>
      <Grid item xs={12} md={2}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <SpeedIcon color="primary" />
              <Typography variant="subtitle2" ml={1}>
                Pontualidade
              </Typography>
            </Box>
            <Box display="flex" alignItems="center">
              <CircularProgress
                variant="determinate"
                value={metricas.pontualidade}
                size={40}
                sx={{ mr: 1 }}
              />
              <Typography variant="h4">{metricas.pontualidade}%</Typography>
            </Box>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={2}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <Star color="primary" />
              <Typography variant="subtitle2" ml={1}>
                Satisfação
              </Typography>
            </Box>
            <Box display="flex" alignItems="center">
              <Rating value={metricas.satisfacaoCliente / 20} readOnly />
              <Typography variant="h4" ml={1}>
                {metricas.satisfacaoCliente}%
              </Typography>
            </Box>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={2}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <Warning color="error" />
              <Typography variant="subtitle2" ml={1}>
                Ocorrências
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.ocorrencias}</Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={2}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <LocalGasStation color="primary" />
              <Typography variant="subtitle2" ml={1}>
                Consumo Médio
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.consumoCombustivel} km/l</Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={2}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <AttachMoney color="primary" />
              <Typography variant="subtitle2" ml={1}>
                Custo por KM
              </Typography>
            </Box>
            <Typography variant="h4">R$ {metricas.custoKm}</Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={2}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <TimelineIcon color="primary" />
              <Typography variant="subtitle2" ml={1}>
                Tempo Médio
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.tempoMedioEntrega}h</Typography>
          </CardContent>
        </Card>
      </Grid>
    </Grid>
  );

  const renderFiltros = () => (
    <Paper sx={{ p: 2, mb: 3 }}>
      <Grid container spacing={2} alignItems="center">
        <Grid item xs={12} md={4}>
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
        <Grid item xs={12} md={4}>
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
        <Grid item xs={12} md={4}>
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
      </Grid>
    </Paper>
  );

  const renderTransportadoras = () => (
    <TableContainer component={Paper}>
      <Table>
        <TableHead>
          <TableRow>
            <TableCell>Transportadora</TableCell>
            <TableCell>Entregas</TableCell>
            <TableCell>Pontualidade</TableCell>
            <TableCell>Satisfação</TableCell>
            <TableCell>Ocorrências</TableCell>
            <TableCell>Custo Médio</TableCell>
            <TableCell>Performance</TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {transportadoras.map((transportadora) => (
            <TableRow key={transportadora.id}>
              <TableCell>{transportadora.nome}</TableCell>
              <TableCell>{transportadora.entregas}</TableCell>
              <TableCell>{transportadora.pontualidade}%</TableCell>
              <TableCell>
                <Rating value={transportadora.satisfacao / 20} readOnly size="small" />
              </TableCell>
              <TableCell>{transportadora.ocorrencias}</TableCell>
              <TableCell>R$ {transportadora.custoMedio}</TableCell>
              <TableCell>
                <Rating value={transportadora.performance / 20} readOnly size="small" />
              </TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>
    </TableContainer>
  );

  const renderVeiculos = () => (
    <TableContainer component={Paper}>
      <Table>
        <TableHead>
          <TableRow>
            <TableCell>Veículo</TableCell>
            <TableCell>Placa</TableCell>
            <TableCell>Km Rodados</TableCell>
            <TableCell>Consumo</TableCell>
            <TableCell>Manutenções</TableCell>
            <TableCell>Custo/Km</TableCell>
            <TableCell>Status</TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {veiculos.map((veiculo) => (
            <TableRow key={veiculo.id}>
              <TableCell>{veiculo.modelo}</TableCell>
              <TableCell>{veiculo.placa}</TableCell>
              <TableCell>{veiculo.kmRodados}</TableCell>
              <TableCell>{veiculo.consumo} km/l</TableCell>
              <TableCell>{veiculo.manutencoes}</TableCell>
              <TableCell>R$ {veiculo.custoKm}</TableCell>
              <TableCell>
                <Tooltip title={veiculo.status}>
                  <IconButton size="small" color={veiculo.status === 'Ativo' ? 'success' : 'error'}>
                    {veiculo.status === 'Ativo' ? <CheckCircle /> : <Warning />}
                  </IconButton>
                </Tooltip>
              </TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>
    </TableContainer>
  );

  const renderMotoristas = () => (
    <TableContainer component={Paper}>
      <Table>
        <TableHead>
          <TableRow>
            <TableCell>Motorista</TableCell>
            <TableCell>Entregas</TableCell>
            <TableCell>Pontualidade</TableCell>
            <TableCell>Avaliação</TableCell>
            <TableCell>Ocorrências</TableCell>
            <TableCell>Consumo Médio</TableCell>
            <TableCell>Performance</TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {motoristas.map((motorista) => (
            <TableRow key={motorista.id}>
              <TableCell>{motorista.nome}</TableCell>
              <TableCell>{motorista.entregas}</TableCell>
              <TableCell>{motorista.pontualidade}%</TableCell>
              <TableCell>
                <Rating value={motorista.avaliacao / 20} readOnly size="small" />
              </TableCell>
              <TableCell>{motorista.ocorrencias}</TableCell>
              <TableCell>{motorista.consumoMedio} km/l</TableCell>
              <TableCell>
                <Rating value={motorista.performance / 20} readOnly size="small" />
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
        Monitoramento de Desempenho
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
          <Tab icon={<LocalShipping />} label="Transportadoras" />
          <Tab icon={<DirectionsCar />} label="Veículos" />
          <Tab icon={<Person />} label="Motoristas" />
          <Tab icon={<Assessment />} label="Relatórios" />
        </Tabs>
        <Box p={3}>
          {tabValue === 0 && renderTransportadoras()}
          {tabValue === 1 && renderVeiculos()}
          {tabValue === 2 && renderMotoristas()}
          {tabValue === 3 && (
            <Typography>Área de relatórios em desenvolvimento</Typography>
          )}
        </Box>
      </Paper>
    </Box>
  );
};

export default MonitoramentoDesempenho;
