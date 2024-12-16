<?php
$title = 'Motoristas';
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
                    <li class="breadcrumb-item active">Motoristas</li>
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
                                <form action="/cadastros/motoristas" method="GET" class="form-inline">
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
                                <a href="/cadastros/motoristas/create" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Novo Motorista
                                </a>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <i class="fas fa-file-export"></i> Exportar
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="/cadastros/motoristas/relatorio?formato=pdf">
                                            <i class="far fa-file-pdf"></i> PDF
                                        </a>
                                        <a class="dropdown-item" href="/cadastros/motoristas/relatorio?formato=excel">
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
                                    <th>CPF</th>
                                    <th>CNH</th>
                                    <th>Categoria</th>
                                    <th>Validade CNH</th>
                                    <th>Telefone</th>
                                    <th>Status</th>
                                    <th width="120">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($motoristas)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center">Nenhum registro encontrado</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($motoristas as $motorista): ?>
                                        <tr>
                                            <td><?php echo $motorista->id; ?></td>
                                            <td><?php echo $motorista->nome; ?></td>
                                            <td><?php echo $motorista->cpf; ?></td>
                                            <td><?php echo $motorista->cnh; ?></td>
                                            <td><?php echo $motorista->categoria_cnh; ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($motorista->validade_cnh)); ?></td>
                                            <td><?php echo $motorista->telefone; ?></td>
                                            <td>
                                                <?php if($motorista->status == 1): ?>
                                                    <span class="badge badge-success">Ativo</span>
                                                <?php else: ?>
                                                    <span class="badge badge-danger">Inativo</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="/cadastros/motoristas/<?php echo $motorista->id; ?>/edit" class="btn btn-default btn-sm" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-default btn-sm" title="Excluir" 
                                                            onclick="excluir(<?php echo $motorista->id; ?>, '<?php echo $motorista->nome; ?>')">
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
        text: `Deseja realmente excluir o motorista "${nome}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/cadastros/motoristas/${id}`,
                method: 'DELETE',
                success: function(response) {
                    Swal.fire(
                        'Excluído!',
                        'Motorista excluído com sucesso.',
                        'success'
                    ).then(() => {
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire(
                        'Erro!',
                        'Ocorreu um erro ao excluir o motorista.',
                        'error'
                    );
                }
            });
        }
    });
}
</script>
