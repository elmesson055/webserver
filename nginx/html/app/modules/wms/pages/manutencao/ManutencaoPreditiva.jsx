import React, { useState, useEffect } from 'react';
import {
  Grid,
  Card,
  CardContent,
  Typography,
  Box,
  Button,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Paper,
  Chip,
  Alert,
  CircularProgress,
  LinearProgress
} from '@mui/material';
import {
  Warning as WarningIcon,
  CheckCircle as CheckIcon,
  Error as ErrorIcon,
  Timeline as TimelineIcon
} from '@mui/icons-material';
import {
  LineChart,
  Line,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  Legend,
  ResponsiveContainer
} from 'recharts';

const ManutencaoPreditiva = () => {
  const [loading, setLoading] = useState(true);
  const [equipamentos, setEquipamentos] = useState([]);
  const [alertas, setAlertas] = useState([]);
  const [metricas, setMetricas] = useState(null);
  const [historico, setHistorico] = useState([]);
  const [erro, setErro] = useState(null);

  useEffect(() => {
    carregarDados();
    const interval = setInterval(carregarDados, 60000); // Atualiza a cada minuto
    return () => clearInterval(interval);
  }, []);

  const carregarDados = async () => {
    try {
      const [equipamentosRes, alertasRes, metricasRes, historicoRes] = await Promise.all([
        fetch('/api/v1/manutencao/equipamentos'),
        fetch('/api/v1/manutencao/alertas'),
        fetch('/api/v1/manutencao/metricas'),
        fetch('/api/v1/manutencao/historico')
      ]);

      const equipamentosData = await equipamentosRes.json();
      const alertasData = await alertasRes.json();
      const metricasData = await metricasRes.json();
      const historicoData = await historicoRes.json();

      setEquipamentos(equipamentosData);
      setAlertas(alertasData);
      setMetricas(metricasData);
      setHistorico(historicoData);
    } catch (error) {
      setErro('Erro ao carregar dados');
      console.error(error);
    } finally {
      setLoading(false);
    }
  };

  const getStatusColor = (status) => {
    switch (status) {
      case 'Crítico':
        return 'error';
      case 'Alerta':
        return 'warning';
      case 'Normal':
        return 'success';
      default:
        return 'default';
    }
  };

  const getStatusIcon = (status) => {
    switch (status) {
      case 'Crítico':
        return <ErrorIcon />;
      case 'Alerta':
        return <WarningIcon />;
      case 'Normal':
        return <CheckIcon />;
      default:
        return null;
    }
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
      <Typography variant="h4" gutterBottom>
        Manutenção Preditiva
      </Typography>

      {erro && (
        <Alert severity="error" sx={{ mb: 2 }}>
          {erro}
        </Alert>
      )}

      <Grid container spacing={3}>
        {/* Métricas */}
        {metricas && (
          <Grid item xs={12}>
            <Grid container spacing={2}>
              <Grid item xs={12} md={3}>
                <Card>
                  <CardContent>
                    <Typography color="textSecondary" gutterBottom>
                      Disponibilidade
                    </Typography>
                    <Typography variant="h4">
                      {metricas.disponibilidade}%
                    </Typography>
                  </CardContent>
                </Card>
              </Grid>
              <Grid item xs={12} md={3}>
                <Card>
                  <CardContent>
                    <Typography color="textSecondary" gutterBottom>
                      MTBF
                    </Typography>
                    <Typography variant="h4">
                      {metricas.mtbf}h
                    </Typography>
                  </CardContent>
                </Card>
              </Grid>
              <Grid item xs={12} md={3}>
                <Card>
                  <CardContent>
                    <Typography color="textSecondary" gutterBottom>
                      MTTR
                    </Typography>
                    <Typography variant="h4">
                      {metricas.mttr}h
                    </Typography>
                  </CardContent>
                </Card>
              </Grid>
              <Grid item xs={12} md={3}>
                <Card>
                  <CardContent>
                    <Typography color="textSecondary" gutterBottom>
                      Custo Evitado
                    </Typography>
                    <Typography variant="h4">
                      R$ {metricas.custoEvitado}
                    </Typography>
                  </CardContent>
                </Card>
              </Grid>
            </Grid>
          </Grid>
        )}

        {/* Alertas */}
        <Grid item xs={12}>
          <Card>
            <CardContent>
              <Typography variant="h6" gutterBottom>
                Alertas Ativos
              </Typography>
              <TableContainer>
                <Table>
                  <TableHead>
                    <TableRow>
                      <TableCell>Equipamento</TableCell>
                      <TableCell>Tipo</TableCell>
                      <TableCell>Descrição</TableCell>
                      <TableCell>Probabilidade</TableCell>
                      <TableCell>Impacto</TableCell>
                      <TableCell>Status</TableCell>
                    </TableRow>
                  </TableHead>
                  <TableBody>
                    {alertas.map((alerta) => (
                      <TableRow key={alerta.id}>
                        <TableCell>{alerta.equipamento}</TableCell>
                        <TableCell>{alerta.tipo}</TableCell>
                        <TableCell>{alerta.descricao}</TableCell>
                        <TableCell>
                          <Box display="flex" alignItems="center">
                            <LinearProgress
                              variant="determinate"
                              value={alerta.probabilidade}
                              sx={{ flexGrow: 1, mr: 1 }}
                            />
                            {alerta.probabilidade}%
                          </Box>
                        </TableCell>
                        <TableCell>
                          <Chip
                            label={alerta.impacto}
                            color={getStatusColor(alerta.impacto)}
                          />
                        </TableCell>
                        <TableCell>
                          <Chip
                            icon={getStatusIcon(alerta.status)}
                            label={alerta.status}
                            color={getStatusColor(alerta.status)}
                          />
                        </TableCell>
                      </TableRow>
                    ))}
                  </TableBody>
                </Table>
              </TableContainer>
            </CardContent>
          </Card>
        </Grid>

        {/* Gráfico de Tendências */}
        <Grid item xs={12}>
          <Card>
            <CardContent>
              <Typography variant="h6" gutterBottom>
                Tendências de Saúde dos Equipamentos
              </Typography>
              <ResponsiveContainer width="100%" height={400}>
                <LineChart
                  data={historico}
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
                  <Line
                    type="monotone"
                    dataKey="saudeGeral"
                    stroke="#8884d8"
                    name="Saúde Geral"
                  />
                  <Line
                    type="monotone"
                    dataKey="temperatura"
                    stroke="#82ca9d"
                    name="Temperatura"
                  />
                  <Line
                    type="monotone"
                    dataKey="vibracao"
                    stroke="#ffc658"
                    name="Vibração"
                  />
                </LineChart>
              </ResponsiveContainer>
            </CardContent>
          </Card>
        </Grid>

        {/* Lista de Equipamentos */}
        <Grid item xs={12}>
          <Card>
            <CardContent>
              <Typography variant="h6" gutterBottom>
                Status dos Equipamentos
              </Typography>
              <TableContainer>
                <Table>
                  <TableHead>
                    <TableRow>
                      <TableCell>Equipamento</TableCell>
                      <TableCell>Última Manutenção</TableCell>
                      <TableCell>Próxima Manutenção</TableCell>
                      <TableCell>Saúde</TableCell>
                      <TableCell>Status</TableCell>
                      <TableCell>Ações</TableCell>
                    </TableRow>
                  </TableHead>
                  <TableBody>
                    {equipamentos.map((equip) => (
                      <TableRow key={equip.id}>
                        <TableCell>{equip.nome}</TableCell>
                        <TableCell>{equip.ultimaManutencao}</TableCell>
                        <TableCell>{equip.proximaManutencao}</TableCell>
                        <TableCell>
                          <Box display="flex" alignItems="center">
                            <LinearProgress
                              variant="determinate"
                              value={equip.saude}
                              sx={{ flexGrow: 1, mr: 1 }}
                            />
                            {equip.saude}%
                          </Box>
                        </TableCell>
                        <TableCell>
                          <Chip
                            icon={getStatusIcon(equip.status)}
                            label={equip.status}
                            color={getStatusColor(equip.status)}
                          />
                        </TableCell>
                        <TableCell>
                          <Button
                            variant="outlined"
                            size="small"
                            startIcon={<TimelineIcon />}
                          >
                            Detalhes
                          </Button>
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

export default ManutencaoPreditiva;
