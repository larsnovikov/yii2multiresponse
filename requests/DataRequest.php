<?php
/**
 * Created by PhpStorm.
 * User: novikov
 * Date: 13.06.18
 * Time: 18:16
 */

namespace vendor\larsnovikov\yii2multiresponse\requests;

/**
 * Class DataRequest
 * @package vendor\larsnovikov\yii2multiresponse\requests
 */
class DataRequest
{
    /** @var string|null  */
    private $token = null;

    /** @var array|null  */
    private $data = null;

    /** @var string|null */
    private $view = null;

    /**
     * DataRequest constructor.
     * @param string $view
     * @param string $widgetClass
     * @param array $data
     * @throws \yii\base\Exception
     */
    public function __construct(string $view, string $widgetClass, array $data)
    {
        $this->token = \Yii::$app->security->generateRandomString();
        $this->data = $data;
        $this->view = $view;

        $this->sendToQueue($widgetClass);
    }

    /**
     * Получить данные
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Получить токен
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Получить путь к вью
     * @return string
     */
    public function getView(): string
    {
        return $this->view;
    }

    /**
     * Послать в очередь
     * @param string $widgetClass
     */
    public function sendToQueue(string $widgetClass): void
    {
        // положить в очередь данные для обработки
        \vendor\larsnovikov\yii2multiresponse\queues\Queue::putInQueue($widgetClass, [
            'view' => $this->getView(),
            'data' => $this->getData(),
            'token' => $this->getToken(),
            'widgetClass' => $widgetClass,
            'url' => $widgetClass::getUrl()
        ]);
    }
}
