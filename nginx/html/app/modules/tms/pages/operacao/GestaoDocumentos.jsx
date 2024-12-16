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
  List,
  ListItem,
  ListItemIcon,
  ListItemText,
  ListItemSecondaryAction,
  Tabs,
  Tab
} from '@mui/material';
import {
  Add as AddIcon,
  Description,
  PictureAsPdf,
  Image,
  AttachFile,
  Delete as DeleteIcon,
  Download as DownloadIcon,
  Share as ShareIcon,
  Print as PrintIcon,
  Search as SearchIcon,
  Visibility as ViewIcon,
  Edit as EditIcon,
  CloudUpload as UploadIcon,
  LocalShipping,
  Person,
  Business,
  Assignment
} from '@mui/icons-material';

const GestaoDocumentos = () => {
  const [loading, setLoading] = useState(true);
  const [tabValue, setTabValue] = useState(0);
  const [documentos, setDocumentos] = useState([]);
  const [selectedDoc, setSelectedDoc] = useState(null);
  const [openDialog, setOpenDialog] = useState(false);
  const [dialogType, setDialogType] = useState('');
  const [metricas, setMetricas] = useState({
    totalDocumentos: 0,
    pendentes: 0,
    vencidos: 0,
    digitalizados: 0
  });
  const [filtros, setFiltros] = useState({
    tipo: 'todos',
    status: 'todos',
    periodo: '30'
  });

  useEffect(() => {
    carregarDados();
  }, [filtros]);

  const carregarDados = async () => {
    setLoading(true);
    try {
      const [metricasRes, documentosRes] = await Promise.all([
        fetch('/api/v1/tms/documentos/metricas'),
        fetch('/api/v1/tms/documentos/lista')
      ]);

      const metricasData = await metricasRes.json();
      const documentosData = await documentosRes.json();

      setMetricas(metricasData);
      setDocumentos(documentosData);
    } catch (error) {
      console.error('Erro ao carregar dados:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleTabChange = (event, newValue) => {
    setTabValue(newValue);
  };

  const handleOpenDialog = (type, doc = null) => {
    setDialogType(type);
    setSelectedDoc(doc);
    setOpenDialog(true);
  };

  const handleCloseDialog = () => {
    setDialogType('');
    setSelectedDoc(null);
    setOpenDialog(false);
  };

  const getStatusColor = (status) => {
    switch (status.toLowerCase()) {
      case 'pendente':
        return 'warning';
      case 'aprovado':
        return 'success';
      case 'vencido':
        return 'error';
      case 'em análise':
        return 'info';
      default:
        return 'default';
    }
  };

  const getIconByType = (type) => {
    switch (type.toLowerCase()) {
      case 'pdf':
        return <PictureAsPdf color="error" />;
      case 'imagem':
        return <Image color="primary" />;
      case 'documento':
        return <Description color="info" />;
      default:
        return <AttachFile />;
    }
  };

  const renderKPIs = () => (
    <Grid container spacing={3} mb={3}>
      <Grid item xs={12} md={3}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <Description color="primary" />
              <Typography variant="subtitle2" ml={1}>
                Total de Documentos
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.totalDocumentos}</Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={3}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <Assignment color="warning" />
              <Typography variant="subtitle2" ml={1}>
                Pendentes
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.pendentes}</Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={3}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <Assignment color="error" />
              <Typography variant="subtitle2" ml={1}>
                Vencidos
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.vencidos}</Typography>
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={12} md={3}>
        <Card>
          <CardContent>
            <Box display="flex" alignItems="center" mb={1}>
              <Assignment color="success" />
              <Typography variant="subtitle2" ml={1}>
                Digitalizados
              </Typography>
            </Box>
            <Typography variant="h4">{metricas.digitalizados}</Typography>
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
            <InputLabel>Tipo</InputLabel>
            <Select
              value={filtros.tipo}
              onChange={(e) => setFiltros({ ...filtros, tipo: e.target.value })}
            >
              <MenuItem value="todos">Todos</MenuItem>
              <MenuItem value="cte">Conhecimento de Transporte</MenuItem>
              <MenuItem value="nfe">Nota Fiscal</MenuItem>
              <MenuItem value="manifesto">Manifesto</MenuItem>
              <MenuItem value="comprovante">Comprovante</MenuItem>
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
              <MenuItem value="pendente">Pendente</MenuItem>
              <MenuItem value="aprovado">Aprovado</MenuItem>
              <MenuItem value="vencido">Vencido</MenuItem>
              <MenuItem value="em_analise">Em Análise</MenuItem>
            </Select>
          </FormControl>
        </Grid>
        <Grid item xs={12} md={3}>
          <FormControl fullWidth>
            <InputLabel>Período</InputLabel>
            <Select
              value={filtros.periodo}
              onChange={(e) => setFiltros({ ...filtros, periodo: e.target.value })}
            >
              <MenuItem value="7">Últimos 7 dias</MenuItem>
              <MenuItem value="15">Últimos 15 dias</MenuItem>
              <MenuItem value="30">Últimos 30 dias</MenuItem>
              <MenuItem value="90">Últimos 90 dias</MenuItem>
            </Select>
          </FormControl>
        </Grid>
        <Grid item xs={12} md={3}>
          <Button
            variant="contained"
            startIcon={<UploadIcon />}
            onClick={() => handleOpenDialog('upload')}
            fullWidth
          >
            Upload
          </Button>
        </Grid>
      </Grid>
    </Paper>
  );

  const renderDocumentos = () => (
    <TableContainer component={Paper}>
      <Table>
        <TableHead>
          <TableRow>
            <TableCell>Tipo</TableCell>
            <TableCell>Número</TableCell>
            <TableCell>Data</TableCell>
            <TableCell>Referência</TableCell>
            <TableCell>Status</TableCell>
            <TableCell>Tamanho</TableCell>
            <TableCell align="center">Ações</TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {documentos.map((doc) => (
            <TableRow key={doc.id}>
              <TableCell>
                <Box display="flex" alignItems="center">
                  {getIconByType(doc.tipo)}
                  <Typography variant="body2" ml={1}>
                    {doc.tipo}
                  </Typography>
                </Box>
              </TableCell>
              <TableCell>{doc.numero}</TableCell>
              <TableCell>{doc.data}</TableCell>
              <TableCell>{doc.referencia}</TableCell>
              <TableCell>
                <Chip
                  label={doc.status}
                  color={getStatusColor(doc.status)}
                  size="small"
                />
              </TableCell>
              <TableCell>{doc.tamanho}</TableCell>
              <TableCell align="center">
                <Tooltip title="Visualizar">
                  <IconButton size="small">
                    <ViewIcon />
                  </IconButton>
                </Tooltip>
                <Tooltip title="Download">
                  <IconButton size="small">
                    <DownloadIcon />
                  </IconButton>
                </Tooltip>
                <Tooltip title="Compartilhar">
                  <IconButton size="small">
                    <ShareIcon />
                  </IconButton>
                </Tooltip>
                <Tooltip title="Imprimir">
                  <IconButton size="small">
                    <PrintIcon />
                  </IconButton>
                </Tooltip>
              </TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>
    </TableContainer>
  );

  const renderUploadDialog = () => (
    <Dialog open={openDialog} onClose={handleCloseDialog} maxWidth="md" fullWidth>
      <DialogTitle>Upload de Documentos</DialogTitle>
      <DialogContent>
        <Box
          sx={{
            border: '2px dashed #ccc',
            borderRadius: 2,
            p: 3,
            textAlign: 'center',
            mt: 2
          }}
        >
          <UploadIcon sx={{ fontSize: 48, color: 'text.secondary' }} />
          <Typography variant="h6" color="text.secondary" mt={2}>
            Arraste e solte arquivos aqui
          </Typography>
          <Typography variant="body2" color="text.secondary">
            ou
          </Typography>
          <Button
            variant="contained"
            component="label"
            sx={{ mt: 2 }}
          >
            Selecionar Arquivos
            <input type="file" hidden multiple />
          </Button>
        </Box>
      </DialogContent>
      <DialogActions>
        <Button onClick={handleCloseDialog}>Cancelar</Button>
        <Button variant="contained" onClick={handleCloseDialog}>
          Enviar
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
        Gestão de Documentos
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
          <Tab icon={<LocalShipping />} label="Transportes" />
          <Tab icon={<Person />} label="Motoristas" />
          <Tab icon={<Business />} label="Empresas" />
          <Tab icon={<Assignment />} label="Outros" />
        </Tabs>
        <Box p={3}>{renderDocumentos()}</Box>
      </Paper>

      {renderUploadDialog()}
    </Box>
  );
};

export default GestaoDocumentos;
