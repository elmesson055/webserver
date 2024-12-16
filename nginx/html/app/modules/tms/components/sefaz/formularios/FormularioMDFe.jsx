import React, { useState, useEffect } from 'react';
import {
  Grid,
  TextField,
  FormControl,
  InputLabel,
  Select,
  MenuItem,
  Button,
  Divider,
  Typography,
  Alert,
  IconButton,
  List,
  ListItem,
  ListItemText,
  ListItemSecondaryAction
} from '@mui/material';
import { Delete as DeleteIcon, Add as AddIcon } from '@mui/icons-material';

import ValidacoesSefaz from '../../../services/sefaz/ValidacoesSefaz';
import CalculosSefaz from '../../../services/sefaz/CalculosSefaz';

const FormularioMDFe = ({ onSubmit, initialData = {} }) => {
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
    
    // Dados da Viagem
    viagem: {
      tipoEmitente: '',
      tipoTransportador: '',
      modalidade: '',
      ufInicio: '',
      ufFim: '',
      dataInicio: '',
      dataFim: ''
    },
    
    // Dados do Veículo
    veiculo: {
      placa: '',
      renavam: '',
      tara: 0,
      capacidadeKG: 0,
      capacidadeM3: 0,
      tipoRodado: '',
      tipoCarroceria: ''
    },
    
    // Dados do Condutor
    condutores: [{
      cpf: '',
      nome: '',
      cnh: ''
    }],
    
    // Documentos Vinculados
    documentos: {
      cte: [],
      nfe: []
    },
    
    // Dados do Seguro
    seguro: {
      responsavel: '',
      cnpj: '',
      seguradora: '',
      apolice: '',
      averbacao: ''
    },
    
    // Dados do Contratante
    contratante: {
      documento: '',
      nome: ''
    },
    
    // Informações de Carga
    carga: {
      unidade: '',
      tipoCarga: '',
      quantidade: 0,
      pesoBruto: 0
    },
    
    // Vale Pedágio
    valePedagio: {
      cnpjFornecedor: '',
      cnpjResponsavel: '',
      numero: '',
      valor: 0
    },
    
    // Informações Adicionais
    informacoesAdicionais: ''
  });

  const [erros, setErros] = useState({});

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

  const handleAddCondutor = () => {
    setFormData(prev => ({
      ...prev,
      condutores: [
        ...prev.condutores,
        { cpf: '', nome: '', cnh: '' }
      ]
    }));
  };

  const handleRemoveCondutor = (index) => {
    setFormData(prev => ({
      ...prev,
      condutores: prev.condutores.filter((_, i) => i !== index)
    }));
  };

  const handleCondutorChange = (index, field, value) => {
    setFormData(prev => ({
      ...prev,
      condutores: prev.condutores.map((condutor, i) => 
        i === index ? { ...condutor, [field]: value } : condutor
      )
    }));
  };

  const validarFormulario = async () => {
    const validacoes = new ValidacoesSefaz();
    const errosValidacao = {};

    // Validar Emitente
    const validacaoEmitente = await validacoes.validarMDFe(formData).emitente;
    if (!validacaoEmitente.validarCNPJ(formData.emitente.cnpj)) {
      errosValidacao.cnpjEmitente = 'CNPJ inválido';
    }

    // Validar Veículo
    const validacaoVeiculo = await validacoes.validarMDFe(formData).veiculo;
    if (!validacaoVeiculo.validarPlaca(formData.veiculo.placa)) {
      errosValidacao.placaVeiculo = 'Placa inválida';
    }

    // Validar Condutores
    const validacaoCondutor = await validacoes.validarMDFe(formData).condutor;
    formData.condutores.forEach((condutor, index) => {
      if (!validacaoCondutor.validarCPF(condutor.cpf)) {
        errosValidacao[`cpfCondutor${index}`] = 'CPF inválido';
      }
    });

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

      {/* Viagem */}
      <Typography variant="h6" gutterBottom>
        Dados da Viagem
      </Typography>
      <Grid container spacing={2}>
        <Grid item xs={12} md={4}>
          <FormControl fullWidth>
            <InputLabel>Tipo de Emitente</InputLabel>
            <Select
              value={formData.viagem.tipoEmitente}
              onChange={(e) => handleChange('viagem', 'tipoEmitente', e.target.value)}
            >
              <MenuItem value="1">Prestador de Serviço de Transporte</MenuItem>
              <MenuItem value="2">Transportador de Carga Própria</MenuItem>
            </Select>
          </FormControl>
        </Grid>
        {/* Adicionar outros campos da viagem */}
      </Grid>

      <Divider sx={{ my: 3 }} />

      {/* Veículo */}
      <Typography variant="h6" gutterBottom>
        Dados do Veículo
      </Typography>
      <Grid container spacing={2}>
        <Grid item xs={12} md={3}>
          <TextField
            fullWidth
            label="Placa"
            value={formData.veiculo.placa}
            onChange={(e) => handleChange('veiculo', 'placa', e.target.value)}
            error={!!erros.placaVeiculo}
            helperText={erros.placaVeiculo}
          />
        </Grid>
        {/* Adicionar outros campos do veículo */}
      </Grid>

      <Divider sx={{ my: 3 }} />

      {/* Condutores */}
      <Typography variant="h6" gutterBottom>
        Condutores
      </Typography>
      <List>
        {formData.condutores.map((condutor, index) => (
          <ListItem key={index}>
            <Grid container spacing={2}>
              <Grid item xs={12} md={4}>
                <TextField
                  fullWidth
                  label="CPF"
                  value={condutor.cpf}
                  onChange={(e) => handleCondutorChange(index, 'cpf', e.target.value)}
                  error={!!erros[`cpfCondutor${index}`]}
                  helperText={erros[`cpfCondutor${index}`]}
                />
              </Grid>
              <Grid item xs={12} md={4}>
                <TextField
                  fullWidth
                  label="Nome"
                  value={condutor.nome}
                  onChange={(e) => handleCondutorChange(index, 'nome', e.target.value)}
                />
              </Grid>
              <Grid item xs={12} md={3}>
                <TextField
                  fullWidth
                  label="CNH"
                  value={condutor.cnh}
                  onChange={(e) => handleCondutorChange(index, 'cnh', e.target.value)}
                />
              </Grid>
              <Grid item xs={12} md={1}>
                <IconButton
                  edge="end"
                  onClick={() => handleRemoveCondutor(index)}
                  disabled={formData.condutores.length === 1}
                >
                  <DeleteIcon />
                </IconButton>
              </Grid>
            </Grid>
          </ListItem>
        ))}
      </List>
      
      <Button
        startIcon={<AddIcon />}
        onClick={handleAddCondutor}
        sx={{ mb: 3 }}
      >
        Adicionar Condutor
      </Button>

      <Divider sx={{ my: 3 }} />

      {/* Documentos */}
      <Typography variant="h6" gutterBottom>
        Documentos Vinculados
      </Typography>
      {/* Implementar lista de documentos */}

      <Divider sx={{ my: 3 }} />

      {/* Botões de Ação */}
      <Grid container spacing={2} justifyContent="flex-end">
        <Grid item>
          <Button type="submit" variant="contained" color="primary">
            Emitir MDF-e
          </Button>
        </Grid>
      </Grid>
    </form>
  );
};

export default FormularioMDFe;
