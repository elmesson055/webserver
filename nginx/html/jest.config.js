module.exports = {
    preset: 'jest-puppeteer',
    testMatch: [
        "**/tests/ui/**/*.js"
    ],
    setupFilesAfterEnv: ['./jest.setup.js'],
    testTimeout: 30000
};
