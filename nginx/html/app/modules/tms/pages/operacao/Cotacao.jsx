import React, { useState, useEffect } from 'react';
import {
  Box,
  Stepper,
  Step,
  StepLabel,
  Button,
  Typography,
  Card,
  CardContent,
  Grid,
  TextField,
  FormControl,
  InputLabel,
  Select,
  MenuItem,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Paper,
  Chip,
  IconButton,
  Dialog,
  DialogTitle,
  DialogContent,
  DialogActions,
  LinearProgress,
  Autocomplete
} from '@mui/material';
import {
  LocalShipping,
  LocationOn,
  Inventory,
  CompareArrows,
  Save as SaveIcon,
  Print as PrintIcon,
  History as HistoryIcon
} from '@mui/icons-material';

const steps = [
  'Dados da Carga',
  'Origem e Destino',
  'Serviços',
  'Resultado'
];

const Cotacao = () => {
  const [activeStep, setActiveStep] = useState(0);
  const [loading, setLoading] = useState(false);
  const [openHistorico, setOpenHistorico] = useState(false);
  const [cotacao, setCotacao] = useState({
    // Dados da Carga
    tipo_volume: '',
    quantidade: 1,
    peso: '',
    valor_mercadoria: '',
    dimensoes: {
      altura: '',
      largura: '',
      comprimento: ''
    },
    // Origem e Destino
    origem: {
      cep: '',
      cidade: '',
      estado: ''
    },
    destino: {
      cep: '',
      cidade: '',
      estado: ''
    },
    // Serviços
    tipo_servico: '',
    prazo_entrega: '',
    transportadora: ''
  });
  const [resultados, setResultados] = useState([]);
  const [historico, setHistorico] = useState([]);

  useEffect(() => {
    carregarHistorico();
  }, []);

  const carregarHistorico = async () => {
    try {
      const response = await fetch('/api/v1/tms/cotacao/historico');
      const data = await response.json();
      setHistorico(data);
    } catch (error) {
      console.error('Erro ao carregar histórico:', error);
    }
  };

  const handleNext = () => {
    if (activeStep === steps.length - 1) {
      handleSalvarCotacao();
    } else {
      setActiveStep((prevStep) => prevStep + 1);
      if (activeStep === 2) {
        buscarCotacoes();
      }
    }
  };

  const handleBack = () => {
    setActiveStep((prevStep) => prevStep - 1);
  };

  const handleReset = () => {
    setActiveStep(0);
    setCotacao({
      tipo_volume: '',
      quantidade: 1,
      peso: '',
      valor_mercadoria: '',
      dimensoes: {
        altura: '',
        largura: '',
        comprimento: ''
      },
      origem: {
        cep: '',
        cidade: '',
        estado: ''
      },
      destino: {
        cep: '',
        cidade: '',
        estado: ''
      },
      tipo_servico: '',
      prazo_entrega: '',
      transportadora: ''
    });
    setResultados([]);
  };

  const buscarCotacoes = async () => {
    setLoading(true);
    try {
      const response = await fetch('/api/v1/tms/cotacao/calcular', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(cotacao),
      });
      const data = await response.json();
      setResultados(data);
    } catch (error) {
      console.error('Erro ao buscar cotações:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleSalvarCotacao = async () => {
    try {
      await fetch('/api/v1/tms/cotacao/salvar', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          ...cotacao,
          resultados
        }),
      });
      handleReset();
    } catch (error) {
      console.error('Erro ao salvar cotação:', error);
    }
  };

  const buscarCEP = async (cep, tipo) => {
    try {
      const response = await fetch(`/api/v1/tms/cep/${cep}`);
      const data = await response.json();
      setCotacao(prev => ({
        ...prev,
        [tipo]: {
          cep,
          cidade: data.cidade,
          estado: data.estado
        }
      }));
    } catch (error) {
      console.error('Erro ao buscar CEP:', error);
    }
  };

  const renderStepContent = (step) => {
    switch (step) {
      case 0:
        return (
          <Grid container spacing={3}>
            <Grid item xs={12} md={6}>
              <FormControl fullWidth>
                <InputLabel>Tipo de Volume</InputLabel>
                <Select
                  value={cotacao.tipo_volume}
                  onChange={(e) => setCotacao({ ...cotacao, tipo_volume: e.target.value })}
                >
                  <MenuItem value="caixa">Caixa</MenuItem>
                  <MenuItem value="pallet">Pallet</MenuItem>
                  <MenuItem value="envelope">Envelope</MenuItem>
                  <MenuItem value="outros">Outros</MenuItem>
                </Select>
              </FormControl>
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Quantidade"
                type="number"
                value={cotacao.quantidade}
                onChange={(e) => setCotacao({ ...cotacao, quantidade: e.target.value })}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Peso (kg)"
                type="number"
                value={cotacao.peso}
                onChange={(e) => setCotacao({ ...cotacao, peso: e.target.value })}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Valor da Mercadoria"
                type="number"
                value={cotacao.valor_mercadoria}
                onChange={(e) => setCotacao({ ...cotacao, valor_mercadoria: e.target.value })}
              />
            </Grid>
            <Grid item xs={12} md={4}>
              <TextField
                fullWidth
                label="Altura (cm)"
                type="number"
                value={cotacao.dimensoes.altura}
                onChange={(e) => setCotacao({
                  ...cotacao,
                  dimensoes: { ...cotacao.dimensoes, altura: e.target.value }
                })}
              />
            </Grid>
            <Grid item xs={12} md={4}>
              <TextField
                fullWidth
                label="Largura (cm)"
                type="number"
                value={cotacao.dimensoes.largura}
                onChange={(e) => setCotacao({
                  ...cotacao,
                  dimensoes: { ...cotacao.dimensoes, largura: e.target.value }
                })}
              />
            </Grid>
            <Grid item xs={12} md={4}>
              <TextField
                fullWidth
                label="Comprimento (cm)"
                type="number"
                value={cotacao.dimensoes.comprimento}
                onChange={(e) => setCotacao({
                  ...cotacao,
                  dimensoes: { ...cotacao.dimensoes, comprimento: e.target.value }
                })}
              />
            </Grid>
          </Grid>
        );
      case 1:
        return (
          <Grid container spacing={3}>
            <Grid item xs={12} md={6}>
              <Typography variant="h6" gutterBottom>
                Origem
              </Typography>
              <Grid container spacing={2}>
                <Grid item xs={12}>
                  <TextField
                    fullWidth
                    label="CEP"
                    value={cotacao.origem.cep}
                    onChange={(e) => {
                      const cep = e.target.value;
                      setCotacao({
                        ...cotacao,
                        origem: { ...cotacao.origem, cep }
                      });
                      if (cep.length === 8) {
                        buscarCEP(cep, 'origem');
                      }
                    }}
                  />
                </Grid>
                <Grid item xs={12} md={8}>
                  <TextField
                    fullWidth
                    label="Cidade"
                    value={cotacao.origem.cidade}
                    InputProps={{ readOnly: true }}
                  />
                </Grid>
                <Grid item xs={12} md={4}>
                  <TextField
                    fullWidth
                    label="Estado"
                    value={cotacao.origem.estado}
                    InputProps={{ readOnly: true }}
                  />
                </Grid>
              </Grid>
            </Grid>
            <Grid item xs={12} md={6}>
              <Typography variant="h6" gutterBottom>
                Destino
              </Typography>
              <Grid container spacing={2}>
                <Grid item xs={12}>
                  <TextField
                    fullWidth
                    label="CEP"
                    value={cotacao.destino.cep}
                    onChange={(e) => {
                      const cep = e.target.value;
                      setCotacao({
                        ...cotacao,
                        destino: { ...cotacao.destino, cep }
                      });
                      if (cep.length === 8) {
                        buscarCEP(cep, 'destino');
                      }
                    }}
                  />
                </Grid>
                <Grid item xs={12} md={8}>
                  <TextField
                    fullWidth
                    label="Cidade"
                    value={cotacao.destino.cidade}
                    InputProps={{ readOnly: true }}
                  />
                </Grid>
                <Grid item xs={12} md={4}>
                  <TextField
                    fullWidth
                    label="Estado"
                    value={cotacao.destino.estado}
                    InputProps={{ readOnly: true }}
                  />
                </Grid>
              </Grid>
            </Grid>
          </Grid>
        );
      case 2:
        return (
          <Grid container spacing={3}>
            <Grid item xs={12} md={6}>
              <FormControl fullWidth>
                <InputLabel>Tipo de Serviço</InputLabel>
                <Select
                  value={cotacao.tipo_servico}
                  onChange={(e) => setCotacao({ ...cotacao, tipo_servico: e.target.value })}
                >
                  <MenuItem value="expresso">Expresso</MenuItem>
                  <MenuItem value="standard">Standard</MenuItem>
                  <MenuItem value="economico">Econômico</MenuItem>
                </Select>
              </FormControl>
            </Grid>
            <Grid item xs={12} md={6}>
              <FormControl fullWidth>
                <InputLabel>Prazo de Entrega</InputLabel>
                <Select
                  value={cotacao.prazo_entrega}
                  onChange={(e) => setCotacao({ ...cotacao, prazo_entrega: e.target.value })}
                >
                  <MenuItem value="1">Até 1 dia útil</MenuItem>
                  <MenuItem value="2">Até 2 dias úteis</MenuItem>
                  <MenuItem value="3">Até 3 dias úteis</MenuItem>
                  <MenuItem value="5">Até 5 dias úteis</MenuItem>
                  <MenuItem value="7">Até 7 dias úteis</MenuItem>
                </Select>
              </FormControl>
            </Grid>
          </Grid>
        );
      case 3:
        return (
          <Box>
            {loading ? (
              <LinearProgress />
            ) : (
              <TableContainer component={Paper}>
                <Table>
                  <TableHead>
                    <TableRow>
                      <TableCell>Transportadora</TableCell>
                      <TableCell>Serviço</TableCell>
                      <TableCell>Prazo</TableCell>
                      <TableCell>Valor</TableCell>
                      <TableCell>Status</TableCell>
                      <TableCell align="center">Ações</TableCell>
                    </TableRow>
                  </TableHead>
                  <TableBody>
                    {resultados.map((resultado) => (
                      <TableRow key={resultado.id}>
                        <TableCell>{resultado.transportadora}</TableCell>
                        <TableCell>{resultado.servico}</TableCell>
                        <TableCell>{resultado.prazo} dias úteis</TableCell>
                        <TableCell>R$ {resultado.valor}</TableCell>
                        <TableCell>
                          <Chip
                            label={resultado.status}
                            color={resultado.status === 'Disponível' ? 'success' : 'error'}
                            size="small"
                          />
                        </TableCell>
                        <TableCell align="center">
                          <IconButton
                            size="small"
                            onClick={() => handleSalvarCotacao(resultado)}
                            disabled={resultado.status !== 'Disponível'}
                          >
                            <SaveIcon />
                          </IconButton>
                          <IconButton size="small">
                            <PrintIcon />
                          </IconButton>
                        </TableCell>
                      </TableRow>
                    ))}
                  </TableBody>
                </Table>
              </TableContainer>
            )}
          </Box>
        );
      default:
        return null;
    }
  };

  return (
    <Box p={3}>
      <Box display="flex" justifyContent="space-between" alignItems="center" mb={3}>
        <Typography variant="h4">Cotação de Frete</Typography>
        <Button
          variant="outlined"
          startIcon={<HistoryIcon />}
          onClick={() => setOpenHistorico(true)}
        >
          Histórico
        </Button>
      </Box>

      <Card sx={{ mb: 3 }}>
        <CardContent>
          <Stepper activeStep={activeStep}>
            {steps.map((label) => (
              <Step key={label}>
                <StepLabel>{label}</StepLabel>
              </Step>
            ))}
          </Stepper>
        </CardContent>
      </Card>

      <Card>
        <CardContent>
          {renderStepContent(activeStep)}
          <Box sx={{ display: 'flex', justifyContent: 'flex-end', mt: 3 }}>
            <Button
              disabled={activeStep === 0}
              onClick={handleBack}
              sx={{ mr: 1 }}
            >
              Voltar
            </Button>
            <Button
              variant="contained"
              onClick={handleNext}
            >
              {activeStep === steps.length - 1 ? 'Finalizar' : 'Próximo'}
            </Button>
          </Box>
        </CardContent>
      </Card>

      {/* Modal de Histórico */}
      <Dialog
        open={openHistorico}
        onClose={() => setOpenHistorico(false)}
        maxWidth="md"
        fullWidth
      >
        <DialogTitle>Histórico de Cotações</DialogTitle>
        <DialogContent>
          <TableContainer>
            <Table>
              <TableHead>
                <TableRow>
                  <TableCell>Data</TableCell>
                  <TableCell>Origem</TableCell>
                  <TableCell>Destino</TableCell>
                  <TableCell>Transportadora</TableCell>
                  <TableCell>Valor</TableCell>
                  <TableCell align="center">Ações</TableCell>
                </TableRow>
              </TableHead>
              <TableBody>
                {historico.map((item) => (
                  <TableRow key={item.id}>
                    <TableCell>{item.data}</TableCell>
                    <TableCell>{item.origem}</TableCell>
                    <TableCell>{item.destino}</TableCell>
                    <TableCell>{item.transportadora}</TableCell>
                    <TableCell>R$ {item.valor}</TableCell>
                    <TableCell align="center">
                      <IconButton size="small">
                        <PrintIcon />
                      </IconButton>
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          </TableContainer>
        </DialogContent>
        <DialogActions>
          <Button onClick={() => setOpenHistorico(false)}>Fechar</Button>
        </DialogActions>
      </Dialog>
    </Box>
  );
};

export default Cotacao;
