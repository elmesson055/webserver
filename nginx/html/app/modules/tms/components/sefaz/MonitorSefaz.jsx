import React, { useState, useEffect } from 'react';
import {
  Box,
  Card,
  CardContent,
  Grid,
  Typography,
  Paper,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Chip,
  IconButton,
  Tooltip,
  Alert,
  LinearProgress
} from '@mui/material';
import {
  CheckCircle as CheckCircleIcon,
  Error as ErrorIcon,
  Warning as WarningIcon,
  Refresh as RefreshIcon,
  Timeline as TimelineIcon
} from '@mui/icons-material';

import SEFAZIntegracoes from '../../services/integracoes/SEFAZIntegracoes';

const MonitorSefaz = () => {
  // Estados
  const [loading, setLoading] = useState(false);
  const [statusServicos, setStatusServicos] = useState({
    autorizadores: [],
    contingencia: false,
    ultimaVerificacao: null
  });

  // Efeitos
  useEffect(() => {
    verificarStatus();
    const interval = setInterval(verificarStatus, 300000); // 5 minutos
    return () => clearInterval(interval);
  }, []);

  // Funções
  const verificarStatus = async () => {
    setLoading(true);
    try {
      const servicos = ['cte', 'mdfe', 'nfe'];
      const status = {
        autorizadores: [],
        contingencia: false,
        ultimaVerificacao: new Date()
      };

      for (const servico of servicos) {
        const response = await SEFAZIntegracoes.statusServico(servico);
        if (response.success) {
          status.autorizadores.push({
            servico,
            status: response.data.status,
            tempoMedio: response.data.tempoMedio,
            ultimaOcorrencia: response.data.ultimaOcorrencia
          });
        }
      }

      setStatusServicos(status);
    } catch (error) {
      console.error('Erro ao verificar status:', error);
    } finally {
      setLoading(false);
    }
  };

  // Renderização
  const renderStatusIcon = (status) => {
    switch (status) {
      case 'online':
        return <CheckCircleIcon color="success" />;
      case 'offline':
        return <ErrorIcon color="error" />;
      default:
        return <WarningIcon color="warning" />;
    }
  };

  if (loading) {
    return <LinearProgress />;
  }

  return (
    <Box p={3}>
      <Typography variant="h4" gutterBottom>
        Monitor SEFAZ
      </Typography>

      <Grid container spacing={3} sx={{ mb: 3 }}>
        <Grid item xs={12} md={4}>
          <Card>
            <CardContent>
              <Typography color="textSecondary" gutterBottom>
                Status Geral
              </Typography>
              <Box display="flex" alignItems="center">
                {statusServicos.contingencia ? (
                  <Chip
                    icon={<WarningIcon />}
                    label="Contingência"
                    color="warning"
                  />
                ) : (
                  <Chip
                    icon={<CheckCircleIcon />}
                    label="Normal"
                    color="success"
                  />
                )}
              </Box>
            </CardContent>
          </Card>
        </Grid>

        <Grid item xs={12} md={4}>
          <Card>
            <CardContent>
              <Typography color="textSecondary" gutterBottom>
                Última Verificação
              </Typography>
              <Typography>
                {statusServicos.ultimaVerificacao?.toLocaleString()}
              </Typography>
            </CardContent>
          </Card>
        </Grid>

        <Grid item xs={12} md={4}>
          <Card>
            <CardContent>
              <Typography color="textSecondary" gutterBottom>
                Tempo Médio de Resposta
              </Typography>
              <Box display="flex" alignItems="center">
                <TimelineIcon sx={{ mr: 1 }} />
                <Typography>
                  {statusServicos.autorizadores.reduce((acc, curr) => acc + curr.tempoMedio, 0) / 
                   statusServicos.autorizadores.length || 0}ms
                </Typography>
              </Box>
            </CardContent>
          </Card>
        </Grid>
      </Grid>

      <Paper>
        <Box p={2} display="flex" justifyContent="space-between" alignItems="center">
          <Typography variant="h6">
            Status dos Serviços
          </Typography>
          <Tooltip title="Atualizar">
            <IconButton onClick={verificarStatus}>
              <RefreshIcon />
            </IconButton>
          </Tooltip>
        </Box>

        <TableContainer>
          <Table>
            <TableHead>
              <TableRow>
                <TableCell>Serviço</TableCell>
                <TableCell>Status</TableCell>
                <TableCell>Tempo de Resposta</TableCell>
                <TableCell>Última Ocorrência</TableCell>
              </TableRow>
            </TableHead>
            <TableBody>
              {statusServicos.autorizadores.map((autorizador) => (
                <TableRow key={autorizador.servico}>
                  <TableCell>
                    <Box display="flex" alignItems="center">
                      {renderStatusIcon(autorizador.status)}
                      <Typography sx={{ ml: 1 }}>
                        {autorizador.servico.toUpperCase()}
                      </Typography>
                    </Box>
                  </TableCell>
                  <TableCell>
                    <Chip
                      label={autorizador.status}
                      color={
                        autorizador.status === 'online' ? 'success' :
                        autorizador.status === 'offline' ? 'error' :
                        'warning'
                      }
                      size="small"
                    />
                  </TableCell>
                  <TableCell>
                    {autorizador.tempoMedio}ms
                  </TableCell>
                  <TableCell>
                    {autorizador.ultimaOcorrencia ?
                      new Date(autorizador.ultimaOcorrencia).toLocaleString() :
                      'Nenhuma ocorrência'
                    }
                  </TableCell>
                </TableRow>
              ))}
            </TableBody>
          </Table>
        </TableContainer>
      </Paper>

      {statusServicos.contingencia && (
        <Alert severity="warning" sx={{ mt: 3 }}>
          Sistema operando em modo de contingência. Alguns serviços podem estar indisponíveis.
        </Alert>
      )}
    </Box>
  );
};

export default MonitorSefaz;
