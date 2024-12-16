import React, { useState, useEffect } from 'react';
import {
  Box,
  Card,
  CardContent,
  Grid,
  Typography,
  Button,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Paper,
  TextField,
  IconButton,
  Chip,
  Dialog,
  DialogTitle,
  DialogContent,
  DialogActions,
  FormControl,
  InputLabel,
  Select,
  MenuItem,
  LinearProgress,
  Tooltip,
  Tabs,
  Tab,
  List,
  ListItem,
  ListItemText,
  ListItemIcon,
  Autocomplete,
  Badge
} from '@mui/material';
import {
  Inventory as InventoryIcon,
  Add as AddIcon,
  Edit as EditIcon,
  Delete as DeleteIcon,
  Warning,
  CheckCircle,
  Timeline as TimelineIcon,
  Assessment,
  Build as BuildIcon,
  LocalShipping,
  AttachMoney,
  Category,
  QrCode,
  ShoppingCart,
  Assignment,
  Refresh
} from '@mui/icons-material';
import { AdapterDateFns } from '@mui/x-date-pickers/AdapterDateFns';
import { LocalizationProvider, DatePicker } from '@mui/x-date-pickers';
import { ptBR } from 'date-fns/locale';

const GestaoEstoque = () => {
  const [loading, setLoading] = useState(true);
  const [tabValue, setTabValue] = useState(0);
  const [pecas, setPecas] = useState([]);
  const [selectedPeca, setSelectedPeca] = useState(null);
  const [openDialog, setOpenDialog] = useState(false);
  const [dialogType, setDialogType] = useState('');
  const [metricas, setMetricas] = useState({
    totalPecas: 0,
    valorTotal: 0,
    abaixoMinimo: 0,
    pedidosPendentes: 0
  });
  const [filtros, setFiltros] = useState({
    categoria: 'todas',
    status: 'todos',
    fornecedor: 'todos'
  });

  useEffect(() => {
    carregarDados();
  }, [filtros]);

  const carregarDados = async () => {
    setLoading(true);
    try {
      const [metricasRes, pecasRes] = await Promise.all([
        fetch('/api/v1/tms/estoque/metricas'),
        fetch('/api/v1/tms/estoque/pecas')
      ]);

      const metricasData = await metricasRes.json();
      const pecasData = await pecasRes.json();

      setMetricas(metricasData);
      setPecas(pecasData);
    } catch (error) {
      console.error('Erro ao carregar dados:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleTabChange = (event, newValue) => {
    setTabValue(newValue);
  };

  const handleOpenDialog = (type, peca = null) => {
    setDialogType(type);
    setSelectedPeca(peca);
    setOpenDialog(true);
  };

  const handleCloseDialog = () => {
    setDialogType('');
    setSelectedPeca(null);
    setOpenDialog(false);
  };

  const getStatusColor = (status) => {
    switch (status.toLowerCase()) {
      case 'disponível':
        return 'success';
      case 'baixo estoque':
        return 'warning';
      case 'indisponível':
        return 'error';
      case 'aguardando':
        return 'info';
      default:
        return 'default';
    }
  };

  const renderKPIs = () => (
    <Grid container spacing={3} mb={3}>
      <Grid item xs={12} md={3}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <InventoryIcon color="primary" />
              <Typography variant="subtitle2" ml={1}>
                Total de Peças
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.totalPecas}</Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={3}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <AttachMoney color="success" />
              <Typography variant="subtitle2" ml={1}>
                Valor em Estoque
              </Typography>
            </Box>
            <Typography variant="h4">R$ {metricas.valorTotal}</Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={3}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <Warning color="warning" />
              <Typography variant="subtitle2" ml={1}>
                Abaixo do Mínimo
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.abaixoMinimo}</Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={3}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <ShoppingCart color="info" />
              <Typography variant="subtitle2" ml={1}>
                Pedidos Pendentes
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.pedidosPendentes}</Typography>
          </CardContent>
        </Card>
      </Grid>
    </Grid>
  );

  const renderFiltros = () => (
    <Paper sx={{ p: 2, mb: 3 }}>
      <Grid container spacing={2} alignItems="center">
        <Grid item xs={12} md={3}>
          <FormControl fullWidth>
            <InputLabel>Categoria</InputLabel>
            <Select
              value={filtros.categoria}
              onChange={(e) => setFiltros({ ...filtros, categoria: e.target.value })}
            >
              <MenuItem value="todas">Todas</MenuItem>
              <MenuItem value="motor">Motor</MenuItem>
              <MenuItem value="suspensao">Suspensão</MenuItem>
              <MenuItem value="freios">Freios</MenuItem>
              <MenuItem value="eletrica">Elétrica</MenuItem>
            </Select>
          </FormControl>
        </Grid>
        <Grid item xs={12} md={3}>
          <FormControl fullWidth>
            <InputLabel>Status</InputLabel>
            <Select
              value={filtros.status}
              onChange={(e) => setFiltros({ ...filtros, status: e.target.value })}
            >
              <MenuItem value="todos">Todos</MenuItem>
              <MenuItem value="disponivel">Disponível</MenuItem>
              <MenuItem value="baixo_estoque">Baixo Estoque</MenuItem>
              <MenuItem value="indisponivel">Indisponível</MenuItem>
            </Select>
          </FormControl>
        </Grid>
        <Grid item xs={12} md={3}>
          <FormControl fullWidth>
            <InputLabel>Fornecedor</InputLabel>
            <Select
              value={filtros.fornecedor}
              onChange={(e) => setFiltros({ ...filtros, fornecedor: e.target.value })}
            >
              <MenuItem value="todos">Todos</MenuItem>
              <MenuItem value="fornecedor1">Fornecedor 1</MenuItem>
              <MenuItem value="fornecedor2">Fornecedor 2</MenuItem>
              <MenuItem value="fornecedor3">Fornecedor 3</MenuItem>
            </Select>
          </FormControl>
        </Grid>
        <Grid item xs={12} md={3}>
          <Button
            variant="contained"
            startIcon={<AddIcon />}
            onClick={() => handleOpenDialog('nova')}
            fullWidth
          >
            Nova Peça
          </Button>
        </Grid>
      </Grid>
    </Paper>
  );

  const renderPecas = () => (
    <TableContainer component={Paper}>
      <Table>
        <TableHead>
          <TableRow>
            <TableCell>Código</TableCell>
            <TableCell>Descrição</TableCell>
            <TableCell>Categoria</TableCell>
            <TableCell>Fornecedor</TableCell>
            <TableCell>Quantidade</TableCell>
            <TableCell>Mínimo</TableCell>
            <TableCell>Status</TableCell>
            <TableCell>Valor Unit.</TableCell>
            <TableCell align="center">Ações</TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {pecas.map((peca) => (
            <TableRow key={peca.id}>
              <TableCell>
                <Box display="flex" alignItems="center">
                  <QrCode />
                  <Typography variant="subtitle2" ml={1}>
                    {peca.codigo}
                  </Typography>
                </Box>
              </TableCell>
              <TableCell>{peca.descricao}</TableCell>
              <TableCell>{peca.categoria}</TableCell>
              <TableCell>{peca.fornecedor}</TableCell>
              <TableCell>
                <Badge
                  badgeContent={peca.quantidade}
                  color={peca.quantidade <= peca.minimo ? 'error' : 'primary'}
                >
                  <InventoryIcon />
                </Badge>
              </TableCell>
              <TableCell>{peca.minimo}</TableCell>
              <TableCell>
                <Chip
                  label={peca.status}
                  color={getStatusColor(peca.status)}
                  size="small"
                />
              </TableCell>
              <TableCell>R$ {peca.valorUnitario}</TableCell>
              <TableCell align="center">
                <Tooltip title="Editar">
                  <IconButton
                    size="small"
                    onClick={() => handleOpenDialog('editar', peca)}
                  >
                    <EditIcon />
                  </IconButton>
                </Tooltip>
                <Tooltip title="Movimentações">
                  <IconButton size="small">
                    <TimelineIcon />
                  </IconButton>
                </Tooltip>
                <Tooltip title="Requisitar">
                  <IconButton size="small">
                    <ShoppingCart />
                  </IconButton>
                </Tooltip>
                <Tooltip title="Ajustar Estoque">
                  <IconButton size="small">
                    <Refresh />
                  </IconButton>
                </Tooltip>
              </TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>
    </TableContainer>
  );

  const renderDialog = () => (
    <Dialog open={openDialog} onClose={handleCloseDialog} maxWidth="md" fullWidth>
      <DialogTitle>
        {selectedPeca ? 'Editar Peça' : 'Nova Peça'}
      </DialogTitle>
      <DialogContent>
        <Box component="form" sx={{ mt: 2 }}>
          <Grid container spacing={2}>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Código"
                defaultValue={selectedPeca?.codigo}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Descrição"
                defaultValue={selectedPeca?.descricao}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <FormControl fullWidth>
                <InputLabel>Categoria</InputLabel>
                <Select defaultValue={selectedPeca?.categoria || ''}>
                  <MenuItem value="motor">Motor</MenuItem>
                  <MenuItem value="suspensao">Suspensão</MenuItem>
                  <MenuItem value="freios">Freios</MenuItem>
                  <MenuItem value="eletrica">Elétrica</MenuItem>
                </Select>
              </FormControl>
            </Grid>
            <Grid item xs={12} md={6}>
              <Autocomplete
                options={[]}
                renderInput={(params) => (
                  <TextField {...params} label="Fornecedor" fullWidth />
                )}
                defaultValue={selectedPeca?.fornecedor || null}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Quantidade"
                type="number"
                defaultValue={selectedPeca?.quantidade}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Quantidade Mínima"
                type="number"
                defaultValue={selectedPeca?.minimo}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Valor Unitário"
                type="number"
                defaultValue={selectedPeca?.valorUnitario}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Localização"
                defaultValue={selectedPeca?.localizacao}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <FormControl fullWidth>
                <InputLabel>Status</InputLabel>
                <Select defaultValue={selectedPeca?.status || 'disponivel'}>
                  <MenuItem value="disponivel">Disponível</MenuItem>
                  <MenuItem value="indisponivel">Indisponível</MenuItem>
                  <MenuItem value="aguardando">Aguardando</MenuItem>
                </Select>
              </FormControl>
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="NCM"
                defaultValue={selectedPeca?.ncm}
              />
            </Grid>
            <Grid item xs={12}>
              <TextField
                fullWidth
                multiline
                rows={4}
                label="Observações"
                defaultValue={selectedPeca?.observacoes}
              />
            </Grid>
          </Grid>
        </Box>
      </DialogContent>
      <DialogActions>
        <Button onClick={handleCloseDialog}>Cancelar</Button>
        <Button variant="contained" onClick={handleCloseDialog}>
          Salvar
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
        Gestão de Estoque
      </Typography>

      {renderKPIs()}
      {renderFiltros()}

      <Paper sx={{ mb: 3 }}>
        <Tabs
          value={tabValue}
          onChange={handleTabChange}
          indicatorColor="primary"
          textColor="primary"
          variant="fullWidth"
        >
          <Tab icon={<InventoryIcon />} label="Peças" />
          <Tab icon={<ShoppingCart />} label="Requisições" />
          <Tab icon={<LocalShipping />} label="Fornecedores" />
          <Tab icon={<Assessment />} label="Relatórios" />
        </Tabs>
        <Box p={3}>
          {tabValue === 0 && renderPecas()}
          {tabValue === 1 && (
            <Typography>Área de requisições em desenvolvimento</Typography>
          )}
          {tabValue === 2 && (
            <Typography>Área de fornecedores em desenvolvimento</Typography>
          )}
          {tabValue === 3 && (
            <Typography>Área de relatórios em desenvolvimento</Typography>
          )}
        </Box>
      </Paper>

      {renderDialog()}
    </Box>
  );
};

export default GestaoEstoque;
