import React, { useState, useEffect } from 'react';
import {
  Grid,
  Card,
  CardContent,
  Typography,
  Box,
  Button,
  IconButton,
  LinearProgress,
  Chip,
  Paper,
  TableContainer,
  Table,
  TableHead,
  TableRow,
  TableCell,
  TableBody,
  Rating
} from '@mui/material';
import {
  Timeline,
  TimelineItem,
  TimelineSeparator,
  TimelineConnector,
  TimelineContent,
  TimelineDot
} from '@mui/lab';
import {
  LocalShipping,
  Assessment,
  Warning,
  CheckCircle,
  Schedule,
  AttachMoney,
  Refresh,
  Speed,
  Assignment
} from '@mui/icons-material';
import {
  BarChart,
  Bar,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  Legend,
  LineChart,
  Line,
  PieChart,
  Pie,
  Cell
} from 'recharts';

const DashboardTMS = () => {
  const [loading, setLoading] = useState(true);
  const [metricas, setMetricas] = useState({
    totalPedidos: 0,
    variacaoPedidos: 0,
    pedidosAtrasados: 0,
    percentualAtrasados: 0,
    aumentoAtrasados: 0,
    entregasNoPrazo: 0,
    custoMedioFrete: 0,
    variacaoCusto: 0,
    tempoMedioEntrega: 0,
    variacaoTempo: 0,
    totalOcorrencias: 0,
    percentualOcorrencias: 0,
    variacaoOcorrencias: 0,
    ocupacaoVeiculos: 0,
    satisfacaoCliente: 0,
    totalAvaliacoes: 0,
    variacaoSatisfacao: 0
  });
  const [entregas, setEntregas] = useState([]);
  const [ocorrencias, setOcorrencias] = useState([]);
  const [performanceRegiao, setPerformanceRegiao] = useState([]);
  const [transportadoras, setTransportadoras] = useState([]);

  useEffect(() => {
    carregarDados();
    const interval = setInterval(carregarDados, 60000); // Atualiza a cada minuto
    return () => clearInterval(interval);
  }, []);

  const carregarDados = async () => {
    try {
      const [metricasRes, entregasRes, ocorrenciasRes, performanceRegiaoRes, transportadorasRes] = await Promise.all([
        fetch('/api/v1/tms/dashboard/metricas'),
        fetch('/api/v1/tms/dashboard/entregas'),
        fetch('/api/v1/tms/dashboard/ocorrencias'),
        fetch('/api/v1/tms/dashboard/performance-regiao'),
        fetch('/api/v1/tms/dashboard/transportadoras')
      ]);

      const metricasData = await metricasRes.json();
      const entregasData = await entregasRes.json();
      const ocorrenciasData = await ocorrenciasRes.json();
      const performanceRegiaoData = await performanceRegiaoRes.json();
      const transportadorasData = await transportadorasRes.json();

      setMetricas(metricasData);
      setEntregas(entregasData);
      setOcorrencias(ocorrenciasData);
      setPerformanceRegiao(performanceRegiaoData);
      setTransportadoras(transportadorasData);
    } catch (error) {
      console.error('Erro ao carregar dados:', error);
    } finally {
      setLoading(false);
    }
  };

  const getStatusColor = (status) => {
    switch (status.toLowerCase()) {
      case 'concluído':
        return 'success';
      case 'em andamento':
        return 'warning';
      case 'atrasado':
        return 'error';
      default:
        return 'default';
    }
  };

  return (
    <Box p={3}>
      <Box display="flex" justifyContent="space-between" alignItems="center" mb={3}>
        <Typography variant="h4">Dashboard TMS</Typography>
        <Button
          startIcon={<Refresh />}
          onClick={carregarDados}
          variant="outlined"
        >
          Atualizar
        </Button>
      </Box>

      {/* KPIs */}
      <Grid container spacing={3} mb={3}>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Box display="flex" alignItems="center" mb={1}>
                <LocalShipping color="primary" />
                <Typography variant="subtitle2" ml={1}>
                  Total de Pedidos
                </Typography>
              </Box>
              <Typography variant="h4">{metricas.totalPedidos}</Typography>
              <Box display="flex" justifyContent="space-between" mt={1}>
                <Typography variant="caption" color="text.secondary">
                  Últimos 30 dias
                </Typography>
                <Typography variant="caption" color={metricas.variacaoPedidos > 0 ? 'success.main' : 'error.main'}>
                  {metricas.variacaoPedidos}% vs mês anterior
                </Typography>
              </Box>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Box display="flex" alignItems="center" mb={1}>
                <Warning color="error" />
                <Typography variant="subtitle2" ml={1}>
                  Pedidos Atrasados
                </Typography>
              </Box>
              <Typography variant="h4">{metricas.pedidosAtrasados}</Typography>
              <Box display="flex" justifyContent="space-between" mt={1}>
                <Typography variant="caption" color="text.secondary">
                  {metricas.percentualAtrasados}% do total
                </Typography>
                <Typography variant="caption" color="error.main">
                  +{metricas.aumentoAtrasados}% vs ontem
                </Typography>
              </Box>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Box display="flex" alignItems="center" mb={1}>
                <CheckCircle color="success" />
                <Typography variant="subtitle2" ml={1}>
                  Entregas no Prazo
                </Typography>
              </Box>
              <Typography variant="h4">{metricas.entregasNoPrazo}%</Typography>
              <LinearProgress 
                variant="determinate" 
                value={metricas.entregasNoPrazo}
                color={metricas.entregasNoPrazo >= 95 ? 'success' : 'warning'}
                sx={{ mt: 1 }}
              />
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Box display="flex" alignItems="center" mb={1}>
                <AttachMoney color="primary" />
                <Typography variant="subtitle2" ml={1}>
                  Custo Médio Frete
                </Typography>
              </Box>
              <Typography variant="h4">R$ {metricas.custoMedioFrete}</Typography>
              <Box display="flex" justifyContent="space-between" mt={1}>
                <Typography variant="caption" color="text.secondary">
                  Por pedido
                </Typography>
                <Typography variant="caption" color={metricas.variacaoCusto < 0 ? 'success.main' : 'error.main'}>
                  {metricas.variacaoCusto}% vs mês anterior
                </Typography>
              </Box>
            </CardContent>
          </Card>
        </Grid>

        {/* Segunda linha de KPIs */}
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Box display="flex" alignItems="center" mb={1}>
                <Speed color="primary" />
                <Typography variant="subtitle2" ml={1}>
                  Tempo Médio Entrega
                </Typography>
              </Box>
              <Typography variant="h4">{metricas.tempoMedioEntrega}h</Typography>
              <Box display="flex" justifyContent="space-between" mt={1}>
                <Typography variant="caption" color="text.secondary">
                  Últimos 7 dias
                </Typography>
                <Typography variant="caption" color={metricas.variacaoTempo < 0 ? 'success.main' : 'error.main'}>
                  {metricas.variacaoTempo}% vs semana anterior
                </Typography>
              </Box>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Box display="flex" alignItems="center" mb={1}>
                <Assignment color="primary" />
                <Typography variant="subtitle2" ml={1}>
                  Ocorrências
                </Typography>
              </Box>
              <Typography variant="h4">{metricas.totalOcorrencias}</Typography>
              <Box display="flex" justifyContent="space-between" mt={1}>
                <Typography variant="caption" color="text.secondary">
                  {metricas.percentualOcorrencias}% dos pedidos
                </Typography>
                <Typography variant="caption" color={metricas.variacaoOcorrencias < 0 ? 'success.main' : 'error.main'}>
                  {metricas.variacaoOcorrencias}% vs mês anterior
                </Typography>
              </Box>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Box display="flex" alignItems="center" mb={1}>
                <LocalShipping color="primary" />
                <Typography variant="subtitle2" ml={1}>
                  Ocupação Veículos
                </Typography>
              </Box>
              <Typography variant="h4">{metricas.ocupacaoVeiculos}%</Typography>
              <LinearProgress 
                variant="determinate" 
                value={metricas.ocupacaoVeiculos}
                color={metricas.ocupacaoVeiculos >= 80 ? 'success' : 'warning'}
                sx={{ mt: 1 }}
              />
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Box display="flex" alignItems="center" mb={1}>
                <Assessment color="primary" />
                <Typography variant="subtitle2" ml={1}>
                  Satisfação Cliente
                </Typography>
              </Box>
              <Typography variant="h4">{metricas.satisfacaoCliente}/5</Typography>
              <Box display="flex" justifyContent="space-between" mt={1}>
                <Typography variant="caption" color="text.secondary">
                  {metricas.totalAvaliacoes} avaliações
                </Typography>
                <Typography variant="caption" color={metricas.variacaoSatisfacao >= 0 ? 'success.main' : 'error.main'}>
                  {metricas.variacaoSatisfacao}% vs mês anterior
                </Typography>
              </Box>
            </CardContent>
          </Card>
        </Grid>
      </Grid>

      {/* Gráficos e Timeline */}
      <Grid container spacing={3}>
        {/* Gráfico de Volume de Pedidos */}
        <Grid item xs={12} md={6}>
          <Card>
            <CardContent>
              <Typography variant="h6" gutterBottom>
                Volume de Pedidos por Status
              </Typography>
              <BarChart
                width={500}
                height={300}
                data={entregas}
                margin={{
                  top: 5,
                  right: 30,
                  left: 20,
                  bottom: 5,
                }}
              >
                <CartesianGrid strokeDasharray="3 3" />
                <XAxis dataKey="data" />
                <YAxis />
                <Tooltip />
                <Legend />
                <Bar dataKey="pendentes" name="Pendentes" fill="#FFA000" />
                <Bar dataKey="em_transito" name="Em Trânsito" fill="#2196F3" />
                <Bar dataKey="entregues" name="Entregues" fill="#4CAF50" />
                <Bar dataKey="atrasados" name="Atrasados" fill="#F44336" />
              </BarChart>
            </CardContent>
          </Card>
        </Grid>

        {/* Gráfico de Performance por Região */}
        <Grid item xs={12} md={6}>
          <Card>
            <CardContent>
              <Typography variant="h6" gutterBottom>
                Performance por Região
              </Typography>
              <BarChart
                width={500}
                height={300}
                data={performanceRegiao}
                margin={{
                  top: 5,
                  right: 30,
                  left: 20,
                  bottom: 5,
                }}
                layout="vertical"
              >
                <CartesianGrid strokeDasharray="3 3" />
                <XAxis type="number" />
                <YAxis dataKey="regiao" type="category" />
                <Tooltip />
                <Legend />
                <Bar dataKey="no_prazo" name="No Prazo %" fill="#4CAF50" />
                <Bar dataKey="custo_medio" name="Custo Médio" fill="#2196F3" />
              </BarChart>
            </CardContent>
          </Card>
        </Grid>

        {/* Gráfico de Ocorrências */}
        <Grid item xs={12} md={6}>
          <Card>
            <CardContent>
              <Typography variant="h6" gutterBottom>
                Top Ocorrências
              </Typography>
              <PieChart width={500} height={300}>
                <Pie
                  data={ocorrencias}
                  cx={250}
                  cy={150}
                  labelLine={false}
                  outerRadius={100}
                  fill="#8884d8"
                  dataKey="valor"
                >
                  {ocorrencias.map((entry, index) => (
                    <Cell key={`cell-${index}`} fill={entry.cor} />
                  ))}
                </Pie>
                <Tooltip />
                <Legend />
              </PieChart>
            </CardContent>
          </Card>
        </Grid>

        {/* Tabela de Transportadoras */}
        <Grid item xs={12} md={6}>
          <Card>
            <CardContent>
              <Typography variant="h6" gutterBottom>
                Performance Transportadoras
              </Typography>
              <TableContainer>
                <Table size="small">
                  <TableHead>
                    <TableRow>
                      <TableCell>Transportadora</TableCell>
                      <TableCell align="center">Entregas</TableCell>
                      <TableCell align="center">No Prazo</TableCell>
                      <TableCell align="center">Atrasos</TableCell>
                      <TableCell align="center">Avaliação</TableCell>
                    </TableRow>
                  </TableHead>
                  <TableBody>
                    {transportadoras.map((transp) => (
                      <TableRow key={transp.id}>
                        <TableCell>{transp.nome}</TableCell>
                        <TableCell align="center">{transp.totalEntregas}</TableCell>
                        <TableCell align="center">
                          <Chip
                            label={`${transp.percentualPrazo}%`}
                            color={transp.percentualPrazo >= 95 ? 'success' : 'warning'}
                            size="small"
                          />
                        </TableCell>
                        <TableCell align="center">{transp.atrasos}</TableCell>
                        <TableCell align="center">
                          <Rating value={transp.avaliacao} readOnly size="small" />
                        </TableCell>
                      </TableRow>
                    ))}
                  </TableBody>
                </Table>
              </TableContainer>
            </CardContent>
          </Card>
        </Grid>
      </Grid>
    </Box>
  );
};

export default DashboardTMS;
