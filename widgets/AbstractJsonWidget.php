<?php
/**
 * Created by PhpStorm.
 * User: lars
 * Date: 13.06.18
 * Time: 20:12
 */

namespace vendor\larsnovikov\yii2multiresponse\widgets;

use vendor\larsnovikov\yii2multiresponse\assets\ContainerAsset;
use yii\web\AssetBundle;

/**
 * Class AbstractJsonWidget
 * @package vendor\larsnovikov\yii2multiresponse\widgets
 */
abstract class AbstractJsonWidget extends AbstractWidget implements WidgetInterface
{
    /**
     * Js функция которая будет вызвана после получения контента от WS server
     * @return string
     */
    public function getCallbackFunction(): string
    {
        return <<<JS
             console.log('json callback function');
             console.log(response.token);
             console.log(JSON.parse(response.message));
JS;
    }

    /**
     * @return AssetBundle|null
     */
    public function getAsset(): ?AssetBundle
    {
        return new ContainerAsset;
    }

    /**
     * Создание пустого контейнера
     * @return string
     */
    public function createContainer(): string
    {
        return '';
    }
}
