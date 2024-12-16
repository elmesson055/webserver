import React, { useState, useEffect } from 'react';
import {
  Grid,
  Card,
  CardContent,
  Typography,
  Box,
  Button,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Paper,
  Chip,
  TextField,
  IconButton,
  Menu,
  MenuItem,
  Dialog,
  DialogTitle,
  DialogContent,
  DialogActions,
  Alert,
  Stepper,
  Step,
  StepLabel,
  FormControl,
  InputLabel,
  Select
} from '@mui/material';
import {
  Add as AddIcon,
  FilterList as FilterIcon,
  Print as PrintIcon,
  CloudDownload as ExportIcon,
  ArrowForward as ArrowIcon,
  CheckCircle as CheckIcon,
  Error as ErrorIcon
} from '@mui/icons-material';

const Movimentacoes = () => {
  const [loading, setLoading] = useState(true);
  const [movimentacoes, setMovimentacoes] = useState([]);
  const [filtros, setFiltros] = useState({
    busca: '',
    tipo: '',
    status: ''
  });
  const [dialogOpen, setDialogOpen] = useState(false);
  const [activeStep, setActiveStep] = useState(0);
  const [novaMovimentacao, setNovaMovimentacao] = useState({
    tipo: '',
    produto: '',
    quantidade: '',
    origem: '',
    destino: '',
    observacao: ''
  });
  const [anchorEl, setAnchorEl] = useState(null);
  const [erro, setErro] = useState(null);

  const steps = ['Tipo', 'Produto', 'Localização', 'Confirmação'];

  useEffect(() => {
    carregarMovimentacoes();
  }, [filtros]);

  const carregarMovimentacoes = async () => {
    try {
      const response = await fetch('/api/v1/movimentacoes?' + new URLSearchParams(filtros));
      const data = await response.json();
      setMovimentacoes(data);
    } catch (error) {
      setErro('Erro ao carregar movimentações');
      console.error(error);
    } finally {
      setLoading(false);
    }
  };

  const handleFilterClick = (event) => {
    setAnchorEl(event.currentTarget);
  };

  const handleFilterClose = () => {
    setAnchorEl(null);
  };

  const handleDialogOpen = () => {
    setDialogOpen(true);
  };

  const handleDialogClose = () => {
    setDialogOpen(false);
    setActiveStep(0);
    setNovaMovimentacao({
      tipo: '',
      produto: '',
      quantidade: '',
      origem: '',
      destino: '',
      observacao: ''
    });
  };

  const handleNext = () => {
    setActiveStep((prevStep) => prevStep + 1);
  };

  const handleBack = () => {
    setActiveStep((prevStep) => prevStep - 1);
  };

  const handleSave = async () => {
    try {
      await fetch('/api/v1/movimentacoes', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(novaMovimentacao)
      });

      carregarMovimentacoes();
      handleDialogClose();
    } catch (error) {
      setErro('Erro ao salvar movimentação');
      console.error(error);
    }
  };

  const getStatusColor = (status) => {
    switch (status.toLowerCase()) {
      case 'concluído':
        return 'success';
      case 'em andamento':
        return 'warning';
      case 'erro':
        return 'error';
      default:
        return 'default';
    }
  };

  const getStepContent = (step) => {
    switch (step) {
      case 0:
        return (
          <FormControl fullWidth>
            <InputLabel>Tipo de Movimentação</InputLabel>
            <Select
              value={novaMovimentacao.tipo}
              onChange={(e) => setNovaMovimentacao({ ...novaMovimentacao, tipo: e.target.value })}
            >
              <MenuItem value="entrada">Entrada</MenuItem>
              <MenuItem value="saida">Saída</MenuItem>
              <MenuItem value="transferencia">Transferência</MenuItem>
            </Select>
          </FormControl>
        );
      case 1:
        return (
          <Grid container spacing={2}>
            <Grid item xs={12}>
              <TextField
                fullWidth
                label="Produto"
                value={novaMovimentacao.produto}
                onChange={(e) => setNovaMovimentacao({ ...novaMovimentacao, produto: e.target.value })}
              />
            </Grid>
            <Grid item xs={12}>
              <TextField
                fullWidth
                type="number"
                label="Quantidade"
                value={novaMovimentacao.quantidade}
                onChange={(e) => setNovaMovimentacao({ ...novaMovimentacao, quantidade: e.target.value })}
              />
            </Grid>
          </Grid>
        );
      case 2:
        return (
          <Grid container spacing={2}>
            <Grid item xs={12}>
              <TextField
                fullWidth
                label="Origem"
                value={novaMovimentacao.origem}
                onChange={(e) => setNovaMovimentacao({ ...novaMovimentacao, origem: e.target.value })}
              />
            </Grid>
            <Grid item xs={12}>
              <TextField
                fullWidth
                label="Destino"
                value={novaMovimentacao.destino}
                onChange={(e) => setNovaMovimentacao({ ...novaMovimentacao, destino: e.target.value })}
              />
            </Grid>
          </Grid>
        );
      case 3:
        return (
          <Box>
            <Typography variant="h6" gutterBottom>
              Confirmar Movimentação
            </Typography>
            <Typography>Tipo: {novaMovimentacao.tipo}</Typography>
            <Typography>Produto: {novaMovimentacao.produto}</Typography>
            <Typography>Quantidade: {novaMovimentacao.quantidade}</Typography>
            <Typography>Origem: {novaMovimentacao.origem}</Typography>
            <Typography>Destino: {novaMovimentacao.destino}</Typography>
          </Box>
        );
      default:
        return 'Passo desconhecido';
    }
  };

  return (
    <Box p={3}>
      <Typography variant="h4" gutterBottom>
        Movimentações
      </Typography>

      {erro && (
        <Alert severity="error" sx={{ mb: 2 }}>
          {erro}
        </Alert>
      )}

      {/* Barra de Ações */}
      <Box display="flex" justifyContent="space-between" alignItems="center" mb={3}>
        <Box display="flex" alignItems="center">
          <TextField
            size="small"
            placeholder="Buscar movimentações..."
            value={filtros.busca}
            onChange={(e) => setFiltros({ ...filtros, busca: e.target.value })}
            sx={{ mr: 2 }}
          />
          <Button
            variant="outlined"
            startIcon={<FilterIcon />}
            onClick={handleFilterClick}
          >
            Filtros
          </Button>
          <Menu
            anchorEl={anchorEl}
            open={Boolean(anchorEl)}
            onClose={handleFilterClose}
          >
            <MenuItem>Tipo</MenuItem>
            <MenuItem>Status</MenuItem>
            <MenuItem>Data</MenuItem>
          </Menu>
        </Box>
        <Box>
          <Button
            variant="contained"
            startIcon={<AddIcon />}
            onClick={handleDialogOpen}
            sx={{ mr: 1 }}
          >
            Nova Movimentação
          </Button>
          <Button
            variant="outlined"
            startIcon={<PrintIcon />}
            sx={{ mr: 1 }}
          >
            Imprimir
          </Button>
          <Button
            variant="outlined"
            startIcon={<ExportIcon />}
          >
            Exportar
          </Button>
        </Box>
      </Box>

      {/* Lista de Movimentações */}
      <TableContainer component={Paper}>
        <Table>
          <TableHead>
            <TableRow>
              <TableCell>ID</TableCell>
              <TableCell>Data/Hora</TableCell>
              <TableCell>Tipo</TableCell>
              <TableCell>Produto</TableCell>
              <TableCell>Quantidade</TableCell>
              <TableCell>Origem</TableCell>
              <TableCell>Destino</TableCell>
              <TableCell>Status</TableCell>
              <TableCell>Responsável</TableCell>
            </TableRow>
          </TableHead>
          <TableBody>
            {movimentacoes.map((mov) => (
              <TableRow key={mov.id}>
                <TableCell>{mov.id}</TableCell>
                <TableCell>{mov.dataHora}</TableCell>
                <TableCell>
                  <Chip
                    label={mov.tipo}
                    color={mov.tipo === 'entrada' ? 'success' : mov.tipo === 'saida' ? 'error' : 'warning'}
                    size="small"
                  />
                </TableCell>
                <TableCell>{mov.produto}</TableCell>
                <TableCell>{mov.quantidade}</TableCell>
                <TableCell>{mov.origem}</TableCell>
                <TableCell>{mov.destino}</TableCell>
                <TableCell>
                  <Chip
                    label={mov.status}
                    color={getStatusColor(mov.status)}
                    size="small"
                    icon={mov.status === 'concluído' ? <CheckIcon /> : mov.status === 'erro' ? <ErrorIcon /> : undefined}
                  />
                </TableCell>
                <TableCell>{mov.responsavel}</TableCell>
              </TableRow>
            ))}
          </TableBody>
        </Table>
      </TableContainer>

      {/* Dialog de Nova Movimentação */}
      <Dialog
        open={dialogOpen}
        onClose={handleDialogClose}
        maxWidth="md"
        fullWidth
      >
        <DialogTitle>
          Nova Movimentação
        </DialogTitle>
        <DialogContent>
          <Box sx={{ mt: 2 }}>
            <Stepper activeStep={activeStep}>
              {steps.map((label) => (
                <Step key={label}>
                  <StepLabel>{label}</StepLabel>
                </Step>
              ))}
            </Stepper>
            <Box sx={{ mt: 3 }}>
              {getStepContent(activeStep)}
            </Box>
          </Box>
        </DialogContent>
        <DialogActions>
          <Button onClick={handleDialogClose}>Cancelar</Button>
          <Button
            disabled={activeStep === 0}
            onClick={handleBack}
          >
            Voltar
          </Button>
          {activeStep === steps.length - 1 ? (
            <Button
              variant="contained"
              onClick={handleSave}
            >
              Confirmar
            </Button>
          ) : (
            <Button
              variant="contained"
              onClick={handleNext}
            >
              Próximo
            </Button>
          )}
        </DialogActions>
      </Dialog>
    </Box>
  );
};

export default Movimentacoes;
