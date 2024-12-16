const lighthouse = require('lighthouse');
const chromeLauncher = require('chrome-launcher');
const assert = require('assert');

async function runPerformanceTest() {
    // Iniciar Chrome
    const chrome = await chromeLauncher.launch({chromeFlags: ['--headless']});
    
    // Configuração do Lighthouse
    const options = {
        logLevel: 'info',
        output: 'json',
        onlyCategories: ['performance'],
        port: chrome.port
    };

    // URL para teste
    const url = 'http://localhost/login';

    try {
        // Executar teste Lighthouse
        const runnerResult = await lighthouse(url, options);
        const reportJson = runnerResult.report;
        const report = JSON.parse(reportJson);

        // Verificar métricas de performance
        const metrics = {
            'First Contentful Paint': report.audits['first-contentful-paint'].numericValue,
            'Time to Interactive': report.audits['interactive'].numericValue,
            'Speed Index': report.audits['speed-index'].numericValue,
            'Total Blocking Time': report.audits['total-blocking-time'].numericValue,
            'Largest Contentful Paint': report.audits['largest-contentful-paint'].numericValue,
            'Cumulative Layout Shift': report.audits['cumulative-layout-shift'].numericValue
        };

        // Verificar limites aceitáveis
        assert(metrics['First Contentful Paint'] < 2000, 'First Contentful Paint deve ser menor que 2s');
        assert(metrics['Time to Interactive'] < 3000, 'Time to Interactive deve ser menor que 3s');
        assert(metrics['Speed Index'] < 3000, 'Speed Index deve ser menor que 3s');
        assert(metrics['Total Blocking Time'] < 300, 'Total Blocking Time deve ser menor que 300ms');
        assert(metrics['Largest Contentful Paint'] < 2500, 'Largest Contentful Paint deve ser menor que 2.5s');
        assert(metrics['Cumulative Layout Shift'] < 0.1, 'Cumulative Layout Shift deve ser menor que 0.1');

        // Verificar otimização de recursos
        assert(report.audits['unminified-css'].score === 1, 'CSS deve estar minificado');
        assert(report.audits['unminified-javascript'].score === 1, 'JavaScript deve estar minificado');
        assert(report.audits['unused-css-rules'].score === 1, 'Não deve haver CSS não utilizado');
        assert(report.audits['unused-javascript'].score === 1, 'Não deve haver JavaScript não utilizado');

        console.log('✅ Todos os testes de performance passaram!');
        console.log('\nMétricas detalhadas:');
        console.table(metrics);

    } catch (error) {
        console.error('❌ Falha nos testes de performance:', error);
        throw error;
    } finally {
        // Fechar Chrome
        await chrome.kill();
    }
}

// Executar testes
runPerformanceTest();
