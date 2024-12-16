import React, { useState, useEffect } from 'react';
import {
  Box,
  Paper,
  Typography,
  Grid,
  Card,
  CardContent,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  FormControl,
  InputLabel,
  Select,
  MenuItem,
  TextField,
  Button,
  IconButton,
  Tooltip,
  LinearProgress,
  Alert
} from '@mui/material';
import {
  DatePicker,
  LocalizationProvider
} from '@mui/x-date-pickers';
import { AdapterDateFns } from '@mui/x-date-pickers/AdapterDateFns';
import ptBR from 'date-fns/locale/pt-BR';
import {
  Timeline as TimelineIcon,
  Speed as SpeedIcon,
  TrendingUp as TrendingUpIcon,
  Warning as WarningIcon,
  Download as DownloadIcon,
  Print as PrintIcon
} from '@mui/icons-material';
import {
  LineChart,
  Line,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip as RechartsTooltip,
  Legend,
  ResponsiveContainer
} from 'recharts';

const AnaliseDesempenho = () => {
  const [loading, setLoading] = useState(false);
  const [filtros, setFiltros] = useState({
    dataInicio: new Date(new Date().setMonth(new Date().getMonth() - 1)),
    dataFim: new Date(),
    tipoDocumento: 'todos',
    servico: 'todos'
  });

  const [dados, setDados] = useState({
    resumo: {
      tempoMedioResposta: 0,
      taxaSucesso: 0,
      taxaRejeicao: 0,
      totalRequisicoes: 0
    },
    desempenho: {
      tempoResposta: [],
      taxaSucesso: [],
      erros: []
    },
    servicos: {
      disponibilidade: [],
      ocorrencias: []
    }
  });

  useEffect(() => {
    carregarDados();
  }, [filtros]);

  const carregarDados = async () => {
    setLoading(true);
    try {
      // Implementar carregamento de dados
      const dadosCarregados = {
        resumo: {
          tempoMedioResposta: 850,
          taxaSucesso: 98.5,
          taxaRejeicao: 1.5,
          totalRequisicoes: 5000
        },
        desempenho: {
          tempoResposta: [
            { data: '01/12', tempo: 800 },
            { data: '02/12', tempo: 850 },
            { data: '03/12', tempo: 900 }
            // ... mais dados
          ],
          taxaSucesso: [
            { data: '01/12', taxa: 98 },
            { data: '02/12', taxa: 99 },
            { data: '03/12', taxa: 98.5 }
            // ... mais dados
          ],
          erros: [
            {
              codigo: 'E001',
              descricao: 'Erro de validação',
              ocorrencias: 50,
              ultimaOcorrencia: '2023-12-01T10:00:00'
            }
            // ... mais erros
          ]
        },
        servicos: {
          disponibilidade: [
            { servico: 'Autorização', disponibilidade: 99.9 },
            { servico: 'Consulta', disponibilidade: 99.8 },
            { servico: 'Cancelamento', disponibilidade: 99.7 }
          ],
          ocorrencias: [
            {
              data: '2023-12-01T09:00:00',
              servico: 'Autorização',
              tipo: 'Indisponibilidade',
              duracao: 15
            }
            // ... mais ocorrências
          ]
        }
      };

      setDados(dadosCarregados);
    } catch (error) {
      console.error('Erro ao carregar dados:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleFiltroChange = (campo, valor) => {
    setFiltros(prev => ({
      ...prev,
      [campo]: valor
    }));
  };

  const renderFiltros = () => (
    <Grid container spacing={2} sx={{ mb: 3 }}>
      <Grid item xs={12} md={3}>
        <LocalizationProvider dateAdapter={AdapterDateFns} adapterLocale={ptBR}>
          <DatePicker
            label="Data Início"
            value={filtros.dataInicio}
            onChange={(newValue) => handleFiltroChange('dataInicio', newValue)}
            renderInput={(params) => <TextField {...params} fullWidth />}
          />
        </LocalizationProvider>
      </Grid>
      <Grid item xs={12} md={3}>
        <LocalizationProvider dateAdapter={AdapterDateFns} adapterLocale={ptBR}>
          <DatePicker
            label="Data Fim"
            value={filtros.dataFim}
            onChange={(newValue) => handleFiltroChange('dataFim', newValue)}
            renderInput={(params) => <TextField {...params} fullWidth />}
          />
        </LocalizationProvider>
      </Grid>
      <Grid item xs={12} md={3}>
        <FormControl fullWidth>
          <InputLabel>Tipo de Documento</InputLabel>
          <Select
            value={filtros.tipoDocumento}
            onChange={(e) => handleFiltroChange('tipoDocumento', e.target.value)}
          >
            <MenuItem value="todos">Todos</MenuItem>
            <MenuItem value="cte">CT-e</MenuItem>
            <MenuItem value="mdfe">MDF-e</MenuItem>
            <MenuItem value="nfe">NF-e</MenuItem>
          </Select>
        </FormControl>
      </Grid>
      <Grid item xs={12} md={3}>
        <FormControl fullWidth>
          <InputLabel>Serviço</InputLabel>
          <Select
            value={filtros.servico}
            onChange={(e) => handleFiltroChange('servico', e.target.value)}
          >
            <MenuItem value="todos">Todos</MenuItem>
            <MenuItem value="autorizacao">Autorização</MenuItem>
            <MenuItem value="consulta">Consulta</MenuItem>
            <MenuItem value="cancelamento">Cancelamento</MenuItem>
          </Select>
        </FormControl>
      </Grid>
    </Grid>
  );

  const renderResumo = () => (
    <Grid container spacing={2} sx={{ mb: 3 }}>
      <Grid item xs={12} md={3}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <SpeedIcon color="primary" sx={{ mr: 1 }} />
              <Typography color="textSecondary">
                Tempo Médio de Resposta
              </Typography>
            </Box>
            <Typography variant="h4">
              {dados.resumo.tempoMedioResposta}ms
            </Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={3}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <TrendingUpIcon color="success" sx={{ mr: 1 }} />
              <Typography color="textSecondary">
                Taxa de Sucesso
              </Typography>
            </Box>
            <Typography variant="h4" color="success.main">
              {dados.resumo.taxaSucesso}%
            </Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={3}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <WarningIcon color="error" sx={{ mr: 1 }} />
              <Typography color="textSecondary">
                Taxa de Rejeição
              </Typography>
            </Box>
            <Typography variant="h4" color="error.main">
              {dados.resumo.taxaRejeicao}%
            </Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={3}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <TimelineIcon color="primary" sx={{ mr: 1 }} />
              <Typography color="textSecondary">
                Total de Requisições
              </Typography>
            </Box>
            <Typography variant="h4">
              {dados.resumo.totalRequisicoes}
            </Typography>
          </CardContent>
        </Card>
      </Grid>
    </Grid>
  );

  const renderGraficos = () => (
    <Grid container spacing={2} sx={{ mb: 3 }}>
      <Grid item xs={12}>
        <Paper sx={{ p: 2 }}>
          <Typography variant="h6" gutterBottom>
            Tempo de Resposta
          </Typography>
          <ResponsiveContainer width="100%" height={300}>
            <LineChart
              data={dados.desempenho.tempoResposta}
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
              <RechartsTooltip />
              <Legend />
              <Line
                type="monotone"
                dataKey="tempo"
                stroke="#8884d8"
                name="Tempo (ms)"
              />
            </LineChart>
          </ResponsiveContainer>
        </Paper>
      </Grid>
    </Grid>
  );

  const renderErros = () => (
    <Paper sx={{ p: 2, mb: 3 }}>
      <Typography variant="h6" gutterBottom>
        Principais Erros
      </Typography>
      <TableContainer>
        <Table>
          <TableHead>
            <TableRow>
              <TableCell>Código</TableCell>
              <TableCell>Descrição</TableCell>
              <TableCell align="right">Ocorrências</TableCell>
              <TableCell>Última Ocorrência</TableCell>
            </TableRow>
          </TableHead>
          <TableBody>
            {dados.desempenho.erros.map((erro) => (
              <TableRow key={erro.codigo}>
                <TableCell>{erro.codigo}</TableCell>
                <TableCell>{erro.descricao}</TableCell>
                <TableCell align="right">{erro.ocorrencias}</TableCell>
                <TableCell>
                  {new Date(erro.ultimaOcorrencia).toLocaleString()}
                </TableCell>
              </TableRow>
            ))}
          </TableBody>
        </Table>
      </TableContainer>
    </Paper>
  );

  const renderDisponibilidade = () => (
    <Paper sx={{ p: 2 }}>
      <Typography variant="h6" gutterBottom>
        Disponibilidade dos Serviços
      </Typography>
      <TableContainer>
        <Table>
          <TableHead>
            <TableRow>
              <TableCell>Serviço</TableCell>
              <TableCell align="right">Disponibilidade</TableCell>
              <TableCell>Status</TableCell>
            </TableRow>
          </TableHead>
          <TableBody>
            {dados.servicos.disponibilidade.map((servico) => (
              <TableRow key={servico.servico}>
                <TableCell>{servico.servico}</TableCell>
                <TableCell align="right">{servico.disponibilidade}%</TableCell>
                <TableCell>
                  {servico.disponibilidade >= 99.5 ? (
                    <Alert severity="success" sx={{ py: 0 }}>
                      Normal
                    </Alert>
                  ) : servico.disponibilidade >= 98 ? (
                    <Alert severity="warning" sx={{ py: 0 }}>
                      Atenção
                    </Alert>
                  ) : (
                    <Alert severity="error" sx={{ py: 0 }}>
                      Crítico
                    </Alert>
                  )}
                </TableCell>
              </TableRow>
            ))}
          </TableBody>
        </Table>
      </TableContainer>
    </Paper>
  );

  if (loading) {
    return <LinearProgress />;
  }

  return (
    <Box p={3}>
      <Box display="flex" justifyContent="space-between" alignItems="center" mb={3}>
        <Typography variant="h4">
          Análise de Desempenho
        </Typography>
        <Box>
          <Tooltip title="Exportar">
            <IconButton>
              <DownloadIcon />
            </IconButton>
          </Tooltip>
          <Tooltip title="Imprimir">
            <IconButton>
              <PrintIcon />
            </IconButton>
          </Tooltip>
        </Box>
      </Box>

      {renderFiltros()}
      {renderResumo()}
      {renderGraficos()}
      {renderErros()}
      {renderDisponibilidade()}
    </Box>
  );
};

export default AnaliseDesempenho;
