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
  LinearProgress
} from '@mui/material';
import {
  Add as AddIcon,
  Edit as EditIcon,
  Delete as DeleteIcon,
  FilterList as FilterIcon,
  Print as PrintIcon,
  CloudDownload as ExportIcon,
  QrCode as QrCodeIcon,
  Inventory as InventoryIcon
} from '@mui/icons-material';

const Inventario = () => {
  const [loading, setLoading] = useState(true);
  const [produtos, setProdutos] = useState([]);
  const [filtros, setFiltros] = useState({
    busca: '',
    categoria: '',
    status: ''
  });
  const [dialogOpen, setDialogOpen] = useState(false);
  const [selectedProduto, setSelectedProduto] = useState(null);
  const [anchorEl, setAnchorEl] = useState(null);
  const [erro, setErro] = useState(null);

  useEffect(() => {
    carregarProdutos();
  }, [filtros]);

  const carregarProdutos = async () => {
    try {
      const response = await fetch('/api/v1/estoque/produtos?' + new URLSearchParams(filtros));
      const data = await response.json();
      setProdutos(data);
    } catch (error) {
      setErro('Erro ao carregar produtos');
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

  const handleDialogOpen = (produto = null) => {
    setSelectedProduto(produto);
    setDialogOpen(true);
  };

  const handleDialogClose = () => {
    setSelectedProduto(null);
    setDialogOpen(false);
  };

  const handleSave = async (produto) => {
    try {
      const method = produto.id ? 'PUT' : 'POST';
      const url = produto.id ? `/api/v1/estoque/produtos/${produto.id}` : '/api/v1/estoque/produtos';
      
      await fetch(url, {
        method,
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(produto)
      });

      carregarProdutos();
      handleDialogClose();
    } catch (error) {
      setErro('Erro ao salvar produto');
      console.error(error);
    }
  };

  const getStatusColor = (status) => {
    switch (status.toLowerCase()) {
      case 'disponível':
        return 'success';
      case 'baixo':
        return 'warning';
      case 'crítico':
        return 'error';
      default:
        return 'default';
    }
  };

  return (
    <Box p={3}>
      <Typography variant="h4" gutterBottom>
        Gestão de Estoque
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
            placeholder="Buscar produtos..."
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
            <MenuItem>Categoria</MenuItem>
            <MenuItem>Status</MenuItem>
            <MenuItem>Localização</MenuItem>
          </Menu>
        </Box>
        <Box>
          <Button
            variant="contained"
            startIcon={<AddIcon />}
            onClick={() => handleDialogOpen()}
            sx={{ mr: 1 }}
          >
            Novo Produto
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

      {/* Lista de Produtos */}
      <TableContainer component={Paper}>
        <Table>
          <TableHead>
            <TableRow>
              <TableCell>Código</TableCell>
              <TableCell>Produto</TableCell>
              <TableCell>Categoria</TableCell>
              <TableCell>Quantidade</TableCell>
              <TableCell>Localização</TableCell>
              <TableCell>Status</TableCell>
              <TableCell>Última Movimentação</TableCell>
              <TableCell>Ações</TableCell>
            </TableRow>
          </TableHead>
          <TableBody>
            {produtos.map((produto) => (
              <TableRow key={produto.id}>
                <TableCell>{produto.codigo}</TableCell>
                <TableCell>{produto.nome}</TableCell>
                <TableCell>{produto.categoria}</TableCell>
                <TableCell>
                  <Box display="flex" alignItems="center">
                    {produto.quantidade}
                    <LinearProgress
                      variant="determinate"
                      value={(produto.quantidade / produto.quantidadeMaxima) * 100}
                      sx={{ ml: 1, width: 100 }}
                    />
                  </Box>
                </TableCell>
                <TableCell>{produto.localizacao}</TableCell>
                <TableCell>
                  <Chip
                    label={produto.status}
                    color={getStatusColor(produto.status)}
                    size="small"
                  />
                </TableCell>
                <TableCell>{produto.ultimaMovimentacao}</TableCell>
                <TableCell>
                  <IconButton
                    size="small"
                    onClick={() => handleDialogOpen(produto)}
                  >
                    <EditIcon />
                  </IconButton>
                  <IconButton size="small">
                    <QrCodeIcon />
                  </IconButton>
                  <IconButton size="small">
                    <InventoryIcon />
                  </IconButton>
                </TableCell>
              </TableRow>
            ))}
          </TableBody>
        </Table>
      </TableContainer>

      {/* Dialog de Produto */}
      <Dialog
        open={dialogOpen}
        onClose={handleDialogClose}
        maxWidth="md"
        fullWidth
      >
        <DialogTitle>
          {selectedProduto ? 'Editar Produto' : 'Novo Produto'}
        </DialogTitle>
        <DialogContent>
          <Grid container spacing={2} sx={{ mt: 1 }}>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Nome do Produto"
                value={selectedProduto?.nome || ''}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Código"
                value={selectedProduto?.codigo || ''}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Categoria"
                value={selectedProduto?.categoria || ''}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Localização"
                value={selectedProduto?.localizacao || ''}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                type="number"
                label="Quantidade"
                value={selectedProduto?.quantidade || ''}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                type="number"
                label="Quantidade Máxima"
                value={selectedProduto?.quantidadeMaxima || ''}
              />
            </Grid>
          </Grid>
        </DialogContent>
        <DialogActions>
          <Button onClick={handleDialogClose}>Cancelar</Button>
          <Button
            variant="contained"
            onClick={() => handleSave(selectedProduto)}
          >
            Salvar
          </Button>
        </DialogActions>
      </Dialog>
    </Box>
  );
};

export default Inventario;
