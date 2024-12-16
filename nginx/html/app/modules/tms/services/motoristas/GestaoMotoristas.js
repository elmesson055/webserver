import axios from 'axios';

class GestaoMotoristas {
  constructor() {
    this.baseURL = process.env.API_URL;
  }

  // Gestão de CNH
  async cnhIntegracoes() {
    return {
      // Consulta situação CNH
      async consultarCNH(numero) {
        try {
          const response = await axios.get(`${this.baseURL}/motoristas/cnh/${numero}`);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro ao consultar CNH:', error);
          return {
            success: false,
            error: error.message
          };
        }
      },

      // Alertas de vencimento
      async alertasVencimento() {
        try {
          const response = await axios.get(`${this.baseURL}/motoristas/cnh/alertas`);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro ao buscar alertas:', error);
          return {
            success: false,
            error: error.message
          };
        }
      },

      // Histórico de renovações
      async historicoRenovacoes(motorista_id) {
        try {
          const response = await axios.get(`${this.baseURL}/motoristas/${motorista_id}/cnh/historico`);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro ao buscar histórico:', error);
          return {
            success: false,
            error: error.message
          };
        }
      }
    };
  }

  // Jornada de Trabalho
  async jornadaTrabalho() {
    return {
      // Registro de ponto
      async registrarPonto(dados) {
        try {
          const response = await axios.post(`${this.baseURL}/motoristas/jornada/registro`, dados);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro ao registrar ponto:', error);
          return {
            success: false,
            error: error.message
          };
        }
      },

      // Consulta horas trabalhadas
      async consultarHoras(motorista_id, periodo) {
        try {
          const response = await axios.get(`${this.baseURL}/motoristas/${motorista_id}/jornada`, {
            params: periodo
          });
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro ao consultar horas:', error);
          return {
            success: false,
            error: error.message
          };
        }
      },

      // Gestão de escalas
      async gerenciarEscalas(dados) {
        try {
          const response = await axios.post(`${this.baseURL}/motoristas/escalas`, dados);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro ao gerenciar escalas:', error);
          return {
            success: false,
            error: error.message
          };
        }
      }
    };
  }

  // Despesas e Adiantamentos
  async despesasAdiantamentos() {
    return {
      // Registro de despesas
      async registrarDespesa(dados) {
        try {
          const response = await axios.post(`${this.baseURL}/motoristas/despesas`, dados);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro ao registrar despesa:', error);
          return {
            success: false,
            error: error.message
          };
        }
      },

      // Solicitação de adiantamento
      async solicitarAdiantamento(dados) {
        try {
          const response = await axios.post(`${this.baseURL}/motoristas/adiantamentos`, dados);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro ao solicitar adiantamento:', error);
          return {
            success: false,
            error: error.message
          };
        }
      },

      // Prestação de contas
      async prestarContas(dados) {
        try {
          const response = await axios.post(`${this.baseURL}/motoristas/prestacao-contas`, dados);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro ao prestar contas:', error);
          return {
            success: false,
            error: error.message
          };
        }
      }
    };
  }

  // Avaliação e Desempenho
  async avaliacaoDesempenho() {
    return {
      // Registro de ocorrências
      async registrarOcorrencia(dados) {
        try {
          const response = await axios.post(`${this.baseURL}/motoristas/ocorrencias`, dados);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro ao registrar ocorrência:', error);
          return {
            success: false,
            error: error.message
          };
        }
      },

      // Avaliação periódica
      async avaliarMotorista(dados) {
        try {
          const response = await axios.post(`${this.baseURL}/motoristas/avaliacoes`, dados);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro ao avaliar motorista:', error);
          return {
            success: false,
            error: error.message
          };
        }
      },

      // Indicadores de desempenho
      async consultarIndicadores(motorista_id, periodo) {
        try {
          const response = await axios.get(`${this.baseURL}/motoristas/${motorista_id}/indicadores`, {
            params: periodo
          });
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro ao consultar indicadores:', error);
          return {
            success: false,
            error: error.message
          };
        }
      }
    };
  }

  // Treinamentos e Certificações
  async treinamentosCertificacoes() {
    return {
      // Registro de treinamentos
      async registrarTreinamento(dados) {
        try {
          const response = await axios.post(`${this.baseURL}/motoristas/treinamentos`, dados);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro ao registrar treinamento:', error);
          return {
            success: false,
            error: error.message
          };
        }
      },

      // Controle de certificações
      async gerenciarCertificacoes(dados) {
        try {
          const response = await axios.post(`${this.baseURL}/motoristas/certificacoes`, dados);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro ao gerenciar certificações:', error);
          return {
            success: false,
            error: error.message
          };
        }
      },

      // Histórico de qualificações
      async consultarQualificacoes(motorista_id) {
        try {
          const response = await axios.get(`${this.baseURL}/motoristas/${motorista_id}/qualificacoes`);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro ao consultar qualificações:', error);
          return {
            success: false,
            error: error.message
          };
        }
      }
    };
  }
}

export default new GestaoMotoristas();
