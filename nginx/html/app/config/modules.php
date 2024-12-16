<?php

return [
    'modules' => [
        'painel' => [
            'name' => 'Painel',
            'icon' => 'fas fa-tachometer-alt',
            'order' => 1,
            'routes' => [
                'index' => ['path' => '/painel', 'name' => 'Dashboard']
            ]
        ],
        'custos_extras' => [
            'name' => 'Custos Extras',
            'icon' => 'fas fa-dollar-sign',
            'order' => 2,
            'routes' => [
                'registro_inicial' => ['path' => '/custos-extras/registro-inicial', 'name' => 'Registro Inicial'],
                'aprovacoes' => ['path' => '/custos-extras/aprovacoes', 'name' => 'Aprovações'],
                'analise' => ['path' => '/custos-extras/analise', 'name' => 'Análise']
            ]
        ],
        'cadastros' => [
            'name' => 'Cadastros',
            'icon' => 'fas fa-clipboard-list',
            'order' => 3,
            'routes' => [
                'fornecedores' => ['path' => '/cadastros/fornecedores', 'name' => 'Fornecedores'],
                'status_gerais' => ['path' => '/cadastros/status-gerais', 'name' => 'Status Gerais'],
                'usuarios' => ['path' => '/cadastros/usuarios', 'name' => 'Usuários'],
                'perfis' => ['path' => '/cadastros/perfis', 'name' => 'Perfis']
            ]
        ],
        'relatorios' => [
            'name' => 'Relatórios',
            'icon' => 'fas fa-chart-bar',
            'order' => 4,
            'routes' => [
                'custos' => ['path' => '/relatorios/custos', 'name' => 'Relatório de Custos'],
                'aprovacoes' => ['path' => '/relatorios/aprovacoes', 'name' => 'Relatório de Aprovações']
            ]
        ],
        'notificacoes' => [
            'name' => 'Notificações',
            'icon' => 'fas fa-bell',
            'order' => 5,
            'routes' => [
                'index' => ['path' => '/notificacoes', 'name' => 'Lista de Notificações'],
                'regras' => ['path' => '/notificacoes/regras', 'name' => 'Regras'],
                'historico' => ['path' => '/notificacoes/historico', 'name' => 'Histórico']
            ]
        ],
        'auditoria' => [
            'name' => 'Auditoria',
            'icon' => 'fas fa-history',
            'order' => 6,
            'routes' => [
                'logs' => ['path' => '/auditoria/logs', 'name' => 'Logs de Auditoria'],
                'versoes' => ['path' => '/auditoria/versoes', 'name' => 'Versões'],
                'alteracoes' => ['path' => '/auditoria/alteracoes', 'name' => 'Alterações']
            ]
        ]
    ]
];
