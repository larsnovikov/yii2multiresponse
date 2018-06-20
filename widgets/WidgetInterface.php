<?php
/**
 * Created by PhpStorm.
 * User: novikov
 * Date: 14.06.18
 * Time: 12:47
 */

namespace vendor\larsnovikov\yii2multiresponse\widgets;

/**
 * Interface WidgetInterface
 * @package vendor\larsnovikov\yii2multiresponse\widgets
 */
interface WidgetInterface
{
    /**
     * Обработка данных в очереди
     * @param array $data
     */
    public static function operate(array $data): void;
}
