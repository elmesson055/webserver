import React, { useState, useEffect } from 'react';
import {
  Grid,
  Card,
  CardContent,
  Typography,
  Box,
  Button,
  CircularProgress,
  Alert,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Paper,
  Chip
} from '@mui/material';
import {
  Timeline,
  TimelineItem,
  TimelineSeparator,
  TimelineConnector,
  TimelineContent,
  TimelineDot
} from '@mui/lab';

const OtimizacaoLayout = () => {
  const [loading, setLoading] = useState(false);
  const [sugestoes, setSugestoes] = useState([]);
  const [metricas, setMetricas] = useState(null);
  const [historico, setHistorico] = useState([]);
  const [erro, setErro] = useState(null);

  useEffect(() => {
    carregarDados();
  }, []);

  const carregarDados = async () => {
    try {
      const [sugestoesRes, metricasRes, historicoRes] = await Promise.all([
        fetch('/api/v1/layout/sugestoes'),
        fetch('/api/v1/layout/metricas'),
        fetch('/api/v1/layout/historico')
      ]);

      const sugestoesData = await sugestoesRes.json();
      const metricasData = await metricasRes.json();
      const historicoData = await historicoRes.json();

      setSugestoes(sugestoesData);
      setMetricas(metricasData);
      setHistorico(historicoData);
    } catch (error) {
      setErro('Erro ao carregar dados');
      console.error(error);
    }
  };

  const gerarNovaOtimizacao = async () => {
    setLoading(true);
    setErro(null);

    try {
      const response = await fetch('/api/v1/layout/otimizar', {
        method: 'POST'
      });
      const data = await response.json();
      await carregarDados();
    } catch (error) {
      setErro('Erro ao gerar otimização');
      console.error(error);
    } finally {
      setLoading(false);
    }
  };

  const aplicarSugestao = async (idSugestao) => {
    try {
      await fetch(`/api/v1/layout/aplicar/${idSugestao}`, {
        method: 'POST'
      });
      await carregarDados();
    } catch (error) {
      setErro('Erro ao aplicar sugestão');
      console.error(error);
    }
  };

  return (
    <Box p={3}>
      <Typography variant="h4" gutterBottom>
        Otimização de Layout
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
                      Redução de Percurso
                    </Typography>
                    <Typography variant="h4">
                      {metricas.reducaoPercurso}%
                    </Typography>
                  </CardContent>
                </Card>
              </Grid>
              <Grid item xs={12} md={3}>
                <Card>
                  <CardContent>
                    <Typography color="textSecondary" gutterBottom>
                      Economia de Tempo
                    </Typography>
                    <Typography variant="h4">
                      {metricas.economiaTempo}h
                    </Typography>
                  </CardContent>
                </Card>
              </Grid>
              <Grid item xs={12} md={3}>
                <Card>
                  <CardContent>
                    <Typography color="textSecondary" gutterBottom>
                      ROI Estimado
                    </Typography>
                    <Typography variant="h4">
                      R$ {metricas.roiEstimado}
                    </Typography>
                  </CardContent>
                </Card>
              </Grid>
              <Grid item xs={12} md={3}>
                <Card>
                  <CardContent>
                    <Typography color="textSecondary" gutterBottom>
                      Eficiência Atual
                    </Typography>
                    <Typography variant="h4">
                      {metricas.eficienciaAtual}%
                    </Typography>
                  </CardContent>
                </Card>
              </Grid>
            </Grid>
          </Grid>
        )}

        {/* Sugestões */}
        <Grid item xs={12}>
          <Card>
            <CardContent>
              <Box display="flex" justifyContent="space-between" alignItems="center" mb={2}>
                <Typography variant="h6">
                  Sugestões de Otimização
                </Typography>
                <Button
                  variant="contained"
                  color="primary"
                  onClick={gerarNovaOtimizacao}
                  disabled={loading}
                >
                  {loading ? <CircularProgress size={24} /> : 'Gerar Nova Otimização'}
                </Button>
              </Box>

              <TableContainer>
                <Table>
                  <TableHead>
                    <TableRow>
                      <TableCell>ID</TableCell>
                      <TableCell>Tipo</TableCell>
                      <TableCell>Descrição</TableCell>
                      <TableCell>Impacto</TableCell>
                      <TableCell>Status</TableCell>
                      <TableCell>Ações</TableCell>
                    </TableRow>
                  </TableHead>
                  <TableBody>
                    {sugestoes.map((sugestao) => (
                      <TableRow key={sugestao.id}>
                        <TableCell>{sugestao.id}</TableCell>
                        <TableCell>{sugestao.tipo}</TableCell>
                        <TableCell>{sugestao.descricao}</TableCell>
                        <TableCell>
                          <Chip
                            label={`${sugestao.impacto}%`}
                            color={
                              sugestao.impacto > 15
                                ? 'success'
                                : sugestao.impacto > 5
                                ? 'warning'
                                : 'default'
                            }
                          />
                        </TableCell>
                        <TableCell>
                          <Chip
                            label={sugestao.status}
                            color={
                              sugestao.status === 'Pendente'
                                ? 'warning'
                                : sugestao.status === 'Aplicado'
                                ? 'success'
                                : 'default'
                            }
                          />
                        </TableCell>
                        <TableCell>
                          <Button
                            variant="outlined"
                            size="small"
                            onClick={() => aplicarSugestao(sugestao.id)}
                            disabled={sugestao.status === 'Aplicado'}
                          >
                            Aplicar
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

        {/* Histórico */}
        <Grid item xs={12}>
          <Card>
            <CardContent>
              <Typography variant="h6" gutterBottom>
                Histórico de Otimizações
              </Typography>
              <Timeline>
                {historico.map((item) => (
                  <TimelineItem key={item.id}>
                    <TimelineSeparator>
                      <TimelineDot color="primary" />
                      <TimelineConnector />
                    </TimelineSeparator>
                    <TimelineContent>
                      <Typography variant="h6" component="span">
                        {item.data}
                      </Typography>
                      <Typography>{item.descricao}</Typography>
                      <Box mt={1}>
                        <Chip
                          size="small"
                          label={`Impacto: ${item.impacto}%`}
                          color="primary"
                        />
                        <Chip
                          size="small"
                          label={`ROI: R$ ${item.roi}`}
                          color="success"
                          sx={{ ml: 1 }}
                        />
                      </Box>
                    </TimelineContent>
                  </TimelineItem>
                ))}
              </Timeline>
            </CardContent>
          </Card>
        </Grid>
      </Grid>
    </Box>
  );
};

export default OtimizacaoLayout;
