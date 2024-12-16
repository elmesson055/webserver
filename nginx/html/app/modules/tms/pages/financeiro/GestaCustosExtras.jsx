import React, { useState, useEffect } from 'react';
import {
  Box,
  Card,
  CardContent,
  Grid,
  Typography,
  Button,
  Paper,
  TextField,
  FormControl,
  InputLabel,
  Select,
  MenuItem,
  IconButton,
  Tooltip,
  Dialog,
  DialogTitle,
  DialogContent,
  DialogActions,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Chip,
  Alert,
  LinearProgress,
  Tabs,
  Tab
} from '@mui/material';
import {
  Add as AddIcon,
  Edit as EditIcon,
  Delete as DeleteIcon,
  AttachMoney as MoneyIcon,
  LocalShipping as TruckIcon,
  Assessment as ReportIcon,
  Warning as AlertIcon,
  Save as SaveIcon,
  FilterList as FilterIcon,
  Refresh as RefreshIcon
} from '@mui/icons-material';
import { DatePicker } from '@mui/x-date-pickers';
import { format } from 'date-fns';

const GestaCustosExtras = () => {
  const [loading, setLoading] = useState(false);
  const [tabValue, setTabValue] = useState(0);
  const [openDialog, setOpenDialog] = useState(false);
  const [custoExtra, setCustoExtra] = useState({
    id: null,
    tipo: '',
    descricao: '',
    valor: '',
    data: null,
    veiculo: '',
    motorista: '',
    viagem: '',
    status: 'pendente',
    comprovante: null,
    observacoes: ''
  });
  const [custosExtras, setCustosExtras] = useState([]);
  const [filtros, setFiltros] = useState({
    dataInicio: null,
    dataFim: null,
    tipo: '',
    status: '',
    veiculo: ''
  });

  const tiposCustoExtra = [
    { id: 'pedagio', nome: 'Pedágio' },
    { id: 'estacionamento', nome: 'Estacionamento' },
    { id: 'alimentacao', nome: 'Alimentação' },
    { id: 'hospedagem', nome: 'Hospedagem' },
    { id: 'combustivel_extra', nome: 'Combustível Extra' },
    { id: 'manutencao_emergencial', nome: 'Manutenção Emergencial' },
    { id: 'multa', nome: 'Multa' },
    { id: 'extra_entrega', nome: 'Taxa Extra de Entrega' },
    { id: 'seguro_adicional', nome: 'Seguro Adicional' },
    { id: 'outros', nome: 'Outros' }
  ];

  useEffect(() => {
    carregarCustosExtras();
  }, [filtros]);

  const carregarCustosExtras = async () => {
    setLoading(true);
    try {
      // Simulação de chamada API
      const response = await fetch('/api/v1/tms/custos-extras', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(filtros)
      });
      const data = await response.json();
      setCustosExtras(data);
    } catch (error) {
      console.error('Erro ao carregar custos extras:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleTabChange = (event, newValue) => {
    setTabValue(newValue);
  };

  const handleSalvarCustoExtra = async () => {
    try {
      setLoading(true);
      // Simulação de chamada API
      const response = await fetch('/api/v1/tms/custos-extras/salvar', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(custoExtra)
      });
      
      if (response.ok) {
        setOpenDialog(false);
        carregarCustosExtras();
      }
    } catch (error) {
      console.error('Erro ao salvar custo extra:', error);
    } finally {
      setLoading(false);
    }
  };

  const renderFormulario = () => (
    <Dialog
      open={openDialog}
      onClose={() => setOpenDialog(false)}
      maxWidth="md"
      fullWidth
    >
      <DialogTitle>
        {custoExtra.id ? 'Editar Custo Extra' : 'Novo Custo Extra'}
      </DialogTitle>
      <DialogContent>
        <Grid container spacing={2} sx={{ mt: 1 }}>
          <Grid item xs={12} md={6}>
            <FormControl fullWidth>
              <InputLabel>Tipo de Custo</InputLabel>
              <Select
                value={custoExtra.tipo}
                onChange={(e) => setCustoExtra({
                  ...custoExtra,
                  tipo: e.target.value
                })}
              >
                {tiposCustoExtra.map((tipo) => (
                  <MenuItem key={tipo.id} value={tipo.id}>
                    {tipo.nome}
                  </MenuItem>
                ))}
              </Select>
            </FormControl>
          </Grid>
          <Grid item xs={12} md={6}>
            <TextField
              fullWidth
              label="Valor"
              type="number"
              value={custoExtra.valor}
              onChange={(e) => setCustoExtra({
                ...custoExtra,
                valor: e.target.value
              })}
            />
          </Grid>
          <Grid item xs={12}>
            <TextField
              fullWidth
              label="Descrição"
              value={custoExtra.descricao}
              onChange={(e) => setCustoExtra({
                ...custoExtra,
                descricao: e.target.value
              })}
            />
          </Grid>
          <Grid item xs={12} md={6}>
            <DatePicker
              label="Data"
              value={custoExtra.data}
              onChange={(newValue) => setCustoExtra({
                ...custoExtra,
                data: newValue
              })}
              renderInput={(params) => <TextField {...params} fullWidth />}
            />
          </Grid>
          <Grid item xs={12} md={6}>
            <FormControl fullWidth>
              <InputLabel>Status</InputLabel>
              <Select
                value={custoExtra.status}
                onChange={(e) => setCustoExtra({
                  ...custoExtra,
                  status: e.target.value
                })}
              >
                <MenuItem value="pendente">Pendente</MenuItem>
                <MenuItem value="aprovado">Aprovado</MenuItem>
                <MenuItem value="rejeitado">Rejeitado</MenuItem>
                <MenuItem value="pago">Pago</MenuItem>
              </Select>
            </FormControl>
          </Grid>
          <Grid item xs={12} md={6}>
            <TextField
              fullWidth
              label="Veículo"
              value={custoExtra.veiculo}
              onChange={(e) => setCustoExtra({
                ...custoExtra,
                veiculo: e.target.value
              })}
            />
          </Grid>
          <Grid item xs={12} md={6}>
            <TextField
              fullWidth
              label="Motorista"
              value={custoExtra.motorista}
              onChange={(e) => setCustoExtra({
                ...custoExtra,
                motorista: e.target.value
              })}
            />
          </Grid>
          <Grid item xs={12}>
            <TextField
              fullWidth
              label="Viagem/Rota"
              value={custoExtra.viagem}
              onChange={(e) => setCustoExtra({
                ...custoExtra,
                viagem: e.target.value
              })}
            />
          </Grid>
          <Grid item xs={12}>
            <TextField
              fullWidth
              multiline
              rows={3}
              label="Observações"
              value={custoExtra.observacoes}
              onChange={(e) => setCustoExtra({
                ...custoExtra,
                observacoes: e.target.value
              })}
            />
          </Grid>
          <Grid item xs={12}>
            <Button
              variant="outlined"
              component="label"
              startIcon={<AddIcon />}
            >
              Anexar Comprovante
              <input
                type="file"
                hidden
                onChange={(e) => setCustoExtra({
                  ...custoExtra,
                  comprovante: e.target.files[0]
                })}
              />
            </Button>
            {custoExtra.comprovante && (
              <Typography variant="caption" sx={{ ml: 2 }}>
                {custoExtra.comprovante.name}
              </Typography>
            )}
          </Grid>
        </Grid>
      </DialogContent>
      <DialogActions>
        <Button onClick={() => setOpenDialog(false)}>Cancelar</Button>
        <Button
          variant="contained"
          onClick={handleSalvarCustoExtra}
          startIcon={<SaveIcon />}
        >
          Salvar
        </Button>
      </DialogActions>
    </Dialog>
  );

  const renderFiltros = () => (
    <Paper sx={{ p: 2, mb: 2 }}>
      <Grid container spacing={2} alignItems="center">
        <Grid item xs={12} md={3}>
          <DatePicker
            label="Data Início"
            value={filtros.dataInicio}
            onChange={(newValue) => setFiltros({
              ...filtros,
              dataInicio: newValue
            })}
            renderInput={(params) => <TextField {...params} fullWidth />}
          />
        </Grid>
        <Grid item xs={12} md={3}>
          <DatePicker
            label="Data Fim"
            value={filtros.dataFim}
            onChange={(newValue) => setFiltros({
              ...filtros,
              dataFim: newValue
            })}
            renderInput={(params) => <TextField {...params} fullWidth />}
          />
        </Grid>
        <Grid item xs={12} md={2}>
          <FormControl fullWidth>
            <InputLabel>Tipo</InputLabel>
            <Select
              value={filtros.tipo}
              onChange={(e) => setFiltros({
                ...filtros,
                tipo: e.target.value
              })}
            >
              <MenuItem value="">Todos</MenuItem>
              {tiposCustoExtra.map((tipo) => (
                <MenuItem key={tipo.id} value={tipo.id}>
                  {tipo.nome}
                </MenuItem>
              ))}
            </Select>
          </FormControl>
        </Grid>
        <Grid item xs={12} md={2}>
          <FormControl fullWidth>
            <InputLabel>Status</InputLabel>
            <Select
              value={filtros.status}
              onChange={(e) => setFiltros({
                ...filtros,
                status: e.target.value
              })}
            >
              <MenuItem value="">Todos</MenuItem>
              <MenuItem value="pendente">Pendente</MenuItem>
              <MenuItem value="aprovado">Aprovado</MenuItem>
              <MenuItem value="rejeitado">Rejeitado</MenuItem>
              <MenuItem value="pago">Pago</MenuItem>
            </Select>
          </FormControl>
        </Grid>
        <Grid item xs={12} md={2}>
          <Button
            fullWidth
            variant="contained"
            startIcon={<FilterIcon />}
            onClick={carregarCustosExtras}
          >
            Filtrar
          </Button>
        </Grid>
      </Grid>
    </Paper>
  );

  const renderTabela = () => (
    <TableContainer component={Paper}>
      <Table>
        <TableHead>
          <TableRow>
            <TableCell>Data</TableCell>
            <TableCell>Tipo</TableCell>
            <TableCell>Descrição</TableCell>
            <TableCell>Valor</TableCell>
            <TableCell>Veículo</TableCell>
            <TableCell>Motorista</TableCell>
            <TableCell>Status</TableCell>
            <TableCell>Ações</TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {custosExtras.map((custo) => (
            <TableRow key={custo.id}>
              <TableCell>{format(new Date(custo.data), 'dd/MM/yyyy')}</TableCell>
              <TableCell>
                {tiposCustoExtra.find(t => t.id === custo.tipo)?.nome}
              </TableCell>
              <TableCell>{custo.descricao}</TableCell>
              <TableCell>
                {new Intl.NumberFormat('pt-BR', {
                  style: 'currency',
                  currency: 'BRL'
                }).format(custo.valor)}
              </TableCell>
              <TableCell>{custo.veiculo}</TableCell>
              <TableCell>{custo.motorista}</TableCell>
              <TableCell>
                <Chip
                  label={custo.status}
                  color={
                    custo.status === 'aprovado' ? 'success' :
                    custo.status === 'rejeitado' ? 'error' :
                    custo.status === 'pago' ? 'primary' : 'warning'
                  }
                  size="small"
                />
              </TableCell>
              <TableCell>
                <Tooltip title="Editar">
                  <IconButton
                    size="small"
                    onClick={() => {
                      setCustoExtra(custo);
                      setOpenDialog(true);
                    }}
                  >
                    <EditIcon />
                  </IconButton>
                </Tooltip>
                <Tooltip title="Excluir">
                  <IconButton
                    size="small"
                    color="error"
                  >
                    <DeleteIcon />
                  </IconButton>
                </Tooltip>
              </TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>
    </TableContainer>
  );

  const renderDashboard = () => (
    <Grid container spacing={2}>
      <Grid item xs={12} md={3}>
        <Card>
          <CardContent>
            <Typography color="textSecondary" gutterBottom>
              Total Custos Extras
            </Typography>
            <Typography variant="h4">
              {new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
              }).format(custosExtras.reduce((acc, curr) => acc + Number(curr.valor), 0))}
            </Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={3}>
        <Card>
          <CardContent>
            <Typography color="textSecondary" gutterBottom>
              Custos Pendentes
            </Typography>
            <Typography variant="h4">
              {custosExtras.filter(c => c.status === 'pendente').length}
            </Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={3}>
        <Card>
          <CardContent>
            <Typography color="textSecondary" gutterBottom>
              Custos Aprovados
            </Typography>
            <Typography variant="h4">
              {custosExtras.filter(c => c.status === 'aprovado').length}
            </Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={3}>
        <Card>
          <CardContent>
            <Typography color="textSecondary" gutterBottom>
              Média por Viagem
            </Typography>
            <Typography variant="h4">
              {new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
              }).format(custosExtras.reduce((acc, curr) => acc + Number(curr.valor), 0) / custosExtras.length || 0)}
            </Typography>
          </CardContent>
        </Card>
      </Grid>
    </Grid>
  );

  if (loading) {
    return <LinearProgress />;
  }

  return (
    <Box p={3}>
      <Box display="flex" justifyContent="space-between" alignItems="center" mb={3}>
        <Typography variant="h4">Gestão de Custos Extras</Typography>
        <Button
          variant="contained"
          startIcon={<AddIcon />}
          onClick={() => {
            setCustoExtra({
              id: null,
              tipo: '',
              descricao: '',
              valor: '',
              data: null,
              veiculo: '',
              motorista: '',
              viagem: '',
              status: 'pendente',
              comprovante: null,
              observacoes: ''
            });
            setOpenDialog(true);
          }}
        >
          Novo Custo Extra
        </Button>
      </Box>

      <Paper sx={{ mb: 3 }}>
        <Tabs
          value={tabValue}
          onChange={handleTabChange}
          indicatorColor="primary"
          textColor="primary"
          variant="fullWidth"
        >
          <Tab icon={<MoneyIcon />} label="Custos" />
          <Tab icon={<TruckIcon />} label="Por Veículo" />
          <Tab icon={<ReportIcon />} label="Relatórios" />
          <Tab icon={<AlertIcon />} label="Alertas" />
        </Tabs>
      </Paper>

      {tabValue === 0 && (
        <>
          {renderDashboard()}
          <Box my={3}>
            {renderFiltros()}
          </Box>
          {renderTabela()}
        </>
      )}

      {tabValue === 1 && (
        <Typography>Análise por veículo em desenvolvimento</Typography>
      )}

      {tabValue === 2 && (
        <Typography>Relatórios em desenvolvimento</Typography>
      )}

      {tabValue === 3 && (
        <Typography>Alertas em desenvolvimento</Typography>
      )}

      {renderFormulario()}
    </Box>
  );
};

export default GestaCustosExtras;
