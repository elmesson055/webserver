<?php

return [
    'host' => 'redis_custos',
    'port' => 6379,
    'timeout' => 2.5,
    'persistent' => true,
    'prefix' => 'custos_',
    
    // Configurações de cache
    'cache' => [
        'default_ttl' => 7200,    // Aumentado para 2 horas
        'long_ttl' => 604800,     // Aumentado para 1 semana
        'volatile_ttl' => 300,    // Cache volátil de 5 minutos
    ],
    
    'deployment' => [
        'cache_prefix' => 'deploy_',
        'lock_timeout' => 1800,   // 30 minutos
        'cache_dependencies' => true,
        'skip_unchanged' => true
    ],
    
    // Configurações de sessão
    'session' => [
        'lifetime' => 7200,    // 2 horas
        'gc_maxlifetime' => 7200
    ]
];
