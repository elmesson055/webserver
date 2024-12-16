import React, { useState, useEffect } from 'react';
import {
  Box,
  Card,
  CardContent,
  Grid,
  Typography,
  Button,
  Paper,
  Stepper,
  Step,
  StepLabel,
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
  Timeline,
  TimelineItem,
  TimelineSeparator,
  TimelineConnector,
  TimelineContent,
  TimelineDot,
  TimelineOppositeContent
} from '@mui/material';
import {
  CheckCircle as ApproveIcon,
  Cancel as RejectIcon,
  AttachMoney as PaymentIcon,
  Assignment as TaskIcon,
  Warning as AlertIcon,
  History as HistoryIcon,
  Schedule as ClockIcon,
  Comment as CommentIcon,
  Attachment as AttachmentIcon
} from '@mui/icons-material';
import { format, differenceInDays, addDays } from 'date-fns';

const FollowUpCustosExtras = () => {
  const [custos, setCustos] = useState([]);
  const [selectedCusto, setSelectedCusto] = useState(null);
  const [openDialog, setOpenDialog] = useState(false);
  const [openHistoryDialog, setOpenHistoryDialog] = useState(false);

  // Estados do Follow-up
  const followUpSteps = [
    { id: 'cadastro', label: 'Cadastro', prazo: 1 }, // 1 dia para cadastro
    { id: 'analise', label: 'Análise', prazo: 2 }, // 2 dias para análise
    { id: 'aprovacao', label: 'Aprovação', prazo: 1 }, // 1 dia para aprovação
    { id: 'financeiro', label: 'Financeiro', prazo: 2 }, // 2 dias para processamento financeiro
    { id: 'pagamento', label: 'Pagamento', prazo: 3 } // 3 dias para pagamento
  ];

  // Regras de Negócio para Alertas
  const regrasAlerta = {
    PRAZO_CADASTRO: 1, // 1 dia para cadastro
    PRAZO_ANALISE: 2, // 2 dias para análise
    PRAZO_APROVACAO: 1, // 1 dia para aprovação
    PRAZO_FINANCEIRO: 2, // 2 dias para processamento financeiro
    PRAZO_PAGAMENTO: 3, // 3 dias para pagamento
    ALERTA_AMARELO: 0.7, // 70% do prazo
    ALERTA_VERMELHO: 1, // 100% do prazo
  };

  useEffect(() => {
    carregarCustosExtras();
  }, []);

  const carregarCustosExtras = async () => {
    try {
      // Simulação de chamada API
      const response = await fetch('/api/v1/tms/custos-extras/follow-up');
      const data = await response.json();
      setCustos(data);
    } catch (error) {
      console.error('Erro ao carregar custos extras:', error);
    }
  };

  const calcularStatusPrazo = (custo) => {
    const etapaAtual = followUpSteps.find(step => step.id === custo.etapa);
    if (!etapaAtual) return 'normal';

    const diasDecorridos = differenceInDays(new Date(), new Date(custo.dataUltimaAtualizacao));
    const prazoCumprido = diasDecorridos / etapaAtual.prazo;

    if (prazoCumprido >= regrasAlerta.ALERTA_VERMELHO) return 'atrasado';
    if (prazoCumprido >= regrasAlerta.ALERTA_AMARELO) return 'alerta';
    return 'normal';
  };

  const getStepColor = (status) => {
    switch (status) {
      case 'atrasado': return 'error';
      case 'alerta': return 'warning';
      default: return 'primary';
    }
  };

  const atualizarEtapa = async (custoId, novaEtapa, observacao) => {
    try {
      // Simulação de chamada API
      await fetch('/api/v1/tms/custos-extras/follow-up/atualizar', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          custoId,
          novaEtapa,
          observacao,
          dataAtualizacao: new Date()
        })
      });
      
      carregarCustosExtras();
    } catch (error) {
      console.error('Erro ao atualizar etapa:', error);
    }
  };

  const renderTimeline = (custo) => (
    <Timeline position="alternate">
      {followUpSteps.map((step, index) => {
        const isCompleted = custo.etapasConcluidas.includes(step.id);
        const isAtual = custo.etapa === step.id;
        const status = isAtual ? calcularStatusPrazo(custo) : 'normal';

        return (
          <TimelineItem key={step.id}>
            <TimelineOppositeContent>
              {isCompleted && custo.historicoEtapas[step.id] && (
                <Typography variant="caption">
                  {format(new Date(custo.historicoEtapas[step.id].data), 'dd/MM/yyyy HH:mm')}
                </Typography>
              )}
            </TimelineOppositeContent>
            <TimelineSeparator>
              <TimelineDot
                color={isCompleted ? 'success' : isAtual ? getStepColor(status) : 'grey'}
                variant={isAtual ? 'outlined' : 'filled'}
              >
                {isCompleted ? <CheckCircle /> : <Circle />}
              </TimelineDot>
              {index < followUpSteps.length - 1 && <TimelineConnector />}
            </TimelineSeparator>
            <TimelineContent>
              <Typography variant="h6" component="span">
                {step.label}
              </Typography>
              {isAtual && (
                <Typography variant="caption" display="block">
                  Prazo: {step.prazo} dias
                </Typography>
              )}
            </TimelineContent>
          </TimelineItem>
        );
      })}
    </Timeline>
  );

  const renderHistorico = (custo) => (
    <Dialog
      open={openHistoryDialog}
      onClose={() => setOpenHistoryDialog(false)}
      maxWidth="md"
      fullWidth
    >
      <DialogTitle>Histórico de Follow-up</DialogTitle>
      <DialogContent>
        <Timeline>
          {custo.historico.map((evento, index) => (
            <TimelineItem key={index}>
              <TimelineOppositeContent>
                <Typography variant="caption">
                  {format(new Date(evento.data), 'dd/MM/yyyy HH:mm')}
                </Typography>
              </TimelineOppositeContent>
              <TimelineSeparator>
                <TimelineDot color={getEventColor(evento.tipo)}>
                  {getEventIcon(evento.tipo)}
                </TimelineDot>
                {index < custo.historico.length - 1 && <TimelineConnector />}
              </TimelineSeparator>
              <TimelineContent>
                <Typography variant="body2">
                  {evento.descricao}
                </Typography>
                {evento.observacao && (
                  <Typography variant="caption" color="textSecondary">
                    {evento.observacao}
                  </Typography>
                )}
              </TimelineContent>
            </TimelineItem>
          ))}
        </Timeline>
      </DialogContent>
    </Dialog>
  );

  const renderTabela = () => (
    <TableContainer component={Paper}>
      <Table>
        <TableHead>
          <TableRow>
            <TableCell>ID</TableCell>
            <TableCell>Data Cadastro</TableCell>
            <TableCell>Tipo</TableCell>
            <TableCell>Valor</TableCell>
            <TableCell>Etapa Atual</TableCell>
            <TableCell>Status</TableCell>
            <TableCell>Prazo</TableCell>
            <TableCell>Ações</TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {custos.map((custo) => {
            const statusPrazo = calcularStatusPrazo(custo);
            return (
              <TableRow key={custo.id}>
                <TableCell>{custo.id}</TableCell>
                <TableCell>
                  {format(new Date(custo.dataCadastro), 'dd/MM/yyyy')}
                </TableCell>
                <TableCell>{custo.tipo}</TableCell>
                <TableCell>
                  {new Intl.NumberFormat('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                  }).format(custo.valor)}
                </TableCell>
                <TableCell>
                  {followUpSteps.find(step => step.id === custo.etapa)?.label}
                </TableCell>
                <TableCell>
                  <Chip
                    label={statusPrazo}
                    color={getStepColor(statusPrazo)}
                    size="small"
                  />
                </TableCell>
                <TableCell>
                  {differenceInDays(
                    addDays(
                      new Date(custo.dataUltimaAtualizacao),
                      followUpSteps.find(step => step.id === custo.etapa)?.prazo || 0
                    ),
                    new Date()
                  )} dias
                </TableCell>
                <TableCell>
                  <Tooltip title="Ver Timeline">
                    <IconButton
                      size="small"
                      onClick={() => {
                        setSelectedCusto(custo);
                        setOpenDialog(true);
                      }}
                    >
                      <TaskIcon />
                    </IconButton>
                  </Tooltip>
                  <Tooltip title="Histórico">
                    <IconButton
                      size="small"
                      onClick={() => {
                        setSelectedCusto(custo);
                        setOpenHistoryDialog(true);
                      }}
                    >
                      <HistoryIcon />
                    </IconButton>
                  </Tooltip>
                  {getAcoesEtapa(custo)}
                </TableCell>
              </TableRow>
            );
          })}
        </TableBody>
      </Table>
    </TableContainer>
  );

  const getAcoesEtapa = (custo) => {
    switch (custo.etapa) {
      case 'analise':
        return (
          <>
            <Tooltip title="Aprovar">
              <IconButton
                size="small"
                color="success"
                onClick={() => atualizarEtapa(custo.id, 'aprovacao', 'Aprovado após análise')}
              >
                <ApproveIcon />
              </IconButton>
            </Tooltip>
            <Tooltip title="Rejeitar">
              <IconButton
                size="small"
                color="error"
                onClick={() => atualizarEtapa(custo.id, 'rejeitado', 'Rejeitado após análise')}
              >
                <RejectIcon />
              </IconButton>
            </Tooltip>
          </>;
        
      case 'aprovacao':
        return (
          <Tooltip title="Enviar para Financeiro">
            <IconButton
              size="small"
              color="primary"
              onClick={() => atualizarEtapa(custo.id, 'financeiro', 'Enviado para processamento financeiro')}
            >
              <PaymentIcon />
            </IconButton>
          </Tooltip>
        );

      case 'financeiro':
        return (
          <Tooltip title="Aprovar Pagamento">
            <IconButton
              size="small"
              color="primary"
              onClick={() => atualizarEtapa(custo.id, 'pagamento', 'Aprovado para pagamento')}
            >
              <PaymentIcon />
            </IconButton>
          </Tooltip>
        );

      default:
        return null;
    }
  };

  const getEventColor = (tipo) => {
    switch (tipo) {
      case 'aprovacao': return 'success';
      case 'rejeicao': return 'error';
      case 'alerta': return 'warning';
      default: return 'primary';
    }
  };

  const getEventIcon = (tipo) => {
    switch (tipo) {
      case 'aprovacao': return <ApproveIcon />;
      case 'rejeicao': return <RejectIcon />;
      case 'alerta': return <AlertIcon />;
      case 'comentario': return <CommentIcon />;
      case 'anexo': return <AttachmentIcon />;
      default: return <TaskIcon />;
    }
  };

  return (
    <Box p={3}>
      <Typography variant="h4" gutterBottom>
        Follow-up de Custos Extras
      </Typography>

      <Grid container spacing={2} sx={{ mb: 3 }}>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Typography color="textSecondary" gutterBottom>
                Total em Análise
              </Typography>
              <Typography variant="h4">
                {custos.filter(c => c.etapa === 'analise').length}
              </Typography>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Typography color="textSecondary" gutterBottom>
                Aguardando Aprovação
              </Typography>
              <Typography variant="h4">
                {custos.filter(c => c.etapa === 'aprovacao').length}
              </Typography>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Typography color="textSecondary" gutterBottom>
                Em Processamento
              </Typography>
              <Typography variant="h4">
                {custos.filter(c => c.etapa === 'financeiro').length}
              </Typography>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Typography color="textSecondary" gutterBottom>
                Atrasados
              </Typography>
              <Typography variant="h4" color="error">
                {custos.filter(c => calcularStatusPrazo(c) === 'atrasado').length}
              </Typography>
            </CardContent>
          </Card>
        </Grid>
      </Grid>

      {renderTabela()}

      {selectedCusto && (
        <Dialog
          open={openDialog}
          onClose={() => setOpenDialog(false)}
          maxWidth="md"
          fullWidth
        >
          <DialogTitle>
            Timeline do Custo Extra #{selectedCusto.id}
          </DialogTitle>
          <DialogContent>
            {renderTimeline(selectedCusto)}
          </DialogContent>
        </Dialog>
      )}

      {selectedCusto && renderHistorico(selectedCusto)}
    </Box>
  );
};

export default FollowUpCustosExtras;
