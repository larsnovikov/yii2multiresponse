<?php
/**
 * Created by PhpStorm.
 * User: lars
 * Date: 16.05.18
 * Time: 21:11
 */

namespace vendor\larsnovikov\yii2multiresponse\widgets;

use vendor\larsnovikov\yii2multiresponse\assets\ContainerAsset;
use vendor\larsnovikov\yii2multiresponse\requests\DataRequest;
use WebSocket\Client;
use yii\base\Exception;
use yii\base\Widget;
use yii\web\AssetBundle;

/**
 * Class AbstractWidget
 * @package vendor\larsnovikov\yii2multiresponse\widgets
 */
abstract class AbstractWidget extends Widget
{
    /**
     * WSServer
     * @var Client|null
     */
    private static $wsServer = null;

    /**
     * Конфиг для фронтенда
     * @var array
     */
    public static $config = [];

    /**
     * @var DataRequest|null
     */
    public $dataRequest = null;

    /**
     * Создание пустого контейнера
     * @return string
     */
    abstract public function createContainer(): string;

    /**
     * Js функция которая будет вызвана после получения контента от WS server
     * @return string
     */
    abstract public function getCallbackFunction(): string;

    /**
     * @return AssetBundle
     */
    abstract public function getAsset(): AssetBundle;

    /**
     * Получить Url для общения с сокетом
     *
     * @return string
     * @throws Exception
     */
    public static function getUrl(): string
    {
        if (!array_key_exists(static::class, \Yii::$app->getModule('yii2multiresponse')->socketUrls)) {
            throw new Exception('Socket url not found!');
        }
        return \Yii::$app->getModule('yii2multiresponse')->socketUrls[static::class];
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function run(): string
    {
        $this->registerAsset();
        if (!$this->dataRequest instanceof DataRequest) {
            throw new \InvalidArgumentException('Invalid dataRequest');
        }

        $this->register();

        return $this->createContainer();
    }

    /**
     * Имя текущего класса
     *
     * @return string
     */
    public function getClassName(): string
    {
        return (substr(static::class, strrpos(static::class, '\\') + 1));
    }

    /**
     * Регистрация
     *
     * @throws \ReflectionException
     */
    public function register(): void
    {
        // если это первая обработка виджета, создадим обработчик на добавление конфигурации
        if (self::$config === []) {
            $this->registerEvent();
        }

        $this->registerContainer($this->dataRequest->getToken());
    }

    /**
     * Регистрация события
     */
    public function registerEvent(): void
    {
        \Yii::$app->response->on(\yii\web\Response::EVENT_BEFORE_SEND, function (\yii\base\Event $event) {
            $response = $event->sender;
            if ($response->format === \yii\web\Response::FORMAT_HTML) {
                $configData = $this->render('@vendor/larsnovikov/yii2multiresponse/widgets/views/config', [
                    'config' => json_encode(self::$config)
                ]);
                $response->data = str_replace('<head>', "<head>$configData", $response->data);
            }
        });
    }

    /**
     * Регистрация контейнера
     *
     * @param string $token
     * @throws \ReflectionException
     */
    public function registerContainer(string $token): void
    {
        if (!array_key_exists($this->getClassName(), self::$config)) {
            // если нет данных об этом виджете, создадим
            self::$config[$this->getClassName()] = [
                'containers' => [],
                'url' => static::getUrl(),
                'callback' => trim($this->getCallbackFunction()),
                'base_asset' => $this->getAsset()::className() === ContainerAsset::class
            ];
        }
        // добавим информацию о текущем токене
        self::$config[$this->getClassName()]['containers'][] = $token;
    }

    /**
     * Регистрация ассета
     */
    private function registerAsset(): void
    {
        $this->getAsset()::register($this->getView());
    }

    /**
     * Послать сообщение в сокетсервер
     *
     * @param $message
     * @param string $token
     * @throws \WebSocket\BadOpcodeException
     */
    public static function sendMessage($message, string $token): void
    {
        if (!self::$wsServer instanceof Client) {
            self::$wsServer = new Client(static::getUrl());
        }
        var_dump('send message');

        self::$wsServer->send(json_encode([
            'action' => 'chat',
            'message' => $message,
            'token' => $token
        ]));
    }
}
