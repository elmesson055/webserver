import React, { useState, useEffect } from 'react';
import {
  Box,
  Card,
  CardContent,
  Grid,
  Typography,
  Button,
  Paper,
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
  Alert
} from '@mui/material';
import {
  Link as LinkIcon,
  Refresh as RefreshIcon,
  Assignment as AssignmentIcon,
  LocalShipping as TruckIcon,
  Warehouse as WarehouseIcon,
  Timeline as TimelineIcon
} from '@mui/icons-material';
import CustosExtrasIntegracoes from '../../services/integracoes/CustosExtrasIntegracoes';

const IntegracaoCustosWMSTMS = () => {
  const [custosWMS, setCustosWMS] = useState([]);
  const [custosTMS, setCustosTMS] = useState([]);
  const [selectedCusto, setSelectedCusto] = useState(null);
  const [openDialog, setOpenDialog] = useState(false);
  const [loading, setLoading] = useState(false);
  const [filtros, setFiltros] = useState({
    dataInicio: null,
    dataFim: null,
    tipo: '',
    origem: ''
  });

  useEffect(() => {
    carregarCustos();
  }, [filtros]);

  const carregarCustos = async () => {
    setLoading(true);
    try {
      // Carregar custos do WMS
      const custosWMS = await CustosExtrasIntegracoes.buscarCustosWMS(filtros);
      if (custosWMS.success) {
        setCustosWMS(custosWMS.data);
      }

      // Carregar custos do TMS
      const custosTMS = await CustosExtrasIntegracoes.buscarCustosTransporte(filtros);
      if (custosTMS.success) {
        setCustosTMS(custosTMS.data);
      }
    } catch (error) {
      console.error('Erro ao carregar custos:', error);
    } finally {
      setLoading(false);
    }
  };

  const vincularCustos = async (custoId, tipo, dadosVinculo) => {
    try {
      let response;
      if (tipo === 'wms') {
        response = await CustosExtrasIntegracoes.vincularCustoArmazenagem(custoId, dadosVinculo);
      } else {
        response = await CustosExtrasIntegracoes.vincularCustoViagem(custoId, dadosVinculo.viagemId);
      }

      if (response.success) {
        carregarCustos();
        setOpenDialog(false);
      }
    } catch (error) {
      console.error('Erro ao vincular custos:', error);
    }
  };

  const renderCustosWMS = () => (
    <Card sx={{ mb: 3 }}>
      <CardContent>
        <Box display="flex" justifyContent="space-between" alignItems="center" mb={2}>
          <Typography variant="h6">
            <WarehouseIcon sx={{ mr: 1 }} />
            Custos WMS
          </Typography>
          <Button
            startIcon={<RefreshIcon />}
            onClick={() => carregarCustos()}
          >
            Atualizar
          </Button>
        </Box>
        <TableContainer>
          <Table>
            <TableHead>
              <TableRow>
                <TableCell>ID</TableCell>
                <TableCell>Tipo</TableCell>
                <TableCell>Descrição</TableCell>
                <TableCell>Valor</TableCell>
                <TableCell>Status</TableCell>
                <TableCell>Ações</TableCell>
              </TableRow>
            </TableHead>
            <TableBody>
              {custosWMS.map((custo) => (
                <TableRow key={custo.id}>
                  <TableCell>{custo.id}</TableCell>
                  <TableCell>{custo.tipo}</TableCell>
                  <TableCell>{custo.descricao}</TableCell>
                  <TableCell>
                    {new Intl.NumberFormat('pt-BR', {
                      style: 'currency',
                      currency: 'BRL'
                    }).format(custo.valor)}
                  </TableCell>
                  <TableCell>
                    <Chip
                      label={custo.status}
                      color={custo.status === 'vinculado' ? 'success' : 'warning'}
                      size="small"
                    />
                  </TableCell>
                  <TableCell>
                    <Tooltip title="Vincular">
                      <IconButton
                        size="small"
                        onClick={() => {
                          setSelectedCusto({ ...custo, origem: 'wms' });
                          setOpenDialog(true);
                        }}
                      >
                        <LinkIcon />
                      </IconButton>
                    </Tooltip>
                    <Tooltip title="Detalhes">
                      <IconButton size="small">
                        <AssignmentIcon />
                      </IconButton>
                    </Tooltip>
                  </TableCell>
                </TableRow>
              ))}
            </TableBody>
          </Table>
        </TableContainer>
      </CardContent>
    </Card>
  );

  const renderCustosTMS = () => (
    <Card>
      <CardContent>
        <Box display="flex" justifyContent="space-between" alignItems="center" mb={2}>
          <Typography variant="h6">
            <TruckIcon sx={{ mr: 1 }} />
            Custos TMS
          </Typography>
          <Button
            startIcon={<RefreshIcon />}
            onClick={() => carregarCustos()}
          >
            Atualizar
          </Button>
        </Box>
        <TableContainer>
          <Table>
            <TableHead>
              <TableRow>
                <TableCell>ID</TableCell>
                <TableCell>Tipo</TableCell>
                <TableCell>Descrição</TableCell>
                <TableCell>Valor</TableCell>
                <TableCell>Status</TableCell>
                <TableCell>Ações</TableCell>
              </TableRow>
            </TableHead>
            <TableBody>
              {custosTMS.map((custo) => (
                <TableRow key={custo.id}>
                  <TableCell>{custo.id}</TableCell>
                  <TableCell>{custo.tipo}</TableCell>
                  <TableCell>{custo.descricao}</TableCell>
                  <TableCell>
                    {new Intl.NumberFormat('pt-BR', {
                      style: 'currency',
                      currency: 'BRL'
                    }).format(custo.valor)}
                  </TableCell>
                  <TableCell>
                    <Chip
                      label={custo.status}
                      color={custo.status === 'vinculado' ? 'success' : 'warning'}
                      size="small"
                    />
                  </TableCell>
                  <TableCell>
                    <Tooltip title="Vincular">
                      <IconButton
                        size="small"
                        onClick={() => {
                          setSelectedCusto({ ...custo, origem: 'tms' });
                          setOpenDialog(true);
                        }}
                      >
                        <LinkIcon />
                      </IconButton>
                    </Tooltip>
                    <Tooltip title="Detalhes">
                      <IconButton size="small">
                        <AssignmentIcon />
                      </IconButton>
                    </Tooltip>
                  </TableCell>
                </TableRow>
              ))}
            </TableBody>
          </Table>
        </TableContainer>
      </CardContent>
    </Card>
  );

  const renderVinculoDialog = () => (
    <Dialog
      open={openDialog}
      onClose={() => setOpenDialog(false)}
      maxWidth="md"
      fullWidth
    >
      <DialogTitle>
        Vincular Custo {selectedCusto?.origem.toUpperCase()}
      </DialogTitle>
      <DialogContent>
        <Grid container spacing={2} sx={{ mt: 1 }}>
          <Grid item xs={12}>
            <Alert severity="info" sx={{ mb: 2 }}>
              Vincule este custo a uma {selectedCusto?.origem === 'wms' ? 'operação de armazenagem' : 'viagem'} específica
            </Alert>
          </Grid>
          {selectedCusto?.origem === 'wms' ? (
            <>
              <Grid item xs={12} md={6}>
                <TextField
                  fullWidth
                  label="Código de Armazenagem"
                  variant="outlined"
                />
              </Grid>
              <Grid item xs={12} md={6}>
                <FormControl fullWidth>
                  <InputLabel>Tipo de Operação</InputLabel>
                  <Select>
                    <MenuItem value="entrada">Entrada</MenuItem>
                    <MenuItem value="saida">Saída</MenuItem>
                    <MenuItem value="movimentacao">Movimentação</MenuItem>
                  </Select>
                </FormControl>
              </Grid>
            </>
          ) : (
            <>
              <Grid item xs={12} md={6}>
                <TextField
                  fullWidth
                  label="ID da Viagem"
                  variant="outlined"
                />
              </Grid>
              <Grid item xs={12} md={6}>
                <FormControl fullWidth>
                  <InputLabel>Tipo de Custo</InputLabel>
                  <Select>
                    <MenuItem value="pedagio">Pedágio</MenuItem>
                    <MenuItem value="combustivel">Combustível</MenuItem>
                    <MenuItem value="manutencao">Manutenção</MenuItem>
                  </Select>
                </FormControl>
              </Grid>
            </>
          )}
          <Grid item xs={12}>
            <TextField
              fullWidth
              multiline
              rows={3}
              label="Observações"
              variant="outlined"
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
          onClick={() => vincularCustos(selectedCusto.id, selectedCusto.origem, {})}
        >
          Vincular
        </Button>
      </DialogActions>
    </Dialog>
  );

  return (
    <Box p={3}>
      <Typography variant="h4" gutterBottom>
        Integração de Custos WMS/TMS
      </Typography>

      <Grid container spacing={3} sx={{ mb: 3 }}>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Typography color="textSecondary" gutterBottom>
                Total Custos WMS
              </Typography>
              <Typography variant="h4">
                {new Intl.NumberFormat('pt-BR', {
                  style: 'currency',
                  currency: 'BRL'
                }).format(custosWMS.reduce((acc, curr) => acc + curr.valor, 0))}
              </Typography>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Typography color="textSecondary" gutterBottom>
                Total Custos TMS
              </Typography>
              <Typography variant="h4">
                {new Intl.NumberFormat('pt-BR', {
                  style: 'currency',
                  currency: 'BRL'
                }).format(custosTMS.reduce((acc, curr) => acc + curr.valor, 0))}
              </Typography>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Typography color="textSecondary" gutterBottom>
                Custos Não Vinculados
              </Typography>
              <Typography variant="h4" color="error">
                {custosWMS.filter(c => c.status !== 'vinculado').length +
                  custosTMS.filter(c => c.status !== 'vinculado').length}
              </Typography>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Typography color="textSecondary" gutterBottom>
                Total Consolidado
              </Typography>
              <Typography variant="h4">
                {new Intl.NumberFormat('pt-BR', {
                  style: 'currency',
                  currency: 'BRL'
                }).format(
                  custosWMS.reduce((acc, curr) => acc + curr.valor, 0) +
                  custosTMS.reduce((acc, curr) => acc + curr.valor, 0)
                )}
              </Typography>
            </CardContent>
          </Card>
        </Grid>
      </Grid>

      {renderCustosWMS()}
      {renderCustosTMS()}
      {selectedCusto && renderVinculoDialog()}
    </Box>
  );
};

export default IntegracaoCustosWMSTMS;
