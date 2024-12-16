<?php
namespace App\Helpers;

class RoutePerformanceLogger {
    private static $logFile = '/var/www/html/route_performance.log';
    private static $slowRouteThreshold = 0.5; // 500ms

    public static function log($route, $method, $duration, $memoryUsage) {
        $logEntry = sprintf(
            "[%s] Route: %s | Method: %s | Duration: %.4f seconds | Memory: %s | Timestamp: %s\n",
            $duration > self::$slowRouteThreshold ? 'SLOW' : 'NORMAL',
            $route,
            $method,
            $duration,
            self::formatMemory($memoryUsage),
            date('Y-m-d H:i:s')
        );

        file_put_contents(self::$logFile, $logEntry, FILE_APPEND);

        // Log adicional para rotas lentas
        if ($duration > self::$slowRouteThreshold) {
            error_log("Slow Route Detected: {$route} - {$duration} seconds");
        }
    }

    private static function formatMemory($bytes) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = $bytes > 0 ? floor(log($bytes, 1024)) : 0;
        return number_format($bytes / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
    }

    public static function setSlowRouteThreshold($threshold) {
        self::$slowRouteThreshold = $threshold;
    }

    public static function analyzePerformanceLogs($days = 1) {
        $logContent = file_get_contents(self::$logFile);
        $lines = explode("\n", $logContent);
        
        $slowRoutes = [];
        $recentTimestamp = strtotime("-{$days} days");

        foreach ($lines as $line) {
            if (preg_match('/\[SLOW\] Route: (.*) \| Method: (.*) \| Duration: ([\d.]+) seconds \| Memory: (.*) \| Timestamp: (.*)/', $line, $matches)) {
                $timestamp = strtotime($matches[5]);
                
                if ($timestamp >= $recentTimestamp) {
                    $slowRoutes[] = [
                        'route' => $matches[1],
                        'method' => $matches[2],
                        'duration' => $matches[3],
                        'memory' => $matches[4],
                        'timestamp' => $matches[5]
                    ];
                }
            }
        }

        return $slowRoutes;
    }
}

// Exemplo de uso em um middleware ou controlador
// RoutePerformanceLogger::log('/dashboard', 'GET', $duration, memory_get_usage());
