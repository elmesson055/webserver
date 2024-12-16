// Serviço de integração para Custos Extras
import axios from 'axios';

class CustosExtrasIntegracoes {
  // Integrações com WMS
  async buscarCustosWMS(filtros) {
    try {
      const response = await axios.get('/api/v1/wms/custos-extras', { params: filtros });
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      console.error('Erro ao buscar custos WMS:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }

  async vincularCustoArmazenagem(custoId, dadosArmazenagem) {
    try {
      const response = await axios.post(`/api/v1/wms/custos-extras/${custoId}/armazenagem`, dadosArmazenagem);
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      console.error('Erro ao vincular custo de armazenagem:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }

  async buscarCustosMovimentacao(filtros) {
    try {
      const response = await axios.get('/api/v1/wms/movimentacao/custos', { params: filtros });
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      console.error('Erro ao buscar custos de movimentação:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }

  // Integrações com TMS
  async buscarCustosTransporte(filtros) {
    try {
      const response = await axios.get('/api/v1/tms/custos-extras/transporte', { params: filtros });
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      console.error('Erro ao buscar custos de transporte:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }

  async vincularCustoViagem(custoId, viagemId) {
    try {
      const response = await axios.post(`/api/v1/tms/custos-extras/${custoId}/viagem/${viagemId}`);
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      console.error('Erro ao vincular custo à viagem:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }

  async buscarCustosEntrega(filtros) {
    try {
      const response = await axios.get('/api/v1/tms/entregas/custos', { params: filtros });
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      console.error('Erro ao buscar custos de entrega:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }

  // Integrações Comuns
  async consolidarCustos(periodo) {
    try {
      const response = await axios.post('/api/v1/custos-extras/consolidar', { periodo });
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      console.error('Erro ao consolidar custos:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }

  async gerarRelatorioIntegrado(filtros) {
    try {
      const response = await axios.post('/api/v1/custos-extras/relatorio-integrado', filtros);
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      console.error('Erro ao gerar relatório integrado:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }
}

export default new CustosExtrasIntegracoes();
