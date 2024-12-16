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
  LinearProgress
} from '@mui/material';
import {
  DatePicker,
  LocalizationProvider
} from '@mui/x-date-pickers';
import { AdapterDateFns } from '@mui/x-date-pickers/AdapterDateFns';
import ptBR from 'date-fns/locale/pt-BR';
import {
  BarChart as BarChartIcon,
  PieChart as PieChartIcon,
  Timeline as TimelineIcon,
  Download as DownloadIcon,
  Print as PrintIcon
} from '@mui/icons-material';
import {
  BarChart,
  Bar,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip as RechartsTooltip,
  Legend,
  PieChart,
  Pie,
  Cell
} from 'recharts';

const CORES = ['#0088FE', '#00C49F', '#FFBB28', '#FF8042'];

const RelatorioDocumentosFiscais = () => {
  const [loading, setLoading] = useState(false);
  const [filtros, setFiltros] = useState({
    dataInicio: new Date(new Date().setMonth(new Date().getMonth() - 1)),
    dataFim: new Date(),
    tipoDocumento: 'todos',
    status: 'todos'
  });

  const [dados, setDados] = useState({
    resumo: {
      total: 0,
      autorizados: 0,
      cancelados: 0,
      pendentes: 0,
      valorTotal: 0
    },
    porTipo: [],
    porStatus: [],
    porPeriodo: [],
    documentos: []
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
          total: 150,
          autorizados: 120,
          cancelados: 20,
          pendentes: 10,
          valorTotal: 250000.00
        },
        porTipo: [
          { tipo: 'CT-e', quantidade: 80, valor: 150000.00 },
          { tipo: 'MDF-e', quantidade: 40, valor: 75000.00 },
          { tipo: 'NF-e', quantidade: 30, valor: 25000.00 }
        ],
        porStatus: [
          { status: 'Autorizado', quantidade: 120 },
          { status: 'Cancelado', quantidade: 20 },
          { status: 'Pendente', quantidade: 10 }
        ],
        porPeriodo: [
          { data: '01/12', quantidade: 15, valor: 25000.00 },
          { data: '02/12', quantidade: 18, valor: 30000.00 },
          { data: '03/12', quantidade: 12, valor: 20000.00 }
          // ... mais dados
        ],
        documentos: [
          {
            chave: '12345678901234567890123456789012345678901234',
            tipo: 'CT-e',
            data: '2023-12-01',
            status: 'Autorizado',
            valor: 1500.00
          }
          // ... mais documentos
        ]
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

  const formatarValor = (valor) => {
    return new Intl.NumberFormat('pt-BR', {
      style: 'currency',
      currency: 'BRL'
    }).format(valor);
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
          <InputLabel>Status</InputLabel>
          <Select
            value={filtros.status}
            onChange={(e) => handleFiltroChange('status', e.target.value)}
          >
            <MenuItem value="todos">Todos</MenuItem>
            <MenuItem value="autorizado">Autorizado</MenuItem>
            <MenuItem value="cancelado">Cancelado</MenuItem>
            <MenuItem value="pendente">Pendente</MenuItem>
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
            <Typography color="textSecondary" gutterBottom>
              Total de Documentos
            </Typography>
            <Typography variant="h4">
              {dados.resumo.total}
            </Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={3}>
        <Card>
          <CardContent>
            <Typography color="textSecondary" gutterBottom>
              Autorizados
            </Typography>
            <Typography variant="h4" color="success.main">
              {dados.resumo.autorizados}
            </Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={3}>
        <Card>
          <CardContent>
            <Typography color="textSecondary" gutterBottom>
              Cancelados
            </Typography>
            <Typography variant="h4" color="error.main">
              {dados.resumo.cancelados}
            </Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={3}>
        <Card>
          <CardContent>
            <Typography color="textSecondary" gutterBottom>
              Valor Total
            </Typography>
            <Typography variant="h4">
              {formatarValor(dados.resumo.valorTotal)}
            </Typography>
          </CardContent>
        </Card>
      </Grid>
    </Grid>
  );

  const renderGraficos = () => (
    <Grid container spacing={2} sx={{ mb: 3 }}>
      <Grid item xs={12} md={8}>
        <Paper sx={{ p: 2 }}>
          <Typography variant="h6" gutterBottom>
            Evolução por Período
          </Typography>
          <BarChart
            width={700}
            height={300}
            data={dados.porPeriodo}
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
            <Bar dataKey="quantidade" fill="#8884d8" name="Quantidade" />
            <Bar dataKey="valor" fill="#82ca9d" name="Valor" />
          </BarChart>
        </Paper>
      </Grid>
      <Grid item xs={12} md={4}>
        <Paper sx={{ p: 2 }}>
          <Typography variant="h6" gutterBottom>
            Distribuição por Status
          </Typography>
          <PieChart width={300} height={300}>
            <Pie
              data={dados.porStatus}
              dataKey="quantidade"
              nameKey="status"
              cx="50%"
              cy="50%"
              outerRadius={100}
              fill="#8884d8"
              label
            >
              {dados.porStatus.map((entry, index) => (
                <Cell key={`cell-${index}`} fill={CORES[index % CORES.length]} />
              ))}
            </Pie>
            <RechartsTooltip />
          </PieChart>
        </Paper>
      </Grid>
    </Grid>
  );

  const renderTabela = () => (
    <TableContainer component={Paper}>
      <Table>
        <TableHead>
          <TableRow>
            <TableCell>Chave</TableCell>
            <TableCell>Tipo</TableCell>
            <TableCell>Data</TableCell>
            <TableCell>Status</TableCell>
            <TableCell align="right">Valor</TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {dados.documentos.map((doc) => (
            <TableRow key={doc.chave}>
              <TableCell>{doc.chave}</TableCell>
              <TableCell>{doc.tipo}</TableCell>
              <TableCell>{new Date(doc.data).toLocaleDateString()}</TableCell>
              <TableCell>{doc.status}</TableCell>
              <TableCell align="right">{formatarValor(doc.valor)}</TableCell>
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
      <Box display="flex" justifyContent="space-between" alignItems="center" mb={3}>
        <Typography variant="h4">
          Relatório de Documentos Fiscais
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
      {renderTabela()}
    </Box>
  );
};

export default RelatorioDocumentosFiscais;
