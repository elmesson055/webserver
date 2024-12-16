import React, { useState, useEffect } from 'react';
import {
  Box,
  Card,
  CardContent,
  Grid,
  Typography,
  Button,
  Paper,
  Tabs,
  Tab,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Chip,
  Dialog,
  DialogTitle,
  DialogContent,
  DialogActions,
  TextField,
  FormControl,
  InputLabel,
  Select,
  MenuItem,
  IconButton,
  Tooltip,
  Alert,
  LinearProgress
} from '@mui/material';
import {
  Business as BusinessIcon,
  Person as PersonIcon,
  LocalShipping as ShippingIcon,
  AccountBalance as AccountIcon,
  AttachMoney as MoneyIcon,
  Assignment as AssignmentIcon,
  Calculate as CalculateIcon,
  Share as ShareIcon
} from '@mui/icons-material';
import CustosExtrasParticipantes from '../../services/integracoes/CustosExtrasParticipantes';

const GestaoParticipantesCustos = () => {
  const [loading, setLoading] = useState(false);
  const [tabValue, setTabValue] = useState(0);
  const [custosParticipantes, setCustosParticipantes] = useState({
    embarcadores: [],
    clientes: [],
    fornecedores: []
  });
  const [selectedCusto, setSelectedCusto] = useState(null);
  const [openDialog, setOpenDialog] = useState(false);
  const [openRateioDialog, setOpenRateioDialog] = useState(false);

  useEffect(() => {
    carregarCustosParticipantes();
  }, [tabValue]);

  const carregarCustosParticipantes = async () => {
    setLoading(true);
    try {
      const tipos = ['embarcadores', 'clientes', 'fornecedores'];
      const custos = {};

      for (const tipo of tipos) {
        const response = await CustosExtrasParticipantes[`buscarCustos${tipo.charAt(0).toUpperCase() + tipo.slice(1)}`]();
        if (response.success) {
          custos[tipo] = response.data;
        }
      }

      setCustosParticipantes(custos);
    } catch (error) {
      console.error('Erro ao carregar custos:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleTabChange = (event, newValue) => {
    setTabValue(newValue);
  };

  const vincularCustoParticipante = async (custoId, participanteId, tipo, dados) => {
    try {
      const response = await CustosExtrasParticipantes[`vincularCusto${tipo}`](custoId, participanteId, dados);
      if (response.success) {
        carregarCustosParticipantes();
        setOpenDialog(false);
      }
    } catch (error) {
      console.error('Erro ao vincular custo:', error);
    }
  };

  const calcularRateio = async (custoId, participantes) => {
    try {
      const response = await CustosExtrasParticipantes.calcularRateioCustos(custoId, participantes);
      if (response.success) {
        carregarCustosParticipantes();
        setOpenRateioDialog(false);
      }
    } catch (error) {
      console.error('Erro ao calcular rateio:', error);
    }
  };

  const renderTabelaCustos = (custos, tipo) => (
    <TableContainer component={Paper}>
      <Table>
        <TableHead>
          <TableRow>
            <TableCell>ID</TableCell>
            <TableCell>Participante</TableCell>
            <TableCell>Tipo de Custo</TableCell>
            <TableCell>Valor</TableCell>
            <TableCell>Status</TableCell>
            <TableCell>Rateio</TableCell>
            <TableCell>Ações</TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {custos.map((custo) => (
            <TableRow key={custo.id}>
              <TableCell>{custo.id}</TableCell>
              <TableCell>{custo.nomeParticipante}</TableCell>
              <TableCell>{custo.tipoCusto}</TableCell>
              <TableCell>
                {new Intl.NumberFormat('pt-BR', {
                  style: 'currency',
                  currency: 'BRL'
                }).format(custo.valor)}
              </TableCell>
              <TableCell>
                <Chip
                  label={custo.status}
                  color={
                    custo.status === 'aprovado' ? 'success' :
                    custo.status === 'pendente' ? 'warning' :
                    'default'
                  }
                  size="small"
                />
              </TableCell>
              <TableCell>
                {custo.percentualRateio ? `${custo.percentualRateio}%` : 'N/A'}
              </TableCell>
              <TableCell>
                <Tooltip title="Detalhes">
                  <IconButton
                    size="small"
                    onClick={() => {
                      setSelectedCusto({ ...custo, tipo });
                      setOpenDialog(true);
                    }}
                  >
                    <AssignmentIcon />
                  </IconButton>
                </Tooltip>
                <Tooltip title="Rateio">
                  <IconButton
                    size="small"
                    onClick={() => {
                      setSelectedCusto({ ...custo, tipo });
                      setOpenRateioDialog(true);
                    }}
                  >
                    <CalculateIcon />
                  </IconButton>
                </Tooltip>
              </TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>
    </TableContainer>
  );

  const renderDialogVinculo = () => (
    <Dialog
      open={openDialog}
      onClose={() => setOpenDialog(false)}
      maxWidth="md"
      fullWidth
    >
      <DialogTitle>
        Detalhes do Custo - {selectedCusto?.nomeParticipante}
      </DialogTitle>
      <DialogContent>
        <Grid container spacing={2} sx={{ mt: 1 }}>
          <Grid item xs={12}>
            <Alert severity="info" sx={{ mb: 2 }}>
              Gerencie os detalhes e aprovações do custo
            </Alert>
          </Grid>
          <Grid item xs={12} md={6}>
            <TextField
              fullWidth
              label="Valor"
              type="number"
              value={selectedCusto?.valor}
              InputProps={{
                readOnly: true,
              }}
            />
          </Grid>
          <Grid item xs={12} md={6}>
            <FormControl fullWidth>
              <InputLabel>Status</InputLabel>
              <Select
                value={selectedCusto?.status || ''}
                onChange={(e) => setSelectedCusto({
                  ...selectedCusto,
                  status: e.target.value
                })}
              >
                <MenuItem value="pendente">Pendente</MenuItem>
                <MenuItem value="aprovado">Aprovado</MenuItem>
                <MenuItem value="rejeitado">Rejeitado</MenuItem>
              </Select>
            </FormControl>
          </Grid>
          <Grid item xs={12}>
            <TextField
              fullWidth
              multiline
              rows={3}
              label="Observações"
              value={selectedCusto?.observacoes || ''}
              onChange={(e) => setSelectedCusto({
                ...selectedCusto,
                observacoes: e.target.value
              })}
            />
          </Grid>
        </Grid>
      </DialogContent>
      <DialogActions>
        <Button onClick={() => setOpenDialog(false)}>
          Cancelar
        </Button>
        <Button
          variant="contained"
          onClick={() => vincularCustoParticipante(
            selectedCusto.id,
            selectedCusto.participanteId,
            selectedCusto.tipo,
            {
              status: selectedCusto.status,
              observacoes: selectedCusto.observacoes
            }
          )}
        >
          Salvar
        </Button>
      </DialogActions>
    </Dialog>
  );

  const renderDialogRateio = () => (
    <Dialog
      open={openRateioDialog}
      onClose={() => setOpenRateioDialog(false)}
      maxWidth="md"
      fullWidth
    >
      <DialogTitle>
        Rateio de Custo - {selectedCusto?.nomeParticipante}
      </DialogTitle>
      <DialogContent>
        <Grid container spacing={2} sx={{ mt: 1 }}>
          <Grid item xs={12}>
            <Alert severity="info" sx={{ mb: 2 }}>
              Configure o rateio do custo entre os participantes
            </Alert>
          </Grid>
          {/* Adicione aqui os campos para configuração do rateio */}
        </Grid>
      </DialogContent>
      <DialogActions>
        <Button onClick={() => setOpenRateioDialog(false)}>
          Cancelar
        </Button>
        <Button
          variant="contained"
          onClick={() => calcularRateio(selectedCusto.id, [])}
        >
          Calcular Rateio
        </Button>
      </DialogActions>
    </Dialog>
  );

  if (loading) {
    return <LinearProgress />;
  }

  return (
    <Box p={3}>
      <Typography variant="h4" gutterBottom>
        Gestão de Custos por Participante
      </Typography>

      <Grid container spacing={3} sx={{ mb: 3 }}>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Typography color="textSecondary" gutterBottom>
                Total Embarcadores
              </Typography>
              <Typography variant="h4">
                {new Intl.NumberFormat('pt-BR', {
                  style: 'currency',
                  currency: 'BRL'
                }).format(
                  custosParticipantes.embarcadores.reduce((acc, curr) => acc + curr.valor, 0)
                )}
              </Typography>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Typography color="textSecondary" gutterBottom>
                Total Clientes
              </Typography>
              <Typography variant="h4">
                {new Intl.NumberFormat('pt-BR', {
                  style: 'currency',
                  currency: 'BRL'
                }).format(
                  custosParticipantes.clientes.reduce((acc, curr) => acc + curr.valor, 0)
                )}
              </Typography>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Typography color="textSecondary" gutterBottom>
                Total Fornecedores
              </Typography>
              <Typography variant="h4">
                {new Intl.NumberFormat('pt-BR', {
                  style: 'currency',
                  currency: 'BRL'
                }).format(
                  custosParticipantes.fornecedores.reduce((acc, curr) => acc + curr.valor, 0)
                )}
              </Typography>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Typography color="textSecondary" gutterBottom>
                Total Geral
              </Typography>
              <Typography variant="h4">
                {new Intl.NumberFormat('pt-BR', {
                  style: 'currency',
                  currency: 'BRL'
                }).format(
                  custosParticipantes.embarcadores.reduce((acc, curr) => acc + curr.valor, 0) +
                  custosParticipantes.clientes.reduce((acc, curr) => acc + curr.valor, 0) +
                  custosParticipantes.fornecedores.reduce((acc, curr) => acc + curr.valor, 0)
                )}
              </Typography>
            </CardContent>
          </Card>
        </Grid>
      </Grid>

      <Paper sx={{ mb: 3 }}>
        <Tabs
          value={tabValue}
          onChange={handleTabChange}
          indicatorColor="primary"
          textColor="primary"
          variant="fullWidth"
        >
          <Tab icon={<BusinessIcon />} label="Embarcadores" />
          <Tab icon={<PersonIcon />} label="Clientes" />
          <Tab icon={<ShippingIcon />} label="Fornecedores" />
          <Tab icon={<AccountIcon />} label="Consolidado" />
        </Tabs>
      </Paper>

      {tabValue === 0 && renderTabelaCustos(custosParticipantes.embarcadores, 'Embarcador')}
      {tabValue === 1 && renderTabelaCustos(custosParticipantes.clientes, 'Cliente')}
      {tabValue === 2 && renderTabelaCustos(custosParticipantes.fornecedores, 'Fornecedor')}
      {tabValue === 3 && (
        <Alert severity="info">
          Visualização consolidada em desenvolvimento
        </Alert>
      )}

      {selectedCusto && renderDialogVinculo()}
      {selectedCusto && renderDialogRateio()}
    </Box>
  );
};

export default GestaoParticipantesCustos;
