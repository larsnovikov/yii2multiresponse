<?php
/**
 * Created by PhpStorm.
 * User: lars
 * Date: 16.05.18
 * Time: 21:11
 */

namespace vendor\larsnovikov\yii2multiresponse\examples\widgets;

use vendor\larsnovikov\yii2multiresponse\widgets\AbstractHtmlWidget;

/**
 * Class HtmlTestWidget
 * @package vendor\larsnovikov\yii2multiresponse\examples\widgets
 */
class HtmlTestWidget extends AbstractHtmlWidget
{
    /**
     * @param array $data
     * @throws \WebSocket\BadOpcodeException
     */
    public static function operate(array $data): void
    {
        sleep(rand(1, 4));
        // TODO обработка данных
        $message = 'test widget ' . time();

        self::sendMessage($message, $data['token']);
    }
}
