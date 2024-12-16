<?php
u<?php
$title = 'Fornecedores';
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
                    <li class="breadcrumb-item active">Fornecedores</li>
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
                                <div class="card-tools">
                                    <?php if ($this->checkPermission('fornecedores.exportar')): ?>
                                        <div class="btn-group mr-2">
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-download"></i> Exportar
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="<?= url('/cadastros/fornecedores/export?format=excel' . (!empty($search) ? '&search=' . urlencode($search) : '')) ?>">
                                                    <i class="fas fa-file-excel text-success"></i> Excel
                                                </a>
                                                <a class="dropdown-item" href="<?= url('/cadastros/fornecedores/export?format=pdf' . (!empty($search) ? '&search=' . urlencode($search) : '')) ?>">
                                                    <i class="fas fa-file-pdf text-danger"></i> PDF
                                                </a>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($this->checkPermission('fornecedores.criar')): ?>
                                        <a href="<?= url('/cadastros/fornecedores/create') ?>" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Novo Fornecedor
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Razão Social</th>
                                    <th>Nome Fantasia</th>
                                    <th>CNPJ</th>
                                    <th>Telefone</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th width="120">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($fornecedores)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Nenhum registro encontrado</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($fornecedores as $fornecedor): ?>
                                        <tr>
                                            <td><?php echo $fornecedor->razao_social; ?></td>
                                            <td><?php echo $fornecedor->nome_fantasia; ?></td>
                                            <td><?php echo $fornecedor->cnpj; ?></td>
                                            <td><?php echo $fornecedor->telefone; ?></td>
                                            <td><?php echo $fornecedor->email; ?></td>
                                            <td>
                                                <span class="badge badge-<?php echo $fornecedor->ativo ? 'success' : 'danger'; ?>">
                                                    <?php echo $fornecedor->ativo ? 'Ativo' : 'Inativo'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <?php if ($this->checkPermission('fornecedores.editar')): ?>
                                                        <a href="/cadastros/fornecedores/<?php echo $fornecedor->id; ?>/edit" 
                                                           class="btn btn-default btn-sm" 
                                                           title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($this->checkPermission('fornecedores.excluir')): ?>
                                                        <button type="button" 
                                                                class="btn btn-danger btn-sm" 
                                                                title="Excluir"
                                                                onclick="excluirFornecedor(<?php echo $fornecedor->id; ?>)">
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
                            $urlPattern = '/cadastros/fornecedores?page=(:num)' . (!empty($search) ? '&search='.$search : '');
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
function excluirFornecedor(id) {
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
                url: `/cadastros/fornecedores/${id}/delete`,
                method: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        Swal.fire(
                            'Excluído!',
                            'Fornecedor excluído com sucesso.',
                            'success'
                        ).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Erro!',
                            response.message || 'Ocorreu um erro ao excluir o fornecedor.',
                            'error'
                        );
                    }
                },
                error: function() {
                    Swal.fire(
                        'Erro!',
                        'Ocorreu um erro ao excluir o fornecedor.',
                        'error'
                    );
                }
            });
        }
    });
}
</script>
