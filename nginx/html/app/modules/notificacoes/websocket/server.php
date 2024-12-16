<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\Modules\Notificacoes\WebSocket\NotificacaoWebSocket;

require dirname(__DIR__) . '/../../vendor/autoload.php';

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new NotificacaoWebSocket()
        )
    ),
    8080
);

$server->run();
