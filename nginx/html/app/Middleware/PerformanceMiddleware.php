<?php
namespace App\Middleware;

class PerformanceMiddleware {
    private static $startTime = [];
    private static $performanceLogs = [];

    public static function start($route) {
        self::$startTime[$route] = microtime(true);
    }

    public static function end($route) {
        $endTime = microtime(true);
        $duration = $endTime - self::$startTime[$route];

        self::$performanceLogs[] = [
            'route' => $route,
            'start_time' => self::$startTime[$route],
            'end_time' => $endTime,
            'duration' => $duration,
            'memory_usage' => memory_get_usage(),
            'peak_memory_usage' => memory_get_peak_usage()
        ];

        // Log de rotas lentas
        if ($duration > 0.5) {
            error_log("Slow Route: {$route} - Duration: {$duration} seconds");
        }

        return $duration;
    }

    public static function generateReport() {
        return [
            'total_routes_tracked' => count(self::$performanceLogs),
            'routes' => self::$performanceLogs,
            'summary' => [
                'total_execution_time' => array_sum(array_column(self::$performanceLogs, 'duration')),
                'average_route_time' => array_sum(array_column(self::$performanceLogs, 'duration')) / count(self::$performanceLogs),
                'slowest_route' => self::findSlowestRoute(),
                'fastest_route' => self::findFastestRoute()
            ]
        ];
    }

    private static function findSlowestRoute() {
        if (empty(self::$performanceLogs)) return null;
        
        return array_reduce(self::$performanceLogs, function($slowest, $current) {
            return ($slowest === null || $current['duration'] > $slowest['duration']) ? $current : $slowest;
        });
    }

    private static function findFastestRoute() {
        if (empty(self::$performanceLogs)) return null;
        
        return array_reduce(self::$performanceLogs, function($fastest, $current) {
            return ($fastest === null || $current['duration'] < $fastest['duration']) ? $current : $fastest;
        });
    }

    public static function saveReportToFile($filename = null) {
        $report = self::generateReport();
        
        if ($filename === null) {
            $filename = '/var/www/html/performance_routes_' . date('Y-m-d_H-i-s') . '.json';
        }

        file_put_contents($filename, json_encode($report, JSON_PRETTY_PRINT));
        return $filename;
    }
}

// Exemplo de uso em rotas
function performanceWrapper($route, $callback) {
    PerformanceMiddleware::start($route);
    $result = $callback();
    PerformanceMiddleware::end($route);
    return $result;
}

// Exemplo de registro de rota
// $router->get('/dashboard', function() {
//     return performanceWrapper('/dashboard', function() {
//         // Lógica da rota
//         return renderDashboard();
//     });
// });
