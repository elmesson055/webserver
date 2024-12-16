import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  Alert,
  RefreshControl
} from 'react-native';
import { Card, Title, Paragraph, Chip, Button, Divider } from 'react-native-paper';
import { MaterialIcons } from '@expo/vector-icons';
import { useNavigation } from '@react-navigation/native';

const Operacoes = () => {
  const [loading, setLoading] = useState(false);
  const [tarefas, setTarefas] = useState([]);
  const [metricas, setMetricas] = useState({
    tarefasPendentes: 0,
    tarefasEmAndamento: 0,
    tarefasConcluidas: 0,
    eficiencia: 0
  });
  const navigation = useNavigation();

  useEffect(() => {
    carregarDados();
  }, []);

  const carregarDados = async () => {
    setLoading(true);
    try {
      const [tarefasRes, metricasRes] = await Promise.all([
        fetch('/api/v1/mobile/operacoes/tarefas'),
        fetch('/api/v1/mobile/operacoes/metricas')
      ]);

      const tarefasData = await tarefasRes.json();
      const metricasData = await metricasRes.json();

      setTarefas(tarefasData);
      setMetricas(metricasData);
    } catch (error) {
      console.error('Erro ao carregar dados:', error);
      Alert.alert('Erro', 'Não foi possível carregar os dados');
    } finally {
      setLoading(false);
    }
  };

  const iniciarTarefa = async (tarefa) => {
    try {
      await fetch(`/api/v1/mobile/operacoes/tarefas/${tarefa.id}/iniciar`, {
        method: 'POST'
      });
      carregarDados();
    } catch (error) {
      console.error('Erro ao iniciar tarefa:', error);
      Alert.alert('Erro', 'Não foi possível iniciar a tarefa');
    }
  };

  const concluirTarefa = async (tarefa) => {
    try {
      await fetch(`/api/v1/mobile/operacoes/tarefas/${tarefa.id}/concluir`, {
        method: 'POST'
      });
      carregarDados();
    } catch (error) {
      console.error('Erro ao concluir tarefa:', error);
      Alert.alert('Erro', 'Não foi possível concluir a tarefa');
    }
  };

  const getTipoIcon = (tipo) => {
    switch (tipo.toLowerCase()) {
      case 'recebimento':
        return 'archive';
      case 'armazenagem':
        return 'home';
      case 'separacao':
        return 'content-cut';
      case 'expedicao':
        return 'local-shipping';
      default:
        return 'work';
    }
  };

  const getPrioridadeColor = (prioridade) => {
    switch (prioridade.toLowerCase()) {
      case 'alta':
        return '#F44336';
      case 'média':
        return '#FFC107';
      case 'baixa':
        return '#4CAF50';
      default:
        return '#9E9E9E';
    }
  };

  return (
    <ScrollView
      style={styles.container}
      refreshControl={
        <RefreshControl refreshing={loading} onRefresh={carregarDados} />
      }
    >
      {/* Métricas */}
      <View style={styles.metricasContainer}>
        <Card style={styles.metricaCard}>
          <Card.Content>
            <Title style={styles.metricaNumero}>{metricas.tarefasPendentes}</Title>
            <Paragraph>Pendentes</Paragraph>
          </Card.Content>
        </Card>
        <Card style={styles.metricaCard}>
          <Card.Content>
            <Title style={styles.metricaNumero}>{metricas.tarefasEmAndamento}</Title>
            <Paragraph>Em Andamento</Paragraph>
          </Card.Content>
        </Card>
        <Card style={styles.metricaCard}>
          <Card.Content>
            <Title style={styles.metricaNumero}>{metricas.eficiencia}%</Title>
            <Paragraph>Eficiência</Paragraph>
          </Card.Content>
        </Card>
      </View>

      {/* Lista de Tarefas */}
      <View style={styles.tarefasContainer}>
        <Title style={styles.titulo}>Minhas Tarefas</Title>

        {tarefas.map((tarefa) => (
          <Card key={tarefa.id} style={styles.tarefaCard}>
            <Card.Content>
              <View style={styles.tarefaHeader}>
                <View style={styles.tarefaTipo}>
                  <MaterialIcons
                    name={getTipoIcon(tarefa.tipo)}
                    size={24}
                    color="#666"
                  />
                  <Text style={styles.tarefaTipoText}>{tarefa.tipo}</Text>
                </View>
                <Chip
                  style={[
                    styles.prioridadeChip,
                    { backgroundColor: getPrioridadeColor(tarefa.prioridade) }
                  ]}
                >
                  {tarefa.prioridade}
                </Chip>
              </View>

              <Divider style={styles.divider} />

              <View style={styles.tarefaInfo}>
                <Text style={styles.produtoText}>{tarefa.produto}</Text>
                <Text style={styles.infoText}>Quantidade: {tarefa.quantidade}</Text>
                <Text style={styles.infoText}>Local: {tarefa.localizacao}</Text>
                {tarefa.prazo && (
                  <Text style={styles.prazoText}>Prazo: {tarefa.prazo}</Text>
                )}
              </View>

              <View style={styles.tarefaAcoes}>
                {tarefa.status === 'pendente' && (
                  <Button
                    mode="contained"
                    onPress={() => iniciarTarefa(tarefa)}
                    style={styles.botaoIniciar}
                  >
                    Iniciar
                  </Button>
                )}
                {tarefa.status === 'em_andamento' && (
                  <Button
                    mode="contained"
                    onPress={() => concluirTarefa(tarefa)}
                    style={styles.botaoConcluir}
                  >
                    Concluir
                  </Button>
                )}
                <Button
                  mode="outlined"
                  onPress={() => navigation.navigate('Rastreamento')}
                  style={styles.botaoScan}
                >
                  Escanear
                </Button>
              </View>
            </Card.Content>
          </Card>
        ))}
      </View>
    </ScrollView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f5f5f5',
  },
  metricasContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    padding: 16,
  },
  metricaCard: {
    flex: 1,
    marginHorizontal: 4,
  },
  metricaNumero: {
    fontSize: 24,
    textAlign: 'center',
  },
  tarefasContainer: {
    padding: 16,
  },
  titulo: {
    marginBottom: 16,
  },
  tarefaCard: {
    marginBottom: 16,
  },
  tarefaHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  tarefaTipo: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  tarefaTipoText: {
    marginLeft: 8,
    fontSize: 16,
    fontWeight: 'bold',
  },
  prioridadeChip: {
    height: 24,
  },
  divider: {
    marginVertical: 12,
  },
  tarefaInfo: {
    marginBottom: 12,
  },
  produtoText: {
    fontSize: 16,
    fontWeight: 'bold',
    marginBottom: 4,
  },
  infoText: {
    color: '#666',
    marginBottom: 2,
  },
  prazoText: {
    color: '#F44336',
    marginTop: 4,
  },
  tarefaAcoes: {
    flexDirection: 'row',
    justifyContent: 'flex-end',
  },
  botaoIniciar: {
    marginRight: 8,
    backgroundColor: '#4CAF50',
  },
  botaoConcluir: {
    marginRight: 8,
    backgroundColor: '#2196F3',
  },
  botaoScan: {
    borderColor: '#666',
  },
});

export default Operacoes;
