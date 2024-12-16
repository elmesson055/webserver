<?php
$title = 'Embarcadores';
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
                    <li class="breadcrumb-item active">Embarcadores</li>
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
                                <form action="/cadastros/embarcadores" method="GET" class="form-inline">
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
                                <a href="/cadastros/embarcadores/create" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Novo Embarcador
                                </a>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <i class="fas fa-file-export"></i> Exportar
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="/cadastros/embarcadores/relatorio?formato=pdf">
                                            <i class="far fa-file-pdf"></i> PDF
                                        </a>
                                        <a class="dropdown-item" href="/cadastros/embarcadores/relatorio?formato=excel">
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
                                    <th>Razão Social</th>
                                    <th>CNPJ</th>
                                    <th>Cidade/UF</th>
                                    <th>Telefone</th>
                                    <th>Status</th>
                                    <th width="120">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($embarcadores)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Nenhum registro encontrado</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($embarcadores as $embarcador): ?>
                                        <tr>
                                            <td><?php echo $embarcador->id; ?></td>
                                            <td><?php echo $embarcador->razao_social; ?></td>
                                            <td><?php echo $embarcador->cnpj; ?></td>
                                            <td><?php echo $embarcador->cidade.'/'.$embarcador->estado; ?></td>
                                            <td><?php echo $embarcador->telefone; ?></td>
                                            <td>
                                                <?php if($embarcador->status == 1): ?>
                                                    <span class="badge badge-success">Ativo</span>
                                                <?php else: ?>
                                                    <span class="badge badge-danger">Inativo</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="/cadastros/embarcadores/<?php echo $embarcador->id; ?>/edit" class="btn btn-default btn-sm" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-default btn-sm" title="Excluir" 
                                                            onclick="excluir(<?php echo $embarcador->id; ?>, '<?php echo $embarcador->razao_social; ?>')">
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
        text: `Deseja realmente excluir o embarcador "${nome}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/cadastros/embarcadores/${id}`,
                method: 'DELETE',
                success: function(response) {
                    Swal.fire(
                        'Excluído!',
                        'Embarcador excluído com sucesso.',
                        'success'
                    ).then(() => {
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire(
                        'Erro!',
                        'Ocorreu um erro ao excluir o embarcador.',
                        'error'
                    );
                }
            });
        }
    });
}
</script>
