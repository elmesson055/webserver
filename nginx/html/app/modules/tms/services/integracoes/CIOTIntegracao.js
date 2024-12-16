import axios from 'axios';

class CIOTIntegracao {
  constructor() {
    this.baseURL = process.env.ANTT_API_URL;
    this.token = null;
  }

  // Autenticação
  async autenticar() {
    try {
      const response = await axios.post(`${this.baseURL}/v1/autenticacoes`, {
        clientId: process.env.ANTT_CLIENT_ID,
        clientSecret: process.env.ANTT_CLIENT_SECRET
      });
      
      this.token = response.data.access_token;
      return {
        success: true,
        token: this.token
      };
    } catch (error) {
      console.error('Erro na autenticação CIOT:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }

  // Geração de CIOT
  async gerarCIOT(dadosOperacao) {
    try {
      const response = await axios.post(`${this.baseURL}/v1/ciot`, dadosOperacao, {
        headers: {
          Authorization: `Bearer ${this.token}`
        }
      });
      
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      console.error('Erro ao gerar CIOT:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }

  // Consulta de CIOT
  async consultarCIOT(numeroCIOT) {
    try {
      const response = await axios.get(`${this.baseURL}/v1/ciot/${numeroCIOT}`, {
        headers: {
          Authorization: `Bearer ${this.token}`
        }
      });
      
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      console.error('Erro ao consultar CIOT:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }

  // Retificação de CIOT
  async retificarCIOT(numeroCIOT, dadosRetificacao) {
    try {
      const response = await axios.put(`${this.baseURL}/v1/ciot/${numeroCIOT}`, dadosRetificacao, {
        headers: {
          Authorization: `Bearer ${this.token}`
        }
      });
      
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      console.error('Erro ao retificar CIOT:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }

  // Cancelamento de CIOT
  async cancelarCIOT(numeroCIOT, motivoCancelamento) {
    try {
      const response = await axios.delete(`${this.baseURL}/v1/ciot/${numeroCIOT}`, {
        headers: {
          Authorization: `Bearer ${this.token}`
        },
        data: { motivoCancelamento }
      });
      
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      console.error('Erro ao cancelar CIOT:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }

  // Consulta RNTRC do Transportador
  async consultarRNTRC(numeroRNTRC) {
    try {
      const response = await axios.get(`${this.baseURL}/v1/transportadores/${numeroRNTRC}`, {
        headers: {
          Authorization: `Bearer ${this.token}`
        }
      });
      
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
  }

  // Consulta de Veículos
  async consultarVeiculo(placa) {
    try {
      const response = await axios.get(`${this.baseURL}/v1/veiculos/${placa}`, {
        headers: {
          Authorization: `Bearer ${this.token}`
        }
      });
      
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      console.error('Erro ao consultar veículo:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }

  // Validação de Operação
  async validarOperacao(dadosOperacao) {
    try {
      const response = await axios.post(`${this.baseURL}/v1/operacoes/validar`, dadosOperacao, {
        headers: {
          Authorization: `Bearer ${this.token}`
        }
      });
      
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      console.error('Erro ao validar operação:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }

  // Consulta de IPEFs
  async consultarIPEFs() {
    try {
      const response = await axios.get(`${this.baseURL}/v1/ipefs`, {
        headers: {
          Authorization: `Bearer ${this.token}`
        }
      });
      
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      console.error('Erro ao consultar IPEFs:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }

  // Histórico de Operações
  async consultarHistoricoOperacoes(filtros) {
    try {
      const response = await axios.get(`${this.baseURL}/v1/operacoes/historico`, {
        headers: {
          Authorization: `Bearer ${this.token}`
        },
        params: filtros
      });
      
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      console.error('Erro ao consultar histórico:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }

  // Relatórios
  async gerarRelatorio(tipo, parametros) {
    try {
      const response = await axios.post(`${this.baseURL}/v1/relatorios/${tipo}`, parametros, {
        headers: {
          Authorization: `Bearer ${this.token}`
        }
      });
      
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      console.error('Erro ao gerar relatório:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }
}

export default new CIOTIntegracao();
