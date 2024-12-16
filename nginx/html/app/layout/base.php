<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Custo Extras'; ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/pt.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Toast Utilities -->
    <script src="/assets/js/toast.js"></script>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">
    <?php include 'sidebar.php'; ?>
    
    <div class="p-4 sm:ml-64 flex-grow">
        <div class="p-4 border-2 border-gray-200 rounded-lg">
            <!-- Conteúdo da página vai aqui -->
            <?php echo $pageContent ?? ''; ?>
        </div>
    </div>

    <!-- Footer -->
    <?php include __DIR__ . '/footer_content.php'; ?>

    <!-- Inicialização do Toast para mensagens da sessão -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (isset($_SESSION['flash'])): ?>
            const flash = <?php echo json_encode($_SESSION['flash']); ?>;
            showToast(flash.type, flash.message, flash.redirect || null);
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        // Configuração global do Flatpickr
        flatpickr.localize(flatpickr.l10ns.pt);
        flatpickr(".datepicker", {
            dateFormat: "d/m/Y",
            allowInput: true
        });

        // Converter todos os formulários para AJAX
        document.querySelectorAll('form[data-ajax="true"]').forEach(form => {
            setupAjaxForm(`#${form.id}`);
        });

        // Setup de botões de exclusão
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('data-url');
                const id = this.getAttribute('data-id');
                const type = this.getAttribute('data-type');
                const message = this.getAttribute('data-message') || 'Tem certeza que deseja excluir este item?';
                
                confirmAction('Confirmar exclusão', message)
                    .then((result) => {
                        if (result.isConfirmed) {
                            const formData = new FormData();
                            formData.append('action', 'delete');
                            if (id) formData.append('id', id);
                            if (type) formData.append('type', type);
                            
                            fetch(url, {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    showToast('success', data.message, data.redirect);
                                } else {
                                    showError(data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Erro:', error);
                                showError('Erro ao excluir item');
                            });
                        }
                    });
            });
        });
    });
    </script>
</body>
</html>
