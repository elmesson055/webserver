<?php

use App\Core\Router\Router;

// Embarcadores
Router::get('/cadastros/embarcadores', 'App\Modules\Cadastros\Controllers\EmbarcadorController@index');
Router::get('/cadastros/embarcadores/create', 'App\Modules\Cadastros\Controllers\EmbarcadorController@create');
Router::post('/cadastros/embarcadores', 'App\Modules\Cadastros\Controllers\EmbarcadorController@store');
Router::get('/cadastros/embarcadores/{id}/edit', 'App\Modules\Cadastros\Controllers\EmbarcadorController@edit');
Router::put('/cadastros/embarcadores/{id}', 'App\Modules\Cadastros\Controllers\EmbarcadorController@update');
Router::delete('/cadastros/embarcadores/{id}', 'App\Modules\Cadastros\Controllers\EmbarcadorController@destroy');
Router::get('/cadastros/embarcadores/relatorio', 'App\Modules\Cadastros\Controllers\EmbarcadorController@relatorio');

// Fornecedores
Router::get('/cadastros/fornecedores', 'App\Modules\Cadastros\Controllers\FornecedorController@index');
Router::get('/cadastros/fornecedores/create', 'App\Modules\Cadastros\Controllers\FornecedorController@create');
Router::post('/cadastros/fornecedores', 'App\Modules\Cadastros\Controllers\FornecedorController@store');
Router::get('/cadastros/fornecedores/{id}/edit', 'App\Modules\Cadastros\Controllers\FornecedorController@edit');
Router::put('/cadastros/fornecedores/{id}', 'App\Modules\Cadastros\Controllers\FornecedorController@update');
Router::delete('/cadastros/fornecedores/{id}', 'App\Modules\Cadastros\Controllers\FornecedorController@destroy');
Router::get('/cadastros/fornecedores/relatorio', 'App\Modules\Cadastros\Controllers\FornecedorController@relatorio');

// Clientes
Router::get('/cadastros/clientes', 'App\Modules\Cadastros\Controllers\ClienteController@index');
Router::get('/cadastros/clientes/create', 'App\Modules\Cadastros\Controllers\ClienteController@create');
Router::post('/cadastros/clientes', 'App\Modules\Cadastros\Controllers\ClienteController@store');
Router::get('/cadastros/clientes/{id}/edit', 'App\Modules\Cadastros\Controllers\ClienteController@edit');
Router::put('/cadastros/clientes/{id}', 'App\Modules\Cadastros\Controllers\ClienteController@update');
Router::delete('/cadastros/clientes/{id}', 'App\Modules\Cadastros\Controllers\ClienteController@destroy');
Router::get('/cadastros/clientes/relatorio', 'App\Modules\Cadastros\Controllers\ClienteController@relatorio');

// Motoristas
Router::get('/cadastros/motoristas', 'App\Modules\Cadastros\Controllers\MotoristaController@index');
Router::get('/cadastros/motoristas/create', 'App\Modules\Cadastros\Controllers\MotoristaController@create');
Router::post('/cadastros/motoristas', 'App\Modules\Cadastros\Controllers\MotoristaController@store');
Router::get('/cadastros/motoristas/{id}/edit', 'App\Modules\Cadastros\Controllers\MotoristaController@edit');
Router::put('/cadastros/motoristas/{id}', 'App\Modules\Cadastros\Controllers\MotoristaController@update');
Router::delete('/cadastros/motoristas/{id}', 'App\Modules\Cadastros\Controllers\MotoristaController@destroy');
Router::get('/cadastros/motoristas/relatorio', 'App\Modules\Cadastros\Controllers\MotoristaController@relatorio');

// Tipos
Router::group(['prefix' => '/cadastros/tipos'], function() {
    // Tipos de Carga
    Router::get('/cargas', 'App\Modules\Cadastros\Controllers\TiposCargaController@index');
    Router::get('/cargas/create', 'App\Modules\Cadastros\Controllers\TiposCargaController@create');
    Router::post('/cargas', 'App\Modules\Cadastros\Controllers\TiposCargaController@store');
    Router::get('/cargas/{id}/edit', 'App\Modules\Cadastros\Controllers\TiposCargaController@edit');
    Router::put('/cargas/{id}', 'App\Modules\Cadastros\Controllers\TiposCargaController@update');
    Router::delete('/cargas/{id}', 'App\Modules\Cadastros\Controllers\TiposCargaController@destroy');
    
    // Tipos de Documentos
    Router::get('/documentos', 'App\Modules\Cadastros\Controllers\TiposDocumentoController@index');
    Router::get('/documentos/create', 'App\Modules\Cadastros\Controllers\TiposDocumentoController@create');
    Router::post('/documentos', 'App\Modules\Cadastros\Controllers\TiposDocumentoController@store');
    Router::get('/documentos/{id}/edit', 'App\Modules\Cadastros\Controllers\TiposDocumentoController@edit');
    Router::put('/documentos/{id}', 'App\Modules\Cadastros\Controllers\TiposDocumentoController@update');
    Router::delete('/documentos/{id}', 'App\Modules\Cadastros\Controllers\TiposDocumentoController@destroy');
    
    // Tipos de Custos
    Router::get('/custos', 'App\Modules\Cadastros\Controllers\TiposCustoController@index');
    Router::get('/custos/create', 'App\Modules\Cadastros\Controllers\TiposCustoController@create');
    Router::post('/custos', 'App\Modules\Cadastros\Controllers\TiposCustoController@store');
    Router::get('/custos/{id}/edit', 'App\Modules\Cadastros\Controllers\TiposCustoController@edit');
    Router::put('/custos/{id}', 'App\Modules\Cadastros\Controllers\TiposCustoController@update');
    Router::delete('/custos/{id}', 'App\Modules\Cadastros\Controllers\TiposCustoController@destroy');
});

// Status
Router::group(['prefix' => '/cadastros/status'], function() {
    // Status Gerais
    Router::get('/gerais', 'App\Modules\Cadastros\Controllers\StatusGeraisController@index');
    Router::get('/gerais/create', 'App\Modules\Cadastros\Controllers\StatusGeraisController@create');
    Router::post('/gerais', 'App\Modules\Cadastros\Controllers\StatusGeraisController@store');
    Router::get('/gerais/{id}/edit', 'App\Modules\Cadastros\Controllers\StatusGeraisController@edit');
    Router::put('/gerais/{id}', 'App\Modules\Cadastros\Controllers\StatusGeraisController@update');
    Router::delete('/gerais/{id}', 'App\Modules\Cadastros\Controllers\StatusGeraisController@destroy');
    
    // Status de Follow-up
    Router::get('/followup', 'App\Modules\Cadastros\Controllers\StatusFollowupController@index');
    Router::get('/followup/create', 'App\Modules\Cadastros\Controllers\StatusFollowupController@create');
    Router::post('/followup', 'App\Modules\Cadastros\Controllers\StatusFollowupController@store');
    Router::get('/followup/{id}/edit', 'App\Modules\Cadastros\Controllers\StatusFollowupController@edit');
    Router::put('/followup/{id}', 'App\Modules\Cadastros\Controllers\StatusFollowupController@update');
    Router::delete('/followup/{id}', 'App\Modules\Cadastros\Controllers\StatusFollowupController@destroy');
    
    // Status de Emissão
    Router::get('/emissao', 'App\Modules\Cadastros\Controllers\StatusEmissaoController@index');
    Router::get('/emissao/create', 'App\Modules\Cadastros\Controllers\StatusEmissaoController@create');
    Router::post('/emissao', 'App\Modules\Cadastros\Controllers\StatusEmissaoController@store');
    Router::get('/emissao/{id}/edit', 'App\Modules\Cadastros\Controllers\StatusEmissaoController@edit');
    Router::put('/emissao/{id}', 'App\Modules\Cadastros\Controllers\StatusEmissaoController@update');
    Router::delete('/emissao/{id}', 'App\Modules\Cadastros\Controllers\StatusEmissaoController@destroy');
});
