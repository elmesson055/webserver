<?php

return [
    'errors' => [
        // Mensagens de erro HTTP
        '403' => [
            'title' => 'Acesso Negado',
            'heading' => 'Oops! Acesso Negado.',
            'message' => 'Você não tem permissão para acessar esta página.',
            'back_link' => 'Voltar para o Dashboard'
        ],
        '404' => [
            'title' => 'Página não encontrada',
            'heading' => '404',
            'message' => 'A página que você está procurando não existe ou foi movida.',
            'back_link' => 'Voltar para a página inicial'
        ],
        '500' => [
            'title' => 'Erro Interno',
            'heading' => 'Erro Interno',
            'message' => 'Desculpe, ocorreu um erro inesperado.',
            'submessage' => 'Nossa equipe técnica foi notificada e está trabalhando para resolver o problema.',
            'back_link' => 'Voltar para a Página Inicial'
        ],
        'db' => [
            'title' => 'Erro de Banco de Dados',
            'heading' => 'Erro de Conexão',
            'message' => 'Desculpe, não foi possível conectar ao banco de dados.',
            'submessage' => 'Por favor, tente novamente mais tarde ou entre em contato com o suporte.',
            'back_link' => 'Voltar para a Página Inicial'
        ]
    ],
    'auth' => [
        'access_denied' => 'Usuário não tem permissão para acessar este recurso.',
        'login_required' => 'Por favor, faça login para continuar.',
        'invalid_credentials' => 'Email ou senha inválidos.',
        'logout_success' => 'Você foi desconectado com sucesso.',
        'session_expired' => 'Sua sessão expirou. Por favor, faça login novamente.'
    ],
    'validation' => [
        'required' => 'O campo {field} é obrigatório.',
        'email' => 'Por favor, insira um endereço de email válido.',
        'min_length' => 'O campo {field} deve ter pelo menos {param} caracteres.',
        'max_length' => 'O campo {field} não pode ter mais que {param} caracteres.',
        'matches' => 'O campo {field} não corresponde ao campo {param}.',
        'unique' => 'Este {field} já está em uso.'
    ],
    'success' => [
        'created' => '{item} criado com sucesso.',
        'updated' => '{item} atualizado com sucesso.',
        'deleted' => '{item} excluído com sucesso.',
        'saved' => 'Alterações salvas com sucesso.'
    ],
    'buttons' => [
        'save' => 'Salvar',
        'cancel' => 'Cancelar',
        'delete' => 'Excluir',
        'edit' => 'Editar',
        'back' => 'Voltar',
        'send' => 'Enviar'
    ]
];
