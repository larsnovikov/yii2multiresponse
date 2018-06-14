<?php
namespace vendor\larsnovikov\yii2multiresponse\controllers;

use consik\yii2websocket\WebSocketServer;
use vendor\larsnovikov\yii2multiresponse\daemons\CommandsServer;
use yii\console\Controller;

/**
 * Class ServerController
 * @package vendor\larsnovikov\yii2multiresponse\controllers
 */
class ServerController extends Controller
{
    /**
     * Сокет сервер
     * @param int|null $port
     */
    public function actionStart(?int $port = null): void
    {
        $server = new CommandsServer();
        $server->port = 3048; //This port must be busy by WebServer and we handle an error

        if ($port !== null) {
            $server->port = $port;
        }
        $server->on(WebSocketServer::EVENT_WEBSOCKET_OPEN_ERROR, function($e) use($server) {
            echo "Error opening port " . $server->port . "\n";
            $server->port += 1; //Try next port to open
            $server->start();
        });

        $server->on(WebSocketServer::EVENT_WEBSOCKET_OPEN, function($e) use($server) {
            echo "Server started at port " . $server->port;
        });

        $server->start();
    }
}
