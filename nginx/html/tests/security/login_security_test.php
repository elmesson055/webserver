<?php

class LoginSecurityTest extends PHPUnit\Framework\TestCase
{
    private $baseUrl = 'http://localhost';

    public function testCSRFProtection()
    {
        // Teste 1: Tentativa de POST sem token CSRF
        $ch = curl_init($this->baseUrl . '/login');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'email' => 'test@example.com',
            'password' => 'password123'
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->assertEquals(403, $httpCode, 'Request without CSRF token should be rejected');
    }

    public function testBruteForceProtection()
    {
        // Teste 2: Múltiplas tentativas de login
        $attempts = 5;
        $lastResponse = null;

        for ($i = 0; $i < $attempts; $i++) {
            $ch = curl_init($this->baseUrl . '/login');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                'email' => 'test@example.com',
                'password' => 'wrongpassword'
            ]));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $lastResponse = curl_exec($ch);
            curl_close($ch);
        }

        // Verificar se após múltiplas tentativas o sistema está bloqueando
        $this->assertStringContainsString(
            'too many attempts',
            strtolower($lastResponse),
            'System should block after multiple failed attempts'
        );
    }

    public function testInputSanitization()
    {
        // Teste 3: Tentativa de SQL Injection
        $maliciousEmail = "' OR '1'='1";
        
        $ch = curl_init($this->baseUrl . '/login');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'email' => $maliciousEmail,
            'password' => 'password123'
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->assertNotEquals(200, $httpCode, 'SQL Injection attempt should not succeed');
    }

    public function testPasswordPolicy()
    {
        // Teste 4: Verificar política de senhas
        $weakPasswords = [
            '123456',
            'password',
            'qwerty',
            'abc123'
        ];

        foreach ($weakPasswords as $password) {
            $ch = curl_init($this->baseUrl . '/login');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                'email' => 'test@example.com',
                'password' => $password
            ]));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            $this->assertStringContainsString(
                'password does not meet requirements',
                strtolower($response),
                'Weak passwords should be rejected'
            );
        }
    }

    public function testSessionSecurity()
    {
        // Teste 5: Verificar configurações de sessão
        $sessionParams = session_get_cookie_params();
        
        $this->assertTrue($sessionParams['secure'], 'Session cookies should be secure');
        $this->assertTrue($sessionParams['httponly'], 'Session cookies should be HTTP only');
        
        // Verificar tempo de expiração da sessão
        $this->assertGreaterThan(0, ini_get('session.gc_maxlifetime'), 'Session should have expiration time');
    }
}
