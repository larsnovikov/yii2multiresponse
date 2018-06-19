<?php
/**
 * Created by PhpStorm.
 * User: lars
 * Date: 16.05.18
 * Time: 21:11
 */

namespace vendor\larsnovikov\yii2multiresponse\examples\widgets;

use vendor\larsnovikov\yii2multiresponse\widgets\AbstractJsonWidget;

/**
 * Class JsonTestWidget
 * @package vendor\larsnovikov\yii2multiresponse\examples\widgets
 */
class JsonTestWidget extends AbstractJsonWidget
{
    /**
     * @param array $data
     * @throws \WebSocket\BadOpcodeException
     */
    public static function operate(array $data): void
    {
        sleep(rand(1, 4));
        // TODO обработка данных

        $message = json_encode([
            'name' => 'test json widget',
            'time' => time()
        ]);

        self::sendMessage($message, $data['token'], $data['url']);
    }
}
