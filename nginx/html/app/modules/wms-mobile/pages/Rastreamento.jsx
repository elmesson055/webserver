import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  ScrollView,
  Alert,
  ActivityIndicator,
  Dimensions
} from 'react-native';
import { Camera } from 'expo-camera';
import { BarCodeScanner } from 'expo-barcode-scanner';
import { MaterialIcons } from '@expo/vector-icons';
import { Card, Title, Chip, Button } from 'react-native-paper';

const Rastreamento = () => {
  const [hasPermission, setHasPermission] = useState(null);
  const [scanned, setScanned] = useState(false);
  const [cameraVisible, setCameraVisible] = useState(false);
  const [loading, setLoading] = useState(false);
  const [historico, setHistorico] = useState([]);
  const [ultimaLeitura, setUltimaLeitura] = useState(null);

  useEffect(() => {
    (async () => {
      const { status } = await Camera.requestCameraPermissionsAsync();
      setHasPermission(status === 'granted');
    })();

    carregarHistorico();
  }, []);

  const carregarHistorico = async () => {
    try {
      const response = await fetch('/api/v1/mobile/rastreamento/historico');
      const data = await response.json();
      setHistorico(data);
    } catch (error) {
      console.error('Erro ao carregar histórico:', error);
      Alert.alert('Erro', 'Não foi possível carregar o histórico');
    }
  };

  const handleBarCodeScanned = async ({ type, data }) => {
    setScanned(true);
    setLoading(true);

    try {
      const response = await fetch('/api/v1/mobile/rastreamento/scan', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ tipo: type, codigo: data })
      });

      const resultado = await response.json();
      setUltimaLeitura(resultado);
      await carregarHistorico();

      Alert.alert(
        'Sucesso',
        `Código lido com sucesso!\nProduto: ${resultado.produto}\nStatus: ${resultado.status}`,
        [{ text: 'OK', onPress: () => setScanned(false) }]
      );
    } catch (error) {
      console.error('Erro ao processar código:', error);
      Alert.alert('Erro', 'Não foi possível processar o código');
      setScanned(false);
    } finally {
      setLoading(false);
      setCameraVisible(false);
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
        return 'label';
    }
  };

  const getStatusColor = (status) => {
    switch (status.toLowerCase()) {
      case 'concluído':
        return '#4CAF50';
      case 'em andamento':
        return '#FFC107';
      case 'pendente':
        return '#2196F3';
      case 'erro':
        return '#F44336';
      default:
        return '#9E9E9E';
    }
  };

  if (hasPermission === null) {
    return <View />;
  }

  if (hasPermission === false) {
    return (
      <View style={styles.container}>
        <Text>Sem acesso à câmera</Text>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      {cameraVisible ? (
        <View style={styles.cameraContainer}>
          <BarCodeScanner
            onBarCodeScanned={scanned ? undefined : handleBarCodeScanned}
            style={StyleSheet.absoluteFillObject}
          />
          <TouchableOpacity
            style={styles.closeButton}
            onPress={() => setCameraVisible(false)}
          >
            <MaterialIcons name="close" size={24} color="white" />
          </TouchableOpacity>
          <View style={styles.overlay}>
            <View style={styles.scanArea} />
          </View>
        </View>
      ) : (
        <ScrollView>
          {/* Botão de Scanner */}
          <TouchableOpacity
            style={styles.scanButton}
            onPress={() => setCameraVisible(true)}
            disabled={loading}
          >
            {loading ? (
              <ActivityIndicator color="white" />
            ) : (
              <>
                <MaterialIcons name="qr-code-scanner" size={32} color="white" />
                <Text style={styles.scanButtonText}>Escanear Código</Text>
              </>
            )}
          </TouchableOpacity>

          {/* Última Leitura */}
          {ultimaLeitura && (
            <Card style={styles.card}>
              <Card.Content>
                <Title>Última Leitura</Title>
                <View style={styles.leituraInfo}>
                  <MaterialIcons
                    name={getTipoIcon(ultimaLeitura.tipo)}
                    size={24}
                    color="#666"
                  />
                  <View style={styles.leituraTexto}>
                    <Text style={styles.produtoText}>{ultimaLeitura.produto}</Text>
                    <Text style={styles.infoText}>
                      Lote: {ultimaLeitura.lote} | Qtd: {ultimaLeitura.quantidade}
                    </Text>
                    <Chip
                      style={[
                        styles.statusChip,
                        { backgroundColor: getStatusColor(ultimaLeitura.status) }
                      ]}
                    >
                      {ultimaLeitura.status}
                    </Chip>
                  </View>
                </View>
              </Card.Content>
            </Card>
          )}

          {/* Histórico */}
          <View style={styles.historicoContainer}>
            <Title style={styles.historicoTitle}>Histórico de Leituras</Title>
            {historico.map((item) => (
              <Card key={item.id} style={styles.historicoCard}>
                <Card.Content>
                  <View style={styles.historicoItem}>
                    <MaterialIcons
                      name={getTipoIcon(item.tipo)}
                      size={24}
                      color="#666"
                    />
                    <View style={styles.historicoInfo}>
                      <Text style={styles.produtoText}>{item.produto}</Text>
                      <Text style={styles.infoText}>
                        {item.data} - {item.hora}
                      </Text>
                      <Text style={styles.infoText}>
                        Local: {item.localizacao}
                      </Text>
                    </View>
                    <Chip
                      style={[
                        styles.statusChip,
                        { backgroundColor: getStatusColor(item.status) }
                      ]}
                    >
                      {item.status}
                    </Chip>
                  </View>
                </Card.Content>
              </Card>
            ))}
          </View>
        </ScrollView>
      )}
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f5f5f5',
  },
  cameraContainer: {
    flex: 1,
    backgroundColor: 'black',
  },
  closeButton: {
    position: 'absolute',
    top: 40,
    right: 20,
    padding: 10,
    zIndex: 1,
  },
  overlay: {
    ...StyleSheet.absoluteFillObject,
    backgroundColor: 'rgba(0,0,0,0.5)',
    justifyContent: 'center',
    alignItems: 'center',
  },
  scanArea: {
    width: 250,
    height: 250,
    borderWidth: 2,
    borderColor: 'white',
    backgroundColor: 'transparent',
  },
  scanButton: {
    backgroundColor: '#2196F3',
    margin: 16,
    padding: 16,
    borderRadius: 8,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
  },
  scanButtonText: {
    color: 'white',
    fontSize: 18,
    marginLeft: 8,
  },
  card: {
    margin: 16,
    elevation: 4,
  },
  leituraInfo: {
    flexDirection: 'row',
    alignItems: 'center',
    marginTop: 8,
  },
  leituraTexto: {
    flex: 1,
    marginLeft: 16,
  },
  produtoText: {
    fontSize: 16,
    fontWeight: 'bold',
  },
  infoText: {
    color: '#666',
    marginTop: 4,
  },
  statusChip: {
    alignSelf: 'flex-start',
    marginTop: 8,
  },
  historicoContainer: {
    padding: 16,
  },
  historicoTitle: {
    marginBottom: 16,
  },
  historicoCard: {
    marginBottom: 8,
  },
  historicoItem: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  historicoInfo: {
    flex: 1,
    marginLeft: 16,
  },
});

export default Rastreamento;
