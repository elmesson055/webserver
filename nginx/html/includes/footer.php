<?php
/**
 * Componente de Rodapé Padrão
 * Este arquivo deve ser incluído em todas as páginas do sistema
 */

// Incluir configurações
require_once __DIR__ . '/../config/config.php';

// Garantir que as constantes necessárias estão definidas
if (!defined('APP_NAME')) define('APP_NAME', 'Sistema');
if (!defined('APP_VERSION')) define('APP_VERSION', '1.0.0');
if (!defined('APP_ENV')) define('APP_ENV', 'development');
if (!defined('COMPANY_NAME')) define('COMPANY_NAME', 'Empresa');
if (!defined('DEVELOPER_NAME')) define('DEVELOPER_NAME', 'Desenvolvedor');
if (!defined('SUPPORT_EMAIL')) define('SUPPORT_EMAIL', 'suporte@email.com');
if (!defined('SUPPORT_PHONE')) define('SUPPORT_PHONE', '(00) 0000-0000');
if (!defined('DOCUMENTATION_URL')) define('DOCUMENTATION_URL', '/docs');

/**
 * Função para renderizar o rodapé
 * @param bool $dark Se true, aplica o tema escuro ao rodapé
 * @return void
 */
function render_footer($dark = false) {
    $class = $dark ? 'footer-dark' : 'footer-light';
    ?>
    <footer class="footer <?php echo $class; ?>">
        <div class="copyright">
            &copy; <?php echo date('Y'); ?> <?php echo COMPANY_NAME; ?> - <?php echo APP_NAME; ?>. Todos os direitos reservados.
            <br>
            <small>Versão <?php echo APP_VERSION; ?> | Ambiente: <?php echo APP_ENV; ?></small>
            <br>
            <small>Desenvolvido por <?php echo DEVELOPER_NAME; ?> | <a href="<?php echo DOCUMENTATION_URL; ?>" class="doc-link">Documentação</a></small>
            <br>
            <small>Suporte: <?php echo SUPPORT_EMAIL; ?> | Tel: <?php echo SUPPORT_PHONE; ?></small>
        </div>
    </footer>
    <?php
}
?>
