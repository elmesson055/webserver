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
  FormControl,
  InputLabel,
  Select,
  Tooltip
} from '@mui/material';
import {
  Add as AddIcon,
  FilterList as FilterIcon,
  Print as PrintIcon,
  CloudDownload as ExportIcon,
  LocalShipping as TruckIcon,
  Assignment as TaskIcon,
  QrCode as QrCodeIcon,
  ViewModule as RackIcon
} from '@mui/icons-material';

const Armazenagem = () => {
  const [loading, setLoading] = useState(true);
  const [operacoes, setOperacoes] = useState([]);
  const [filtros, setFiltros] = useState({
    busca: '',
    tipo: '',
    status: ''
  });
  const [dialogOpen, setDialogOpen] = useState(false);
  const [selectedOperacao, setSelectedOperacao] = useState(null);
  const [anchorEl, setAnchorEl] = useState(null);
  const [erro, setErro] = useState(null);
  const [metricas, setMetricas] = useState({
    ocupacaoTotal: 0,
    pedidosPendentes: 0,
    eficienciaOperacional: 0,
    sla: 0
  });

  useEffect(() => {
    carregarDados();
  }, [filtros]);

  const carregarDados = async () => {
    try {
      const [operacoesRes, metricasRes] = await Promise.all([
        fetch('/api/v1/armazenagem/operacoes?' + new URLSearchParams(filtros)),
        fetch('/api/v1/armazenagem/metricas')
      ]);

      const operacoesData = await operacoesRes.json();
      const metricasData = await metricasRes.json();

      setOperacoes(operacoesData);
      setMetricas(metricasData);
    } catch (error) {
      setErro('Erro ao carregar dados');
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

  const handleDialogOpen = (operacao = null) => {
    setSelectedOperacao(operacao);
    setDialogOpen(true);
  };

  const handleDialogClose = () => {
    setSelectedOperacao(null);
    setDialogOpen(false);
  };

  const handleSave = async (operacao) => {
    try {
      const method = operacao.id ? 'PUT' : 'POST';
      const url = operacao.id ? `/api/v1/armazenagem/operacoes/${operacao.id}` : '/api/v1/armazenagem/operacoes';
      
      await fetch(url, {
        method,
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(operacao)
      });

      carregarDados();
      handleDialogClose();
    } catch (error) {
      setErro('Erro ao salvar operação');
      console.error(error);
    }
  };

  const getStatusColor = (status) => {
    switch (status.toLowerCase()) {
      case 'concluído':
        return 'success';
      case 'em andamento':
        return 'warning';
      case 'pendente':
        return 'info';
      case 'atrasado':
        return 'error';
      default:
        return 'default';
    }
  };

  return (
    <Box p={3}>
      <Typography variant="h4" gutterBottom>
        Gestão de Armazenagem
      </Typography>

      {erro && (
        <Alert severity="error" sx={{ mb: 2 }}>
          {erro}
        </Alert>
      )}

      {/* Métricas */}
      <Grid container spacing={2} sx={{ mb: 3 }}>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Typography color="textSecondary" gutterBottom>
                Ocupação Total
              </Typography>
              <Typography variant="h4">
                {metricas.ocupacaoTotal}%
              </Typography>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Typography color="textSecondary" gutterBottom>
                Pedidos Pendentes
              </Typography>
              <Typography variant="h4">
                {metricas.pedidosPendentes}
              </Typography>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Typography color="textSecondary" gutterBottom>
                Eficiência Operacional
              </Typography>
              <Typography variant="h4">
                {metricas.eficienciaOperacional}%
              </Typography>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Typography color="textSecondary" gutterBottom>
                SLA
              </Typography>
              <Typography variant="h4">
                {metricas.sla}%
              </Typography>
            </CardContent>
          </Card>
        </Grid>
      </Grid>

      {/* Barra de Ações */}
      <Box display="flex" justifyContent="space-between" alignItems="center" mb={3}>
        <Box display="flex" alignItems="center">
          <TextField
            size="small"
            placeholder="Buscar operações..."
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
            <MenuItem>Prioridade</MenuItem>
          </Menu>
        </Box>
        <Box>
          <Button
            variant="contained"
            startIcon={<AddIcon />}
            onClick={() => handleDialogOpen()}
            sx={{ mr: 1 }}
          >
            Nova Operação
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

      {/* Lista de Operações */}
      <TableContainer component={Paper}>
        <Table>
          <TableHead>
            <TableRow>
              <TableCell>ID</TableCell>
              <TableCell>Data/Hora</TableCell>
              <TableCell>Tipo</TableCell>
              <TableCell>Produto</TableCell>
              <TableCell>Quantidade</TableCell>
              <TableCell>Localização</TableCell>
              <TableCell>Prioridade</TableCell>
              <TableCell>Status</TableCell>
              <TableCell>Operador</TableCell>
              <TableCell>Ações</TableCell>
            </TableRow>
          </TableHead>
          <TableBody>
            {operacoes.map((op) => (
              <TableRow key={op.id}>
                <TableCell>{op.id}</TableCell>
                <TableCell>{op.dataHora}</TableCell>
                <TableCell>
                  <Chip
                    label={op.tipo}
                    size="small"
                    icon={op.tipo === 'recebimento' ? <TruckIcon /> : <TaskIcon />}
                  />
                </TableCell>
                <TableCell>{op.produto}</TableCell>
                <TableCell>{op.quantidade}</TableCell>
                <TableCell>{op.localizacao}</TableCell>
                <TableCell>
                  <Chip
                    label={op.prioridade}
                    color={op.prioridade === 'alta' ? 'error' : op.prioridade === 'média' ? 'warning' : 'default'}
                    size="small"
                  />
                </TableCell>
                <TableCell>
                  <Chip
                    label={op.status}
                    color={getStatusColor(op.status)}
                    size="small"
                  />
                </TableCell>
                <TableCell>{op.operador}</TableCell>
                <TableCell>
                  <Tooltip title="Editar">
                    <IconButton
                      size="small"
                      onClick={() => handleDialogOpen(op)}
                    >
                      <TaskIcon />
                    </IconButton>
                  </Tooltip>
                  <Tooltip title="QR Code">
                    <IconButton size="small">
                      <QrCodeIcon />
                    </IconButton>
                  </Tooltip>
                  <Tooltip title="Ver Localização">
                    <IconButton size="small">
                      <RackIcon />
                    </IconButton>
                  </Tooltip>
                </TableCell>
              </TableRow>
            ))}
          </TableBody>
        </Table>
      </TableContainer>

      {/* Dialog de Operação */}
      <Dialog
        open={dialogOpen}
        onClose={handleDialogClose}
        maxWidth="md"
        fullWidth
      >
        <DialogTitle>
          {selectedOperacao ? 'Editar Operação' : 'Nova Operação'}
        </DialogTitle>
        <DialogContent>
          <Grid container spacing={2} sx={{ mt: 1 }}>
            <Grid item xs={12} md={6}>
              <FormControl fullWidth>
                <InputLabel>Tipo</InputLabel>
                <Select
                  value={selectedOperacao?.tipo || ''}
                  label="Tipo"
                >
                  <MenuItem value="recebimento">Recebimento</MenuItem>
                  <MenuItem value="armazenagem">Armazenagem</MenuItem>
                  <MenuItem value="separacao">Separação</MenuItem>
                </Select>
              </FormControl>
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Produto"
                value={selectedOperacao?.produto || ''}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                type="number"
                label="Quantidade"
                value={selectedOperacao?.quantidade || ''}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Localização"
                value={selectedOperacao?.localizacao || ''}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <FormControl fullWidth>
                <InputLabel>Prioridade</InputLabel>
                <Select
                  value={selectedOperacao?.prioridade || ''}
                  label="Prioridade"
                >
                  <MenuItem value="alta">Alta</MenuItem>
                  <MenuItem value="média">Média</MenuItem>
                  <MenuItem value="baixa">Baixa</MenuItem>
                </Select>
              </FormControl>
            </Grid>
            <Grid item xs={12} md={6}>
              <FormControl fullWidth>
                <InputLabel>Operador</InputLabel>
                <Select
                  value={selectedOperacao?.operador || ''}
                  label="Operador"
                >
                  <MenuItem value="op1">Operador 1</MenuItem>
                  <MenuItem value="op2">Operador 2</MenuItem>
                  <MenuItem value="op3">Operador 3</MenuItem>
                </Select>
              </FormControl>
            </Grid>
            <Grid item xs={12}>
              <TextField
                fullWidth
                multiline
                rows={4}
                label="Observações"
                value={selectedOperacao?.observacoes || ''}
              />
            </Grid>
          </Grid>
        </DialogContent>
        <DialogActions>
          <Button onClick={handleDialogClose}>Cancelar</Button>
          <Button
            variant="contained"
            onClick={() => handleSave(selectedOperacao)}
          >
            Salvar
          </Button>
        </DialogActions>
      </Dialog>
    </Box>
  );
};

export default Armazenagem;
