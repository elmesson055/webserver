import axios from 'axios';

class ANTTIntegracoes {
  constructor() {
    this.baseURL = process.env.ANTT_API_URL;
    this.tokens = {};
  }

  // RNTRC - Registro Nacional de Transportadores Rodoviários de Cargas
  async rntrcIntegracoes() {
    return {
      // Consulta situação RNTRC
      async consultarSituacao(rntrc) {
        try {
          const response = await axios.get(`${this.baseURL}/rntrc/${rntrc}/situacao`);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro ao consultar RNTRC:', error);
          return {
            success: false,
            error: error.message
          };
        }
      },

      // Renovação RNTRC
      async solicitarRenovacao(rntrc, dados) {
        try {
          const response = await axios.post(`${this.baseURL}/rntrc/${rntrc}/renovacao`, dados);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro ao solicitar renovação:', error);
          return {
            success: false,
            error: error.message
          };
        }
      },

      // Inclusão de veículos
      async incluirVeiculo(rntrc, dadosVeiculo) {
        try {
          const response = await axios.post(`${this.baseURL}/rntrc/${rntrc}/veiculos`, dadosVeiculo);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro ao incluir veículo:', error);
          return {
            success: false,
            error: error.message
          };
        }
      }
    };
  }

  // Vale Pedágio Obrigatório
  async valePedagioIntegracoes() {
    return {
      // Emissão de vale-pedágio
      async emitir(dados) {
        try {
          const response = await axios.post(`${this.baseURL}/vale-pedagio/emissao`, dados);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro ao emitir vale-pedágio:', error);
          return {
            success: false,
            error: error.message
          };
        }
      },

      // Consulta de saldo
      async consultarSaldo(codigo) {
        try {
          const response = await axios.get(`${this.baseURL}/vale-pedagio/${codigo}/saldo`);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro ao consultar saldo:', error);
          return {
            success: false,
            error: error.message
          };
        }
      },

      // Cancelamento
      async cancelar(codigo, motivo) {
        try {
          const response = await axios.post(`${this.baseURL}/vale-pedagio/${codigo}/cancelamento`, { motivo });
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro ao cancelar vale-pedágio:', error);
          return {
            success: false,
            error: error.message
          };
        }
      }
    };
  }

  // TAC - Transportador Autônomo de Cargas
  async tacIntegracoes() {
    return {
      // Consulta situação TAC
      async consultarSituacao(cpf) {
        try {
          const response = await axios.get(`${this.baseURL}/tac/${cpf}/situacao`);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro ao consultar TAC:', error);
          return {
            success: false,
            error: error.message
          };
        }
      },

      // Cadastro de TAC
      async cadastrar(dados) {
        try {
          const response = await axios.post(`${this.baseURL}/tac/cadastro`, dados);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro ao cadastrar TAC:', error);
          return {
            success: false,
            error: error.message
          };
        }
      },

      // Atualização de dados
      async atualizar(cpf, dados) {
        try {
          const response = await axios.put(`${this.baseURL}/tac/${cpf}`, dados);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro ao atualizar TAC:', error);
          return {
            success: false,
            error: error.message
          };
        }
      }
    };
  }

  // Manifesto Eletrônico
  async manifestoIntegracoes() {
    return {
      // Emissão de manifesto
      async emitir(dados) {
        try {
          const response = await axios.post(`${this.baseURL}/manifesto/emissao`, dados);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro ao emitir manifesto:', error);
          return {
            success: false,
            error: error.message
          };
        }
      },

      // Consulta manifesto
      async consultar(numero) {
        try {
          const response = await axios.get(`${this.baseURL}/manifesto/${numero}`);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro ao consultar manifesto:', error);
          return {
            success: false,
            error: error.message
          };
        }
      },

      // Encerramento de manifesto
      async encerrar(numero, dados) {
        try {
          const response = await axios.post(`${this.baseURL}/manifesto/${numero}/encerramento`, dados);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro ao encerrar manifesto:', error);
          return {
            success: false,
            error: error.message
          };
        }
      }
    };
  }

  // Pagamento Eletrônico de Frete
  async pagamentoFreteIntegracoes() {
    return {
      // Registro de operação
      async registrarOperacao(dados) {
        try {
          const response = await axios.post(`${this.baseURL}/pagamento-frete/registro`, dados);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro ao registrar operação:', error);
          return {
            success: false,
            error: error.message
          };
        }
      },

      // Consulta de pagamentos
      async consultarPagamentos(filtros) {
        try {
          const response = await axios.get(`${this.baseURL}/pagamento-frete/consulta`, { params: filtros });
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro ao consultar pagamentos:', error);
          return {
            success: false,
            error: error.message
          };
        }
      }
    };
  }
}

export default new ANTTIntegracoes();
