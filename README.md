Multiresponse for Yii2
=========
Компонент для дозагрузки контента через websocket

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require larsnovikov/yii2multiresponse
```

or add

```
"larsnovikov/yii2multiresponse": "*"
```

to the require section of your `composer.json` file.


Usage
-----


1. Добавь в конфиге в `modules`:
```
'yii2multiresponse' => [
    'class' => 'vendor\larsnovikov\yii2multiresponse\Module',
    'socketUrls' => [
        // TODO тут необходимо сконфигурировать пути для виджетов к WebSocket серверу
        \vendor\larsnovikov\yii2multiresponse\examples\widgets\HtmlTestWidget::class => [
            'ws://socket-test.loc:5005',
            'ws://socket-test.loc:5006', 
            'ws://socket-test.loc:5007'
        ],
        \vendor\larsnovikov\yii2multiresponse\examples\widgets\JsonTestWidget::class => [
            'ws://socket-test.loc:5005',
            'ws://socket-test.loc:5006',
            'ws://socket-test.loc:5007'
        ]
    ]
],
```

2. Добавь в конфиге в `components` очередь:

```
'multiResponseQueue' => array_merge(
    [
        'class' => \yii\queue\amqp_interop\Queue::class,
        'queueName' => 'multiresponse.queue'
    ],
    [
        'port' => 5672,
        'user' => 'public',
        'password' => 'public',
        'driver' => yii\queue\amqp_interop\Queue::ENQUEUE_AMQP_LIB,
        'dsn' => 'amqp://public:public@172.17.0.1:5672/%2F',
    ]
),
```
3. Добавь в конфиге в `bootstrap` название компонента очереди

4. Унаследуй свой виджет от `vendor\larsnovikov\yii2multiresponse\widgets\AbstractHtmlWidget` или `vendor\larsnovikov\yii2multiresponse\widgets\AbstractJsonWidget` в зависимости от того какой ответ от WS-сервера надо получить

5. Выведи виджет, например:
```
echo \vendor\larsnovikov\yii2multiresponse\examples\widgets\HtmlTestWidget::widget([
        'dataRequest' => new \vendor\larsnovikov\yii2multiresponse\requests\DataRequest(
                             '@vendor/larsnovikov/yii2multiresponse/widgets/views/empty_container',
                             \vendor\larsnovikov\yii2multiresponse\examples\widgets\HtmlTestWidget::class,
                             [
                                 'test1' => rand(0, 9999),
                                 'test2' => rand(0, 9999)
                             ])
        ]);
```

Запуск
-----
1. Запусти WSServer `php yii yii2multiresponse/server/start <port>` или `bash socket.sh <port>`, где `<port>` - номер порта
2. Запусти слушателей очередей, например: `php yii multi-response-queue/listen`
