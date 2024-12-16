import React, { useState, useEffect } from 'react';
import {
  Grid,
  Card,
  CardContent,
  Typography,
  Box,
  TextField,
  Button,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Paper,
  Alert,
  CircularProgress,
  Autocomplete
} from '@mui/material';
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
import DatePicker from '@mui/lab/DatePicker';

const PrevisaoDemanda = () => {
  const [loading, setLoading] = useState(false);
  const [produtos, setProdutos] = useState([]);
  const [previsoes, setPrevisoes] = useState([]);
  const [produtoSelecionado, setProdutoSelecionado] = useState(null);
  const [dataInicio, setDataInicio] = useState(null);
  const [dataFim, setDataFim] = useState(null);
  const [erro, setErro] = useState(null);

  useEffect(() => {
    carregarProdutos();
  }, []);

  const carregarProdutos = async () => {
    try {
      const response = await fetch('/api/v1/produtos');
      const data = await response.json();
      setProdutos(data);
    } catch (error) {
      setErro('Erro ao carregar produtos');
      console.error(error);
    }
  };

  const gerarPrevisao = async () => {
    if (!produtoSelecionado || !dataInicio || !dataFim) {
      setErro('Preencha todos os campos');
      return;
    }

    setLoading(true);
    setErro(null);

    try {
      const response = await fetch('/api/v1/previsao/demanda', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          idProduto: produtoSelecionado.id,
          dataInicio: dataInicio.toISOString(),
          dataFim: dataFim.toISOString(),
        }),
      });

      const data = await response.json();
      setPrevisoes(data);
    } catch (error) {
      setErro('Erro ao gerar previsão');
      console.error(error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <Box p={3}>
      <Typography variant="h4" gutterBottom>
        Previsão de Demanda
      </Typography>

      {erro && (
        <Alert severity="error" sx={{ mb: 2 }}>
          {erro}
        </Alert>
      )}

      <Card sx={{ mb: 3 }}>
        <CardContent>
          <Grid container spacing={2} alignItems="center">
            <Grid item xs={12} md={4}>
              <Autocomplete
                options={produtos}
                getOptionLabel={(option) => option.nome}
                value={produtoSelecionado}
                onChange={(event, newValue) => setProdutoSelecionado(newValue)}
                renderInput={(params) => (
                  <TextField
                    {...params}
                    label="Produto"
                    variant="outlined"
                    fullWidth
                  />
                )}
              />
            </Grid>
            <Grid item xs={12} md={3}>
              <DatePicker
                label="Data Início"
                value={dataInicio}
                onChange={setDataInicio}
                renderInput={(params) => <TextField {...params} fullWidth />}
              />
            </Grid>
            <Grid item xs={12} md={3}>
              <DatePicker
                label="Data Fim"
                value={dataFim}
                onChange={setDataFim}
                renderInput={(params) => <TextField {...params} fullWidth />}
              />
            </Grid>
            <Grid item xs={12} md={2}>
              <Button
                variant="contained"
                color="primary"
                onClick={gerarPrevisao}
                disabled={loading}
                fullWidth
              >
                {loading ? <CircularProgress size={24} /> : 'Gerar Previsão'}
              </Button>
            </Grid>
          </Grid>
        </CardContent>
      </Card>

      {previsoes.length > 0 && (
        <Grid container spacing={3}>
          <Grid item xs={12}>
            <Card>
              <CardContent>
                <Typography variant="h6" gutterBottom>
                  Gráfico de Previsão
                </Typography>
                <ResponsiveContainer width="100%" height={400}>
                  <LineChart
                    data={previsoes}
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
                      dataKey="previsao"
                      stroke="#8884d8"
                      name="Previsão"
                    />
                    <Line
                      type="monotone"
                      dataKey="realizado"
                      stroke="#82ca9d"
                      name="Realizado"
                    />
                    <Line
                      type="monotone"
                      dataKey="limite_superior"
                      stroke="#ff7300"
                      name="Limite Superior"
                      strokeDasharray="5 5"
                    />
                    <Line
                      type="monotone"
                      dataKey="limite_inferior"
                      stroke="#ff7300"
                      name="Limite Inferior"
                      strokeDasharray="5 5"
                    />
                  </LineChart>
                </ResponsiveContainer>
              </CardContent>
            </Card>
          </Grid>

          <Grid item xs={12}>
            <TableContainer component={Paper}>
              <Table>
                <TableHead>
                  <TableRow>
                    <TableCell>Data</TableCell>
                    <TableCell align="right">Previsão</TableCell>
                    <TableCell align="right">Realizado</TableCell>
                    <TableCell align="right">Erro (%)</TableCell>
                    <TableCell align="right">Intervalo de Confiança</TableCell>
                  </TableRow>
                </TableHead>
                <TableBody>
                  {previsoes.map((row) => (
                    <TableRow key={row.data}>
                      <TableCell component="th" scope="row">
                        {row.data}
                      </TableCell>
                      <TableCell align="right">{row.previsao}</TableCell>
                      <TableCell align="right">{row.realizado || '-'}</TableCell>
                      <TableCell align="right">
                        {row.erro_percentual ? `${row.erro_percentual}%` : '-'}
                      </TableCell>
                      <TableCell align="right">
                        {`${row.limite_inferior} - ${row.limite_superior}`}
                      </TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </TableContainer>
          </Grid>
        </Grid>
      )}
    </Box>
  );
};

export default PrevisaoDemanda;
