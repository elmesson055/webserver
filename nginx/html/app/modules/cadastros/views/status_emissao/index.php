<?php
$title = 'Status de Emissão';
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?php echo $title; ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Status de Emissão</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <form action="/cadastros/status-emissao" method="GET" class="form-inline">
                                    <div class="input-group">
                                        <input type="text" name="termo" class="form-control" placeholder="Buscar..." value="<?php echo $filtros['termo'] ?? ''; ?>">
                                        <div class="input-group-append">
                                            <button class="btn btn-default" type="submit">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="/cadastros/status-emissao/create" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Novo Status
                                </a>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <i class="fas fa-file-export"></i> Exportar
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="/cadastros/status-emissao/relatorio?formato=pdf">
                                            <i class="far fa-file-pdf"></i> PDF
                                        </a>
                                        <a class="dropdown-item" href="/cadastros/status-emissao/relatorio?formato=excel">
                                            <i class="far fa-file-excel"></i> Excel
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Nome</th>
                                    <th>Tipo</th>
                                    <th>Documentos</th>
                                    <th>Prazo (dias)</th>
                                    <th>Status</th>
                                    <th width="120">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($status)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Nenhum registro encontrado</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($status as $item): ?>
                                        <tr>
                                            <td><?php echo $item->codigo; ?></td>
                                            <td><?php echo $item->nome; ?></td>
                                            <td>
                                                <span class="badge badge-info">
                                                    <?php echo $item->tipo; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if($item->documentos_obrigatorios): ?>
                                                    <span class="badge badge-warning">
                                                        <i class="fas fa-file-alt"></i> Requer Documentos
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge badge-secondary">
                                                        <i class="fas fa-file-alt"></i> Opcional
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($item->prazo_dias): ?>
                                                    <?php echo $item->prazo_dias; ?> dias
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($item->status == 1): ?>
                                                    <span class="badge badge-success">Ativo</span>
                                                <?php else: ?>
                                                    <span class="badge badge-danger">Inativo</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="/cadastros/status-emissao/<?php echo $item->id; ?>/edit" class="btn btn-default btn-sm" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-default btn-sm" title="Excluir" 
                                                            onclick="excluir(<?php echo $item->id; ?>, '<?php echo $item->nome; ?>')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if(isset($paginacao)): ?>
                        <div class="card-footer clearfix">
                            <?php echo $paginacao; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function excluir(id, nome) {
    Swal.fire({
        title: 'Tem certeza?',
        text: `Deseja realmente excluir o status "${nome}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/cadastros/status-emissao/${id}`,
                method: 'DELETE',
                success: function(response) {
                    Swal.fire(
                        'Excluído!',
                        'Status excluído com sucesso.',
                        'success'
                    ).then(() => {
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire(
                        'Erro!',
                        'Ocorreu um erro ao excluir o status.',
                        'error'
                    );
                }
            });
        }
    });
}
</script>
