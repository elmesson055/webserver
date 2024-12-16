import React, { useState, useEffect } from 'react';
import {
  Grid,
  TextField,
  FormControl,
  InputLabel,
  Select,
  MenuItem,
  FormGroup,
  FormControlLabel,
  Checkbox,
  Button,
  Divider,
  Typography,
  Alert
} from '@mui/material';

import ValidacoesSefaz from '../../../services/sefaz/ValidacoesSefaz';
import CalculosSefaz from '../../../services/sefaz/CalculosSefaz';

const FormularioCTe = ({ onSubmit, initialData = {} }) => {
  const [formData, setFormData] = useState({
    // Dados do Emitente
    emitente: {
      cnpj: '',
      ie: '',
      razaoSocial: '',
      endereco: {
        logradouro: '',
        numero: '',
        complemento: '',
        bairro: '',
        municipio: '',
        uf: '',
        cep: ''
      }
    },
    
    // Dados do Destinatário
    destinatario: {
      documento: '',
      ie: '',
      nome: '',
      endereco: {
        logradouro: '',
        numero: '',
        complemento: '',
        bairro: '',
        municipio: '',
        uf: '',
        cep: ''
      }
    },
    
    // Dados do Transporte
    transporte: {
      modalidade: '',
      veiculoPlaca: '',
      veiculoUF: '',
      veiculoRNTRC: '',
      motoristaCPF: '',
      motoristaNome: ''
    },
    
    // Dados do Serviço
    servico: {
      cfop: '',
      naturezaOperacao: '',
      tipoServico: '',
      inicioPrevisao: '',
      fimPrevisao: '',
      carregamento: {
        data: '',
        endereco: ''
      },
      entrega: {
        data: '',
        endereco: ''
      }
    },
    
    // Dados da Carga
    carga: {
      tipo: '',
      unidade: '',
      quantidade: 0,
      pesoBruto: 0,
      pesoLiquido: 0,
      valor: 0,
      seguro: 0
    },
    
    // Valores e Impostos
    valores: {
      servico: 0,
      responsavel: '',
      pedagio: 0,
      outrasDepesas: 0,
      baseCalculoICMS: 0,
      aliquotaICMS: 0,
      valorICMS: 0,
      aliquotaISS: 0,
      valorISS: 0
    },
    
    // Informações Adicionais
    informacoesAdicionais: {
      contribuinte: '',
      fiscais: '',
      complementares: ''
    }
  });

  const [erros, setErros] = useState({});
  const [calculando, setCalculando] = useState(false);

  useEffect(() => {
    if (Object.keys(initialData).length > 0) {
      setFormData({ ...formData, ...initialData });
    }
  }, [initialData]);

  const handleChange = (section, field, value) => {
    setFormData(prev => ({
      ...prev,
      [section]: {
        ...prev[section],
        [field]: value
      }
    }));
  };

  const handleEnderecoChange = (section, field, value) => {
    setFormData(prev => ({
      ...prev,
      [section]: {
        ...prev[section],
        endereco: {
          ...prev[section].endereco,
          [field]: value
        }
      }
    }));
  };

  const calcularImpostos = async () => {
    setCalculando(true);
    try {
      const calculos = new CalculosSefaz();
      
      // Cálculo do ICMS
      const baseICMS = await calculos.calcularCTe().valores.calcularFrete({
        valor: formData.valores.servico,
        pedagio: formData.valores.pedagio,
        outrasDepesas: formData.valores.outrasDepesas
      });
      
      const valorICMS = (baseICMS * (formData.valores.aliquotaICMS / 100));
      
      // Cálculo do ISS
      const valorISS = (formData.valores.servico * (formData.valores.aliquotaISS / 100));
      
      setFormData(prev => ({
        ...prev,
        valores: {
          ...prev.valores,
          baseCalculoICMS: baseICMS,
          valorICMS,
          valorISS
        }
      }));
    } catch (error) {
      console.error('Erro ao calcular impostos:', error);
    } finally {
      setCalculando(false);
    }
  };

  const validarFormulario = async () => {
    const validacoes = new ValidacoesSefaz();
    const errosValidacao = {};

    // Validar Emitente
    const validacaoEmitente = await validacoes.validarCTe(formData).emitente;
    if (!validacaoEmitente.validarCNPJ(formData.emitente.cnpj)) {
      errosValidacao.cnpjEmitente = 'CNPJ inválido';
    }

    // Validar Destinatário
    const validacaoDestinatario = await validacoes.validarCTe(formData).destinatario;
    if (!validacaoDestinatario.validarDocumento(formData.destinatario.documento)) {
      errosValidacao.documentoDestinatario = 'Documento inválido';
    }

    // Validar Transporte
    const validacaoTransporte = await validacoes.validarCTe(formData).transporte;
    if (!validacaoTransporte.validarVeiculo(formData.transporte.veiculoPlaca)) {
      errosValidacao.placaVeiculo = 'Placa inválida';
    }

    setErros(errosValidacao);
    return Object.keys(errosValidacao).length === 0;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    if (await validarFormulario()) {
      onSubmit(formData);
    }
  };

  return (
    <form onSubmit={handleSubmit}>
      {/* Emitente */}
      <Typography variant="h6" gutterBottom>
        Dados do Emitente
      </Typography>
      <Grid container spacing={2}>
        <Grid item xs={12} md={4}>
          <TextField
            fullWidth
            label="CNPJ"
            value={formData.emitente.cnpj}
            onChange={(e) => handleChange('emitente', 'cnpj', e.target.value)}
            error={!!erros.cnpjEmitente}
            helperText={erros.cnpjEmitente}
          />
        </Grid>
        {/* Adicionar outros campos do emitente */}
      </Grid>

      <Divider sx={{ my: 3 }} />

      {/* Destinatário */}
      <Typography variant="h6" gutterBottom>
        Dados do Destinatário
      </Typography>
      <Grid container spacing={2}>
        <Grid item xs={12} md={4}>
          <TextField
            fullWidth
            label="Documento (CPF/CNPJ)"
            value={formData.destinatario.documento}
            onChange={(e) => handleChange('destinatario', 'documento', e.target.value)}
            error={!!erros.documentoDestinatario}
            helperText={erros.documentoDestinatario}
          />
        </Grid>
        {/* Adicionar outros campos do destinatário */}
      </Grid>

      <Divider sx={{ my: 3 }} />

      {/* Transporte */}
      <Typography variant="h6" gutterBottom>
        Dados do Transporte
      </Typography>
      <Grid container spacing={2}>
        <Grid item xs={12} md={4}>
          <FormControl fullWidth>
            <InputLabel>Modalidade</InputLabel>
            <Select
              value={formData.transporte.modalidade}
              onChange={(e) => handleChange('transporte', 'modalidade', e.target.value)}
            >
              <MenuItem value="rodoviario">Rodoviário</MenuItem>
              <MenuItem value="aereo">Aéreo</MenuItem>
              <MenuItem value="aquaviario">Aquaviário</MenuItem>
              <MenuItem value="ferroviario">Ferroviário</MenuItem>
            </Select>
          </FormControl>
        </Grid>
        {/* Adicionar outros campos do transporte */}
      </Grid>

      <Divider sx={{ my: 3 }} />

      {/* Valores e Impostos */}
      <Typography variant="h6" gutterBottom>
        Valores e Impostos
      </Typography>
      <Grid container spacing={2}>
        <Grid item xs={12} md={3}>
          <TextField
            fullWidth
            type="number"
            label="Valor do Serviço"
            value={formData.valores.servico}
            onChange={(e) => handleChange('valores', 'servico', parseFloat(e.target.value))}
          />
        </Grid>
        {/* Adicionar outros campos de valores e impostos */}
      </Grid>

      <Button
        variant="outlined"
        onClick={calcularImpostos}
        disabled={calculando}
        sx={{ mt: 2 }}
      >
        Calcular Impostos
      </Button>

      <Divider sx={{ my: 3 }} />

      {/* Botões de Ação */}
      <Grid container spacing={2} justifyContent="flex-end">
        <Grid item>
          <Button type="submit" variant="contained" color="primary">
            Emitir CT-e
          </Button>
        </Grid>
      </Grid>
    </form>
  );
};

export default FormularioCTe;
