// Serviço de integração para Custos Extras com Participantes
import axios from 'axios';

class CustosExtrasParticipantes {
  // Integrações com Embarcadores
  async buscarCustosEmbarcador(filtros) {
    try {
      const response = await axios.get('/api/v1/embarcadores/custos-extras', { params: filtros });
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      console.error('Erro ao buscar custos do embarcador:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }

  async vincularCustoEmbarcador(custoId, embarcadorId, dados) {
    try {
      const response = await axios.post(`/api/v1/embarcadores/${embarcadorId}/custos-extras/${custoId}`, dados);
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      console.error('Erro ao vincular custo ao embarcador:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }

  // Integrações com Clientes
  async buscarCustosCliente(filtros) {
    try {
      const response = await axios.get('/api/v1/clientes/custos-extras', { params: filtros });
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      console.error('Erro ao buscar custos do cliente:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }

  async vincularCustoCliente(custoId, clienteId, dados) {
    try {
      const response = await axios.post(`/api/v1/clientes/${clienteId}/custos-extras/${custoId}`, dados);
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      console.error('Erro ao vincular custo ao cliente:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }

  // Integrações com Fornecedores
  async buscarCustosFornecedor(filtros) {
    try {
      const response = await axios.get('/api/v1/fornecedores/custos-extras', { params: filtros });
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      console.error('Erro ao buscar custos do fornecedor:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }

  async vincularCustoFornecedor(custoId, fornecedorId, dados) {
    try {
      const response = await axios.post(`/api/v1/fornecedores/${fornecedorId}/custos-extras/${custoId}`, dados);
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      console.error('Erro ao vincular custo ao fornecedor:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }

  // Consolidações e Relatórios
  async consolidarCustosParticipante(participanteId, tipo, periodo) {
    try {
      const response = await axios.post('/api/v1/custos-extras/consolidar-participante', {
        participanteId,
        tipo,
        periodo
      });
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      console.error('Erro ao consolidar custos do participante:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }

  async gerarRelatorioParticipante(participanteId, tipo, filtros) {
    try {
      const response = await axios.post(`/api/v1/custos-extras/relatorio-participante/${tipo}/${participanteId}`, filtros);
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      console.error('Erro ao gerar relatório do participante:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }

  // Rateios e Divisões
  async calcularRateioCustos(custoId, participantes) {
    try {
      const response = await axios.post(`/api/v1/custos-extras/${custoId}/rateio`, { participantes });
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      console.error('Erro ao calcular rateio:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }

  // Aprovações e Workflow
  async solicitarAprovacaoParticipante(custoId, participanteId, tipo) {
    try {
      const response = await axios.post(`/api/v1/custos-extras/${custoId}/aprovacao/${tipo}/${participanteId}`);
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      console.error('Erro ao solicitar aprovação:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }
}

export default new CustosExtrasParticipantes();
