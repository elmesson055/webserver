// Testes automatizados para a página de login
describe('Login Page UI Tests', () => {
    // Configuração dos viewports para teste de responsividade
    const viewports = [
        { width: 1920, height: 1080, name: 'desktop' },
        { width: 1366, height: 768, name: 'laptop' },
        { width: 768, height: 1024, name: 'tablet' },
        { width: 375, height: 667, name: 'mobile' }
    ];

    beforeEach(async () => {
        await page.goto('http://localhost/login');
    });

    // Testes de Responsividade
    viewports.forEach(viewport => {
        test(`should render correctly on ${viewport.name}`, async () => {
            await page.setViewport({
                width: viewport.width,
                height: viewport.height
            });
            
            // Verificar se os elementos principais estão visíveis
            await expect(page).toHaveSelector('.login-container');
            await expect(page).toHaveSelector('.login-form-container');
            await expect(page).toHaveSelector('.login-form');
            
            // Em mobile, verificar se o layout está adequado
            if (viewport.width <= 768) {
                const formContainer = await page.$('.login-form-container');
                const width = await formContainer.evaluate(el => el.offsetWidth);
                expect(width).toBe(viewport.width);
            }
        });
    });

    // Testes de Elementos Visuais
    test('should have all visual elements properly rendered', async () => {
        // Logo
        await expect(page).toHaveSelector('.login-logo h1');
        await expect(page).toHaveSelector('.login-logo span');
        
        // Campos do formulário
        await expect(page).toHaveSelector('input[type="email"]');
        await expect(page).toHaveSelector('input[type="password"]');
        
        // Botão de submit
        await expect(page).toHaveSelector('button[type="submit"]');
        
        // Link "Esqueceu sua senha?"
        await expect(page).toHaveSelector('a[href="/forgot-password"]');
    });

    // Testes de Estilo
    test('should have correct styles applied', async () => {
        // Verificar cores e estilos do botão
        const buttonColor = await page.$eval('button[type="submit"]', 
            el => window.getComputedStyle(el).backgroundColor);
        expect(buttonColor).toBe('rgb(45, 55, 72)'); // #2d3748

        // Verificar estilos do formulário
        const formWidth = await page.$eval('.login-form', 
            el => window.getComputedStyle(el).maxWidth);
        expect(formWidth).toBe('400px');
    });

    // Testes de Interação
    test('should show validation messages', async () => {
        // Tentar submeter formulário vazio
        await page.click('button[type="submit"]');
        
        // Verificar mensagens de validação HTML5
        const emailInput = await page.$('input[type="email"]');
        const isEmailRequired = await emailInput.evaluate(el => el.hasAttribute('required'));
        expect(isEmailRequired).toBe(true);

        const passwordInput = await page.$('input[type="password"]');
        const isPasswordRequired = await passwordInput.evaluate(el => el.hasAttribute('required'));
        expect(isPasswordRequired).toBe(true);
    });

    // Teste de Carregamento de Imagem
    test('should load background image', async () => {
        const bgImage = await page.$('#bgImage');
        const backgroundImage = await bgImage.evaluate(
            el => window.getComputedStyle(el).backgroundImage
        );
        expect(backgroundImage).toContain('unsplash.com');
    });
});
