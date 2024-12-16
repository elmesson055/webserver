<?php

return [
    // Rotas do Painel
    'GET|/painel' => ['controller' => 'PainelController', 'action' => 'index'],

    // Rotas de Custos Extras
    'GET|/custos-extras/registro-inicial' => ['controller' => 'RegistroInicialController', 'action' => 'index'],
    'POST|/custos-extras/registro-inicial' => ['controller' => 'RegistroInicialController', 'action' => 'store'],
    'GET|/custos-extras/registro-inicial/create' => ['controller' => 'RegistroInicialController', 'action' => 'create'],
    'GET|/custos-extras/registro-inicial/{id}' => ['controller' => 'RegistroInicialController', 'action' => 'show'],
    'GET|/custos-extras/registro-inicial/{id}/edit' => ['controller' => 'RegistroInicialController', 'action' => 'edit'],
    'POST|/custos-extras/registro-inicial/{id}' => ['controller' => 'RegistroInicialController', 'action' => 'update'],
    'DELETE|/custos-extras/registro-inicial/{id}' => ['controller' => 'RegistroInicialController', 'action' => 'destroy'],
    
    'GET|/custos-extras/aprovacoes' => ['controller' => 'AprovacoesController', 'action' => 'index'],
    'POST|/custos-extras/aprovacoes/{id}/aprovar' => ['controller' => 'AprovacoesController', 'action' => 'aprovar'],
    'POST|/custos-extras/aprovacoes/{id}/rejeitar' => ['controller' => 'AprovacoesController', 'action' => 'rejeitar'],
    
    'GET|/custos-extras/analise' => ['controller' => 'AnaliseController', 'action' => 'index'],

    // Rotas de Cadastros
    'GET|/cadastros/fornecedores' => ['controller' => 'FornecedorController', 'action' => 'index'],
    'POST|/cadastros/fornecedores' => ['controller' => 'FornecedorController', 'action' => 'store'],
    'GET|/cadastros/fornecedores/create' => ['controller' => 'FornecedorController', 'action' => 'create'],
    'GET|/cadastros/fornecedores/{id}' => ['controller' => 'FornecedorController', 'action' => 'show'],
    'GET|/cadastros/fornecedores/{id}/edit' => ['controller' => 'FornecedorController', 'action' => 'edit'],
    'POST|/cadastros/fornecedores/{id}' => ['controller' => 'FornecedorController', 'action' => 'update'],
    'DELETE|/cadastros/fornecedores/{id}' => ['controller' => 'FornecedorController', 'action' => 'destroy'],

    'GET|/cadastros/status-gerais' => ['controller' => 'StatusGeraisController', 'action' => 'index'],
    'POST|/cadastros/status-gerais' => ['controller' => 'StatusGeraisController', 'action' => 'store'],
    'GET|/cadastros/status-gerais/create' => ['controller' => 'StatusGeraisController', 'action' => 'create'],
    'GET|/cadastros/status-gerais/{id}' => ['controller' => 'StatusGeraisController', 'action' => 'show'],
    'GET|/cadastros/status-gerais/{id}/edit' => ['controller' => 'StatusGeraisController', 'action' => 'edit'],
    'POST|/cadastros/status-gerais/{id}' => ['controller' => 'StatusGeraisController', 'action' => 'update'],
    'DELETE|/cadastros/status-gerais/{id}' => ['controller' => 'StatusGeraisController', 'action' => 'destroy'],

    // Rotas de Relatórios
    'GET|/relatorios/custos' => ['controller' => 'RelatoriosCustosController', 'action' => 'index'],
    'GET|/relatorios/custos/export' => ['controller' => 'RelatoriosCustosController', 'action' => 'export'],
    'GET|/relatorios/aprovacoes' => ['controller' => 'RelatoriosAprovacoesController', 'action' => 'index'],
    'GET|/relatorios/aprovacoes/export' => ['controller' => 'RelatoriosAprovacoesController', 'action' => 'export'],

    // Rotas de Notificações
    'GET|/notificacoes' => ['controller' => 'NotificacaoController', 'action' => 'index'],
    'GET|/notificacoes/create' => ['controller' => 'NotificacaoController', 'action' => 'create'],
    'POST|/notificacoes' => ['controller' => 'NotificacaoController', 'action' => 'store'],
    'GET|/notificacoes/{id}' => ['controller' => 'NotificacaoController', 'action' => 'show'],
    'POST|/notificacoes/{id}/marcar-como-lida' => ['controller' => 'NotificacaoController', 'action' => 'marcarComoLida'],
    'DELETE|/notificacoes/{id}' => ['controller' => 'NotificacaoController', 'action' => 'destroy'],
    
    'GET|/notificacoes/regras' => ['controller' => 'RegrasNotificacaoController', 'action' => 'index'],
    'POST|/notificacoes/regras' => ['controller' => 'RegrasNotificacaoController', 'action' => 'store'],
    'GET|/notificacoes/regras/create' => ['controller' => 'RegrasNotificacaoController', 'action' => 'create'],
    'GET|/notificacoes/regras/{id}' => ['controller' => 'RegrasNotificacaoController', 'action' => 'show'],
    'GET|/notificacoes/regras/{id}/edit' => ['controller' => 'RegrasNotificacaoController', 'action' => 'edit'],
    'POST|/notificacoes/regras/{id}' => ['controller' => 'RegrasNotificacaoController', 'action' => 'update'],
    'DELETE|/notificacoes/regras/{id}' => ['controller' => 'RegrasNotificacaoController', 'action' => 'destroy'],
    
    'GET|/notificacoes/historico' => ['controller' => 'NotificacaoController', 'action' => 'historico'],

    // Rotas de Auditoria
    'GET|/auditoria/logs' => ['controller' => 'AuditoriaController', 'action' => 'logs'],
    'GET|/auditoria/versoes' => ['controller' => 'AuditoriaController', 'action' => 'versoes'],
    'GET|/auditoria/alteracoes' => ['controller' => 'AuditoriaController', 'action' => 'alteracoes'],
    'GET|/auditoria/logs/{id}' => ['controller' => 'AuditoriaController', 'action' => 'showLog'],
    'GET|/auditoria/versoes/{id}' => ['controller' => 'AuditoriaController', 'action' => 'showVersao'],
    'GET|/auditoria/alteracoes/{id}' => ['controller' => 'AuditoriaController', 'action' => 'showAlteracao']
];
