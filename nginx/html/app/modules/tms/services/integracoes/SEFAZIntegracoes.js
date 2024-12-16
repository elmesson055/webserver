import axios from 'axios';
import { signXML, validateXML } from '../utils/xmlUtils';
import { generateQRCode } from '../utils/qrcodeUtils';

class SEFAZIntegracoes {
  constructor() {
    this.baseURL = process.env.SEFAZ_API_URL;
    this.certificado = process.env.CERTIFICADO_DIGITAL;
    this.ambiente = process.env.AMBIENTE_SEFAZ; // 1-Produção, 2-Homologação
  }

  // CT-e (Conhecimento de Transporte Eletrônico)
  async cteIntegracoes() {
    return {
      // Emissão de CT-e
      async emitir(dados) {
        try {
          // 1. Gera XML
          const xml = await this.gerarXMLCTe(dados);
          
          // 2. Assina digitalmente
          const xmlAssinado = await signXML(xml, this.certificado);
          
          // 3. Valida esquema
          await validateXML(xmlAssinado, 'cte');
          
          // 4. Transmite para SEFAZ
          const response = await axios.post(`${this.baseURL}/cte/emissao`, {
            xml: xmlAssinado
          });

          // 5. Gera QR Code
          const qrCode = await generateQRCode(response.data.chaveAcesso);

          return {
            success: true,
            data: {
              ...response.data,
              qrCode
            }
          };
        } catch (error) {
          console.error('Erro na emissão de CT-e:', error);
          return {
            success: false,
            error: error.message
          };
        }
      },

      // Consulta status CT-e
      async consultar(chave) {
        try {
          const response = await axios.get(`${this.baseURL}/cte/${chave}`);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro na consulta de CT-e:', error);
          return {
            success: false,
            error: error.message
          };
        }
      },

      // Cancelamento de CT-e
      async cancelar(chave, justificativa) {
        try {
          const response = await axios.post(`${this.baseURL}/cte/${chave}/cancelamento`, {
            justificativa
          });
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro no cancelamento de CT-e:', error);
          return {
            success: false,
            error: error.message
          };
        }
      },

      // Carta de Correção
      async corrigir(chave, correcoes) {
        try {
          const response = await axios.post(`${this.baseURL}/cte/${chave}/carta-correcao`, {
            correcoes
          });
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro na carta de correção de CT-e:', error);
          return {
            success: false,
            error: error.message
          };
        }
      }
    };
  }

  // MDF-e (Manifesto Eletrônico de Documentos Fiscais)
  async mdfeIntegracoes() {
    return {
      // Emissão de MDF-e
      async emitir(dados) {
        try {
          // 1. Gera XML
          const xml = await this.gerarXMLMDFe(dados);
          
          // 2. Assina digitalmente
          const xmlAssinado = await signXML(xml, this.certificado);
          
          // 3. Valida esquema
          await validateXML(xmlAssinado, 'mdfe');
          
          // 4. Transmite para SEFAZ
          const response = await axios.post(`${this.baseURL}/mdfe/emissao`, {
            xml: xmlAssinado
          });

          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro na emissão de MDF-e:', error);
          return {
            success: false,
            error: error.message
          };
        }
      },

      // Consulta status MDF-e
      async consultar(chave) {
        try {
          const response = await axios.get(`${this.baseURL}/mdfe/${chave}`);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro na consulta de MDF-e:', error);
          return {
            success: false,
            error: error.message
          };
        }
      },

      // Encerramento de MDF-e
      async encerrar(chave, dados) {
        try {
          const response = await axios.post(`${this.baseURL}/mdfe/${chave}/encerramento`, dados);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro no encerramento de MDF-e:', error);
          return {
            success: false,
            error: error.message
          };
        }
      },

      // Inclusão de condutor
      async incluirCondutor(chave, condutor) {
        try {
          const response = await axios.post(`${this.baseURL}/mdfe/${chave}/condutor`, condutor);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro na inclusão de condutor:', error);
          return {
            success: false,
            error: error.message
          };
        }
      }
    };
  }

  // NF-e (Nota Fiscal Eletrônica)
  async nfeIntegracoes() {
    return {
      // Consulta NF-e
      async consultar(chave) {
        try {
          const response = await axios.get(`${this.baseURL}/nfe/${chave}`);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro na consulta de NF-e:', error);
          return {
            success: false,
            error: error.message
          };
        }
      },

      // Manifestação do Destinatário
      async manifestar(chave, tipo, justificativa) {
        try {
          const response = await axios.post(`${this.baseURL}/nfe/${chave}/manifestacao`, {
            tipo,
            justificativa
          });
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro na manifestação de NF-e:', error);
          return {
            success: false,
            error: error.message
          };
        }
      },

      // Download de XML
      async downloadXML(chave) {
        try {
          const response = await axios.get(`${this.baseURL}/nfe/${chave}/xml`);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro no download de XML:', error);
          return {
            success: false,
            error: error.message
          };
        }
      }
    };
  }

  // EFD (Escrituração Fiscal Digital)
  async efdIntegracoes() {
    return {
      // Geração de EFD
      async gerar(periodo, tipo) {
        try {
          const response = await axios.post(`${this.baseURL}/efd/geracao`, {
            periodo,
            tipo
          });
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro na geração de EFD:', error);
          return {
            success: false,
            error: error.message
          };
        }
      },

      // Validação de EFD
      async validar(arquivo) {
        try {
          const response = await axios.post(`${this.baseURL}/efd/validacao`, {
            arquivo
          });
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro na validação de EFD:', error);
          return {
            success: false,
            error: error.message
          };
        }
      },

      // Transmissão de EFD
      async transmitir(arquivo) {
        try {
          const response = await axios.post(`${this.baseURL}/efd/transmissao`, {
            arquivo
          });
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro na transmissão de EFD:', error);
          return {
            success: false,
            error: error.message
          };
        }
      },

      // Consulta status de transmissão
      async consultarStatus(recibo) {
        try {
          const response = await axios.get(`${this.baseURL}/efd/status/${recibo}`);
          return {
            success: true,
            data: response.data
          };
        } catch (error) {
          console.error('Erro na consulta de status EFD:', error);
          return {
            success: false,
            error: error.message
          };
        }
      }
    };
  }

  // Utilitários
  async statusServico(servico) {
    try {
      const response = await axios.get(`${this.baseURL}/status/${servico}`);
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      console.error('Erro na consulta de status:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }

  async consultarCadastro(documento, uf) {
    try {
      const response = await axios.get(`${this.baseURL}/cadastro/${uf}/${documento}`);
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      console.error('Erro na consulta de cadastro:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }
}

export default new SEFAZIntegracoes();
