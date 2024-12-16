import React, { useState, useEffect } from 'react';
import {
  Grid,
  Card,
  CardContent,
  Typography,
  Box,
  CircularProgress,
  Button,
  IconButton,
  Menu,
  MenuItem
} from '@mui/material';
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
import {
  Settings,
  Refresh,
  Download,
  Share
} from '@mui/icons-material';

const DashboardCliente = () => {
  const [loading, setLoading] = useState(true);
  const [kpis, setKpis] = useState({});
  const [movimentacoes, setMovimentacoes] = useState([]);
  const [ocupacao, setOcupacao] = useState([]);
  const [previsoes, setPrevisoes] = useState([]);
  const [anchorEl, setAnchorEl] = useState(null);

  useEffect(() => {
    carregarDados();
  }, []);

  const carregarDados = async () => {
    setLoading(true);
    try {
      // Carregar KPIs
      const responseKpis = await fetch('/api/v1/dashboard/kpis');
      const dataKpis = await responseKpis.json();
      setKpis(dataKpis);

      // Carregar Movimentações
      const responseMovimentacoes = await fetch('/api/v1/dashboard/movimentacoes');
      const dataMovimentacoes = await responseMovimentacoes.json();
      setMovimentacoes(dataMovimentacoes);

      // Carregar Ocupação
      const responseOcupacao = await fetch('/api/v1/dashboard/ocupacao');
      const dataOcupacao = await responseOcupacao.json();
      setOcupacao(dataOcupacao);

      // Carregar Previsões
      const responsePrevisoes = await fetch('/api/v1/dashboard/previsoes');
      const dataPrevisoes = await responsePrevisoes.json();
      setPrevisoes(dataPrevisoes);
    } catch (error) {
      console.error('Erro ao carregar dados:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleMenu = (event) => {
    setAnchorEl(event.currentTarget);
  };

  const handleClose = () => {
    setAnchorEl(null);
  };

  if (loading) {
    return (
      <Box display="flex" justifyContent="center" alignItems="center" minHeight="100vh">
        <CircularProgress />
      </Box>
    );
  }

  return (
    <Box p={3}>
      <Box display="flex" justifyContent="space-between" alignItems="center" mb={3}>
        <Typography variant="h4">Dashboard</Typography>
        <Box>
          <Button
            variant="contained"
            color="primary"
            startIcon={<Refresh />}
            onClick={carregarDados}
            sx={{ mr: 1 }}
          >
            Atualizar
          </Button>
          <IconButton onClick={handleMenu}>
            <Settings />
          </IconButton>
          <Menu
            anchorEl={anchorEl}
            open={Boolean(anchorEl)}
            onClose={handleClose}
          >
            <MenuItem onClick={handleClose}>Configurações</MenuItem>
            <MenuItem onClick={handleClose}>Exportar Dados</MenuItem>
            <MenuItem onClick={handleClose}>Compartilhar</MenuItem>
          </Menu>
        </Box>
      </Box>

      <Grid container spacing={3}>
        {/* KPIs */}
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Typography color="textSecondary" gutterBottom>
                Total em Estoque
              </Typography>
              <Typography variant="h4">
                {kpis.totalEstoque}
              </Typography>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Typography color="textSecondary" gutterBottom>
                Movimentações Hoje
              </Typography>
              <Typography variant="h4">
                {kpis.movimentacoesHoje}
              </Typography>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Typography color="textSecondary" gutterBottom>
                Ocupação
              </Typography>
              <Typography variant="h4">
                {kpis.ocupacaoPercentual}%
              </Typography>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Typography color="textSecondary" gutterBottom>
                SLA
              </Typography>
              <Typography variant="h4">
                {kpis.slaPercentual}%
              </Typography>
            </CardContent>
          </Card>
        </Grid>

        {/* Gráficos */}
        <Grid item xs={12} md={6}>
          <Card>
            <CardContent>
              <Typography variant="h6" gutterBottom>
                Movimentações por Período
              </Typography>
              <LineChart
                width={500}
                height={300}
                data={movimentacoes}
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
                <Line type="monotone" dataKey="entradas" stroke="#8884d8" />
                <Line type="monotone" dataKey="saidas" stroke="#82ca9d" />
              </LineChart>
            </CardContent>
          </Card>
        </Grid>

        <Grid item xs={12} md={6}>
          <Card>
            <CardContent>
              <Typography variant="h6" gutterBottom>
                Ocupação por Área
              </Typography>
              <PieChart width={500} height={300}>
                <Pie
                  data={ocupacao}
                  cx={200}
                  cy={150}
                  labelLine={false}
                  outerRadius={100}
                  fill="#8884d8"
                  dataKey="valor"
                >
                  {ocupacao.map((entry, index) => (
                    <Cell key={`cell-${index}`} fill={entry.cor} />
                  ))}
                </Pie>
                <Tooltip />
                <Legend />
              </PieChart>
            </CardContent>
          </Card>
        </Grid>

        <Grid item xs={12}>
          <Card>
            <CardContent>
              <Typography variant="h6" gutterBottom>
                Previsão de Demanda
              </Typography>
              <BarChart
                width={1100}
                height={300}
                data={previsoes}
                margin={{
                  top: 5,
                  right: 30,
                  left: 20,
                  bottom: 5,
                }}
              >
                <CartesianGrid strokeDasharray="3 3" />
                <XAxis dataKey="periodo" />
                <YAxis />
                <Tooltip />
                <Legend />
                <Bar dataKey="previsto" fill="#8884d8" />
                <Bar dataKey="realizado" fill="#82ca9d" />
              </BarChart>
            </CardContent>
          </Card>
        </Grid>
      </Grid>
    </Box>
  );
};

export default DashboardCliente;
