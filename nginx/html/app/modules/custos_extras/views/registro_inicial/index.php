<?php
$title = 'Registros Iniciais';
include_once '../../layouts/sidebar.php';
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
                    <li class="breadcrumb-item active">Registros Iniciais</li>
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
                                <form method="GET" class="form-inline">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" placeholder="Buscar..." value="<?php echo $search; ?>">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6 text-right">
                                <?php if ($this->checkPermission('custos_extras.criar')): ?>
                                    <a href="/custos-extras/registro-inicial/create" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Novo Registro
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Descrição</th>
                                    <th>Valor</th>
                                    <th>Data de Registro</th>
                                    <th>Status</th>
                                    <th width="120">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($registros)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Nenhum registro encontrado</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($registros as $registro): ?>
                                        <tr>
                                            <td><?php echo $registro->descricao; ?></td>
                                            <td>R$ <?php echo number_format($registro->valor, 2, ',', '.'); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($registro->data_registro)); ?></td>
                                            <td>
                                                <span class="badge badge-<?php echo $registro->status->cor; ?>">
                                                    <?php echo $registro->status->nome; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <?php if ($this->checkPermission('custos_extras.editar')): ?>
                                                        <a href="/custos-extras/registro-inicial/<?php echo $registro->id; ?>/edit" 
                                                           class="btn btn-default btn-sm" 
                                                           title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($this->checkPermission('custos_extras.excluir')): ?>
                                                        <button type="button" 
                                                                class="btn btn-danger btn-sm" 
                                                                title="Excluir"
                                                                onclick="excluirRegistro(<?php echo $registro->id; ?>)">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if ($total > $perPage): ?>
                        <div class="card-footer clearfix">
                            <?php 
                            $totalPages = ceil($total / $perPage);
                            $urlPattern = '/custos-extras/registro-inicial?page=(:num)' . (!empty($search) ? '&search='.$search : '');
                            echo $this->pagination($totalPages, $page, $urlPattern);
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function excluirRegistro(id) {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Esta ação não poderá ser revertida!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/custos-extras/registro-inicial/${id}/delete`,
                method: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        Swal.fire(
                            'Excluído!',
                            'Registro excluído com sucesso.',
                            'success'
                        ).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Erro!',
                            response.message || 'Ocorreu um erro ao excluir o registro.',
                            'error'
                        );
                    }
                },
                error: function() {
                    Swal.fire(
                        'Erro!',
                        'Ocorreu um erro ao excluir o registro.',
                        'error'
                    );
                }
            });
        }
    });
}
</script>
