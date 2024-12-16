<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-file-alt"></i> Documentos</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        <i class="fas fa-upload"></i> Upload de Documento
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($categorias as $categoria): ?>
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h6 class="mb-0"><?= $categoria['nome'] ?></h6>
                                    </div>
                                    <div class="card-body">
                                        <?php foreach ($categoria['documentos'] as $doc): ?>
                                            <div class="document-card">
                                                <div class="card mb-2">
                                                    <div class="card-body">
                                                        <span class="status-badge badge bg-<?= $doc['status_class'] ?>">
                                                            <?= $doc['status'] ?>
                                                        </span>
                                                        <h6 class="card-title"><?= $doc['nome'] ?></h6>
                                                        <p class="card-text small">
                                                            <strong>Validade:</strong> <?= $doc['validade'] ?><br>
                                                            <strong>Último Upload:</strong> <?= $doc['data_upload'] ?>
                                                        </p>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-sm btn-primary" 
                                                                    onclick="uploadDocument(<?= $doc['id'] ?>)">
                                                                <i class="fas fa-upload"></i>
                                                            </button>
                                                            <?php if ($doc['arquivo']): ?>
                                                                <a href="/portal/documentos/download/<?= $doc['id'] ?>" 
                                                                   class="btn btn-sm btn-success">
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                                <button type="button" class="btn btn-sm btn-info"
                                                                        onclick="viewDocument(<?= $doc['id'] ?>)">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload de Documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="uploadForm" action="/portal/documentos/upload" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="documento_id" id="documento_id">
                    
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoria</label>
                        <select class="form-select" id="categoria" name="categoria" required>
                            <option value="">Selecione...</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= $cat['nome'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="documento" class="form-label">Documento</label>
                        <select class="form-select" id="documento" name="documento" required disabled>
                            <option value="">Selecione a categoria primeiro...</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="validade" class="form-label">Data de Validade</label>
                        <input type="date" class="form-control" id="validade" name="validade" required>
                    </div>

                    <div class="mb-3">
                        <label for="arquivo" class="form-label">Arquivo</label>
                        <input type="file" class="form-control" id="arquivo" name="arquivo" required>
                        <small class="text-muted">Formatos aceitos: PDF, JPG, PNG. Tamanho máximo: 5MB</small>
                    </div>

                    <div class="mb-3">
                        <label for="observacoes" class="form-label">Observações</label>
                        <textarea class="form-control" id="observacoes" name="observacoes" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="uploadForm" class="btn btn-primary">Upload</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Visualizar -->
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Visualizar Documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <iframe id="documentViewer" style="width: 100%; height: 500px; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Atualiza lista de documentos quando categoria é selecionada
    $('#categoria').change(function() {
        var categoriaId = $(this).val();
        if (categoriaId) {
            $.get('/portal/documentos/por-categoria/' + categoriaId, function(documentos) {
                var options = '<option value="">Selecione...</option>';
                documentos.forEach(function(doc) {
                    options += `<option value="${doc.id}">${doc.nome}</option>`;
                });
                $('#documento').html(options).prop('disabled', false);
            });
        } else {
            $('#documento').html('<option value="">Selecione a categoria primeiro...</option>').prop('disabled', true);
        }
    });

    // Validação do formulário
    $('#uploadForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Erro ao fazer upload do documento');
            }
        });
    });
});

function uploadDocument(id) {
    $('#documento_id').val(id);
    $('#uploadModal').modal('show');
}

function viewDocument(id) {
    $('#documentViewer').attr('src', '/portal/documentos/view/' + id);
    $('#viewModal').modal('show');
}
</script>
