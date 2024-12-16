import React, { useState, useEffect } from 'react';
import {
  Box,
  Card,
  CardContent,
  Typography,
  Grid,
  Button,
  TextField,
  IconButton,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Paper,
  Dialog,
  DialogTitle,
  DialogContent,
  DialogActions,
  Chip,
  MenuItem,
  Tab,
  Tabs,
  Rating,
  Switch,
  FormControlLabel
} from '@mui/material';
import {
  Add,
  Edit,
  Delete,
  Search,
  LocalShipping,
  Assessment,
  AttachMoney,
  Speed
} from '@mui/icons-material';

const tiposVeiculo = [
  { value: 'van', label: 'Van' },
  { value: 'truck', label: 'Caminhão 3/4' },
  { value: 'toco', label: 'Caminhão Toco' },
  { value: 'truck', label: 'Caminhão Truck' },
  { value: 'carreta', label: 'Carreta' }
];

const Transportadoras = () => {
  const [transportadoras, setTransportadoras] = useState([]);
  const [dialogOpen, setDialogOpen] = useState(false);
  const [selectedTransportadora, setSelectedTransportadora] = useState(null);
  const [tabValue, setTabValue] = useState(0);
  const [loading, setLoading] = useState(false);
  const [filtros, setFiltros] = useState({
    busca: '',
    status: 'todos'
  });

  useEffect(() => {
    carregarTransportadoras();
  }, [filtros]);

  const carregarTransportadoras = async () => {
    setLoading(true);
    try {
      const response = await fetch('/api/v1/tms/transportadoras', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(filtros)
      });
      const data = await response.json();
      setTransportadoras(data);
    } catch (error) {
      console.error('Erro ao carregar transportadoras:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleDialogOpen = (transportadora = null) => {
    setSelectedTransportadora(transportadora);
    setDialogOpen(true);
  };

  const handleDialogClose = () => {
    setSelectedTransportadora(null);
    setDialogOpen(false);
  };

  const handleSalvar = async (event) => {
    event.preventDefault();
    const formData = new FormData(event.target);
    const transportadoraData = Object.fromEntries(formData.entries());

    try {
      const url = selectedTransportadora
        ? `/api/v1/tms/transportadoras/${selectedTransportadora.id}`
        : '/api/v1/tms/transportadoras';
      const method = selectedTransportadora ? 'PUT' : 'POST';

      await fetch(url, {
        method,
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(transportadoraData)
      });

      handleDialogClose();
      carregarTransportadoras();
    } catch (error) {
      console.error('Erro ao salvar transportadora:', error);
    }
  };

  const handleDelete = async (id) => {
    if (window.confirm('Deseja realmente excluir esta transportadora?')) {
      try {
        await fetch(`/api/v1/tms/transportadoras/${id}`, {
          method: 'DELETE'
        });
        carregarTransportadoras();
      } catch (error) {
        console.error('Erro ao excluir transportadora:', error);
      }
    }
  };

  return (
    <Box p={3}>
      {/* Cabeçalho */}
      <Box display="flex" justifyContent="space-between" alignItems="center" mb={3}>
        <Typography variant="h4">Gestão de Transportadoras</Typography>
        <Button
          variant="contained"
          color="primary"
          startIcon={<Add />}
          onClick={() => handleDialogOpen()}
        >
          Nova Transportadora
        </Button>
      </Box>

      {/* Filtros */}
      <Card sx={{ mb: 3 }}>
        <CardContent>
          <Grid container spacing={2} alignItems="center">
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Buscar"
                value={filtros.busca}
                onChange={(e) =>
                  setFiltros({ ...filtros, busca: e.target.value })
                }
                InputProps={{
                  endAdornment: (
                    <IconButton>
                      <Search />
                    </IconButton>
                  )
                }}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                select
                fullWidth
                label="Status"
                value={filtros.status}
                onChange={(e) =>
                  setFiltros({ ...filtros, status: e.target.value })
                }
              >
                <MenuItem value="todos">Todos</MenuItem>
                <MenuItem value="ativo">Ativo</MenuItem>
                <MenuItem value="inativo">Inativo</MenuItem>
              </TextField>
            </Grid>
          </Grid>
        </CardContent>
      </Card>

      {/* Lista de Transportadoras */}
      <TableContainer component={Paper}>
        <Table>
          <TableHead>
            <TableRow>
              <TableCell>Nome</TableCell>
              <TableCell>CNPJ</TableCell>
              <TableCell>Tipos de Veículo</TableCell>
              <TableCell>Avaliação</TableCell>
              <TableCell>Status</TableCell>
              <TableCell align="center">Ações</TableCell>
            </TableRow>
          </TableHead>
          <TableBody>
            {transportadoras.map((transportadora) => (
              <TableRow key={transportadora.id}>
                <TableCell>{transportadora.nome}</TableCell>
                <TableCell>{transportadora.cnpj}</TableCell>
                <TableCell>
                  {transportadora.tiposVeiculo.map((tipo) => (
                    <Chip
                      key={tipo}
                      label={tipo}
                      size="small"
                      sx={{ mr: 1 }}
                    />
                  ))}
                </TableCell>
                <TableCell>
                  <Rating value={transportadora.avaliacao} readOnly />
                </TableCell>
                <TableCell>
                  <Chip
                    label={transportadora.status}
                    color={transportadora.status === 'ativo' ? 'success' : 'default'}
                  />
                </TableCell>
                <TableCell align="center">
                  <IconButton
                    color="primary"
                    onClick={() => handleDialogOpen(transportadora)}
                  >
                    <Edit />
                  </IconButton>
                  <IconButton color="primary">
                    <Assessment />
                  </IconButton>
                  <IconButton color="primary">
                    <AttachMoney />
                  </IconButton>
                  <IconButton
                    color="error"
                    onClick={() => handleDelete(transportadora.id)}
                  >
                    <Delete />
                  </IconButton>
                </TableCell>
              </TableRow>
            ))}
          </TableBody>
        </Table>
      </TableContainer>

      {/* Dialog de Cadastro/Edição */}
      <Dialog open={dialogOpen} onClose={handleDialogClose} maxWidth="md" fullWidth>
        <form onSubmit={handleSalvar}>
          <DialogTitle>
            {selectedTransportadora ? 'Editar Transportadora' : 'Nova Transportadora'}
          </DialogTitle>
          <DialogContent>
            <Tabs
              value={tabValue}
              onChange={(e, newValue) => setTabValue(newValue)}
              sx={{ mb: 2 }}
            >
              <Tab label="Dados Gerais" />
              <Tab label="Veículos" />
              <Tab label="Preços" />
              <Tab label="SLA" />
            </Tabs>

            {tabValue === 0 && (
              <Grid container spacing={2}>
                <Grid item xs={12} md={6}>
                  <TextField
                    fullWidth
                    label="Nome"
                    name="nome"
                    defaultValue={selectedTransportadora?.nome || ''}
                    required
                  />
                </Grid>
                <Grid item xs={12} md={6}>
                  <TextField
                    fullWidth
                    label="CNPJ"
                    name="cnpj"
                    defaultValue={selectedTransportadora?.cnpj || ''}
                    required
                  />
                </Grid>
                <Grid item xs={12} md={6}>
                  <TextField
                    fullWidth
                    label="Email"
                    name="email"
                    type="email"
                    defaultValue={selectedTransportadora?.email || ''}
                    required
                  />
                </Grid>
                <Grid item xs={12} md={6}>
                  <TextField
                    fullWidth
                    label="Telefone"
                    name="telefone"
                    defaultValue={selectedTransportadora?.telefone || ''}
                    required
                  />
                </Grid>
                <Grid item xs={12}>
                  <TextField
                    fullWidth
                    label="Endereço"
                    name="endereco"
                    defaultValue={selectedTransportadora?.endereco || ''}
                    required
                  />
                </Grid>
                <Grid item xs={12}>
                  <FormControlLabel
                    control={
                      <Switch
                        defaultChecked={selectedTransportadora?.ativo || true}
                        name="ativo"
                      />
                    }
                    label="Ativo"
                  />
                </Grid>
              </Grid>
            )}

            {tabValue === 1 && (
              <Grid container spacing={2}>
                {tiposVeiculo.map((tipo) => (
                  <Grid item xs={12} key={tipo.value}>
                    <FormControlLabel
                      control={
                        <Switch
                          defaultChecked={selectedTransportadora?.tiposVeiculo?.includes(
                            tipo.value
                          )}
                          name={`veiculo_${tipo.value}`}
                        />
                      }
                      label={tipo.label}
                    />
                  </Grid>
                ))}
              </Grid>
            )}

            {tabValue === 2 && (
              <Grid container spacing={2}>
                <Grid item xs={12} md={6}>
                  <TextField
                    fullWidth
                    label="Preço por KM"
                    name="precoKm"
                    type="number"
                    defaultValue={selectedTransportadora?.precoKm || ''}
                  />
                </Grid>
                <Grid item xs={12} md={6}>
                  <TextField
                    fullWidth
                    label="Preço Mínimo"
                    name="precoMinimo"
                    type="number"
                    defaultValue={selectedTransportadora?.precoMinimo || ''}
                  />
                </Grid>
                <Grid item xs={12}>
                  <TextField
                    fullWidth
                    label="Observações de Preço"
                    name="obsPreco"
                    multiline
                    rows={4}
                    defaultValue={selectedTransportadora?.obsPreco || ''}
                  />
                </Grid>
              </Grid>
            )}

            {tabValue === 3 && (
              <Grid container spacing={2}>
                <Grid item xs={12} md={6}>
                  <TextField
                    fullWidth
                    label="SLA de Entrega (horas)"
                    name="slaEntrega"
                    type="number"
                    defaultValue={selectedTransportadora?.slaEntrega || ''}
                  />
                </Grid>
                <Grid item xs={12} md={6}>
                  <TextField
                    fullWidth
                    label="% Entregas no Prazo"
                    name="percentualPrazo"
                    type="number"
                    defaultValue={selectedTransportadora?.percentualPrazo || ''}
                  />
                </Grid>
                <Grid item xs={12}>
                  <TextField
                    fullWidth
                    label="Observações de SLA"
                    name="obsSla"
                    multiline
                    rows={4}
                    defaultValue={selectedTransportadora?.obsSla || ''}
                  />
                </Grid>
              </Grid>
            )}
          </DialogContent>
          <DialogActions>
            <Button onClick={handleDialogClose}>Cancelar</Button>
            <Button type="submit" variant="contained" color="primary">
              Salvar
            </Button>
          </DialogActions>
        </form>
      </Dialog>
    </Box>
  );
};

export default Transportadoras;
