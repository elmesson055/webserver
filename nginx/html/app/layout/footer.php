<?php
// Fechar as divs abertas no header e sidebar
?>
        </div> <!-- Fechamento do container principal -->
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Inicialização do Flatpickr para campos de data
        flatpickr(".datepicker", {
            dateFormat: "Y-m-d",
            locale: "pt"
        });
    </script>
</body>
</html>
