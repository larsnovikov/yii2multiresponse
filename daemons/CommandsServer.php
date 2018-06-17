<?php
namespace vendor\larsnovikov\yii2multiresponse\daemons;

use consik\yii2websocket\WebSocketServer;
use Ratchet\ConnectionInterface;
use vendor\larsnovikov\yii2multiresponse\Module;

/**
 * Class CommandsServer
 * @package vendor\larsnovikov\yii2multiresponse\daemons
 */
class CommandsServer extends WebSocketServer
{
    /**
     * @param ConnectionInterface $from
     * @param $msg
     * @return null|string
     */
    protected function getCommand(ConnectionInterface $from, $msg)
    {
        $request = json_decode($msg, true);
        return !empty($request['action']) ? $request['action'] : parent::getCommand($from, $msg);
    }

    /**
     * Получение данных от кролика
     * @param ConnectionInterface $client
     * @param $msg
     */
    public function commandRegisterResponse(ConnectionInterface $client, $msg): void
    {
        echo "register response\r\n";
        $request = json_decode($msg, true);
        /** @var Module $module */
        $module = \Yii::$app->getModule('yii2multiresponse');

        $token = $request['token'];

        $registeredClient = $module->storage::getClientByToken($token);
        if ($registeredClient instanceof ConnectionInterface) {
            // если токен зарегистрирован, отдадим клиенту данные
            echo "send_data_to_client\r\n";
            $registeredClient->send( json_encode([
                'type' => 'registerResponse',
                'token' => $token,
                'message' => $request['message']
            ]));
        } else {
            // если токен еще не зарегистрирован, сохраним ответ во временное хранилище
            $module->storage::registerResponse($token, $request['message']);
        }
    }


    /**
     * Регистрация токена
     *
     * @param ConnectionInterface $client
     * @param string $msg
     */
    public function commandRegisterToken(ConnectionInterface $client, string $msg): void
    {
        echo "register token\r\n";
        /** @var Module $module */
        $module = \Yii::$app->getModule('yii2multiresponse');
        $request = json_decode($msg, true);

        $token = $request['token'];

        $response = $module->storage::getResponseByToken($token);
        if ($response) {
            // для этого токена уже готов ответ, отдадим его
            $client->send( json_encode([
                'type' => 'registerResponse',
                'token' => $token,
                'message' => $response
            ]));
        } else {
            // если ответ для токена не получен, будем ждать
            $module->storage::registerToken($token, $client);
        }
    }
}
