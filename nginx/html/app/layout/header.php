<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function showNotification(title, message, type = 'success') {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    Toast.fire({
        icon: type,
        title: title,
        text: message
    });
}

// Função para lidar com respostas AJAX
function handleAjaxResponse(response) {
    if (response.success) {
        showNotification(response.title || 'Sucesso', response.message, 'success');
        if (response.redirect) {
            setTimeout(() => {
                window.location.href = response.redirect;
            }, 1000);
        }
    } else {
        showNotification(response.title || 'Erro', response.message, 'error');
    }
}
</script>
<style>
    /* Estilos para inputs e selects */
    input[type="text"],
    input[type="date"],
    input[type="number"],
    input[type="email"],
    input[type="password"],
    input[type="tel"],
    select,
    textarea {
        border: 1px solid #d1d5db !important;
        border-radius: 0.25rem !important;
        padding: 0.15rem 0.35rem !important; /* Padding ainda menor */
        width: 100% !important;
        outline: none !important;
        font-size: 0.813rem !important; /* Fonte ainda menor */
        line-height: 1rem !important; /* Linha mais compacta */
        min-height: 1.75rem !important; /* Altura menor */
        max-height: 1.75rem !important;
    }

    /* Ajuste específico para selects */
    select {
        padding-right: 1.5rem !important; /* Espaço menor para o ícone */
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e") !important;
        background-position: right 0.35rem center !important;
        background-repeat: no-repeat !important;
        background-size: 1.25em 1.25em !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        appearance: none !important;
    }

    /* Container para inputs e labels */
    .form-group {
        margin-bottom: 0.5rem !important; /* Margem menor entre grupos */
    }

    /* Estilo para labels */
    label {
        font-size: 0.75rem !important; /* Label menor */
        color: #374151 !important;
        margin-bottom: 0.15rem !important; /* Margem menor */
        display: block !important;
        font-weight: 500 !important; /* Um pouco mais bold para compensar o tamanho menor */
    }

    /* Estilo hover */
    input[type="text"]:hover,
    input[type="date"]:hover,
    input[type="number"]:hover,
    input[type="email"]:hover,
    input[type="password"]:hover,
    input[type="tel"]:hover,
    select:hover,
    textarea:hover {
        border-color: #9ca3af !important;
    }

    /* Estilo focus */
    input[type="text"]:focus,
    input[type="date"]:focus,
    input[type="number"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus,
    input[type="tel"]:focus,
    select:focus,
    textarea:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 1px #3b82f6 !important;
    }

    /* Estilo placeholder */
    ::placeholder {
        color: #9ca3af !important;
        opacity: 1 !important;
        font-size: 0.75rem !important; /* Placeholder menor */
    }

    /* Ajuste para campos desabilitados */
    input:disabled,
    select:disabled,
    textarea:disabled {
        background-color: #f3f4f6 !important;
        cursor: not-allowed !important;
    }

    /* Ajuste para campos somente leitura */
    input:read-only,
    textarea:read-only {
        background-color: #f9fafb !important;
    }

    /* Ajuste para campos com erro */
    .input-error {
        border-color: #ef4444 !important;
    }

    .input-error:focus {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 1px #ef4444 !important;
    }

    /* Ajustes específicos para melhor visualização em telas pequenas */
    @media (max-width: 640px) {
        input[type="text"],
        input[type="date"],
        input[type="number"],
        input[type="email"],
        input[type="password"],
        input[type="tel"],
        select,
        textarea {
            min-height: 2rem !important; /* Um pouco maior em mobile para melhor toque */
            max-height: 2rem !important;
            font-size: 0.875rem !important; /* Fonte um pouco maior em mobile */
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
