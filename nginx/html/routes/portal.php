<?php

use App\Modules\Portal\Controllers\PortalController;
use App\Modules\Portal\Controllers\FinanceiroController;

// Rotas do Portal do Fornecedor
Route::group(['prefix' => 'portal', 'middleware' => ['web', 'auth.fornecedor']], function () {
    // Dashboard
    Route::get('/', [PortalController::class, 'index'])->name('portal.dashboard');

    // Documentos
    Route::get('/documentos', [PortalController::class, 'documentos'])->name('portal.documentos');
    Route::get('/documentos/por-categoria/{id}', [PortalController::class, 'documentosPorCategoria'])->name('portal.documentos.por-categoria');
    Route::post('/documentos/upload', [PortalController::class, 'uploadDocumento'])->name('portal.documentos.upload');
    Route::get('/documentos/download/{id}', [PortalController::class, 'downloadDocumento'])->name('portal.documentos.download');
    Route::get('/documentos/view/{id}', [PortalController::class, 'visualizarDocumento'])->name('portal.documentos.view');

    // Notificações
    Route::get('/notificacoes', [PortalController::class, 'notificacoes'])->name('portal.notificacoes');
    Route::post('/notificacoes/marcar-lida/{id}', [PortalController::class, 'marcarNotificacaoComoLida'])->name('portal.notificacoes.marcar-lida');
    Route::post('/notificacoes/marcar-todas-lidas', [PortalController::class, 'marcarTodasNotificacoesComoLidas'])->name('portal.notificacoes.marcar-todas-lidas');

    // Dados do Fornecedor
    Route::get('/dados', [PortalController::class, 'dados'])->name('portal.dados');
    Route::post('/dados/atualizar', [PortalController::class, 'atualizarDados'])->name('portal.dados.atualizar');

    // Autenticação
    Route::get('/login', [PortalController::class, 'loginForm'])->name('portal.login');
    Route::post('/login', [PortalController::class, 'login'])->name('portal.login.post');
    Route::post('/logout', [PortalController::class, 'logout'])->name('portal.logout');
    Route::post('/recuperar-senha', [PortalController::class, 'recuperarSenha'])->name('portal.recuperar-senha');

    // Rotas do módulo financeiro
    Route::get('/financeiro/dados-bancarios', [FinanceiroController::class, 'dadosBancarios']);
    Route::post('/financeiro/dados-bancarios/salvar', [FinanceiroController::class, 'salvarDadosBancarios']);
    Route::get('/financeiro/dados-bancarios/{id}', [FinanceiroController::class, 'getDadosBancarios']);
    Route::delete('/financeiro/dados-bancarios/{id}/excluir', [FinanceiroController::class, 'excluirDadosBancarios']);

    Route::get('/financeiro/movimentacoes', [FinanceiroController::class, 'movimentacoes']);
    Route::get('/financeiro/movimentacoes/{id}', [FinanceiroController::class, 'detalhesMovimentacao']);
    Route::post('/financeiro/movimentacoes/{id}/comprovante', [FinanceiroController::class, 'uploadComprovante']);
});
