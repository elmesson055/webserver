import React, { useState, useEffect, useRef } from 'react';
import {
  Box,
  Paper,
  TextField,
  IconButton,
  Typography,
  Avatar,
  List,
  ListItem,
  ListItemAvatar,
  ListItemText,
  CircularProgress,
  Divider,
  Card,
  CardContent,
  Grid
} from '@mui/material';
import {
  Send as SendIcon,
  SmartToy as BotIcon,
  Person as PersonIcon,
  AttachFile as AttachFileIcon
} from '@mui/icons-material';

const Chatbot = () => {
  const [mensagens, setMensagens] = useState([]);
  const [novaMensagem, setNovaMensagem] = useState('');
  const [loading, setLoading] = useState(false);
  const [typing, setTyping] = useState(false);
  const chatRef = useRef(null);
  const [metricas, setMetricas] = useState({
    totalConversas: 0,
    satisfacaoMedia: 0,
    tempoMedioResposta: 0,
    taxaResolucao: 0
  });

  useEffect(() => {
    carregarHistorico();
    carregarMetricas();
  }, []);

  useEffect(() => {
    if (chatRef.current) {
      chatRef.current.scrollTop = chatRef.current.scrollHeight;
    }
  }, [mensagens]);

  const carregarHistorico = async () => {
    try {
      const response = await fetch('/api/v1/chatbot/historico');
      const data = await response.json();
      setMensagens(data);
    } catch (error) {
      console.error('Erro ao carregar histórico:', error);
    }
  };

  const carregarMetricas = async () => {
    try {
      const response = await fetch('/api/v1/chatbot/metricas');
      const data = await response.json();
      setMetricas(data);
    } catch (error) {
      console.error('Erro ao carregar métricas:', error);
    }
  };

  const enviarMensagem = async () => {
    if (!novaMensagem.trim()) return;

    const mensagemUsuario = {
      id: Date.now(),
      texto: novaMensagem,
      tipo: 'usuario',
      timestamp: new Date().toISOString()
    };

    setMensagens([...mensagens, mensagemUsuario]);
    setNovaMensagem('');
    setTyping(true);

    try {
      const response = await fetch('/api/v1/chatbot/mensagem', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ mensagem: novaMensagem })
      });

      const data = await response.json();

      setMensagens(msgs => [...msgs, {
        id: Date.now() + 1,
        texto: data.resposta,
        tipo: 'bot',
        timestamp: new Date().toISOString()
      }]);
    } catch (error) {
      console.error('Erro ao enviar mensagem:', error);
      setMensagens(msgs => [...msgs, {
        id: Date.now() + 1,
        texto: 'Desculpe, ocorreu um erro ao processar sua mensagem.',
        tipo: 'bot',
        timestamp: new Date().toISOString()
      }]);
    } finally {
      setTyping(false);
    }
  };

  const handleKeyPress = (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      enviarMensagem();
    }
  };

  return (
    <Box p={3}>
      <Grid container spacing={3}>
        {/* Métricas */}
        <Grid item xs={12}>
          <Grid container spacing={2}>
            <Grid item xs={12} md={3}>
              <Card>
                <CardContent>
                  <Typography color="textSecondary" gutterBottom>
                    Total de Conversas
                  </Typography>
                  <Typography variant="h4">
                    {metricas.totalConversas}
                  </Typography>
                </CardContent>
              </Card>
            </Grid>
            <Grid item xs={12} md={3}>
              <Card>
                <CardContent>
                  <Typography color="textSecondary" gutterBottom>
                    Satisfação Média
                  </Typography>
                  <Typography variant="h4">
                    {metricas.satisfacaoMedia}%
                  </Typography>
                </CardContent>
              </Card>
            </Grid>
            <Grid item xs={12} md={3}>
              <Card>
                <CardContent>
                  <Typography color="textSecondary" gutterBottom>
                    Tempo Médio de Resposta
                  </Typography>
                  <Typography variant="h4">
                    {metricas.tempoMedioResposta}s
                  </Typography>
                </CardContent>
              </Card>
            </Grid>
            <Grid item xs={12} md={3}>
              <Card>
                <CardContent>
                  <Typography color="textSecondary" gutterBottom>
                    Taxa de Resolução
                  </Typography>
                  <Typography variant="h4">
                    {metricas.taxaResolucao}%
                  </Typography>
                </CardContent>
              </Card>
            </Grid>
          </Grid>
        </Grid>

        {/* Chat */}
        <Grid item xs={12}>
          <Paper elevation={3} sx={{ height: '600px', display: 'flex', flexDirection: 'column' }}>
            {/* Cabeçalho */}
            <Box p={2} bgcolor="primary.main" color="white">
              <Typography variant="h6">
                Assistente Virtual WMS
              </Typography>
            </Box>

            {/* Área de Mensagens */}
            <Box
              ref={chatRef}
              flex={1}
              p={2}
              sx={{
                overflowY: 'auto',
                bgcolor: 'background.default'
              }}
            >
              <List>
                {mensagens.map((msg) => (
                  <ListItem
                    key={msg.id}
                    sx={{
                      flexDirection: msg.tipo === 'usuario' ? 'row-reverse' : 'row',
                      alignItems: 'flex-start',
                      mb: 1
                    }}
                  >
                    <ListItemAvatar>
                      <Avatar>
                        {msg.tipo === 'usuario' ? <PersonIcon /> : <BotIcon />}
                      </Avatar>
                    </ListItemAvatar>
                    <Paper
                      sx={{
                        p: 2,
                        maxWidth: '70%',
                        bgcolor: msg.tipo === 'usuario' ? 'primary.light' : 'background.paper',
                        color: msg.tipo === 'usuario' ? 'white' : 'text.primary'
                      }}
                    >
                      <Typography>{msg.texto}</Typography>
                      <Typography variant="caption" color="textSecondary">
                        {new Date(msg.timestamp).toLocaleTimeString()}
                      </Typography>
                    </Paper>
                  </ListItem>
                ))}
                {typing && (
                  <ListItem>
                    <ListItemAvatar>
                      <Avatar>
                        <BotIcon />
                      </Avatar>
                    </ListItemAvatar>
                    <CircularProgress size={20} />
                  </ListItem>
                )}
              </List>
            </Box>

            {/* Área de Input */}
            <Box p={2} bgcolor="background.paper">
              <Box display="flex" alignItems="center">
                <IconButton color="primary">
                  <AttachFileIcon />
                </IconButton>
                <TextField
                  fullWidth
                  variant="outlined"
                  placeholder="Digite sua mensagem..."
                  value={novaMensagem}
                  onChange={(e) => setNovaMensagem(e.target.value)}
                  onKeyPress={handleKeyPress}
                  sx={{ mx: 1 }}
                />
                <IconButton
                  color="primary"
                  onClick={enviarMensagem}
                  disabled={!novaMensagem.trim() || loading}
                >
                  <SendIcon />
                </IconButton>
              </Box>
            </Box>
          </Paper>
        </Grid>
      </Grid>
    </Box>
  );
};

export default Chatbot;
